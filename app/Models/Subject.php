<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model {
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    public $fillable  = [
        'id', 'name', 'description', 'status', 'deleted_at', 'subject_group_id'
    ];

    public function group()
    {
        return $this->belongsTo(SubjectGroup::class, 'subject_group_id');
    }

    public function userSubjects(): HasMany
    {
        return $this->hasMany(UserSubject::class);
    }

    public function slotBookings(): HasMany
    {
        return $this->hasMany(SlotBooking::class, 'subject_id');
    }

}
