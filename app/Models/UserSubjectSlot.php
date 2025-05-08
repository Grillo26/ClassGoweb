<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class UserSubjectSlot extends Model {
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'duracion',
        'date',
        'user_id',
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
        'date' => 'date:Y-m-d',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subjects() {
        return $this->belongsToMany(Subject::class, 'user_subject_group_subjects', 'subject_id', 'id');
    }

    public function bookings(): HasMany{
        return $this->hasMany(SlotBooking::class, 'user_subject_slot_id');
    }

    public function students(): HasManyThrough{
        return $this->hasManyThrough(Profile::class, SlotBooking::class, 'user_subject_slot_id', 'user_id', 'id', 'student_id');
    }
    
}
