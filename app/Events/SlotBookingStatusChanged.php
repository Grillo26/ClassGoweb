<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlotBookingStatusChanged implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $slotBookingId;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct($slotBookingId, $newStatus)
    {
        $this->slotBookingId = $slotBookingId;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        \Log::info('SlotBookingStatusChanged: evento disparado', ['slotBookingId' => $this->slotBookingId, 'newStatus' => $this->newStatus]);
        $booking = \App\Models\SlotBooking::with(['tutor', 'booker'])->find($this->slotBookingId);
        // Mapeo de estado a imagen
        $imagenesPorEstado = [
            'aceptada' => url('storage/notificaciones/aceptada.png'),
            'completada' => url('storage/notificaciones/completada.jpeg'),
            'no-completada' => url('storage/notificaciones/no-completada.jpeg'),
            'pendiente' => url('storage/notificaciones/pendiente.jpeg'),
            'rechazada-observada' => url('storage/notificaciones/rechazada-observada.jpeg'),
        ];
        $image = $imagenesPorEstado[$this->newStatus] ?? url('storage/notificaciones/pendiente.jpeg');
        if ($booking) {
            $fcmService = new \App\Services\FcmService();
            // Notificar al tutor
            if ($booking->tutor && $booking->tutor->user && $booking->tutor->user->fcm_token) {
                \Log::info('Enviando notificación FCM al tutor', ['user_id' => $booking->tutor->user->id, 'fcm_token' => $booking->tutor->user->fcm_token, 'image' => $image]);
                $fcmService->sendNotification(
                    $booking->tutor->user->fcm_token,
                    'Estado de tutoría actualizado',
                    'El estado de la sesión ha cambiado a: ' . $this->newStatus,
                    [],
                    $image
                );
            } else {
                \Log::warning('No se encontró fcm_token para el tutor', ['tutor' => $booking->tutor?->user?->id]);
            }
            // Notificar al estudiante
            if ($booking->booker && $booking->booker->fcm_token) {
                \Log::info('Enviando notificación FCM al estudiante', ['user_id' => $booking->booker->id, 'fcm_token' => $booking->booker->fcm_token, 'image' => $image]);
                $fcmService->sendNotification(
                    $booking->booker->fcm_token,
                    'Estado de tutoría actualizado',
                    'El estado de la sesión ha cambiado a: ' . $this->newStatus,
                    [],
                    $image
                );
            } else {
                \Log::warning('No se encontró fcm_token para el estudiante', ['student' => $booking->booker?->id]);
            }
        } else {
            \Log::warning('No se encontró la tutoría para enviar notificación', ['slotBookingId' => $this->slotBookingId]);
        }
        return new Channel('slot-bookings');
    }

    public function broadcastAs()
    {
        return 'SlotBookingStatusChanged';
    }
}
