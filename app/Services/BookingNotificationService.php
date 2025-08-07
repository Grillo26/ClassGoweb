<?php

namespace App\Services;

use App\Models\SlotBooking;
use App\Models\User;
use App\Jobs\SendNotificationJob;
use App\Events\SlotBookingStatusChanged;
use Illuminate\Support\Facades\Log;

class BookingNotificationService
{
    /**
     * Maneja las notificaciones de cambio de estado de tutoría
     * Solo envía notificaciones en casos específicos
     *
     * @param SlotBooking $booking
     * @param string $oldStatus
     * @param string $newStatus
     * @return void
     */
    public function handleStatusChangeNotification(SlotBooking $booking, string $oldStatus, string $newStatus): void
    {
        Log::info('BookingNotificationService: Procesando cambio de estado', [
            'booking_id' => $booking->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'old_status_type' => gettype($oldStatus),
            'new_status_type' => gettype($newStatus)
        ]);

        // Cargar relaciones necesarias
        $booking->load(['tutor', 'booker', 'subject']);

        // Caso 1: Cambio a "Aceptado" - Notificar al tutor Y al estudiante
        if ($newStatus === 'Aceptado' || $newStatus === '1' || $newStatus === 1) {
            Log::info('BookingNotificationService: Enviando notificaciones de aceptación');
            $this->sendAcceptedNotificationToTutor($booking);
            $this->sendAcceptedNotificationToStudent($booking);
        }

        // Caso 2: Cambio a "Cursando" - Notificar solo al estudiante
        if ($newStatus === 'Cursando' || $newStatus === '6' || $newStatus === 6) {
            Log::info('BookingNotificationService: Enviando notificación de cursando');
            $this->sendCursandoNotificationToStudent($booking);
        }

        // Siempre emitir evento de broadcasting para la app móvil
        $this->emitBroadcastingEvent($booking, $newStatus);
    }

    /**
     * Envía notificación intensa al tutor cuando la tutoría es aceptada
     *
     * @param SlotBooking $booking
     * @return void
     */
    private function sendAcceptedNotificationToTutor(SlotBooking $booking): void
    {
        try {
            // Obtener el usuario tutor directamente
            $tutor = User::find($booking->tutor_id);
            if (!$tutor) {
                Log::warning('Usuario tutor no encontrado', [
                    'booking_id' => $booking->id,
                    'tutor_id' => $booking->tutor_id
                ]);
                return;
            }

            // Obtener el usuario estudiante directamente
            $student = User::find($booking->student_id);

            $notificationData = [
                'tutorName' => $tutor->profile->full_name ?? 'Tutor',
                'studentName' => $student ? ($student->profile->full_name ?? 'Estudiante') : 'Estudiante',
                'bookingDetails' => [
                    'id' => $booking->id,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'subject' => $booking->subject ? $booking->subject->name : 'Materia no definida',
                    'meeting_link' => $booking->meeting_link ?? 'Enlace no disponible',
                    'status' => 'Aceptado'
                ]
            ];

            // Enviar notificación por email
            dispatch(new SendNotificationJob('sessionBooking', $tutor, $notificationData));

            // Enviar push notification si está configurado
            if ($tutor->fcm_token) {
                $this->sendPushNotification($tutor, [
                    'title' => '🎉 ¡Tutoría Aceptada!',
                    'body' => "Tu tutoría con {$notificationData['studentName']} ha sido aceptada para el {$notificationData['sessionDate']}",
                    'data' => [
                        'booking_id' => $booking->id,
                        'type' => 'booking_accepted',
                        'status' => 'Aceptado'
                    ]
                ]);
            }

            Log::info('Notificación de aceptación enviada al tutor', [
                'tutor_id' => $tutor->id,
                'booking_id' => $booking->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al enviar notificación de aceptación al tutor', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envía notificación al estudiante cuando la tutoría es aceptada
     *
     * @param SlotBooking $booking
     * @return void
     */
    private function sendAcceptedNotificationToStudent(SlotBooking $booking): void
    {
        try {
            // Obtener el usuario estudiante directamente
            $student = User::find($booking->student_id);
            if (!$student) {
                Log::warning('Usuario estudiante no encontrado', [
                    'booking_id' => $booking->id,
                    'student_id' => $booking->student_id
                ]);
                return;
            }

            // Obtener el usuario tutor directamente
            $tutor = User::find($booking->tutor_id);

            $notificationData = [
                'studentName' => $student->profile->full_name ?? 'Estudiante',
                'tutorName' => $tutor ? ($tutor->profile->full_name ?? 'Tutor') : 'Tutor',
                'bookingDetails' => [
                    'id' => $booking->id,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'subject' => $booking->subject ? $booking->subject->name : 'Materia no definida',
                    'meeting_link' => $booking->meeting_link ?? 'Enlace no disponible',
                    'status' => 'Aceptado'
                ]
            ];

            // Enviar notificación por email
            dispatch(new SendNotificationJob('sessionBooking', $student, $notificationData));

            // Enviar push notification si está configurado
            if ($student->fcm_token) {
                $this->sendPushNotification($student, [
                    'title' => '✅ Tutoría Aceptada',
                    'body' => "Tu tutoría con {$notificationData['tutorName']} ha sido aceptada para el {$notificationData['sessionDate']}",
                    'data' => [
                        'booking_id' => $booking->id,
                        'type' => 'booking_accepted',
                        'status' => 'Aceptado'
                    ]
                ]);
            }

            Log::info('Notificación de aceptación enviada al estudiante', [
                'student_id' => $student->id,
                'booking_id' => $booking->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al enviar notificación de aceptación al estudiante', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envía notificación al estudiante cuando la tutoría cambia a "Cursando"
     *
     * @param SlotBooking $booking
     * @return void
     */
    private function sendCursandoNotificationToStudent(SlotBooking $booking): void
    {
        try {
            // Obtener el usuario estudiante directamente
            $student = User::find($booking->student_id);
            if (!$student) {
                Log::warning('Usuario estudiante no encontrado', [
                    'booking_id' => $booking->id,
                    'student_id' => $booking->student_id
                ]);
                return;
            }

            // Obtener el usuario tutor directamente
            $tutor = User::find($booking->tutor_id);

            $notificationData = [
                'studentName' => $student->profile->full_name ?? 'Estudiante',
                'tutorName' => $tutor ? ($tutor->profile->full_name ?? 'Tutor') : 'Tutor',
                'bookingDetails' => [
                    'id' => $booking->id,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'subject' => $booking->subject ? $booking->subject->name : 'Materia no definida',
                    'meeting_link' => $booking->meeting_link ?? 'Enlace no disponible',
                    'status' => 'Cursando'
                ]
            ];

            // Enviar notificación por email
            dispatch(new SendNotificationJob('sessionBooking', $student, $notificationData));

            // Enviar push notification si está configurado
            if ($student->fcm_token) {
                $this->sendPushNotification($student, [
                    'title' => '🚀 ¡La tutoría está comenzando!',
                    'body' => "Tu sesión con {$notificationData['tutorName']} está por comenzar. ¡Únete ahora!",
                    'data' => [
                        'booking_id' => $booking->id,
                        'type' => 'booking_cursando',
                        'status' => 'Cursando'
                    ]
                ]);
            }

            Log::info('Notificación de cursando enviada al estudiante', [
                'student_id' => $student->id,
                'booking_id' => $booking->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al enviar notificación de cursando al estudiante', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Emite evento de broadcasting para la app móvil
     *
     * @param SlotBooking $booking
     * @param string $newStatus
     * @return void
     */
    private function emitBroadcastingEvent(SlotBooking $booking, string $newStatus): void
    {
        try {
            event(new SlotBookingStatusChanged($booking->id, $newStatus));
            
            Log::info('Evento de broadcasting emitido', [
                'booking_id' => $booking->id,
                'status' => $newStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Error al emitir evento de broadcasting', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envía notificación push (método placeholder)
     *
     * @param $user
     * @param array $data
     * @return void
     */
    private function sendPushNotification($user, array $data): void
    {
        try {
            // Aquí puedes implementar la lógica de push notification
            // Por ejemplo, usando Firebase Cloud Messaging
            
            Log::info('Push notification preparada', [
                'user_id' => $user->id,
                'data' => $data
            ]);

            // Implementar envío de push notification
            // firebase()->messaging()->send($data, $user->fcm_token);

        } catch (\Exception $e) {
            Log::error('Error al enviar push notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
} 