<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->loadMonthData();
    }

    /**
     * Carga los datos de disponibilidad para el mes actual.
     * En un caso real, aquí harías una única consulta a tu BBDD para el mes visible.
     */
    public function loadMonthData()
    {
        // EJEMPLO: Simula datos de la BBDD para Agosto 2025
        $this->timeSlotsByDay = [
            // Día 8: Tiene horas disponibles y algunas ocupadas
            8 => [
                ['time' => '10:00', 'status' => 'free'],
                ['time' => '10:20', 'status' => 'occupied'],
                ['time' => '10:40', 'status' => 'free'],
            ],
            // Día 15: Todas sus horas están ocupadas (no debería aparecer en naranja)
            15 => [
                ['time' => '16:00', 'status' => 'occupied'],
                ['time' => '16:20', 'status' => 'occupied'],
            ],
            // Día 22: Tiene solo horas libres
            22 => [
                ['time' => '09:00', 'status' => 'free'],
                ['time' => '09:20', 'status' => 'free'],
            ],
        ];

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
    public function selectDay(int $day)
    {
        if ($this->isPastDay($day)) return;

        $this->selectedDay = $day;
        $this->selectedTime = null; // Resetea la hora al cambiar de día
        $this->availableTimeSlots = $this->timeSlotsByDay[$day] ?? [];
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
            $this->dispatch('show-error', message: 'Por favor, selecciona un día y una hora antes de continuar.');
            return;
        }

        // Emite un evento global que el JavaScript del frontend escuchará.
        $this->dispatch('open-modal');
    }

    public function someAction()
    {
        // ... tu lógica ...
        $this->dispatch('open-modal');
    }

    /**
     * Finaliza la reserva. Se llama desde el formulario del modal.
     */
    public function makeReservation()
    {
        $this->validate([
            'paymentReceipt' => 'required|image|max:2048', // 2MB Max
            'selectedSubject' => 'required',
        ]);

        // --- LÓGICA DE BACKEND ---
        // 1. Guardar el archivo de comprobante
        // $path = $this->paymentReceipt->store('receipts');

        // 2. Crear la reserva en la base de datos
        // Reservation::create([
        //     'user_id' => auth()->id(),
        //     'date' => $this->currentDate->copy()->setDay($this->selectedDay),
        //     'time' => $this->selectedTime,
        //     'subject' => $this->selectedSubject,
        //     'receipt_path' => $path,
        // ]);
        
        // 3. Marcar la hora como 'occupied' para futuras consultas.

        // Resetea el estado y envía un mensaje de éxito
        $this->resetSelection();
        session()->flash('success_message', '¡Hora reservada correctamente!');
        $this->dispatch('close-modal-and-refresh');
    }
    
    private function resetSelection()
    {
        $this->reset(['selectedDay', 'selectedTime', 'availableTimeSlots']);
    }

    private function isPastDay(int $day): bool
    {
        return $this->currentDate->copy()->setDay($day)->isBefore(Carbon::today());
    }

    public function render()
    {
        // ... (lógica de renderizado sin cambios)
        $startDay = ($this->currentDate->copy()->startOfMonth()->dayOfWeekIso % 7);
        $daysInMonth = $this->currentDate->daysInMonth;

        return view('livewire.reserva', [
            'startDay' => $startDay,
            'daysInMonth' => $daysInMonth,
        ]);
    }
}