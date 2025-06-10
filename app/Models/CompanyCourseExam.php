<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCourseExam extends Model
{
    //
    protected $table= 'company_course_exams';

    protected $fillable = [
        'company_course_id',
        'title',
        'total_score',
    ];

    public function course()
    {
        return $this->belongsTo(CompanyCourse::class, 'company_course_id');
    }

    public function questions()
    {
        return $this->hasMany(CompanyCourseExamQuestion::class);
    }
}
