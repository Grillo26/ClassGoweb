<?php

namespace App\Livewire\Pages\Tutor\CompanyCourses;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\CompanyCourse;
use Illuminate\Support\Facades\Auth;

class Courses extends Component
{
    public $currentCourse;
    public $exam;
    public $answers = [];

    public function mount()
    {
        $user = Auth::user();
        $courses = CompanyCourse::whereHas('users', function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->whereIn('company_course_user.status', ['pending', 'in_progress']);
        })->with(['users' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }, 'exams.questions'])->get();

        // Ordenar en PHP: primero pending, luego in_progress
        $this->currentCourse = $courses->sortBy(function($course) use ($user) {
            $status = $course->users->first()?->pivot?->status;
            return $status === 'pending' ? 0 : 1;
        })->first();
        $this->exam = $this->currentCourse?->exams?->first();
    }

    public function submitExam()
    {
        $user = Auth::user();
        $exam = $this->exam;
        $questions = $exam->questions;
        $validated = $this->validate([
            'answers' => ['required', 'array'],
        ]);
        $score = 0;
        $total = 0;
        $allCorrect = true;
        foreach ($questions as $q) {
            $qid = $q->id;
            $userAnswer = $this->answers[$qid] ?? null;
            $correct = false;
            if ($q->type === 'opcion_unica') {
                // DEBUG: Log valores para comparar
                \Log::debug('Pregunta', [
                    'id' => $qid,
                    'userAnswer' => $userAnswer,
                    'correct_answer' => $q->correct_answer,
                    'comparacion' => $userAnswer == $q->correct_answer
                ]);
                $correct = $userAnswer !== null && $userAnswer == $q->correct_answer;
            } else {
                // Si no es de selección única, no se considera para el examen
                continue;
            }
            if ($correct) {
                $score += $q->score;
            } else {
                $allCorrect = false;
            }
            $total += $q->score;
        }
        if ($allCorrect) {
            // Marcar como completado en la tabla pivot
            $pivot = \App\Models\CompanyCourseUser::where('company_course_id', $this->currentCourse->id)
                ->where('user_id', $user->id)
                ->first();
            if ($pivot) {
                $pivot->status = 'completed';
                $pivot->save();
            }
            session()->flash('exam_success', '¡Examen correcto! Curso completado.');
            // Buscar el siguiente curso pendiente o en progreso
            $courses = \App\Models\CompanyCourse::whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->whereIn('company_course_user.status', ['pending', 'in_progress']);
            })->with(['users' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }, 'exams.questions'])->get();
            $this->currentCourse = $courses->sortBy(function($course) use ($user) {
                $status = $course->users->first()?->pivot?->status;
                return $status === 'pending' ? 0 : 1;
            })->first();
            $this->exam = $this->currentCourse?->exams?->first();
            $this->answers = [];
            $this->dispatch('close-exam-modal');
        } else {
            session()->flash('exam_error', 'Algunas respuestas son incorrectas. Intenta de nuevo.');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.tutor.company-courses.courses', [
            'currentCourse' => $this->currentCourse,
            'exam' => $this->exam
        ]);
    }
}
