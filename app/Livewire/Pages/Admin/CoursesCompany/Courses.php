<?php

namespace App\Livewire\Pages\Admin\CoursesCompany;

use App\Models\CompanyCourse;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Courses extends Component
{
    use WithFileUploads;

    public $course_id;
  
    public $name;

    public $instructor_name;

    public $editMode = false;

    public $video_url;

    public $video_file;

    public $description;


     protected $rules = [
        'name' => 'required|string|max:255',
        'instructor_name' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
    ];


    #[Layout('layouts.admin-app')]
    public function render()
    {
         $courses= CompanyCourse::orderBy('id','desc')->paginate(10);
         return view('livewire.pages.admin.courses-company.courses',compact('courses'));
    }



    public function resetForm()
    {
        $this->course_id = null;
        $this->name = '';
        $this->instructor_name = '';
        $this->editMode = false;
    }


    public function save()
    {
        $this->validate();

        // Guardar el video si se subió
        if ($this->video_file) {
            $videoPath = $this->video_file->store('courses/videos', 'public');
            $this->video_url = $videoPath;
        }

        if ($this->editMode) {
            $course = CompanyCourse::find($this->course_id);
            $course->update([
                'name' => $this->name,
                'instructor_name' => $this->instructor_name,
                'description' => $this->description,
                'video_url' => $this->video_url ?? $course->video_url,
            ]);
        } else {
            CompanyCourse::create([
                'name' => $this->name,
                'instructor_name' => $this->instructor_name,
                'description' => $this->description,
                'video_url' => $this->video_url ?? null,
            ]);
        }
        session()->flash('message', 'Course saved successfully.');
        $this->resetForm();
    }
    

    public function edit($id)
    {
        $course = CompanyCourse::find($id);
        if ($course) {
            $this->course_id = $course->id;
            $this->name = $course->name;
            $this->instructor_name = $course->instructor_name;
            $this->editMode = true;
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
