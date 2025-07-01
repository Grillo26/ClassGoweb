<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'slot_booking_id',
        'description',
        'image_url',
        'status',
    ];

    protected $table = 'claims'; 

    public function slotBooking()
    {
        return $this->belongsTo(SlotBooking::class);
    }
}