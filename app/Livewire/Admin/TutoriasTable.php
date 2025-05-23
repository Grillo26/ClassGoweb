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
        $map = [
            1 => 'aceptado',
            2 => 'pendiente',
            3 => 'no_completado',
            4 => 'rechazado',
            5 => 'completado',
        ];
        $this->modalTutoriaId = $id;
        $this->modalStatus = $map[$status] ?? 'pendiente';
    }

    public function updateStatus()
    {
        $tutoria = SlotBooking::find($this->modalTutoriaId);
        if ($tutoria) {
            $estados = [
                'aceptado'      => 1,
                'pendiente'     => 2,
                'no_completado' => 3,
                'rechazado'     => 4,
                'completado'    => 5,
            ];
            $nuevoStatus = $this->modalStatus;
            if (!is_numeric($nuevoStatus)) {
                $nuevoStatus = $estados[strtolower($nuevoStatus)] ?? 2;
            }
            $tutoria->status = $nuevoStatus;
            // Si el nuevo estado es 'Aceptada' (3), crear reunión Zoom y enviar correos
            if ($nuevoStatus == 1) {
                $zoomService = new ZoomService();
                $startTime = $tutoria->start_time ? date('c', strtotime($tutoria->start_time)) : null;
                $duration = null;
                if ($tutoria->start_time && $tutoria->end_time) {
                    $start = strtotime($tutoria->start_time);
                    $end = strtotime($tutoria->end_time);
                    $duration = 20;
                }

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

                // Enviar correo al estudiante
                $notifyService = new \App\Services\NotificationService();
                $studentProfile = $tutoria->student->profile;
                $studentName = $studentProfile ? ($studentProfile->first_name . ' ' . $studentProfile->last_name) : '';
                $studentTemplate = $notifyService->parseEmailTemplate('sessionBooking', 'student', [
                    'userName' => $studentName,
                    'bookingDetails' => "Fecha: " . date('d/m/Y H:i', strtotime($tutoria->start_time)) . "<br>Enlace de Zoom: " . $tutoria->meeting_link
                ]);
                $studentUser = $tutoria->student?->user;
                if ($studentUser) {
                    $studentUser->notify(new \App\Notifications\EmailNotification($studentTemplate));
                }

                // Enviar correo al tutor
                $tutorProfile = $tutoria->tutor->profile;
                $tutorName = $tutorProfile ? ($tutorProfile->first_name . ' ' . $tutorProfile->last_name) : '';
                $tutorTemplate = $notifyService->parseEmailTemplate('sessionBooking', 'tutor', [
                    'userName' => $tutorName,
                    'bookingDetails' => "Fecha: " . date('d/m/Y H:i', strtotime($tutoria->start_time)) . "<br>Enlace de Zoom: " . $tutoria->meeting_link
                ]);
                $tutorUser = $tutoria->tutor?->user;
                if ($tutorUser) {
                    $tutorUser->notify(new \App\Notifications\EmailNotification($tutorTemplate));
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
