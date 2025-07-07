<?php

namespace App\Http\Livewire\Admin;

use App\Models\SlotBooking;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class TutoriaStatusModal extends Component
{
    public $tutoriaId;
    public $status;
    public $statusOptions = [];

    public function mount($tutoriaId, $status)
    {
        $this->statusOptions = [
            'pendiente' => 'Pendiente',
            'rechazado' => 'Rechazado',
            'aceptado' => 'Aceptado',
            'no_completado' => 'No completado',
            'completado' => 'Completado',
            'cursando' => 'Cursando',
        ];
        $this->tutoriaId = $tutoriaId;
        $this->status = $status;
    }

    public function updateStatus()
    {
        $tutoria = SlotBooking::find($this->tutoriaId);
        if ($tutoria) {
            $tutoria->status = $this->status;
            $tutoria->save();
            $this->emit('tutoriaStatusUpdated');
        }
    }

    public function close()
    {
        $this->emitUp('cerrarModalTutoria');
    }

    public function render()
    {
        $this->dispatchBrowserEvent('modal-debug', ['msg' => 'Método render ejecutado']);
        return view('livewire.admin.tutoria-status-modal');
    }
} 