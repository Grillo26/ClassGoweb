<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Subject;

class BuscarTutor extends Component
{
    use WithPagination;

    public $search = '';
    public $materia = '';
    public $perPage = 10;

    protected $updatesQueryString = ['search', 'materia', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingMateria()
    {
        $this->resetPage();
    }

    public function render()
    {
        $materias = Subject::orderBy('name')->get();

        $tutores = User::whereHas('profile', function($q) {
                $q->whereNotNull('verified_at');
            })
            ->whereHas('userSubjects.subject', function($q) {
                if ($this->materia) {
                    $q->where('subjects.id', $this->materia);
                }
                if ($this->search) {
                    $q->where('subjects.name', 'like', '%'.$this->search.'%');
                }
            })
            ->when($this->search, function($q) {
                $q->whereHas('profile', function($q2) {
                    $q2->where(function($q3) {
                        $q3->where('first_name', 'like', '%'.$this->search.'%')
                            ->orWhere('last_name', 'like', '%'.$this->search.'%');
                    });
                });
            })
            ->with(['profile', 'userSubjects.subject', 'languages'])
            ->withAvg('reviews as avg_rating', 'rating')
            ->withCount('reviews as total_reviews')
            ->paginate($this->perPage);

        return view('livewire.buscar-tutor', [
            'tutores' => $tutores,
            'materias' => $materias,
        ]);
    }
} 