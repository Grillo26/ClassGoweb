<?php

namespace App\Livewire\Admin\Tutors;

use App\Livewire\Forms\Admin\User\UserForm;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Tutors extends Component
{
     use WithPagination;

    public $search = '';
    public $sortby = 'desc';
    public $per_page = 10;
    public $roles_list = [];
    public $filterUser = '';
    public $verification = '';

    #[Layout('layouts.admin-app')]
    public function render()
    {
        $tutors = User::whereHas('roles', function ($query) {
                $query->where('name', 'tutor');
            })
            ->with([
                'profile' => function ($q) {
                    $q->select('id', 'user_id', 'first_name', 'last_name', 'slug', 'image');
                }
            ]);

        if (!empty($this->search)) {
            $tutors = $tutors->whereHas('profile', function ($query) {
                $query->where(function ($sub_query) {
                    $sub_query->where('first_name', 'like', '%' . $this->search . '%')
                              ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            });
        }

        if (!empty($this->filterUser)) {
            $tutors = $this->filterUser === 'active' ? $tutors->where('status', 'active') : $tutors->where('status', 'inactive');
        }

        if (!empty($this->verification)) {
            if ($this->verification === 'verified') {
                $tutors = $tutors->whereNotNull('email_verified_at');
            } elseif ($this->verification === 'unverified') {
                $tutors = $tutors->whereNull('email_verified_at');
            }
        }

        $tutors = $tutors->orderBy('id', $this->sortby)->paginate($this->per_page);

        return view('livewire.admin.tutors.tutors', compact('tutors'));
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'filterUser', 'verification', 'sortby'])) {
            $this->resetPage();
        }
    }
   
}
