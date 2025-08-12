<?php


namespace App\Services;

use App\Services\interfaces;
use  App\Models\User;
use  App\Models\SlotBooking;
use App\Models\UserSubjectSlot; 
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\Eloquent\Collection;

class SlotBookingService implements interfaces\ISlotBookingService
{
    
    public function getSlotBookingByUserId(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();

        if ($user->hasRole('student')) {
            return SlotBooking::where('student_id', $user->id);
        }
        else{
            return SlotBooking::where('tutor_id', $user->id);
        }

       /*  if (!$user) {
            return SlotBooking::query()->whereRaw('1 = 0'); // Retorna una consulta vacía
        }
        if ($user->hasRole('tutor')) {
            // Si es tutor, recupera el registro del usuario (estudiante) y su perfil
            return SlotBooking::with(['booker', 'booker.profile', 'subject', 'slot'])
                ->where('tutor_id', $user->id);
        } elseif ($user->hasRole('student')) {
            // Si es estudiante, recupera el registro del tutor y su perfil
            return SlotBooking::with(['tutor', 'subject', 'slot'])
                ->where('student_id', $user->id);
        }
        // Si no es ninguno, retorna una consulta vacía
        return SlotBooking::query()->whereRaw('1 = 0'); */
    }

    public function bookSlot($slotId, $userId, $additionalData = [])
    {
        // Implementación de la lógica para reservar un slot
    }


    public function tiempoLibreTutor($tutorId)
    {
         return UserSubjectSlot::where('user_id', $tutorId)->get();
    }



    public function crearReserva( $studentId, $tutorId, $subjectId,$fecha)
    {
        





        $startTime = \Carbon\Carbon::parse($fecha); 
        $endTime = $startTime->copy()->addMinutes(20);
        

        // Crear la reserva
        $booking = new SlotBooking();
        //$booking->user_subject_slot_id = $slotId;
        $booking->student_id = $studentId;
        $booking->tutor_id = $tutorId;
        $booking->subject_id = $subjectId;
        $booking->session_fee=15;
        $booking->start_time = $fecha; // Asignar la fecha completa
        $booking->end_time = $endTime->format('Y-m-d H:i:s');     // Convertir de vuelta a string para la BD
        $booking->booked_at = now();
        $booking->user_subject_slot_id = null; // Asignar el ID del slot creado
        $booking->status = 2; // Estado inicial
        $booking->save();
        return $booking;
    }

    
}