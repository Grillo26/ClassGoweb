<?php


namespace App\Services;

use App\Services\interfaces;
use  App\Models\User;
use  App\Models\SlotBooking;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\Eloquent\Collection;

class SlotBookingService implements interfaces\ISlotBookingService
{
    
    public function getSlotBookingByUserId(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        if (!$user) {
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
        return SlotBooking::query()->whereRaw('1 = 0');
    }

    public function bookSlot($slotId, $userId, $additionalData = [])
    {
        // Implementación de la lógica para reservar un slot
    }
}