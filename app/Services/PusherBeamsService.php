<?php

namespace App\Services;

use Pusher\PushNotifications\PushNotifications;

class PusherBeamsService
{
    protected $beamsClient;

    public function __construct()
    {
        $this->beamsClient = new PushNotifications([
            "instanceId" => env('PUSHER_BEAMS_INSTANCE_ID'),
            "secretKey" => env('PUSHER_BEAMS_SECRET_KEY'),
        ]);
    }

    public function sendNotification($userId, $title, $message)
    {
        return $this->beamsClient->publishToUsers(
            [$userId],  // User ID must be registered for notifications
            [
                "web" => [
                    "notification" => [
                        "title" => $title,
                        "body" => $message,
                        "deep_link" => "https://yourwebsite.com/dashboard"
                    ]
                ]
            ]
        );
    }
}
