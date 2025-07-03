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
        // Enviar notificación push si el estado es 'aceptada'
        if ($this->newStatus === 'aceptada') {
            $booking = \App\Models\SlotBooking::with(['tutor', 'booker'])->find($this->slotBookingId);
            if ($booking) {
                $fcmService = new \App\Services\FcmService();
                // Notificar al tutor
                if ($booking->tutor && $booking->tutor->user && $booking->tutor->user->fcm_token) {
                    $fcmService->sendNotification(
                        $booking->tutor->user->fcm_token,
                        '¡Nueva tutoría aceptada!',
                        'Tu sesión ha sido aceptada. Revisa los detalles en la app.'
                    );
                }
                // Notificar al estudiante
                if ($booking->booker && $booking->booker->fcm_token) {
                    $fcmService->sendNotification(
                        $booking->booker->fcm_token,
                        '¡Tu tutoría fue aceptada!',
                        'El tutor ha aceptado tu sesión. Revisa los detalles en la app.'
                    );
                }
            }
        }
        return new Channel('slot-bookings');
    }

    public function broadcastAs()
    {
        return 'SlotBookingStatusChanged';
    }
}
