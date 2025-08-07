<?php

namespace App\Livewire\Admin;

use App\Models\SlotBooking;
use App\Services\GoogleMeetService;
use App\Services\MailService;
use App\Services\BookingNotificationService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

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
            // Guardar el estado anterior ANTES de cambiarlo
            $oldStatus = $tutoria->status;
            
            Log::info('TutoriasTable: Estados de la tutoría', [
                'oldStatus' => $oldStatus,
                'newStatus' => $nuevoStatus,
                'oldStatus_type' => gettype($oldStatus),
                'newStatus_type' => gettype($nuevoStatus)
            ]);
            
            $tutoria->status = $nuevoStatus;
            
            // Debug: Verificar si entra al bloque de Zoom
            Log::info('TutoriasTable: Verificando si debe crear reunión de Zoom', [
                'nuevoStatus' => $nuevoStatus,
                'nuevoStatus_type' => gettype($nuevoStatus),
                'should_create_meeting' => ($nuevoStatus == 1)
            ]);
            
            // Si el nuevo estado es 'Aceptada' (1), crear reunión Zoom y enviar correos
            if ($nuevoStatus == 1) {
                Log::info('TutoriasTable: Entrando al bloque de creación de reunión de Zoom');

                $googlemeetservice = new GoogleMeetService;
                
                // Debug: Verificar configuraciones de Zoom desde .env
                Log::info('TutoriasTable: Verificando configuraciones de Zoom desde .env', [
                    'zoom_account_id' => env('ZOOM_ACCOUNT_ID') ? 'CONFIGURED' : 'EMPTY',
                    'zoom_client_id' => env('ZOOM_CLIENT_ID') ? 'CONFIGURED' : 'EMPTY',
                    'zoom_client_secret' => env('ZOOM_CLIENT_SECRET') ? 'CONFIGURED' : 'EMPTY'
                ]);
                
                // Crear instancia de Zoom driver directamente con credenciales del .env
                $meetingService = null;
                if (env('ZOOM_ACCOUNT_ID') && env('ZOOM_CLIENT_ID') && env('ZOOM_CLIENT_SECRET')) {
                    try {
                        $meetingService = new \Modules\MeetFusion\Drivers\Zoom();
                        $meetingService->setKeys([
                            'account_id' => env('ZOOM_ACCOUNT_ID'),
                            'client_id' => env('ZOOM_CLIENT_ID'),
                            'client_secret' => env('ZOOM_CLIENT_SECRET'),
                        ]);
                        Log::info('TutoriasTable: Servicio de Zoom configurado con credenciales del .env', [
                            'account_id' => env('ZOOM_ACCOUNT_ID'),
                            'client_id' => env('ZOOM_CLIENT_ID'),
                            'client_secret_length' => strlen(env('ZOOM_CLIENT_SECRET'))
                        ]);
                    } catch (\Exception $e) {
                        Log::error('TutoriasTable: Error al configurar servicio de Zoom', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                } else {
                    Log::warning('TutoriasTable: Credenciales de Zoom no encontradas en .env', [
                        'account_id_exists' => !empty(env('ZOOM_ACCOUNT_ID')),
                        'client_id_exists' => !empty(env('ZOOM_CLIENT_ID')),
                        'client_secret_exists' => !empty(env('ZOOM_CLIENT_SECRET'))
                    ]);
                }
                // Formatear la fecha correctamente para Zoom (ISO 8601)
                $startTime = $tutoria->start_time ? \Carbon\Carbon::parse($tutoria->start_time)->toIso8601String() : null;
                
                $meetingData = [
                    'topic' => 'Tutoría',
                    'agenda' => 'Sesión de tutoría',
                    'start_time' => $startTime,
                    'timezone' => 'America/La_Paz',
                    'duration' => 20, // Duración en minutos
                ];


                $meetingDatameet = [
                    'title' => 'Reunión de Prueba',
                    'description' => 'Esta es una reunión de prueba con Google Meet',
                    'start_time' => now()->addHour()->toISOString(), // En 1 hora
                    'end_time' => now()->addHour()->addMinutes(30)->toISOString(), // Duración 30 min
                    'timezone' => 'America/La_Paz',
                ];

                $joinUrl = null; // Inicializar la variable
                
                if ($meetingService) {
                    Log::info('TutoriasTable: Servicio de reuniones obtenido, creando reunión', [
                        'meeting_data' => $meetingData,
                        'tutoria_id' => $tutoria->id
                    ]);
                    
                    try {
                        $zoomResponse = $meetingService->createMeeting($meetingData);
                        Log::info('TutoriasTable: Respuesta del servicio de reuniones', ['response' => $zoomResponse]);
                    } catch (\Exception $e) {
                        Log::error('TutoriasTable: Error al crear reunión', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'tutoria_id' => $tutoria->id
                        ]);
                        $zoomResponse = [
                            'status' => false,
                            'message' => 'Error al crear reunión: ' . $e->getMessage()
                        ];
                    }
                    
                    if ($zoomResponse['status']) {
                        // MeetFusion devuelve el enlace en ['data']['link']
                        $joinUrl = $zoomResponse['data']['link'] ?? null;
                        $tutoria->meeting_link = $joinUrl;
                        Log::info('TutoriasTable: Enlace de reunión creado exitosamente', [
                            'join_url' => $joinUrl,
                            'meeting_id' => $zoomResponse['data']['meeting_id'] ?? 'N/A'
                        ]);
                    } else {
                        Log::warning('TutoriasTable: No se pudo crear reunión', [
                            'error' => $zoomResponse['message'] ?? 'Error desconocido',
                            'booking_id' => $tutoria->id,
                            'response' => $zoomResponse
                        ]);
                        $tutoria->meeting_link = null;
                    }
                } else {
                    Log::warning('TutoriasTable: Servicio de reuniones no configurado', [
                        'booking_id' => $tutoria->id
                    ]);
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

                Log::info('TutoriasTable: Finalizando bloque de creación de reunión de Zoom', [
                    'meeting_link_created' => !empty($tutoria->meeting_link),
                    'meeting_link' => $tutoria->meeting_link
                ]);
            } else {
                Log::info('TutoriasTable: No se creará reunión de Zoom - estado diferente a 1', [
                    'nuevoStatus' => $nuevoStatus,
                    'nuevoStatus_type' => gettype($nuevoStatus)
                ]);
            }
            
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