<?php

namespace App\Livewire\Components;

use App\Facades\Cart;
use App\Jobs\SendNotificationJob;
use App\Livewire\Forms\Frontend\RequestSessionForm;
use App\Models\SlotBooking;
use App\Models\User;
use App\Services\BookingService;
use App\Services\SubjectService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;
use App\Models\UserSubjectSlot;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Models\PaymentSlotBooking;
use Illuminate\Support\Facades\Log;


class TutorSessions extends Component
{
    use WithFileUploads;

    public $currentDate, $startOfWeek, $timezone;
    public $activeId;
    public $disablePrevious, $showCurrent = false, $isCurrent = false;
    public $dateRange = [];
    public $availableSlots = [];
    public $filter = [];
    public $subjectGroups;
    public $userId;
    public $currency_symbol;
    public $pageLoaded = false;
    public $user;
    public $currentSlot = null;
    public $cartItems = [];
    public $showConfirmationDiv = false;
    public $selectedSlotId = null;
    public $imagePreview = null; // Imagen cargada desde el backend
    public $uploadedImage = null; // Imagen subida por el usuario
    public $uploadedImagePreview = null; // Previsualización de la imagen subida por el usuario
    public $selectedDate = null;
    public $hourRange = []; // Rango de horas para el modal
    public $selectedHour; // Hora seleccionada por el usuario
    public $subjects = []; // Lista de subjects del tutor
    public $selectedSubject;
    public $subjectError = '';
    public $successMessage = '';
    public $minTime;
    public $maxTime;
    public $enableTimes = [];
    private $bookingService, $subjectService;

    public RequestSessionForm $requestSessionForm;

    protected $listeners = ['closeConfirmationDiv'];

    /**
     * Renderiza la vista del componente y carga los slots disponibles.
     */
    public function render()
    {
        if (!empty($this->timezone) && !empty($this->pageLoaded)) {
            $this->availableSlots = $this->bookingService->getTutorAvailableSlots($this->userId, $this->timezone, $this->dateRange, $this->filter);
        }
        $this->cartItems = Cart::content();
        $this->dispatch('initCalendarJs', currentDate: $this->getDateFormat());
        return view('livewire.components.tutor-sessions');
    }

    /**
     * Inicializa la página configurando la fecha actual y el rango de fechas.
     */
    public function loadPage()
    {
        $this->currentDate = Carbon::now($this->timezone);
        $this->getRange();
        $this->pageLoaded = true;
    }

    /**
     * Inicializa los servicios necesarios al cargar el componente.
     */
    public function boot()
    {
        $this->bookingService = new BookingService($this->user);
    }

    /**
     * Configura las propiedades iniciales del componente al montarlo.
     *
     * @param User $user El usuario actual.
     */
    public function mount($user)
    {
        $this->user = $user;
        $this->userId = $user->id;
        $this->selectedDate = now($this->timezone)->toDateString();
        $this->currentDate = parseToUserTz(Carbon::now());
        $this->startOfWeek = (int) (setting('_lernen.start_of_week') ?? Carbon::SUNDAY);
        $this->subjectService = new SubjectService($user);
        $this->subjectGroups = $this->subjectService->getSubjectsByUserRole();
        if (Auth::check()) {
            $this->timezone = getUserTimezone();
        }
        $currency = setting('_general.currency');
        $currency_detail = !empty($currency) ? currencyList($currency) : array();
        if (!empty($currency_detail['symbol'])) {
            $this->currency_symbol = $currency_detail['symbol'];
        }
    }

    /**
     * Muestra los detalles de un slot específico en un modal.
     *
     * @param int $id ID del slot.
     */
    public function showSlotDetail($id)
    {
        $this->currentSlot = $this->bookingService->getSlotDetail($id);
        $this->dispatch('toggleModel', id: 'slot-detail', action: 'show');
    }

    /**
     * Reserva un slot para el usuario actual.
     *
     * @param int $id ID del slot a reservar.
     */
    public function bookSession($id)
    {
        $slot = $this->bookingService->getSlotDetail($id);
        if (!empty($slot)) {
            if ($slot->total_booked < $slot->spaces) {
                $bookedSlot = $this->bookingService->reservarSlotBoooking($slot, $this->selectedSubject);
                $data = [
                    'id' => $bookedSlot->id,
                    'slot_id' => $slot->id,
                    'tutor_id' => $this->user->id,
                    'tutor_name' => $this->user?->profile?->full_name,
                    'session_time' => parseToUserTz($slot->start_time, $this->timezone)->format('h:i a') . ' - ' . parseToUserTz($slot->end_time, $this->timezone)->format('h:i a'),
                    'currency_symbol' => $this->currency_symbol,
                    'price' => number_format($slot->session_fee, 2),
                ];
                if (Module::has('subscriptions') && Module::isEnabled('subscriptions')) {
                    $data['allowed_for_subscriptions'] = $slot->meta_data['allowed_for_subscriptions'] ?? 0;
                }

                Cart::add(
                    cartableId: $data['id'],
                    cartableType: SlotBooking::class,
                    name: $data['tutor_name'],
                    qty: 1,
                    price: $slot->session_fee,
                    options: $data
                );
                $this->dispatch('scrollToTop');

                if (\Nwidart\Modules\Facades\Module::has('kupondeal') && \Nwidart\Modules\Facades\Module::isEnabled('kupondeal')) {
                    // $response = \Modules\KuponDeal\Facades\KuponDeal::applyCouponIfAvailable($slot->subjectGroupSubjects->id, UserSubjectGroupSubject::class);
                } else {
                    $this->dispatch('cart-updated', cart_data: Cart::content(), total: formatAmount(Cart::total(), true), subTotal: formatAmount(Cart::subtotal(), true), toggle_cart: 'open');
                }

                if (!empty($this->currentSlot)) {
                    $this->dispatch('toggleModel', id: 'slot-detail', action: 'hide');
                }
            } else {
                $this->dispatch('showAlertMessage', type: 'error', title: __('general.error_title'), message: __('tutor.not_available_slot'));
            }
        }
    }

    /**
     * Elimina un elemento del carrito y libera el slot reservado.
     *
     * @param array $params Parámetros del elemento a eliminar.
     */
    /* #[On('remove-cart')]
    public function removeCartItem($params)
    {
        if (!empty($params['cartable_id']) && !empty($params['cartable_type'])) {
            $this->bookingService->removeReservedBooking($params['cartable_id']);
            Cart::remove(
                cartableId: $params['cartable_id'],
                cartableType: $params['cartable_type']
            );
            $this->dispatch('cart-updated', cart_data: Cart::content(), discount: formatAmount(Cart::discount(), true), total: formatAmount(Cart::total(), true), subTotal: formatAmount(Cart::subtotal(), true), toggle_cart: 'open');
        }
    } */

    /**
     * Salta a una fecha específica en el calendario.
     *
     * @param string|null $date Fecha a la que se desea saltar.
     */
    public function jumpToDate($date = null)
    {
        if (!empty($date)) {
            $format = 'Y-m-d';
            $this->currentDate = Carbon::createFromFormat($format, $date, $this->timezone);
        } else {
            $this->currentDate = Carbon::now($this->timezone);
        }
        $this->getRange();
    }



    /**
     * Método para alternar la visibilidad del modal y cargar la imagen desde el backend.
     */
    public function toggleConfirmationDiv($slotId)
    {
        // Limpiar el estado SIEMPRE antes de abrir el modal
        $this->resetImageFields();
        $this->hourRange = [];
        $this->subjects = [];
        $this->minTime = null;
        $this->maxTime = null;
        $this->enableTimes = [];
        $this->showConfirmationDiv = false;

        // Ahora sí, abrir y cargar los datos
        $this->selectedSlotId = $slotId;
        $this->showConfirmationDiv = true;

        $this->imagePreview = Storage::url('qr/77b1a7da.jpg');
        // Buscar el bloque libre exacto (tarjeta verde) en availableSlots
        $selectedBlock = null;
        foreach ($this->availableSlots as $daySlots) {
            foreach ($daySlots as $slot) {
                if (isset($slot->id) && $slot->id == $slotId) {
                    $selectedBlock = $slot;
                    break 2;
                }
            }
        }
        if ($selectedBlock) {
            $startTime = \Carbon\Carbon::parse($selectedBlock->start_time);
            $endTime = \Carbon\Carbon::parse($selectedBlock->end_time);
            $maxTime = $endTime->copy()->subMinutes(20);
            $this->minTime = $startTime->format('H:i');
            $this->maxTime = $maxTime->format('H:i');
            // Solo permitir el inicio exacto del bloque
            $this->enableTimes = [$startTime->format('H:i')];
            // Obtener los subjects del tutor
            $slotModel = \App\Models\UserSubjectSlot::find($selectedBlock->slot_id ?? $selectedBlock->id);
            $this->subjects = $slotModel && $slotModel->user ? $slotModel->user->userSubjects->map(function ($userSubject) {
                return $userSubject->subject;
            }) : [];
        } else {
            // Fallback: buscar el slot original
            $slot = \App\Models\UserSubjectSlot::find($slotId);
            if ($slot) {
                $startTime = $slot->start_time;
                $endTime = $slot->end_time;
                $maxTime = \Carbon\Carbon::parse($endTime)->copy()->subMinutes(20);
                $this->minTime = \Carbon\Carbon::parse($startTime)->format('H:i');
                $this->maxTime = $maxTime->format('H:i');
                $this->enableTimes = [\Carbon\Carbon::parse($startTime)->format('H:i')];
                $this->subjects = $slot->user->userSubjects->map(function ($userSubject) {
                    return $userSubject->subject;
                });
            }
        }
        $this->dispatch('initFlatpickr');
    }

    private function generateHourRange($startTime, $endTime)
    {
        $range = [];
        $current = $startTime->copy();
        // Generar el rango de horas con intervalos de 20 minutos
        while ($current->diffInMinutes($endTime) >= 20) {
            $range[] = $current->format('H:i');
            $current->addMinutes(20);
        }
        return $range;
    }

    /**
     * Método para manejar la subida de imágenes.
     */
    public function updatedUploadedImage()
    {
        $this->validate([
            'uploadedImage' => 'image|max:2048', // Máximo 2MB
        ]);
        // Generar una URL temporal para previsualizar la imagen subida
        //$this->uploadedImagePreview = $this->uploadedImage->temporaryUrl();
    }

    /**
     * Método para resetear las variables relacionadas con las imágenes.
     */
    public function resetImageFields()
    {
        $this->imagePreview = null;
        $this->uploadedImage = null;
        $this->uploadedImagePreview = null;
    }

    /**
     * Método para confirmar la reserva del estudiante.
     */
    public function estudianteReserva($slotId)
    {

        $this->subjectError = '';
        if (empty($this->selectedSubject)) {
            $this->subjectError = 'Debes seleccionar una materia.';
            return;
        }
        $slot = UserSubjectSlot::find($slotId);
        if (!empty($slot)) {
            $sessionFee = $slot->session_fee ?? 15;
            $bookedSlot = $this->bookingService->reservarSlotBoooking($slot, $this->selectedSubject, $this->selectedHour);
            $data = [
                'id' => $bookedSlot->id,
                'slot_id' => $slot->id,
                'tutor_id' => $this->user->id,
                'tutor_name' => $this->user?->profile?->full_name,
                'session_time' => parseToUserTz($slot->start_time, $this->timezone)->format('h:i a') . ' - ' . parseToUserTz($slot->end_time, $this->timezone)->format('h:i a'),
                'currency_symbol' => $this->currency_symbol,
                'price' => number_format($sessionFee, 2),
            ];
            if (Module::has('subscriptions') && Module::isEnabled('subscriptions')) {
                $data['allowed_for_subscriptions'] = $slot->meta_data['allowed_for_subscriptions'] ?? 0;
            }

            Cart::add(
                cartableId: $data['id'],
                cartableType: SlotBooking::class,
                name: $data['tutor_name'],
                qty: 1,
                price: $sessionFee,
                options: $data
            );
            $this->dispatch('scrollToTop');

            if (\Nwidart\Modules\Facades\Module::has('kupondeal') && \Nwidart\Modules\Facades\Module::isEnabled('kupondeal')) {
                // $response = \Modules\KuponDeal\Facades\KuponDeal::applyCouponIfAvailable($slot->subjectGroupSubjects->id, UserSubjectGroupSubject::class);
            } else {
                $this->dispatch('cart-updated', cart_data: Cart::content(), total: formatAmount(Cart::total(), true), subTotal: formatAmount(Cart::subtotal(), true), toggle_cart: 'open');
            }

            // Guardar la imagen del comprobante si existe
            if ($this->uploadedImage) {
                try {
                    // Nombre único para evitar sobrescribir archivos
                    $fileName = uniqid() . '_' . $this->uploadedImage->getClientOriginalName();

                    // Guarda el archivo en storage/app/public/uploads/bookings
                    $this->uploadedImage->storeAs('uploads/bookings', $fileName, 'public');

                    // Copia el archivo a public/storage/uploads/bookings
                    $source = storage_path('app/public/uploads/bookings/' . $fileName);
                    $destination = public_path('storage/uploads/bookings/' . $fileName);
                    if (!file_exists(dirname($destination))) {
                        mkdir(dirname($destination), 0775, true);
                    }
                    copy($source, $destination);

                    // Guarda la ruta relativa en la base de datos
                    $path = 'uploads/bookings/' . $fileName;

                    PaymentSlotBooking::create([
                        'slot_booking_id' => $bookedSlot->id,
                        'image_url' => $path,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);


                    $student = Auth::user();
                    $fechaHora = now()->format('d/m/Y H:i');
                    $contenido = "Se ha registrado una nueva reserva de tutoría el {$fechaHora}.\n\n";
                    $contenido .= "Detalles de la reserva:\n";
                    $contenido .= "Estudiante: {$student->name} ({$student->email})\n";
                    $contenido .= "Tutor: {$this->user?->profile?->full_name}\n";
                    $contenido .= "Materia: {$this->selectedSubject}\n";
                    $contenido .= "Horario: " . parseToUserTz($slot->start_time, $this->timezone)->format('d/m/Y H:i') . " - " . parseToUserTz($slot->end_time, $this->timezone)->format('H:i') . "\n";
                    $contenido .= "Precio: {$this->currency_symbol}" . number_format($sessionFee, 2) . "\n\n";
                    $contenido .= "Por favor, revise el panel de administración para más detalles.";
                    \Mail::raw($contenido, function ($message) {
                        $message->to(env('MAIL_ADMIN'))
                            ->subject('Nueva reserva de tutoría registrada');
                    });


                } catch (\Exception $e) {
                    Log::error('Error al guardar el comprobante: ' . $e->getMessage());
                    $this->dispatch('showAlertMessage', type: 'error', title: __('general.error_title'), message: 'Error al guardar el comprobante de pago.');
                    return;
                }
            }

            if (!empty($this->currentSlot)) {
                $this->dispatch('toggleModel', id: 'slot-detail', action: 'hide');
            }
            $this->successMessage = '¡Tutoría reservada exitosamente!';
            $this->reset(['selectedSubject', 'uploadedImage']);
            $this->dispatch('showSuccessAndCloseModal');
        }
    }

    /**
     * Avanza al siguiente rango de fechas en el calendario.
     */
    public function nextBookings()
    {
        $this->currentDate->setTimezone($this->timezone);
        $this->currentDate->addWeek();
        $this->selectedDate = $this->currentDate->startOfWeek($this->startOfWeek)->toDateString();
        $this->getRange();
    }

    /**
     * Retrocede al rango de fechas anterior en el calendario.
     */
    public function previousBookings()
    {
        $this->currentDate->setTimezone($this->timezone);
        $this->currentDate->subWeek();
        $this->selectedDate = $this->currentDate->startOfWeek($this->startOfWeek)->toDateString();
        $this->getRange();
    }

    /**
     * Calcula el rango de fechas actual basado en la semana seleccionada.
     */
    protected function getRange()
    {
        $start = $end = null;
        $this->disablePrevious = $this->isCurrent = false;
        $now = Carbon::now($this->timezone);
        $start = $this->currentDate->copy()->startOfWeek($this->startOfWeek)->toDateString() . " 00:00:00";
        $end = $this->currentDate->copy()->endOfWeek(getEndOfWeek($this->startOfWeek))->toDateString() . " 23:59:59";
        if ($now->between($this->currentDate->copy()->startOfWeek($this->startOfWeek), $this->currentDate->copy()->endOfWeek(getEndOfWeek($this->startOfWeek)))) {
            $this->disablePrevious = true;
            $this->isCurrent = true;
        }
        $startDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start, $this->timezone);
        $endDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $end, $this->timezone);
        $this->dateRange['start_date'] = parseToUTC($startDate);
        $this->dateRange['end_date'] = parseToUTC($endDate);
        $this->selectedDate = $this->currentDate->copy()->toDateString();
    }

    /**
     * Devuelve el formato de fecha para el rango actual.
     *
     * @return string Formato de fecha.
     */
    protected function getDateFormat()
    {
        $start = $this->currentDate->copy()->startOfWeek($this->startOfWeek);
        $end = $this->currentDate->copy()->endOfWeek(getEndOfWeek($this->startOfWeek));
        return $start->format('F') . " " . $start->format('d') . " - " . $end->format('F') . " " . $end->format('d') . " " . $end->format('Y');
    }

    /**
     * Establece la zona horaria predeterminada si no está configurada.
     *
     * @param string $timezone Zona horaria.
     */
    public function setDefaultTimezone($timezone)
    {
        if (empty($this->timezone)) {
            $this->timezone = $timezone;
        }
    }

    /**
     * Actualiza la zona horaria y limpia el carrito.
     *
     * @param string $value Nueva zona horaria.
     */
    public function updatedTimezone($value)
    {
        Cart::clear();
        $this->dispatch('cart-updated', cart_data: Cart::content(), discount: formatAmount(Cart::discount(), true), total: formatAmount(Cart::total(), true), subTotal: formatAmount(Cart::subtotal(), true), toggle_cart: 'close');
        if (Auth::check()) {
            $service = new UserService(Auth::user());
            $service->setAccountSetting('timezone', [$value]);
            Cache::forget('userTimeZone_' . Auth::user()?->id);
        }
        $this->currentDate = Carbon::now($value);
    }

    /**
     * Muestra los slots disponibles para una fecha específica.
     *
     * @param string $date Fecha seleccionada.
     */
    public function showSlotsForDate($date)
    {
        $this->selectedDate = $date;
    }

    /**
     * Abre un modal para solicitar una sesión.
     */
    public function openModel()
    {
        if (Auth::check()) {
            if (Auth::user()->role == 'student') {
                $this->requestSessionForm->setUserFormData(Auth::user());
                $this->dispatch('toggleModel', id: 'requestsession-popup', action: 'show');
            } else {
                $this->dispatch('showAlertMessage', type: `error`, message: __('general.not_allowed'));
            }
        } else {
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.login_error'));
        }
    }

    /**
     * Envía una solicitud de sesión al tutor.
     */
    public function sendRequestSession()
    {
        $this->requestSessionForm->validateData();
        $response = isDemoSite();
        if ($response) {
            $this->requestSessionForm->reset();
            $this->dispatch('toggleModel', id: 'requestsession-popup', action: 'hide');
            $this->dispatch('showAlertMessage', type: 'error', title: __('general.demosite_res_title'), message: __('general.demosite_res_txt'));
            return;
        }
        $templateData = [
            'userName' => $this->user?->profile?->full_name,
            'studentName' => $this->requestSessionForm->last_name,
            'studentEmail' => $this->requestSessionForm->email,
            'sessionType' => __('tutor.' . $this->requestSessionForm->type . '_session'),
            'message' => $this->requestSessionForm->message
        ];
        dispatch(new SendNotificationJob('sessionRequest', $this->user, $templateData));
        dispatch(new SendNotificationJob('sessionRequest', User::admin(), $templateData));
        $this->dispatch('toggleModel', id: 'requestsession-popup', action: 'hide');
        $this->dispatch('showAlertMessage', type: 'success', title: __('general.success_title'), message: __('tutor.request_session_success'));
        $this->requestSessionForm->reset();
    }


    public function resetImagePreview()
    {
        $this->imagePreview = Storage::url('qr/77b1a7da.jpg'); // Vuelve a cargar la imagen
    }

    public function closeConfirmationDiv()
    {
        $this->showConfirmationDiv = false;
        $this->resetImageFields();
        $this->hourRange = [];
        $this->subjects = [];
        $this->minTime = null;
        $this->maxTime = null;
        $this->enableTimes = [];
    }
}
