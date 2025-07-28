<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SlotBooking;
use App\Services\BookingNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
            
            // Guardar el estado anterior para comparar
            $oldStatus = $booking->status;
            
            // Cambiar el estado a "Cursando" (ID: 6)
            $booking->status = 'Cursando';
            $booking->save();

            // Usar el servicio centralizado para manejar notificaciones
            $notificationService = new BookingNotificationService();
            $notificationService->handleStatusChangeNotification($booking, $oldStatus, 'Cursando');

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
            Log::error('Error al cambiar estado de tutoría a Cursando', [
                'booking_id' => $request->booking_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la tutoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambia el estado de una tutoría a "Aceptado" con notificación intensa
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeToAceptado(Request $request): JsonResponse
    {
        // Validar que se proporcione el ID de la tutoría
        $request->validate([
            'booking_id' => 'required|integer|exists:slot_bookings,id'
        ]);

        try {
            // Buscar la tutoría
            $booking = SlotBooking::findOrFail($request->booking_id);
            
            // Guardar el estado anterior para comparar
            $oldStatus = $booking->status;
            
            // Cambiar el estado a "Aceptado" (ID: 1)
            $booking->status = 'Aceptado';
            $booking->save();

            // Usar el servicio centralizado para manejar notificaciones
            $notificationService = new BookingNotificationService();
            $notificationService->handleStatusChangeNotification($booking, $oldStatus, 'Aceptado');

            return response()->json([
                'success' => true,
                'message' => 'Estado de la tutoría cambiado a "Aceptado" exitosamente',
                'data' => [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                    'status_id' => 1
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de tutoría a Aceptado', [
                'booking_id' => $request->booking_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la tutoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }


} 