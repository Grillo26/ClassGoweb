<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSlotBooking extends Model
{
    use HasFactory;
  
    protected $table = 'payment_slot_bookings';

    protected $fillable = [
        'id',
        'image_url',
        'created_at',
        'updated_at',
        'slot_booking_id',
    ];

    public function slotBooking()
    {
        return $this->belongsTo(SlotBooking::class, 'slot_booking_id');
    }
  
}