<?php


namespace App\Livewire;

use App\Models\PaymentSlotBooking;
use App\Models\SlotBooking;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubject;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\SlotBookingService;
use App\Services\ImagenesService;
use App\Services\PagosTutorReservaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\MailService;



class Reserva extends Component
{
    use WithFileUploads;

    public Carbon $currentDate;
    public ?int $selectedDay = null;
    public ?string $selectedTime = null; // Para guardar la hora seleccionada

    // Datos de ejemplo que simulan la BBDD
    public array $daysWithAvailability = []; // Días con horas disponibles (para el círculo naranja)
    public array $timeSlotsByDay = [];     // Todas las horas (libres y ocupadas) por día
    public array $availableTimeSlots = []; // Horas que se muestran al seleccionar un día
    // Propiedades para el formulario del modal
    public $paymentReceipt;
    public $selectedSubject;
    public $showModal = false;



    // Propiedades para el tutor
    public $tutorId;
    public $materiasTutor;





    public function mount($tutorId)
    {
        $this->tutorId = $tutorId;
        $this->currentDate = Carbon::now();
        $this->loadMonthData();
        $this->materiasTutor = UserSubject::where("user_id", $this->tutorId)->get();
    }

    /**
     * Carga los datos de disponibilidad para el mes actual.
     * En un caso real, aquí harías una única consulta a tu BBDD para el mes visible.
     */
    public function loadMonthData()
    {
        $slotBookingService = app(SlotBookingService::class);
        $hoarioslibres = $slotBookingService->tiempoLibreTutor($this->tutorId);

        // Obtener el año y mes actual del calendario
        $currentYear = $this->currentDate->year;
        $currentMonth = $this->currentDate->month;

        // Procesar los datos reales de la BBDD
        $this->timeSlotsByDay = $this->processRealSlotData($hoarioslibres, $currentYear, $currentMonth);



        // Determina qué días tienen al menos una hora libre para marcarlos en naranja
        $this->daysWithAvailability = collect($this->timeSlotsByDay)
            ->filter(fn($slots) => collect($slots)->where('status', 'free')->isNotEmpty())
            ->keys()
            ->toArray();

    }



    public function goToPreviousMonth()
    {
        $this->currentDate->subMonth();
        $this->resetSelection();
        $this->loadMonthData(); // Recarga los datos para el nuevo mes
    }



    public function goToNextMonth()
    {
        $this->currentDate->addMonth();
        $this->resetSelection();
        $this->loadMonthData(); // Recarga los datos para el nuevo mes
    }

    /**
     * Se ejecuta cuando el usuario hace clic en un día.
     */
    public function selectDay(int $day, string $month)
    {
        $fecha_actual = now();
        if ($this->isPastDay($day))
            return;
        $this->selectedDay = $day;
        $this->selectedTime = null; // Resetea la hora al cambiar de día



        if ($month == $fecha_actual->month && $day == $fecha_actual->day) {
            $slotsForToday = $this->timeSlotsByDay[$day] ?? [];
             $slotfiltrados = [];      
             $horaActual = $fecha_actual->format('H:i');
              
        
             for ($i = 0; $i < count($slotsForToday); $i++) {
                 if($slotsForToday[$i]['time'] > $horaActual ) {
                     
                    $slotfiltrados[] = $slotsForToday[$i];
                  }
              }
              //dd($slotfiltrados);
              $this->availableTimeSlots = $slotfiltrados;
        } else {
            $this->availableTimeSlots = $this->timeSlotsByDay[$day] ?? [];

            
        }

        //$this->availableTimeSlots = $this->timeSlotsByDay[$day] ?? [];
    }

    /**
     * Se ejecuta cuando el usuario hace clic en una hora.
     */
    public function selectTime(string $time)
    {
        // Busca el slot para asegurarse de que está libre
        $slot = collect($this->availableTimeSlots)->firstWhere('time', $time);

        if ($slot && $slot['status'] === 'free') {
            $this->selectedTime = $time;
        }
    }

    public function openReservationModal()
    {
        // Opcional: Puedes añadir una validación aquí para asegurarte
        // de que el usuario ya ha seleccionado un día y una hora.
        if (!$this->selectedDay || !$this->selectedTime) {
            session()->flash('error', 'Por favor, selecciona un día y una hora antes de continuar.');
            return;
        }

        $this->showModal = true;
        // Emite un evento global que el JavaScript del frontend escuchará.
        //$this->dispatch('open-modal');

    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['paymentReceipt', 'selectedSubject']);
    }


    /**
     * Finaliza la reserva. Se llama desde el formulario del modal.
     */
    public function makeReservation()
    {
        $this->validate([
            'paymentReceipt' => 'required|image|max:4096',
            'selectedSubject' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $pagostutorreserva = new PagosTutorReservaService();
            $sessionFee = 15;
            $estudianteId = auth()->user()->id;

            $fechaCompleta = $this->currentDate->copy()
                ->setDay($this->selectedDay)
                ->setTimeFromTimeString($this->selectedTime . ':00');
            $fechaString = $fechaCompleta->format('Y-m-d H:i:s');

            // 1. Guardar imagen - Obtener el servicio cuando lo necesites
            $imageService = app(ImagenesService::class);
            $path = $imageService->guardarqrEstudianteReserva($this->paymentReceipt);

            // 2. Crear reserva
            $slotBookingService = app(SlotBookingService::class);
            $reserva = $slotBookingService->crearReserva(
                $estudianteId,
                $this->tutorId,
                $this->selectedSubject,
                $fechaString
            );

            // 3. Crear registro de pago
            PaymentSlotBooking::create([
                'slot_booking_id' => $reserva->id,
                'image_url' => $path,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 4. Crear registro de pago del tutor
            $pagostutorreserva->create(
                slot_booking_id: $reserva->id,
                payment_date: now(),
                amount: $sessionFee,
                message: ''
            );

            DB::commit();

            // 5. Enviar email
            try {
                $tutor = User::where('id', $this->tutorId)->first();
                $emailService = app(MailService::class);
                $emailService->sendAdminNuevaTutoria(
                    $tutor?->profile?->full_name,
                    $this->selectedSubject,
                    $fechaString
                );
            } catch (\Exception $emailError) {
                Log::warning('Error enviando email de nueva tutoría', [
                    'error' => $emailError->getMessage(),
                    'reserva_id' => $reserva->id
                ]);
            }

            $this->loadMonthData();
            // Resetear estado y mostrar éxito
            $this->showModal = false;
            $this->resetSelection();
            session()->flash('success_message', '¡Hora reservada correctamente!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creando reserva', [
                'error' => $e->getMessage(),
                'tutor_id' => $this->tutorId,
                'student_id' => $estudianteId,
                'fecha' => $fechaString ?? null
            ]);

            session()->flash('error', 'Hubo un error al procesar tu reserva. Por favor, inténtalo de nuevo.');
        }
    }




    private function resetSelection()
    {
        $this->reset(['selectedDay', 'selectedTime', 'availableTimeSlots', 'paymentReceipt']);
    }



    private function isPastDay(int $day): bool
    {
        return $this->currentDate->copy()->setDay($day)->isBefore(Carbon::today());
    }


    /**
     * Método auxiliar para procesar los datos reales de la BBDD
     * Genera slots de 20 minutos entre start_time y end_time para cada fecha
     */
    private function processRealSlotData($tiempolibre, $year, $month)
    {
        $processedData = [];      // Array final que se retorna
        $totalProcesados = 0;     // Contador para debug

        // ===== PASO 1: OPTIMIZACIÓN DE CONSULTAS =====
        // En lugar de consultar la BD por cada slot de 20 minutos,
        // obtenemos TODAS las reservas del mes de una sola vez
        $reservasDelMes = SlotBooking::where('tutor_id', $this->tutorId)
            ->whereYear('start_time', $year)    // Filtra por año
            ->whereMonth('start_time', $month)  // Filtra por mes
            ->get()
            ->keyBy(function ($reserva) {
                // Crea un índice usando la fecha/hora como clave
                // Ejemplo: "2025-08-14 08:20:00" => objeto_reserva
                return Carbon::parse($reserva->start_time)->format('Y-m-d H:i:s');
            });

        // ===== PASO 2: PROCESAR CADA HORARIO DISPONIBLE DEL TUTOR =====
        foreach ($tiempolibre as $slot) {

            // --- 2.1: Extraer fecha del slot ---
            // $slot->date contiene algo como "2025-08-14 00:00:00"
            // startOfDay() asegura que sea medianoche: "2025-08-14 00:00:00"
            $slotDate = Carbon::parse($slot->date)->startOfDay();

            // --- 2.2: Verificar si el slot pertenece al mes actual ---
            // Solo procesa slots que coincidan con el año/mes del calendario
            if ($slotDate->year == $year && $slotDate->month == $month) {

                // --- 2.3: Determinar el día del mes ---
                $day = $slotDate->day; // Ej: 14 (para el 14 de agosto)

                // --- 2.4: Inicializar array para este día si no existe ---
                if (!isset($processedData[$day])) {
                    $processedData[$day] = [];
                }

                // --- 2.5: Construir horarios de inicio y fin ---
                // $slot->start_time puede ser "06:00:00" o una fecha completa
                // setTimeFromTimeString() toma solo la parte de hora
                $horaInicio = Carbon::parse($slot->start_time)->format('H:i:s');
                $horaFin = Carbon::parse($slot->end_time)->format('H:i:s');

                $startTime = $slotDate->copy()->setTimeFromTimeString($horaInicio);
                $endTime = $slotDate->copy()->setTimeFromTimeString($horaFin);

                // Ejemplo:
                // $startTime = "2025-08-14 06:00:00"
                // $endTime   = "2025-08-14 13:00:00"

                // --- 2.6: Inicializar tiempo actual para el bucle ---
                $currentTime = $startTime->copy();

                // ===== PASO 3: GENERAR SLOTS DE 20 MINUTOS =====
                // Divide el horario disponible en slots de 20 minutos
                while ($currentTime->lessThan($endTime)) {

                    // --- 3.1: Formatear hora para mostrar ---
                    $timeString = $currentTime->format('H:i'); // Ej: "08:20"

                    // --- 3.2: Crear clave para buscar en reservas ---
                    $datetimeKey = $currentTime->format('Y-m-d H:i:s');
                    // Ej: "2025-08-14 08:20:00"

                    // --- 3.3: VERIFICAR SI ESTÁ OCUPADO ---
                    // Busca en el array de reservas si existe esta fecha/hora exacta
                    // has() es mucho más rápido que consultar la BD cada vez
                    $isBooked = $reservasDelMes->has($datetimeKey);

                    $totalProcesados++; // Contador para debug

                    // --- 3.4: AGREGAR SLOT AL RESULTADO ---
                    $processedData[$day][] = [
                        'time' => $timeString,                           // "08:20"
                        'status' => $isBooked ? 'occupied' : 'free',     // Estado del slot
                        'slot_id' => $slot->id                           // ID del horario base
                    ];

                    // --- 3.5: AVANZAR 20 MINUTOS ---
                    // Pasa al siguiente slot de tiempo
                    $currentTime->addMinutes(20);
                    // Siguiente iteración: "08:40", luego "09:00", etc.
                }

                // Al terminar el while, este slot está completamente procesado
                // Continúa con el siguiente slot del foreach
            }

            // Si el slot no pertenece al mes actual, se omite completamente
        }

        // ]
        return $processedData;
    }






    /**
     * Verifica si un slot específico está reservado
     * Aquí deberías consultar tu tabla de reservas/bookings
     */
    private function isTimeSlotBooked($tutorId, $dateTime)
    {


        $ocupados = SlotBooking::where('tutor_id', $tutorId)
            //->where('date', $dateTime->format('Y-m-d'))
            ->where('start_time', $dateTime->format('Y-m-d H:i:s'))
            ->exists();

        //dd($ocupados);  
        return $ocupados;

    }

    public function render()
    {
        // ... (lógica de renderizado sin cambios)
        $startDay = ($this->currentDate->copy()->startOfMonth()->dayOfWeekIso % 7);
        $daysInMonth = $this->currentDate->daysInMonth;

        return view('livewire.reserva', [
            'startDay' => $startDay,
            'daysInMonth' => $daysInMonth,
            'materiasTutor' => $this->materiasTutor
        ]);
    }
}