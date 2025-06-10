<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCourseUser extends Model
{
    use HasFactory;

    protected $table = 'company_course_user';

    protected $fillable = [
        'company_course_id',
        'user_id',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(CompanyCourse::class, 'company_course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
