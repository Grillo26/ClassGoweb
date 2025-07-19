<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SlotBookingCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $slotBookingId;

    /**
     * Create a new event instance.
     */
    public function __construct($slotBookingId)
    {
        $this->slotBookingId = $slotBookingId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        Log::info('SlotBookingCreated: evento disparado', ['slotBookingId' => $this->slotBookingId]);
        $booking = \App\Models\SlotBooking::with(['tutor', 'booker'])->find($this->slotBookingId);
        
        if ($booking) {
            $fcmService = new \App\Services\FcmService();
            
            // Notificar al estudiante que creó la tutoría
            if ($booking->booker && $booking->booker->fcm_token) {
                Log::info('Enviando notificación FCM al estudiante - Tutoría creada', [
                    'user_id' => $booking->booker->id,
                    'fcm_token' => $booking->booker->fcm_token
                ]);
                
                $fcmService->sendNotification(
                    $booking->booker->fcm_token,
                    'Tutoría creada exitosamente',
                    'Tu tutoría ha sido creada exitosamente',
                    [
                        'icon' => 'tutoria_creada',
                        'slotBookingId' => $this->slotBookingId,
                        'type' => 'booking_created'
                    ]
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

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'SlotBookingCreated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'slotBookingId' => $this->slotBookingId,
            'message' => 'Tutoría creada exitosamente',
            'type' => 'booking_created'
        ];
    }
} 