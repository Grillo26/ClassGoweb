<?php
// filepath: app/Mail/TutorTutoriaNotificationMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TutorTutoriaNotificationMail extends Mailable
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
        return $this->subject('Nueva reserva de tutoría - ClassGo')
                    ->view('emails.tutor-tutoria-notification');
    }
}
