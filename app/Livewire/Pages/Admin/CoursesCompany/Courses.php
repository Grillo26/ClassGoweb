<?php

namespace App\Livewire\Pages\Admin\CoursesCompany;

use App\Models\CompanyCourse;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Courses extends Component
{
    use WithFileUploads, WithPagination;

    public $course_id;

    public $name;

    public $instructor_name;

    public $editMode = false;

    public $video_url;

    public $video_file;

    public $description;

    public $exam_questions = [];
    public $showQuestionModal = false;
    public $question_text = '';
    public $question_type = 'opcion_unica';
    public $question_score = 0;
    public $question_options = '';
    public $question_correct = '';

    public $question_options_list = [];
    public $question_option_input = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'instructor_name' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
        'video_url' => 'required|string|max:255',
    ];


    #[Layout('layouts.admin-app')]
    public function render()
    {
        $courses = CompanyCourse::orderBy('id', 'desc')->paginate(10);
        return view('livewire.pages.admin.courses-company.courses', compact('courses'));
    }



    public function resetForm()
    {
        $this->course_id = null;
        $this->name = '';
        $this->instructor_name = '';
        $this->editMode = false;
    }

    public function openQuestionModal()
    {
        $this->showQuestionModal = true;
        $this->resetQuestionForm();
    }

    public function closeQuestionModal()
    {
        $this->showQuestionModal = false;
    }

    public function addOption()
    {
        $option = trim($this->question_option_input);
        if ($option !== '') {
            $this->question_options_list[] = $option;
            $this->question_option_input = '';
        }
    }

    public function removeOption($idx)
    {
        unset($this->question_options_list[$idx]);
        $this->question_options_list = array_values($this->question_options_list);
    }

    public function resetQuestionOptions()
    {
        $this->question_options_list = [];
        $this->question_option_input = '';
        $this->question_correct = '';
    }

    public function addExamQuestion()
    {
        // Solo permitimos preguntas de opción única
        $options = $this->question_options_list;
        $correct = $this->question_correct;
        $this->exam_questions[] = [
            'question' => $this->question_text,
            'type' => 'opcion_unica',
            'score' => $this->question_score,
            'options' => $options,
            'correct_answer' => $correct,
        ];
        $this->resetQuestionForm();
        $this->showQuestionModal = false;
        $this->resetQuestionOptions();
    }

    public function resetQuestionForm()
    {
        $this->question_text = '';
        $this->question_type = 'opcion_unica';
        $this->question_score = 0;
        $this->question_correct = '';
        $this->resetQuestionOptions();
    }






    public function save()
    {
        $this->validate();
        // Ya no se sube archivo, solo se guarda el link
        if ($this->editMode) {
            $course = CompanyCourse::find($this->course_id);
            $course->update([
                'name' => $this->name,
                'instructor_name' => $this->instructor_name,
                'description' => $this->description,
                'video_url' => $this->video_url,
            ]);
            // Actualizar preguntas del examen
            $exam = $course->exams()->first();
            if ($exam) {
                $exam->questions()->delete(); // Elimina las preguntas anteriores
                if (!empty($this->exam_questions)) {
                    foreach ($this->exam_questions as $q) {
                        $exam->questions()->create([
                            'question' => $q['question'],
                            'type' => $q['type'],
                            'score' => $q['score'],
                            'options' => $q['options'],
                            'correct_answer' => $q['correct_answer'],
                        ]);
                    }
                }
            }
        } else {
            $course = CompanyCourse::create([
                'name' => $this->name,
                'instructor_name' => $this->instructor_name,
                'description' => $this->description,
                'video_url' => $this->video_url,
            ]);
            $tutors = User::whereHas('roles', function ($q) {
                $q->where('name', 'tutor');
            })->get();
            foreach ($tutors as $tutor) {
                $course->users()->attach($tutor->id, ['status' => 'pending']);
            }
            $examn = $course->exams()->create([
                'title' => 'Examen de ' . $course->name,
                'total_score' => 100,
            ]);
            // Guardar preguntas del array exam_questions
            //dd($this->exam_questions);
            if (!empty($this->exam_questions)) {
                foreach ($this->exam_questions as $q) {
                    $examn->questions()->create([
                        'question' => $q['question'],
                        'type' => $q['type'],
                        'score' => $q['score'],
                        'options' => $q['options'],
                        'correct_answer' => $q['correct_answer'],
                    ]);
                }
            }
        }
        session()->flash('message', 'Course saved successfully.');
        $this->resetForm();
        $this->exam_questions = [];
    }


    public function edit($id)
    {
        $course = CompanyCourse::find($id);
        if ($course) {
            $this->course_id = $course->id;
            $this->name = $course->name;
            $this->instructor_name = $course->instructor_name;
            $this->description = $course->description;
            $this->video_url = $course->video_url;
            $this->editMode = true;
            $this->exam_questions = $course->exams->first()->questions->map(function ($question) {
                return [
                    'question' => $question->question,
                    'type' => $question->type,
                    'score' => $question->score,
                    'options' => $question->options,
                    'correct_answer' => $question->correct_answer,
                ];
            })->toArray();
        } else {
            session()->flash('error', 'Course not found.');
        }
    }


    public function delete($id)
    {
        $course = CompanyCourse::find($id);
        if ($course) {
            $course->delete();
            session()->flash('message', 'Course deleted successfully.');
        } else {
            session()->flash('error', 'Course not found.');
        }
    }

}
