<?php

namespace App\Livewire\Admin;

use App\Models\SlotBooking;
use App\Services\GoogleMeetService;
use App\Services\MailService;
use App\Services\BookingNotificationService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Services\ZoomService;
use App\Mail\SessionBookingMail;
use Illuminate\Support\Facades\Mail;

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

    public $fecha; // Para una sola fecha
    public $fecha_inicio;
    public $fecha_fin;

    public $modalPaymentStatus;
    public $modalPaymentMethod;
    public $modalPaymentMessage;
    public $modalPaymentId;

    public $successMessage = '';

    public $errorMessage = '';


    //protected $queryString = ['tutor', 'student', 'status'];

    public function updating($property)
    {
        if (in_array($property, ['tutor', 'student', 'status'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = SlotBooking::with(['tutor', 'student', 'paymentSlotBooking', 'payment']);



        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->tutor) {
            $query->whereHas('tutor', function ($q) {
                $q->where('first_name', 'like', '%' . $this->tutor . '%');
            });
        }

        if ($this->student) {
            $query->whereHas('student', function ($q) {
                $q->where('first_name', 'like', '%' . $this->student . '%');
            });
        }

        if ($this->status) {
            $this->status = $this->estado($this->status);
            $query->where('status', $this->status);
        }

        // Filtro por una sola fecha
        if ($this->fecha) {
            $query->whereDate('start_time', $this->fecha);
        } elseif ($this->fecha_inicio && $this->fecha_fin) {
            $query->whereBetween('start_time', [$this->fecha_inicio, $this->fecha_fin . ' 23:59:59']);
        }
        $tutorias = $query->orderByDesc('start_time')->paginate($this->perPage);

        return view('livewire.admin.tutorias-table', compact('tutorias'));
    }



    public function estado($status)
    {
        switch ($status) {
            case 'pendiente':
                return 2;
            case 'aceptado':
                return 1;
            case 'no_completado':
                return 3;
            case 'rechazado':
                return 4;
            case 'completado':
                return 5;
            default:
                return 'Desconocido';
        }

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
                'aceptado' => 1,
                'pendiente' => 2,
                'no_completado' => 3,
                'rechazado' => 4,
                'completado' => 5,
                'cursando' => 6,
            ];
            Log::info('Valor recibido en modalStatus:', ['modalStatus' => $this->modalStatus]);
            $nuevoStatus = $this->modalStatus;
            if (!is_numeric($nuevoStatus)) {
                $nuevoStatus = $estados[strtolower($nuevoStatus)] ?? 2;
            }
            Log::info('Valor de status que se guardará:', ['nuevoStatus' => $nuevoStatus]);
            $tutoria->status = $nuevoStatus;
            // Si el nuevo estado es 'Aceptada' (3), crear reunión Zoom y enviar correos
            if ($nuevoStatus == 1) {

                $googlemeetservice = new GoogleMeetService;
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


                $meetingDatameet = [
                    'title' => 'Reunión de Prueba',
                    'description' => 'Esta es una reunión de prueba con Google Meet',
                    'start_time' => now()->addHour()->toISOString(), // En 1 hora
                    'end_time' => now()->addHour()->addMinutes(30)->toISOString(), // Duración 30 min
                    'timezone' => 'America/La_Paz',
                ];

                $zoomResponse = $zoomService->createMeeting($meetingData);
                
                if ($zoomResponse['status']) {
                    $joinUrl = $zoomResponse['data']['join_url'];
                    $tutoria->meeting_link = $joinUrl;
                    Log::info('TutoriasTable: Enlace de Zoom creado exitosamente', ['join_url' => $joinUrl]);
                } else {
                    Log::warning('TutoriasTable: No se pudo crear reunión de Zoom', [
                        'error' => $zoomResponse['message'] ?? 'Error desconocido',
                        'booking_id' => $tutoria->id
                    ]);
                    // Continuar sin enlace de reunión
                    $tutoria->meeting_link = null;
                }
                // dd($result);
                $studentProfile = $tutoria->student->profile;
                $studentName = $studentProfile ? ($studentProfile->first_name . ' ' . $studentProfile->last_name) : '';
                $studentUser = $tutoria->student?->user;
                $mailService = new MailService();
                $mailService->sendTutoriaNotification($tutoria, $joinUrl);

                // Enviar correo al tutor
                $tutorProfile = $tutoria->tutor->profile;
                $tutorName = $tutorProfile ? ($tutorProfile->first_name . ' ' . $tutorProfile->last_name) : '';
                $tutorUser = $tutoria->tutor?->user;

            }
            
            // Guardar el estado anterior para comparar
            $oldStatus = $tutoria->status;
            
            // Usar el servicio centralizado para manejar notificaciones ANTES de guardar
            $notificationService = new BookingNotificationService();
            $notificationService->handleStatusChangeNotification($tutoria, $oldStatus, $nuevoStatus);
            
            // Guardar después de procesar las notificaciones
            $tutoria->save();
        }
        $this->dispatch('cerrar-modal-tutoria');
    }



    public function clearFilters()
    {
        $this->reset(['tutor', 'student', 'fecha', 'fecha_inicio', 'fecha_fin', 'status']);
    }



    public function abrirModalPagoTutor($tutoria)
    {

        $bookingId = is_array($tutoria) ? $tutoria['id'] : $tutoria->id;
        
        $pago = \App\Models\SlotPayment::where('slot_booking_id', $bookingId)->first();
        
        if ($pago) {
            $this->modalPaymentId = $pago->id;
            $this->modalPaymentStatus = $pago->status;
            $this->modalPaymentMethod = $pago->payment_method;
            $this->modalPaymentMessage = $pago->message;
        }
    }




    public function updatePayment()
    {

        try {
            $pago = \App\Models\SlotPayment::find($this->modalPaymentId);
              //dd($pago,"adahsgdas");
            if ($pago) {

               
                $estadoActual = (int) $pago->status;
                $nuevoEstado = (int) $this->modalPaymentStatus;

                // Definir transiciones válidas
                $transicionesValidas = [
                    1 => [2, 3], // pendiente -> pagado u observado
                    3 => [2, 4], // observado -> pagado o cancelado
                ];
                // Si la transición no es válida, mostrar error y salir
                if (
                    ($estadoActual !== $nuevoEstado) && (
                        !isset($transicionesValidas[$estadoActual]) ||
                        !in_array($nuevoEstado, $transicionesValidas[$estadoActual])
                    )
                ) {
                    $this->errorMessage = 'Transición de estado no válida.';

                    $this->dispatch('cerrar-modal-pago-tutor');
                    $this->dispatch('mostrar-modal-error', ['message' => $this->errorMessage]);
                    return;
                }

                $pago->status = $nuevoEstado;
                $pago->payment_method = $this->modalPaymentMethod;
                $pago->message = $this->modalPaymentMessage;
                $pago->save();


                $this->dispatch('cerrar-modal-pago-tutor');
            }
            $this->successMessage = 'Pago actualizado correctamente.';
            $this->dispatch('mostrar-modal-success', ['message' => $this->successMessage]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar el pago: ' . $e->getMessage());
            $this->dispatch('mostrar-mensaje-error', ['message' => 'Error al actualizar el pago.']);
        }
    }




    public function cerrarModalTutoria()
    {
        $this->showModal = false;
    }



}




/*  if ($tutorUser) {
                      $mailService->sendTutoriaNotification($tutoria, $result);
                      Mail::to($tutorUser->email)->send(new SessionBookingMail([
                         'userName' => $tutorName,
                         'sessionDate' => date('d/m/Y', strtotime($tutoria->start_time)),
                         'sessionTime' => date('H:i', strtotime($tutoria->start_time)),
                         'meetingLink' => $tutoria->meeting_link,
                         'role' => 'Estudiante',
                         'oppositeName' => $studentName,
                     ])); 
                 }  */



/*  if ($studentUser) {
                 Mail::to($studentUser->email)->send(new SessionBookingMail([
                     'userName' => $studentName,
                     'sessionDate' => date('d/m/Y', strtotime($tutoria->start_time)),
                     'sessionTime' => date('H:i', strtotime($tutoria->start_time)),
                     'meetingLink' => $result,
                     'role' => 'Tutor',
                     'oppositeName' => $tutoria->tutor?->profile?->first_name . ' ' . $tutoria->tutor?->profile?->last_name,
                 ]));
             }  */