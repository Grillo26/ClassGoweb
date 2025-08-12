<?php
// filepath: app/Services/MailService.php

namespace App\Services;

use App\Mail\StudentTutoriaNotificationMail;
use App\Mail\SessionBookingMail;
use App\Mail\TutorTutoriaNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AdminNuevaTutoriaMail;
use App\Models\Subject as Materia;
use Illuminate\Support\Facades\Auth;
class MailService
{
    /**
     * Enviar correos de notificación de tutoría
     */
    public function sendTutoriaNotification($tutoria, $meetingLink)
    {
        Log::info('Iniciando envío de notificaciones de tutoría', [
            'tutoria_id' => $tutoria->id,
            'meeting_link' => $meetingLink
        ]);

        // Enviar correo al estudiante
        $this->sendStudentNotification($tutoria, $meetingLink);

        // Enviar correo al tutor
        $this->sendTutorNotification($tutoria, $meetingLink);

        Log::info('Finalizando envío de notificaciones de tutoría', [
            'tutoria_id' => $tutoria->id
        ]);
    }

    /**
     * Enviar correo al estudiante usando Mailable
     */
    private function sendStudentNotification($tutoria, $meetingLink)
    {
        try {
            $studentProfile = optional($tutoria->student)->profile;
            $studentName = $tutoria->student->first_name . ' ' . $tutoria->student->last_name;
            $studentUser = optional($tutoria->student)->user;
            if (!$studentUser || !$studentUser->email) {
                return;
            }
            $tutorProfile = optional($tutoria->tutor)->profile;
            $tutorName = $tutoria->tutor->first_name . ' ' . $tutoria->tutor->last_name;
            // ✅ USAR MAILABLE EN LUGAR DE HTML CRUDO
            Mail::to($studentUser->email)->send(new StudentTutoriaNotificationMail(
                $studentName,
                date('d/m/Y', strtotime($tutoria->start_time)),
                date('H:i', strtotime($tutoria->start_time)),
                $meetingLink,
                $tutorName
            ));
        } catch (\Exception $e) {
            Log::error('Error al enviar correo al estudiante', [
                'tutoria_id' => $tutoria->id,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Enviar correo al tutor
     */
    private function sendTutorNotification($tutoria, $meetingLink)
    {
        try {
            $tutorProfile = optional($tutoria->tutor)->profile;
            $tutorName = $tutoria->tutor->first_name . ' ' . $tutoria->tutor->last_name;
            $tutorUser = optional($tutoria->tutor)->user;

            if (!$tutorUser || !$tutorUser->email) {
                Log::warning('Tutor sin email válido', [
                    'tutoria_id' => $tutoria->id,
                    'tutor_id' => $tutoria->tutor->id ?? 'N/A'
                ]);
                return;
            }

            $studentProfile = optional($tutoria->student)->profile;

            Log::info('Enviando correo al tutor', [
                'tutoria_id' => $tutoria->id,
                'tutor_email' => $tutorUser->email,
                'tutor_name' => $tutorName
            ]);

            // Crear y enviar el correo al tutor
            Mail::to($tutorUser->email)->send(new TutorTutoriaNotificationMail(
                $tutorName,
                date('d/m/Y', strtotime($tutoria->start_time)),
                date('H:i', strtotime($tutoria->start_time)),
                $meetingLink,
                $tutoria->student->first_name . ' ' . $tutoria->student->last_name
            ));

            Log::info('Correo enviado exitosamente al tutor', [
                'tutoria_id' => $tutoria->id,
                'tutor_email' => $tutorUser->email
            ]);

        } catch (\Exception $e) {
            Log::error('Error al enviar correo al tutor', [
                'tutoria_id' => $tutoria->id,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
        }
    }




    public function sendAdminNuevaTutoria($tutor, $materia,$fecha)
    {
        $studiante = Auth::user();
        $nombreEstudiante = $studiante->profile->full_name;
        $adminEmail = env('MAIL_ADMIN');
        $materia = Materia::find($materia);
       // $fechaHora = $fecha->format('d/m/Y H:i');
        Mail::to($adminEmail)->send(new AdminNuevaTutoriaMail(
            $nombreEstudiante,
            $fecha,
            $tutor,
            $materia->name
        ));
    }


}