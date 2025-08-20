<?php


namespace App\Services;

use App\Services\interfaces;
use App\Models\User;
use App\Models\SlotBooking;
use App\Models\UserSubjectSlot;
use Illuminate\Support\Facades\Auth;


class SlotBookingService implements interfaces\ISlotBookingService
{

    public function getSlotBookingByUserId(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        if ($user->hasRole('student')) {
            return SlotBooking::where('student_id', $user->id)
                ->orderBy('start_time', 'desc'); 
        } else {
            return SlotBooking::where('tutor_id', $user->id)
                ->orderBy('start_time', 'desc');
        }
    }

    public function bookSlot($slotId, $userId, $additionalData = [])
    {
        // Implementación de la lógica para reservar un slot
    }

    public function tiempoLibreTutor($tutorId)
    {
        return UserSubjectSlot::where('user_id', $tutorId)->get();
    }

    public function crearReserva($studentId, $tutorId, $subjectId, $fecha)
    {

        $startTime = \Carbon\Carbon::parse($fecha);
        $endTime = $startTime->copy()->addMinutes(20);
        // Crear la reserva
        $booking = new SlotBooking();
        //$booking->user_subject_slot_id = $slotId;
        $booking->student_id = $studentId;
        $booking->tutor_id = $tutorId;
        $booking->subject_id = $subjectId;
        $booking->session_fee = 15;
        $booking->start_time = $fecha; // Asignar la fecha completa
        $booking->end_time = $endTime->format('Y-m-d H:i:s');     // Convertir de vuelta a string para la BD
        $booking->booked_at = now();
        $booking->user_subject_slot_id = null; // Asignar el ID del slot creado
        $booking->status = 2; // Estado inicial
        $booking->save();
        return $booking;
    }


}