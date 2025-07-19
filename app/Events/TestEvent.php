<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message;
    public $userId;

    public function __construct($message, $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        Log::info('TestEvent: evento disparado', [
            'message' => $this->message,
            'userId' => $this->userId,
            'canal' => 'private-user.' . $this->userId
        ]);

        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'TestEvent';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'timestamp' => now()->toISOString(),
            'type' => 'test'
        ];
    }
} 