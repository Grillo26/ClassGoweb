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
        return new Channel('slot-bookings');
    }

    public function broadcastAs()
    {
        return 'SlotBookingStatusChanged';
    }
}
