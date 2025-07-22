<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        Log::info('SlotBookingStatusChanged: evento disparado', ['slotBookingId' => $this->slotBookingId, 'newStatus' => $this->newStatus]);
        $booking = \App\Models\SlotBooking::with(['tutor', 'booker'])->find($this->slotBookingId);
        
        // Mapeo de estado a icono (nombre del recurso en drawable, sin extensión)
        $iconosPorEstado = [
            'aceptada' => 'aceptada',
            'completada' => 'completada',
            'no-completada' => 'no_completada',
            'no_completada' => 'no_completada',
            'pendiente' => 'ic_stat_pendiente',
            'rechazada_observada' => 'rechazada_observada',
        ];
        // Normalizar el estado para el mapeo
        $estado = str_replace('-', '_', $this->newStatus);
        $icon = $iconosPorEstado[$estado] ?? 'pendiente';
        
        if ($booking) {
            $fcmService = new \App\Services\FcmService();
            
            // Notificar al tutor SOLO cuando el estado cambie a "aceptada"
            if ($this->newStatus === 'aceptada' && $booking->tutor && $booking->tutor->user && $booking->tutor->user->fcm_token) {
                Log::info('Enviando notificación FCM al tutor - Estado aceptada', ['user_id' => $booking->tutor->user->id, 'fcm_token' => $booking->tutor->user->fcm_token, 'icon' => $icon]);
                $fcmService->sendNotification(
                    $booking->tutor->user->fcm_token,
                    'Tutoría aceptada',
                    'Tu tutoría ha sido aceptada por el estudiante',
                    ['icon' => $icon]
                );
            } else {
                Log::info('No se envía notificación al tutor - Estado: ' . $this->newStatus, ['tutor' => $booking->tutor?->user?->id]);
            }
            
            // Notificar al estudiante para todos los cambios de estado
            if ($booking->booker && $booking->booker->fcm_token) {
                Log::info('Enviando notificación FCM al estudiante', ['user_id' => $booking->booker->id, 'fcm_token' => $booking->booker->fcm_token, 'icon' => $icon]);
                $fcmService->sendNotification(
                    $booking->booker->fcm_token,
                    'Estado de tutoría actualizado',
                    'El estado de la sesión ha cambiado a: ' . $this->newStatus,
                    ['icon' => $icon]
                );
            } else {
                Log::warning('No se encontró fcm_token para el estudiante', ['student' => $booking->booker?->id]);
            }
        } else {
            Log::warning('No se encontró la tutoría para enviar notificación', ['slotBookingId' => $this->slotBookingId]);
        }
        
        // Retornar canales privados para cada usuario involucrado
        $channels = [];
        
        if ($booking) {
            // Canal para el tutor
            if ($booking->tutor && $booking->tutor->user) {
                $channels[] = new \Illuminate\Broadcasting\PrivateChannel('user.' . $booking->tutor->user->id);
            }
            
            // Canal para el estudiante
            if ($booking->booker) {
                $channels[] = new \Illuminate\Broadcasting\PrivateChannel('user.' . $booking->booker->id);
            }
        }
        
        // Si no hay usuarios específicos, usar canal público como fallback
        if (empty($channels)) {
            $channels[] = new Channel('slot-bookings');
        }
        
        return $channels;
    }

    public function broadcastAs()
    {
        return 'SlotBookingStatusChanged';
    }
}
