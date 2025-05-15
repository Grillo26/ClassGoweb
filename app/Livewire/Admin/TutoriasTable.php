<?php

namespace App\Livewire\Admin;

use App\Models\SlotBooking;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class TutoriasTable extends Component
{
    use WithPagination;

    public $tutor = '';
    public $student = '';
    public $status = '';
    public $perPage = 10;
    public $showModal = false;
    public $modalTutoriaId;
    public $modalStatus;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['tutor', 'student', 'status'];

    public function updating($property)
    {
        if (in_array($property, ['tutor', 'student', 'status'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = SlotBooking::with(['tutor', 'student']);
        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->tutor) {
            $query->whereHas('tutor', function($q) {
                $q->where('first_name', 'like', '%'.$this->tutor.'%');
            });
        }
        if ($this->student) {
            $query->whereHas('student', function($q) {
                $q->where('first_name', 'like', '%'.$this->student.'%');
            });
        }
        $tutorias = $query->orderByDesc('start_time')->paginate($this->perPage);
        return view('livewire.admin.tutorias-table', compact('tutorias'));
    }

    public function abrirModalTutoria($id, $status)
    {
        $this->modalTutoriaId = $id;
        $this->modalStatus = $status;
    }

    public function updateStatus()
    {
        $tutoria = SlotBooking::find($this->modalTutoriaId);
        if ($tutoria) {
            $tutoria->status = $this->modalStatus;
            $tutoria->save();
        }
        $this->dispatch('cerrar-modal-tutoria');
    }

    public function cerrarModalTutoria()
    {
        $this->showModal = false;
    }
}
