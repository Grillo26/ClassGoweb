<?php
// filepath: app/Mail/StudentTutoriaNotificationMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentTutoriaNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $sessionDate;
    public $sessionTime;
    public $meetingLink;
    public $oppositeName;

    public function __construct($userName, $sessionDate, $sessionTime, $meetingLink, $oppositeName)
    {
        $this->userName = $userName;
        $this->sessionDate = $sessionDate;
        $this->sessionTime = $sessionTime;
        $this->meetingLink = $meetingLink;
        $this->oppositeName = $oppositeName;
    }

    public function build()
    {
        return $this->view('emails.student-tutoria-notification')
                    ->subject('🎓 Tu tutoría ha sido confirmada - ClassGo')
                    ->with([
                        'userName' => $this->userName,
                        'sessionDate' => $this->sessionDate,
                        'sessionTime' => $this->sessionTime,
                        'meetingLink' => $this->meetingLink,
                        'oppositeName' => $this->oppositeName,
                    ]);
    }
}