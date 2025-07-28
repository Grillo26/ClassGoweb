<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SlotBooking;
use App\Services\BookingNotificationService;
use Illuminate\Http\Request;
use function view;
use function compact;
use function redirect;

class SlotBookingAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\SlotBooking::with(['tutor', 'student']);

        // Filtros básicos (por estado, por tutor, por estudiante)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tutor')) {
            $query->whereHas('tutor', function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->tutor.'%');
            });
        }
        if ($request->filled('student')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->student.'%');
            });
        }

        $tutorias = $query->orderByDesc('start_time')->paginate(10);
        return view('admin.tutorias.index', compact('tutorias'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $tutoria = SlotBooking::findOrFail($id);
        
        // Guardar el estado anterior para comparar
        $oldStatus = $tutoria->status;
        
        // Actualizar el estado
        $tutoria->status = $request->status;
        $tutoria->save();

        // Usar el servicio centralizado para manejar notificaciones
        $notificationService = new BookingNotificationService();
        $notificationService->handleStatusChangeNotification($tutoria, $oldStatus, $request->status);

        return redirect()->route('admin.tutorias.index')->with('success', 'Estado actualizado');
    }

    public static function getStatusOptions()
    {
        return [
            'pendiente' => 'Pendiente',
            'rechazado' => 'Rechazado',
            'aceptado' => 'Aceptado',
            'no_completado' => 'No completado',
            'completado' => 'Completado',
            'cursando' => 'Cursando',
        ];
    }
} 