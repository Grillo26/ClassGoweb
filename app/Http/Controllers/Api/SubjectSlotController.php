<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubjectSlot;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Crear un nuevo slot de disponibilidad para un tutor
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUserSubjectSlot(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'date' => 'required|date|after_or_equal:today',
            'session_fee' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'duracion' => 'nullable|integer|min:1', // en minutos
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Calcular duración automáticamente si no se proporciona
            $startTime = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
            $endTime = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);
            $duracion = $request->duracion ?? $startTime->diffInMinutes($endTime);

            // Crear el slot
            $slot = UserSubjectSlot::create([
                'user_id' => $request->user_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'date' => $request->date,
                'session_fee' => $request->session_fee,
                'description' => $request->description,
                'duracion' => $duracion,
                'total_booked' => 0,
                'meta_data' => []
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Slot de disponibilidad creado exitosamente',
                'data' => $slot
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el slot de disponibilidad',
                'error' => $e->getMessage()
            ], 500);
        }
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
