<?php
// filepath: app/Mail/StudentTutoriaNotificationMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNuevaTutoriaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre_estudiante;
    public $sessionDate;
    public $sessionTime;
    public $meetingLink;
    public $nombre_tutor;




    public function __construct($nombre_estudiante, $sessionDate, $sessionTime, $nombre_tutor)
    {
        $this->nombre_estudiante = $nombre_estudiante;
        $this->sessionDate = $sessionDate;
        $this->sessionTime = $sessionTime;
        $this->nombre_tutor = $nombre_tutor;
    }

    public function build()
    {
        return $this->view('emails.student-tutoria-notification')
                    ->subject('🎓 Nueva tutoría programada - ClassGo')
                    ->with([
                        'nombre_estudiante' => $this->nombre_estudiante,
                        'sessionDate' => $this->sessionDate,
                        'sessionTime' => $this->sessionTime,
                        'nombre_tutor' => $this->nombre_tutor,
                        
                    ]);
    }
}