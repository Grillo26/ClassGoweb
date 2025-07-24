<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Subject;
use App\Services\SiteService;


class kkkk extends Component
{
    use WithPagination;

    public $search = '';
    public $materia = '';
    public $perPage = 10;

    public $count = 0;

    protected $updatesQueryString = ['search', 'materia', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingMateria()
    {
        $this->resetPage();
    }

     public function increment()
    {
        $this->count++;
    }   

    public function render()
    {
       $count = 3;
        return view('livewire.kkkk', ['count' => $count]);
    }
} 