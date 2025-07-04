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
        // Mapeo de estado a icono (nombre del recurso en drawable, sin extensión)
        $iconosPorEstado = [
            'aceptada' => 'aceptada',
            'completada' => 'completada',
            'no-completada' => 'no_completada',
            'no_completada' => 'no_completada',
            'pendiente' => 'pendiente',
            'rechazada_observada' => 'rechazada_observada',
        ];
        // Normalizar el estado para el mapeo
        $estado = str_replace('-', '_', $this->newStatus);
        $icon = $iconosPorEstado[$estado] ?? 'pendiente';
        if ($booking) {
            $fcmService = new \App\Services\FcmService();
            // Notificar al tutor
            if ($booking->tutor && $booking->tutor->user && $booking->tutor->user->fcm_token) {
                \Log::info('Enviando notificación FCM al tutor', ['user_id' => $booking->tutor->user->id, 'fcm_token' => $booking->tutor->user->fcm_token, 'icon' => $icon]);
                $fcmService->sendNotification(
                    $booking->tutor->user->fcm_token,
                    'Estado de tutoría actualizado',
                    'El estado de la sesión ha cambiado a: ' . $this->newStatus,
                    ['icon' => $icon]
                );
            } else {
                \Log::warning('No se encontró fcm_token para el tutor', ['tutor' => $booking->tutor?->user?->id]);
            }
            // Notificar al estudiante
            if ($booking->booker && $booking->booker->fcm_token) {
                \Log::info('Enviando notificación FCM al estudiante', ['user_id' => $booking->booker->id, 'fcm_token' => $booking->booker->fcm_token, 'icon' => $icon]);
                $fcmService->sendNotification(
                    $booking->booker->fcm_token,
                    'Estado de tutoría actualizado',
                    'El estado de la sesión ha cambiado a: ' . $this->newStatus,
                    ['icon' => $icon]
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
