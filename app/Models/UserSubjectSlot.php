<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class UserSubjectSlot extends Model {
    use HasFactory;


    protected $table = 'user_subject_slots';
    protected $fillable = [
        'start_time',
        'end_time',
        'duracion',
        'date',
        'user_id',
        'session_fee',
        'description',
        'total_booked',
        'meta_data'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date:Y-m-d',
        'meta_data' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany {
        return $this->hasMany(SlotBooking::class, 'user_subject_slot_id');
    }

    public function students(): HasManyThrough {
        return $this->hasManyThrough(Profile::class, SlotBooking::class, 'user_subject_slot_id', 'user_id', 'id', 'student_id');
    }
}
