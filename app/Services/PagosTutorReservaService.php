<?php

namespace App\Services;

use App\Models\SlotPayment;
use Illuminate\Support\Facades\DB;

class PagosTutorReservaService
{
    /**
     * Crear un pago para una reserva.
     */
    public function create(array $data): SlotPayment
    {
        return SlotPayment::create($data);
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
