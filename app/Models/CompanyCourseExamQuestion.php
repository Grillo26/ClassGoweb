<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCourseExamQuestion extends Model
{
    //

    protected $table = "company_course_exam_questions";

    protected $fillable = [
        'company_course_exam_id',
        'question',
        'type',
        'score',
        'options',
        'correct_answer',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(CompanyCourseExam::class, 'company_course_exam_id');
    }
}
