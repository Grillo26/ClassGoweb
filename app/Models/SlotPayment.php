<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SlotPayment extends Model
{
    use HasFactory;

    protected $table = 'slot_payments';

    protected $fillable = [
        'slot_booking_id',
        'payment_date',
        'payment_method',
        'amount',
        'status',
        'message',
        'receipt_pdf',
    ];

    // Relación con la reserva
    public function slotBooking()
    {
        return $this->belongsTo(SlotBooking::class, 'slot_booking_id');
    }

    // Estados posibles
    const STATUS_PENDIENTE = 1;
    const STATUS_PAGADO = 2;
    const STATUS_OBSERVADO = 3;
    const STATUS_CANCELADO = 4;

    public static function statusLabels()
    {
        return [
            self::STATUS_PENDIENTE => 'Pendiente',
            self::STATUS_PAGADO => 'Pagado',
            self::STATUS_OBSERVADO => 'Observado',
            self::STATUS_CANCELADO => 'Cancelado',
        ];
    }
}
