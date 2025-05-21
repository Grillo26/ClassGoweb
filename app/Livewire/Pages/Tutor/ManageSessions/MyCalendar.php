<?php

namespace App\Livewire\Pages\Tutor\ManageSessions;

use App\Livewire\Forms\Tutor\ManageSessions\SessionBookingForm;
use App\Models\Day;
use App\Models\UserSubjectSlot;
use App\Services\BookingService;
use App\Services\SubjectService;
use App\Services\UserService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Log;

class MyCalendar extends Component
{
    public $availableSlots, $subjectGroups, $days;
    public $currentDate, $currentMonth, $currentYear, $startOfCalendar, $endOfCalendar, $startOfWeek;

    public $subjectGroupIds = null;
    protected $bookingService, $subjectService;

    public SessionBookingForm $form;
    public $MAX_SESSION_CHAR = 500;
    public $isLoading = true;
    public $activeRoute;
    public $templates = [];
    public $template_id = '';
    public $allowed_for_subscriptions = 0;
    public $editableSlotId;
    public $slotHasBookings = false;
    protected $listeners = ['loadSlotForEdit', 'refreshCalendar' => 'loadData'];

    public function boot() {
        $this->bookingService = new BookingService(Auth::user());
        $this->subjectService  = new SubjectService(Auth::user());
    }

    public function mount() {
        $this->activeRoute = Route::currentRouteName();
        $this->subjectGroups = $this->subjectService->getUserSubjectGroups(['subjects:id,name','group:id,name']);
        $this->startOfWeek = (int) (setting('_lernen.start_of_week') ?? Carbon::SUNDAY); 
        $this->days = Day::get();
        // if(\Nwidart\Modules\Facades\Module::has('upcertify') && \Nwidart\Modules\Facades\Module::isEnabled('upcertify')){
        //     $this->templates = get_templates();
        // }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        \Log::info('Renderizando calendario', [
            'currentDate' => $this->currentDate,
            'days' => $this->days,
            'availableSlots' => $this->availableSlots,
        ]);
        if (empty($this->currentDate)) {
            $this->currentDate = \Carbon\Carbon::now(getUserTimezone());
        }
        if (empty($this->currentDate) || empty($this->availableSlots)) {
            $this->makeCalendar();
        }
        return view('livewire.pages.tutor.manage-sessions.my-calendar');
    }

    public function loadData() {
        $this->isLoading = true;
        $this->makeCalendar();
        $this->isLoading = false;
    }

    public function addSessionForm(){
        $this->form->reset();
        $this->form->resetErrorBag();
        $this->dispatch('toggleModel', id:'new-booking-modal', action:'show');
    }

    public function addSession(){
        try {
        $validatedData = $this->form->validateData();

        // Validación: la hora de fin no puede ser menor o igual que la de inicio
        if (strtotime($validatedData['end_time']) <= strtotime($validatedData['start_time'])) {
            $this->addError('form.end_time', 'La hora de fin debe ser mayor que la hora de inicio.');
                $this->dispatch('toggleModel', id: 'new-booking-modal', action: 'hide');
                $this->dispatch('showAlertMessage', type: 'error', title: __('general.error_title'), message: 'La hora de fin debe ser mayor que la hora de inicio.');
            return;
        }

        // Procesar el rango de fechas
        $dates = explode(' to ', $validatedData['date_range']);
        $startDate = Carbon::parse($dates[0]);
        $endDate = isset($dates[1]) ? Carbon::parse($dates[1]) : $startDate;

        $period = CarbonPeriod::create($startDate, $endDate);

        // Calcular duración en horas
        $startTime = Carbon::createFromFormat('H:i', $validatedData['start_time']);
        $endTime = Carbon::createFromFormat('H:i', $validatedData['end_time']);
        $durationHours = $endTime->diffInMinutes($startTime) / 60;
        $duracion = $durationHours . ' horas';

        foreach ($period as $date) {
                // Validar solapamiento
                $overlap = UserSubjectSlot::where('user_id', Auth::id())
                    ->where('date', $date->format('Y-m-d'))
                    ->where(function($query) use ($validatedData) {
                        $query->where(function($q) use ($validatedData) {
                            $q->where('start_time', '<', $validatedData['end_time'])
                              ->where('end_time', '>', $validatedData['start_time']);
                        });
                    })
                    ->exists();
                if ($overlap) {
                    $this->dispatch('toggleModel', id: 'new-booking-modal', action: 'hide');
                    $this->dispatch('showAlertMessage', type: 'error', title: __('general.error_title'), message: 'Ya existe una reserva en ese rango de horas para el día ' . $date->format('Y-m-d'));
                    return;
                }
            UserSubjectSlot::create([
                'start_time' => $validatedData['start_time'],
                'end_time'   => $validatedData['end_time'],
                'duracion'   => $duracion,
                'date'       => $date->format('Y-m-d'),
                'user_id'    => Auth::id(),
            ]);
        }

        $this->form->reset();
        $this->dispatch('toggleModel', id: 'new-booking-modal', action: 'hide');
        $this->dispatch('showAlertMessage', type: 'success', title: __('general.success_title'), message: __('general.success_message'));
        } catch (\Exception $e) {
            $this->dispatch('toggleModel', id: 'new-booking-modal', action: 'hide');
            // Si es una excepción de validación, mostrar el campo y el mensaje
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errors = $e->validator->errors()->toArray();
                $firstField = array_key_first($errors);
                $firstMsg = $errors[$firstField][0] ?? 'Error de validación.';
                $this->dispatch('showAlertMessage', type: 'error', title: __('general.error_title'), message: ucfirst(str_replace('form.', '', $firstField)) . ': ' . $firstMsg);
            } else {
                $this->dispatch('showAlertMessage', type: 'error', title: __('general.error_title'), message: $e->getMessage());
            }
        }
    }

    public function updatedForm($value, $key){
        if($key == 'subject_group_id' && !empty($value)) {
            $subjectGroup = $this->subjectService->getUserGroupSubject($value);
            $this->form->setFee($subjectGroup->hour_rate);
        }
    }

    public function updatedCurrentMonth($month) {
        $date = "$month/01/$this->currentYear";
        $this->makeCalendar($date);
    }

    public function jumpToDate($date=null) {
        if (!empty($date)) {
            $date = Carbon::createFromFormat('d F, Y', "01 $date");
        }
        $this->makeCalendar($date);
    }

    public function updatedCurrentYear($year) {
        $date = "$this->currentMonth/01/$year";
        $this->makeCalendar($date);
    }

    public function previousMonthCalendar($dateString) {
        $date = Carbon::createFromDate($dateString);
        $date->subMonth();
        $this->makeCalendar($date);
        $this->dispatch('initCalendarJs', currentDate: parseToUserTz($this->currentDate->copy())->format('F, Y'));
    }

    public function nextMonthCalendar($dateString) {
        $date = Carbon::createFromDate($dateString);
        $date->addMonth();
        $this->makeCalendar($date);
        $this->dispatch('initCalendarJs', currentDate: parseToUserTz($this->currentDate->copy())->format('F, Y'));
    }

    private function makeCalendar($date = null) {
        // Usar la fecha actual si no hay currentDate
        $date = empty($date) ? ($this->currentDate ?: Carbon::now(getUserTimezone())) : Carbon::createFromDate($date)->setTimezone(getUserTimezone());
        $this->currentDate = $date;
        $this->currentMonth = $date->format('m');
        $this->currentYear = $date->format('Y');
        $this->startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek($this->startOfWeek);
        $this->endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(getEndOfWeek($this->startOfWeek));
        // Cargar las reservas inmediatamente después de actualizar las fechas
        $this->availableSlots = $this->bookingService->getAvailableSlots($this->subjectGroupIds, $this->currentDate);
    }

    public function loadSlotForEdit($slotId)
    {
        $slot = UserSubjectSlot::findOrFail($slotId);
        $this->editableSlotId = $slot->id;
        $this->form->form_date = $slot->date instanceof \DateTimeInterface ? $slot->date->format('Y-m-d') : substr($slot->date, 0, 10);
        $this->form->start_time = $slot->start_time instanceof \DateTimeInterface ? $slot->start_time->format('H:i') : $slot->start_time;
        $this->form->end_time = $slot->end_time instanceof \DateTimeInterface ? $slot->end_time->format('H:i') : $slot->end_time;
        $this->form->meeting_link = $slot->meta_data['meeting_link'] ?? '';
        $this->form->action = 'edit';
        // Verificar si el slot tiene reservas asociadas
        $this->slotHasBookings = $slot->bookings()->count() > 0;
        $this->dispatch('toggleModel', id: 'edit-session', action: 'show');
    }

    public function editSession()
    {
        try {
        $validatedData = $this->form->validateData();
        $slot = UserSubjectSlot::findOrFail($this->editableSlotId);
        $slot->start_time = $validatedData['start_time'];
        $slot->end_time = $validatedData['end_time'];
        $slot->save();
        $this->dispatch('toggleModel', id: 'edit-session', action: 'hide');
        $this->dispatch('showAlertMessage', type: 'success', title: __('general.success_title'), message: __('general.updated_msg'));
        } catch (\Exception $e) {
            $this->dispatch('toggleModel', id: 'edit-session', action: 'hide');
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errors = $e->validator->errors()->toArray();
                $firstField = array_key_first($errors);
                $firstMsg = $errors[$firstField][0] ?? 'Error de validación.';
                $this->dispatch('showAlertMessage', type: 'error', title: __('general.error_title'), message: ucfirst(str_replace('form.', '', $firstField)) . ': ' . $firstMsg);
            } else {
                $this->dispatch('showAlertMessage', type: 'error', title: __('general.error_title'), message: $e->getMessage());
            }
        }
    }

    public function deleteSession()
    {
        try {
            $slot = UserSubjectSlot::findOrFail($this->editableSlotId);
            $slot->delete();
            $this->dispatch('toggleModel', id: 'edit-session', action: 'hide');
            $this->dispatch('showAlertMessage', type: 'success', title: __('general.success_title'), message: 'Reserva eliminada correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('toggleModel', id: 'edit-session', action: 'hide');
            $this->dispatch('showAlertMessage', type: 'error', title: __('general.error_title'), message: $e->getMessage());
        }
    }
}