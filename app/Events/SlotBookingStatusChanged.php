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
        // Canal público único para todos los cambios de estado de tutoría
        return [new \Illuminate\Broadcasting\Channel('public-slot-bookings')];
    }

    public function broadcastWith()
    {
        $booking = \App\Models\SlotBooking::with(['tutor', 'booker'])->find($this->slotBookingId);
        return [
            'slotBookingId' => $this->slotBookingId,
            'newStatus' => $this->newStatus,
            'tutor_id' => $booking && $booking->tutor && $booking->tutor->user ? $booking->tutor->user->id : null,
            'student_id' => $booking && $booking->booker ? $booking->booker->id : null,
        ];
    }

    public function broadcastAs()
    {
        return 'SlotBookingStatusChanged';
    }
}
