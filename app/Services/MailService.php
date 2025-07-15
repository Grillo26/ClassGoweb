<?php

namespace App\Services;

use App\Models\CountryState;
use App\Mail\SessionBookingMail;
use Illuminate\Support\Facades\Mail;

class MailService
{

    /**
     * Enviar correos de notificación de tutoría
     */
    public function sendTutoriaNotification($tutoria, $meetingLink)
    {
        // Enviar correo al estudiante
        $this->sendStudentNotification($tutoria, $meetingLink);

        // Enviar correo al tutor
        $this->sendTutorNotification($tutoria, $meetingLink);
    }

    /**
     * Enviar correo al estudiante
     */

    private function sendStudentNotification($tutoria, $meetingLink)
    {
        $studentProfile = optional($tutoria->student)->profile;
        $studentName = $studentProfile ? ($studentProfile->first_name . ' ' . $studentProfile->last_name) : 'Estudiante';
        $studentUser = optional($tutoria->student)->user;

        if ($studentUser) {
            $tutorProfile = optional($tutoria->tutor)->profile;
            $tutorName = $tutorProfile ? ($tutorProfile->first_name . ' ' . $tutorProfile->last_name) : 'Tutor';

            // Usar vista personalizada
            $htmlContent = view('emails.student-tutoria-notification', [
                'userName' => $studentName,
                'sessionDate' => date('d/m/Y', strtotime($tutoria->start_time)),
                'sessionTime' => date('H:i', strtotime($tutoria->start_time)),
                'meetingLink' => $meetingLink,
                'oppositeName' => $tutorName,
            ])->render();

            Mail::html($htmlContent, function ($message) use ($studentUser) {
                $message->to($studentUser->email)
                    ->subject('🎓 Tu tutoría ha sido confirmada - ClassGo');
            });
        }
    }

    /**
     * Enviar correo al tutor
     */
    private function sendTutorNotification($tutoria, $meetingLink)
    {
        $tutorProfile = optional($tutoria->tutor)->profile;
        $tutorName = $tutorProfile ? ($tutorProfile->first_name . ' ' . $tutorProfile->last_name) : 'Tutor';
        $tutorUser = optional($tutoria->tutor)->user;

        if ($tutorUser) {
            $studentProfile = optional($tutoria->student)->profile;
            $studentName = $studentProfile ? ($studentProfile->first_name . ' ' . $studentProfile->last_name) : 'Estudiante';

            Mail::to($tutorUser->email)->send(new SessionBookingMail([
                'userName' => $tutorName,
                'sessionDate' => date('d/m/Y', strtotime($tutoria->start_time)),
                'sessionTime' => date('H:i', strtotime($tutoria->start_time)),
                'meetingLink' => $meetingLink,
                'role' => 'Estudiante',
                'oppositeName' => $studentName,
            ]));
        }
    }


}