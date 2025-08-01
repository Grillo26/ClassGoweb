<?php

namespace App\Livewire;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Livewire\Component;

class Reserva extends Component
{
    public Carbon $currentDate;
    public ?int $selectedDay = null;

    // Propiedades para almacenar datos de la BBDD (ejemplos)
    public array $bookedDates = [];
    public array $availableSlots = [];

    // Esta función se ejecuta una sola vez al cargar el componente
    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->loadBookedDatesForMonth();
    }

    /**
     * Carga las fechas reservadas para el mes actual.
     * En un caso real, aquí harías una consulta a la BBDD.
     */
    public function loadBookedDatesForMonth()
    {
        // EJEMPLO: Simula que estos días de Agosto 2025 están reservados
        // El formato clave-valor es 'dia' => true para una búsqueda rápida
        $this->bookedDates = [
            5 => true,
            12 => true,
            19 => true,
        ];
    }

    /**
     * Obtiene las horas disponibles para un día seleccionado.
     * En un caso real, aquí consultarías la BBDD para esa fecha.
     */
    public function getAvailableSlotsForDay(int $day)
    {
        // EJEMPLO: Simula las horas disponibles según el día
        if (isset($this->bookedDates[$day])) {
            return []; // No hay horas si el día está totalmente reservado
        }

        // Devuelve un array de horas si el día tiene disponibilidad
        switch ($day) {
            case 15:
                return ['16:00', '16:20', '16:40', '17:00'];
            case 22:
                return ['09:00', '09:20', '09:40', '10:00', '10:20', '10:40'];
            default:
                // Horas por defecto para un día cualquiera
                return ['18:00', '18:20', '18:40', '19:00', '19:20', '19:40'];
        }
    }

    /**
     * Cambia al mes anterior.
     */
    public function goToPreviousMonth()
    {
        $this->currentDate->subMonth();
        $this->resetSelection();
        $this->loadBookedDatesForMonth();
    }

    /**
     * Cambia al mes siguiente.
     */
    public function goToNextMonth()
    {
        $this->currentDate->addMonth();
        $this->resetSelection();
        $this->loadBookedDatesForMonth();
    }

    /**
     * Se ejecuta cuando el usuario hace clic en un día.
     */
    public function selectDay(int $day)
    {
        // No permite seleccionar días pasados
        if ($this->isPastDay($day)) {
            return;
        }

        $this->selectedDay = $day;
        $this->availableSlots = $this->getAvailableSlotsForDay($day);
    }
    
    /**
     * Reinicia la selección de día y horas.
     */
    private function resetSelection()
    {
        $this->selectedDay = null;
        $this->availableSlots = [];
    }

    /**
     * Verifica si un día ya pasó.
     */
    private function isPastDay(int $day): bool
    {
        $today = Carbon::now();
        $checkingDate = $this->currentDate->copy()->setDay($day);

        // Compara si la fecha a verificar es anterior a hoy (sin contar la hora)
        return $checkingDate->isBefore($today->startOfDay());
    }


    /**
     * Renderiza el componente.
     */
    public function render()
    {
        $firstDayOfMonth = $this->currentDate->copy()->startOfMonth()->dayOfWeek;
        // Ajuste para que Lunes sea el primer día (1) y Domingo el último (0 o 7)
        $startDay = ($firstDayOfMonth === 0) ? 6 : $firstDayOfMonth - 1;

        $daysInMonth = $this->currentDate->daysInMonth;

        return view('livewire.reserva', [
            'startDay' => $startDay,
            'daysInMonth' => $daysInMonth,
        ]);
    }
}
