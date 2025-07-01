<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubjectSlot;

class SubjectSlotController extends Controller
{
    public function getUserSubjectSlots(Request $request)
    {
        // Obtener `user_id` y fechas de la solicitud
        $userId = $request->input('user_id');
        $date = $request->only(['start_date', 'end_date']);

        // Validar que se haya enviado el user_id
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        // Obtener los horarios filtrados por usuario
        $slotsData = $this->fetchUserSubjectSlots($userId, $date);

        return response()->json($slotsData);
    }

    public function getTutorAvailableSlots($id, Request $request)
    {
        $date = $request->only(['start_date', 'end_date']);
        $slotsData = $this->fetchUserSubjectSlots($id, $date);
        return response()->json($slotsData);
    }

    protected function fetchUserSubjectSlots($userId, $date = null) {
        $slots = UserSubjectSlot::select('id','start_time','end_time','duracion','date','user_id')
            ->withCount('bookings')
            ->with('students', fn($query) => $query->select('profiles.id','profiles.user_id', 'profiles.image')->limit(5))
            ->when($date, function ($slots) use ($date) {
                $slots->where('start_time', '>=', $date['start_date']);
                $slots->where('end_time', '<=', $date['end_date']);
            })
            ->where('user_id', $userId)
            ->orderBy('start_time')
            ->get();

        return $slots;
    }
}
