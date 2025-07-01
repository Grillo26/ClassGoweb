<?php

namespace App\Livewire\Pages\Student;

use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
class Promociones extends Component
{
    use WithPagination;

    public $search = '';
    public $sortby = 'desc';
    public $status = '';
    public $user;

    public $student;

    public function boot()
    {
        $this->user = Auth::user();
    }

    public function mount()
    {

    }

    public function loadData()
    {
        $this->isLoading = false;
        $this->dispatch('loadPageJs');
    }

    public function render()
    {
        return view('livewire.pages.student.promociones');
    }
}
