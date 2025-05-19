<?php

namespace App\Livewire\Admin;

use App\Models\SlotBooking;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Services\ZoomService;

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
        $query = SlotBooking::with(['tutor', 'student', 'paymentSlotBooking']);
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
            // Si el nuevo estado es 'aceptado', crear reunión Zoom
            if ($this->modalStatus === 'aceptado') {
                $zoomService = new ZoomService();
                $startTime = $tutoria->start_time ? date('c', strtotime($tutoria->start_time)) : null;
                $duration = null;
                if ($tutoria->start_time && $tutoria->end_time) {
                    $start = strtotime($tutoria->start_time);
                    $end = strtotime($tutoria->end_time);
                    $duration = 20;
                }

                //dd($startTime, $duration,);
                $meetingData = [
                    'host_email' => $tutoria->tutor?->email,
                    'topic' => 'Tutoría',
                    'agenda' => 'Sesión de tutoría',
                    'duration' => '20',
                    'timezone' => 'America/La_Paz', 
                    'start_time' => $startTime,
                ];
                $zoomResponse = $zoomService->createMeeting($meetingData);
                if ($zoomResponse['status'] && !empty($zoomResponse['data']['join_url'])) {
                    $tutoria->meeting_link = $zoomResponse['data']['join_url'];
                }
            }
            $tutoria->save();
        }
        $this->dispatch('cerrar-modal-tutoria');
    }

    public function cerrarModalTutoria()
    {
        $this->showModal = false;
    }
}
