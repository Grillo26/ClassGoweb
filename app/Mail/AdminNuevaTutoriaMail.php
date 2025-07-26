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
    public $fechaHora;
    public $sessionTime;
    public $meetingLink;
    public $nombre_tutor;

    public $nombre_materia;




    public function __construct($nombre_estudiante, $fechayhora, $nombre_tutor,$materia)
    {
        $this->nombre_estudiante = $nombre_estudiante;
        $this->fechaHora = $fechayhora;
        $this->nombre_materia = $materia;
        $this->nombre_tutor = $nombre_tutor;
    }

    public function build()
    {
        return $this->view('emails.admin-nueva-tutoria')
                    ->subject('🎓 Nueva tutoría programada - ClassGo')
                    ->with([
                        'nombre_estudiante' => $this->nombre_estudiante,
                        'sessionDate' => $this->fechaHora,
                        'nombre_tutor' => $this->nombre_tutor,
                    ]);
    }
}