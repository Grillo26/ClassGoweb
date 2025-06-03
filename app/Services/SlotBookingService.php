<?php


namespace App\Services;

use App\Services\interfaces;
use  App\Models\User;
use  App\Models\SlotBooking;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\Eloquent\Collection;

class SlotBookingService implements interfaces\ISlotBookingService
{
    
    public function getSlotBookingByUserId(): Collection
    {
        $user = Auth::user();
        if (!$user) {
            return collect(); // Si no hay usuario logueado, retorna colección vacía
        }
        if ($user->hasRole('tutor')) {
            // Si es tutor, recupera el registro del usuario (estudiante) y su perfil
            return SlotBooking::with(['booker', 'booker.profile', 'subject', 'slot'])
                ->where('tutor_id', $user->id)
                ->where('status', 5)
                ->get();
        } elseif ($user->hasRole('student')) {
            // Si es estudiante, recupera el registro del tutor y su perfil
            return SlotBooking::with(['tutor', 'tutor.profile', 'subject', 'slot'])
                ->where('student_id', $user->id)
                ->where('status', 5)
                ->get();
        }
        // Si no es ninguno, retorna colección vacía
        return collect();
    }

    public function bookSlot($slotId, $userId, $additionalData = [])
    {
        // Implementación de la lógica para reservar un slot
    }
}