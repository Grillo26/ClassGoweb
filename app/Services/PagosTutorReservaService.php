<?php

namespace App\Services;

use App\Models\SlotPayment;
use Illuminate\Support\Facades\DB;

class PagosTutorReservaService
{
    /**
     * Crear un pago para una reserva desde la reserva que genero el estudiante.
     */
    public function create(
        $slot_booking_id,
        $payment_date,
        $amount,
        $message,
      
    ): SlotPayment {
        return SlotPayment::create([
            'slot_booking_id' => $slot_booking_id,
            'payment_date' => $payment_date,
            'payment_method' => "",
            'amount' => $amount,
            'status' => 1,
            'message' => $message,
        ]);
    }

    /**
     * Actualizar los datos generales del pago (excepto estado).
     */
    public function update(int $id, array $data): bool
    {
        $payment = SlotPayment::findOrFail($id);
        // Evita modificar el estado aquí
        unset($data['status']);
        return $payment->update($data);
    }

    /**
     * Actualizar solo el estado y el mensaje del pago.
     */
    public function updateStatus(int $id, int $status, ?string $message = null): bool
    {
        $payment = SlotPayment::findOrFail($id);
        $payment->status = $status;
        if ($message !== null) {
            $payment->message = $message;
        }
        return $payment->save();
    }
}
