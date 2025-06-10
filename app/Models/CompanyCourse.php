<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCourse extends Model
{
    //

    protected $table= 'company_courses';

    protected $fillable = [
        'name',
        'instructor_name',
        'video_url',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_course_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function exams()
    {
        return $this->hasMany(CompanyCourseExam::class);
    }
}
