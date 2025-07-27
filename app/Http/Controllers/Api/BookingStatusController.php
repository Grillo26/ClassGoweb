<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SlotBooking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookingStatusController extends Controller
{
    /**
     * Cambia el estado de una tutoría a "Cursando"
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeToCursando(Request $request): JsonResponse
    {
        // Validar que se proporcione el ID de la tutoría
        $request->validate([
            'booking_id' => 'required|integer|exists:slot_bookings,id'
        ]);

        try {
            // Buscar la tutoría
            $booking = SlotBooking::findOrFail($request->booking_id);
            
            // Cambiar el estado a "Cursando" (ID: 6)
            $booking->status = 'Cursando';
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado de la tutoría cambiado a "Cursando" exitosamente',
                'data' => [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                    'status_id' => 6
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la tutoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 