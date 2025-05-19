<div>
    <div class="am-userinfo_section" wire:init="loadPage" x-data="{
        defaultTimeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        timezone: $wire.entangle('timezone'),
        }"
        x-init="if(!timezone) {
            $wire.set('timezone', defaultTimeZone, false);
        }">
        @if(empty($pageLoaded))
        <div class="am-booking-skeleton">
            @include('skeletons.book-sessions')
        </div>
        @else
        <div class="d-none" wire:target="jumpToDate, nextBookings, previousBookings, timezone, filter" wire:loading.class.remove="d-none">
            @include('skeletons.book-sessions')
        </div>
        <div class="am-userinfo_content" wire:loading.class="d-none" wire:target="jumpToDate, nextBookings, previousBookings, timezone, filter">
            @php
            $show_all = false;
            $showAll = array();
            @endphp
            <div class="am-booksession-title">
                <h3>{{ __('tutor.book_session') }} </h3>
                {{-- <button class="am-btn" wire:click='openModel' wire:loading.class="am-btn_disable" wire:target="openModel">{{ __('tutor.request_a_session') }}</button> --}}
            </div>



            {{-- header del calendario --}}
            <div class="am-booking-calander">
                <div class="am-booking-calander_header">
                    <div class="am-booking-dates-slot">
                        <div class="am-booking-calander-day">
                            <a href="javascript:void(0);" @if($isCurrent) disabled @else wire:click="jumpToDate()" @endif>
                                {{ __('calendar.today') }}
                            </a>
                        </div>
                    </div>
                    <div class="am-booking-filter-slot">
                        {{--<div class="flatpicker" x-init="$wire.dispatch('initSelect2', {target:'.am-customselect'})" wire:ignore>
                            <span class="am-select">
                                <select
                                    data-componentid="@this"
                                    class="am-customselect"
                                    value="{{$timezone}}"
                        data-searchable="true"
                        id="timezone"
                        data-live="true"
                        data-wiremodel="timezone"
                        data-placeholder="{{ __('settings.timezone_placeholder') }}">
                        <option value=""></option>
                        @foreach (timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}" x-bind:selected="timezone == '{{ $tz }}'">{{ $tz }}</option>
                        @endforeach
                        </select>
                        <div class="am-tooltipicon am-custom-tooltip">
                            <span class="am-tooltip-text">
                                <span>{{ __('tutor.tooltip_text') }}</span>
                            </span>
                            <i class="am-icon-exclamation-01"></i>
                        </div>
                        </span>
                    </div> --}}



                    <div class="am-booking-filter-wrapper">
                        {{-- <a class="am-booking-filter" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="am-icon-sliders-horiz-01"></i>
                        </a> --}}
                        <form class="am-itemdropdown_list am-filter-list dropdown-menu" aria-labelledby="dropdownMenuLink" x-on:submit.prevent
                            x-data="{
                                    selectedValues: [],
                                    init() {
                                        const selectElement = document.getElementById('filter_subject_group');
                                        const updateSelectedValues = () => {
                                            this.selectedValues = Array.from(selectElement.selectedOptions)
                                                .filter(option => option.value)
                                                .map(option => ({
                                                    value: option.value,
                                                    text: option.text,
                                                    price: option.getAttribute('data-price')
                                                })
                                            );
                                        };
                                        $(selectElement).select2().on('change', updateSelectedValues);
                                        updateSelectedValues();
                                    },
                                    removeValue(value) {
                                        const selectElement = document.getElementById('filter_subject_group');
                                        const optionToDeselect = Array.from(selectElement.options).find(option => option.value === value);
                                        if (optionToDeselect) {
                                            optionToDeselect.selected = false;
                                            $(selectElement).trigger('change');
                                        }
                                    },
                                    submitFilter() {
                                        let filters = {}
                                        const selectSbj = document.getElementById('filter_subject_group');
                                        const selectType = document.getElementById('type_fiter');
                                        filters.type = $(selectType).select2('val');
                                        filters.subject_group_ids = $(selectSbj).select2('val');
                                        @this.set('filter', filters);
                                    }
                                }">
                            <fieldset>
                                <div class="form-group">
                                    <label>{{ __('calendar.session_type_placeholder') }}</label>
                                    <span class="am-select am-filter-select" wire:ignore>
                                        <select id="type_fiter" data-componentid="@this" data-parent=".am-filter-list" class="am-customselect" data-searchable="false" data-wiremodel="filter.type">
                                            <option value="*">{{ __('calendar.show_all_type') }}</option>
                                            <option value="one">{{ __('calendar.one') }}</option>
                                            <option value="group">{{ __('calendar.group') }}</option>
                                        </select>
                                    </span>
                                </div>
                                <div class="form-group">
                                    {{-- <label>{{ __('calendar.subject_placeholder') }}</label>
                                    <span class="am-select am-multiple-select am-filter-select" wire:ignore>
                                        <select id="filter_subject_group" data-componentid="@this" data-parent=".am-filter-list" class="am-customselect" data-class="subject-dropdown-select2" data-format="custom" data-searchable="true" data-wiremodel="filter.subject_group_ids" data-placeholder="{{ __('calendar.subject_placeholder') }}" multiple>
                                            <option label="{{ __('calendar.subject_placeholder') }}"></option>
                                            @if(!empty($subjectGroups))
                                            @foreach ($subjectGroups as $group)
                                            <optgroup label="{{ $group['group_name'] }}">
                                                @foreach ($group['subjects'] as $subject)
                                                <option value="{{ $subject['id'] }}"
                                                    data-price="{{ formatAmount($subject['hour_rate']) }}">
                                                    {{ $subject['subject_name'] }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                            @endif
                                        </select>
                                    </span> --}}
                                    <template x-if="selectedValues.length > 0">
                                        <ul class="am-subject-tag-list">
                                            <template x-for="(subject, index) in selectedValues">
                                                <li>
                                                    <a href="javascript:void(0)" class="am-subject-tag" @click="removeValue(subject.value)">
                                                        <span x-text="`${subject.text} (${subject.price})`"></span>
                                                        <i class="am-icon-multiply-02"></i>
                                                    </a>
                                                </li>
                                            </template>
                                        </ul>
                                    </template>
                                </div>
                                <div class="form-group">
                                    <button class="am-btn" @click="submitFilter()">{{ __('general.apply_filter') }}</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>







            <div class="am-booking-calander_body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="weeklytab">
                        <a class="tab-pane_leftarrow" href="javascript:void(0);" @if($disablePrevious) disabled @else wire:click="previousBookings" @endif>
                            <i class="am-icon-chevron-left"></i>
                        </a>
                        <div class="am-booksession-details">
                            <table class="am-booking-weekly-clander">
                                <thead>
                                    <tr>
                                        @for ($date = $currentDate->copy()->startOfWeek($startOfWeek); $date->lte($currentDate->copy()->endOfWeek(getEndOfWeek($startOfWeek))); $date->addDay())
                                        {{-- <th @class(['active' => $date->isToday()])> --}}
                                        <th @class(['active'=> $selectedDate === $date->toDateString()])>
                                            <a href="javascript:void(0);" class="text-decoration-none" wire:click.prevent="showSlotsForDate('{{ $date->toDateString() }}')">
                                                <div class="am-booking-calander-title">
                                                    <strong>{{ $date->format('j M') }}</strong>
                                                    <span>{{ $date->translatedFormat('D') }}</span>
                                                </div>
                                            </a>
                                        </th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @for ($date = $currentDate->copy()->startOfWeek($startOfWeek); $date->lte($currentDate->copy()->endOfWeek(getEndOfWeek($startOfWeek))); $date->addDay())
                                        @php
                                        $active_date = $date->toDateString();
                                        $slots = $availableSlots[$active_date] ?? [];
                                        @endphp
                                        <td>
                                            <div class="am-weekly-slots">
                                                @if(!empty($slots))
                                                @foreach ($slots as $index => $slot)
                                                @php
                                                $total_spaces = $slot->spaces ?? 0;
                                                $total_booked = $slot->total_booked ?? 0;
                                                $available_slot = $total_spaces - $total_booked;
                                                $show_all = $index > 3;
                                                $cartItemIds = array_column(array_column($cartItems?->toArray() ?? [], 'options'), 'slot_id');
                                                $tooltipClass = Arr::random(['warning', 'pending', 'ready', 'success']);
                                                $discountedFee = 0;
                                                if (\Nwidart\Modules\Facades\Module::has('kupondeal') && \Nwidart\Modules\Facades\Module::isEnabled('kupondeal')){
                                                $coupon = $slot?->subjectGroupSubjects?->coupons?->first();
                                                if(!empty($coupon)){
                                                $discountedFee = getDiscountedTotal($slot->session_fee, $coupon->discount_type, $coupon->discount_value);
                                                }
                                                }
                                                @endphp
                                                <div @class(['d-none'=> $index > 3, 'am-slot-selected' => in_array($slot->id, $cartItemIds), 'am-weekly-slots_card', 'am-slot-'.$tooltipClass]) id="sessionslot-{{ $slot->id}}">
                                                    @if(!empty($group))
                                                    <h6>
                                                        {{ $group }}
                                                        @if(in_array($slot->id, $cartItemIds))
                                                        <div class="am-closepopup"
                                                            @click="$wire.dispatch('remove-cart', { params: { cartable_id: @js($slot->bookings->first()?->id), cartable_type: 'App\\Models\\SlotBooking' } })">
                                                            <i class="am-icon-multiply-01"></i>
                                                        </div>
                                                        @endif
                                                    </h6>
                                                    @endif
                                                    @if(!empty($subject))<h5>{{ $subject }}</h5>@endif
                                                    <div class="am-weekly-slots_info">
                                                        <span>
                                                            <i class="am-icon-time"></i>
                                                            @if(setting('_lernen.time_format') == '12')
                                                            <em>{{ parseToUserTz($slot->start_time, $timezone)->format('h:i a') }} -
                                                                {{ parseToUserTz($slot->end_time, $timezone)->format('h:i a') }}</em>
                                                            @else
                                                            <em>{{ parseToUserTz($slot->start_time, $timezone)->format('H:i') }} -
                                                                {{ parseToUserTz($slot->end_time, $timezone)->format('H:i') }}</em>
                                                            @endif
                                                        </span>
                                                    </div>

                                                    {{--
                                                    <div class="am-bookingbtns">
                                                        @if(((Auth::check() && Auth()->user()->role == 'student') && !in_array($slot->id, $cartItemIds) && $available_slot > 0) && $slot->bookings->isEmpty())
                                                        <button class="am-booksession" wire:loading.class="am-btn_disable" wire:target="bookSiosion $slot->id }})" wire:click.prevent="boobookS({sionlot->id }})">{{ __('tutor.book_session_txt') }}</button>
                                                    @endif
                                                    <button class="am-viewdetail" wire:loading.class="am-btn_disable" wire:target="showSlotDetail('{{ $slot->id }}')" wire:click.prevent="showSlotDetail('{{ $slot->id }}');">{{ __('tutor.view_detail') }}</button>
                                                </div> --}}
                                                <div class="am-bookingbtns">
                                                    {{--
                                                            @if(((Auth::check() && Auth()->user()->role == 'student') && !in_array($slot->id, $cartItemIds) && $available_slot > 0) && $slot->bookings->isEmpty())
                                                                <button class="am-booksession" wire:loading.class="am-btn_disable" wire:target="bookSiosion $slot->id }})" wire:click.prevent="boobookS({sionlot->id }})" >{{ __('tutor.book_session_txt') }}</button>
                                                    @endif
                                                    --}}
                                                    @php
                                                    $isPastDate = \Carbon\Carbon::parse($date)->lt(\Carbon\Carbon::now()->startOfDay());
                                                    @endphp
                                                    @if(!$isPastDate)
                                                    <button class="am-viewdetail" wire:loading.class="am-btn_disable" wire:target="estudianteReserva('{{ $slot->id }}')" wire:click.prevent="toggleConfirmationDiv('{{ $slot->id }}');" data-bs-toggle="modal" data-bs-target="#confirmationModal">
                                                        {{ __('tutor.view_booking') }}
                                                    </button>
                                                    @endif
                                                </div>

                                            </div>
                                            @endforeach
                                            @else
                                            <span class="am-emptyslot">{{ __('calendar.no_sessions') }}</span>
                                            @endif
                        </div>
                        </td>
                        @endfor
                        </tr>
                        </tbody>
                        </table>
                    </div>

                    <div class="am-booking-weekly-clander am-booking-mobile">
                        @for ($date = $currentDate->copy()->startOfWeek($startOfWeek); $date->lte($currentDate->copy()->endOfWeek(getEndOfWeek($startOfWeek))); $date->addDay())
                        @php
                        $active_date = $date->toDateString();
                        $slots = $availableSlots[$active_date] ?? [];
                        $showAll[$active_date] = false;
                        @endphp
                        <td>
                            <div class="am-weekly-slots @if($selectedDate === $date->toDateString()) am-day-slots-show @endif">
                                @if(!empty($slots))

                                @foreach ($slots as $index => $slot)
                                @php
                                $total_spaces = $slot->spaces ?? 0;
                                $total_booked = $slot->total_booked ?? 0;
                                $available_slot = $total_spaces - $total_booked;
                                $showAll[$active_date] = $index > 4;
                                $cartItemIds = array_column(array_column($cartItems?->toArray() ?? [], 'options'), 'slot_id');
                                $tooltipClass = Arr::random(['warning', 'pending', 'ready', 'success'])
                                @endphp
                                <div @class(['d-none'=> $index > 4, 'am-slot-selected' => in_array($slot->id, $cartItemIds), 'am-weekly-slots_card', 'am-slot-'.$tooltipClass]) id="sessionslot-{{ $slot->id}}">
                                    @if(!empty($group))
                                    <h6>
                                        {{ $group }}
                                        @if(in_array($slot->id, $cartItemIds))
                                        <div class="am-closepopup"
                                            @click="$wire.dispatch('remove-cart', { params: { cartable_id: @js($slot->bookings->first()?->id), cartable_type: 'App\\Models\\SlotBooking' } })">
                                            <i class="am-icon-multiply-02"></i>
                                        </div>
                                        @endif
                                    </h6>
                                    @endif
                                    @if(!empty($subject))<h5>{{ $subject }}</h5>
                                    @endif
                                    <div class="am-weekly-slots_info">
                                        <span>
                                            <i class="am-icon-time"></i>
                                            @if(setting('_lernen.time_format') == '12')
                                            <em>{{ parseToUserTz($slot->start_time, $timezone)->format('h:i a') }} -
                                                {{ parseToUserTz($slot->end_time, $timezone)->format('h:i a') }}</em>
                                            @else
                                            <em>{{ parseToUserTz($slot->start_time, $timezone)->format('H:i') }} -
                                                {{ parseToUserTz($slot->end_time, $timezone)->format('H:i') }}</em>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="am-bookingbtns">
                                        @if((Auth::check() && Auth()->user()->role == 'student') && !in_array($slot->id, $cartItemIds) && $available_slot > 0)
                                        <button class="am-booksession" wire:loading.class="am-btn_disable" wire:target="bookSession({{ $slot->id }})" wire:click.prevent="bookSession({{ $slot->id }})">{{ __('tutor.book_session_txt') }}</button>
                                        @endif
                                        @php
                                        $isPastDate = \Carbon\Carbon::parse($date)->lt(\Carbon\Carbon::now()->startOfDay());
                                        @endphp
                                        @if(!$isPastDate)
                                        <button class="am-viewdetail" wire:loading.class="am-btn_disable" wire:target="estudianteReserva('{{ $slot->id }}')" wire:click.prevent="toggleConfirmationDiv('{{ $slot->id }}');" data-bs-toggle="modal" data-bs-target="#confirmationModal">
                                            {{ __('tutor.view_booking') }}
                                        </button>
                                        @endif
                                    </div>

                                </div>
                                @endforeach
                                @else
                                <span class="am-emptyslot">{{ __('calendar.no_sessions') }}</span>
                                @endif
                            </div>
                        </td>
                        @if($showAll[$active_date] && $selectedDate == $active_date)
                        <div class="am-view_schedule-wrap">
                            <button class="am-white-btn am-view_schedule am-showmore">{{ __('tutor.view_full_schedules')}}</button>
                        </div>
                        @endif
                        @endfor
                    </div>
                    <a class="tab-pane_rightarrow" href="javascript:void(0);" wire:click="nextBookings">
                        <i class="am-icon-chevron-right"></i>
                    </a>
                </div>
            </div>
            @if($show_all)
            <div class="am-view_schedule-wrap am-showmore-btn">
                <button class="am-white-btn am-view_schedule am-showmore">{{ __('tutor.view_full_schedules')}}</button>
            </div>
            @endif
        </div>


    </div>





    <x-slot-detail-modal :cartItems="$cartItems" :timezone="$timezone" :currentSlot="$currentSlot" :user="$user" wire:key="{{ time() }}" />
    @endif






    <div wire:ignore.self class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="confirmationModalLabel">{{ __('tutor.confirm_booking') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($successMessage)
                        <div class="alert alert-success mt-2 mb-3 text-center">{{ $successMessage }}</div>
                    @endif
                    <!-- Imagen obtenida desde el backend -->
                    @if($imagePreview)
                    <div class="mb-4">
                        <img src="{{ $imagePreview }}" alt="Imagen previa desde el backend" class="w-full h-auto border border-gray-300 rounded-md">
                    </div>
                    @endif

                    <!-- Previsualización de la imagen subida por el usuario -->
                    {{--

                    @if($uploadedImagePreview)
                    <div class="mb-4">
                        <img src="{{ $uploadedImagePreview }}" alt="Previsualización de la imagen subida" class="w-full h-auto border border-gray-300 rounded-md">
                </div>
                @endif

                --}}

                <!-- Input para subir una imagen -->
                <div class="mb-4">
                    <label for="image-upload" class="block text-sm font-medium text-gray-700 mb-2">{{ __('tutor.upload_image') }}</label>
                    <input type="file" id="image-upload" wire:model="uploadedImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring focus:ring-indigo-500">
                    @error('uploadedImage')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Selector de hora basado en el rango de horas -->

                @if(!empty($hourRange))
                <div class="mb-4">
                    <label for="hour-selector" class="block text-sm font-medium text-gray-700 mb-2">{{ __('tutor.select_hour') }}</label>
                    <select id="hour-selector" wire:model="selectedHour" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring focus:ring-indigo-500">
                        @foreach($hourRange as $hour)
                        <option value="{{ $hour }}">{{ $hour }}</option>
                        @endforeach
                    </select>
                </div>
                @endif


                <!-- Selector de subjects del tutor -->
                @if(!empty($subjects))
                <div class="mb-4">
                    <label for="subject-selector" class="block text-sm font-medium text-gray-700 mb-2">{{ __('tutor.select_subject') }}</label>
                    <select id="subject-selector" wire:model="selectedSubject" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring focus:ring-indigo-500">
                        <option value="">Selecciona una materia</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @if($subjectError)
                        <span class="text-red-500 text-sm mt-1">{{ $subjectError }}</span>
                    @endif
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('tutor.cancel') }}</button>
                <button type="button" class="btn btn-primary" wire:click.prevent="estudianteReserva('{{ $selectedSlotId }}'); return false;">{{ __('tutor.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

</div>
<div id="data-container" data-start-of-week="{{ json_encode($startOfWeek) }}"></div>

@push('styles')
@vite([
'public/css/flatpicker-month-year-plugin.css',
])
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Livewire.on('scrollToTop', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>

<script defer src="{{ asset('js/flatpicker.js') }}"></script>
<script defer src="{{ asset('js/weekSelect.min.js') }}"></script>
<script defer src="{{ asset('js/flatpicker-month-year-plugin.js') }}"></script>
<script>
    let dateInstance = null;
    document.addEventListener("DOMContentLoaded", (event) => {
        document.addEventListener('initCalendarJs', (event) => {
            setTimeout(() => {
                initFlatPicker(event.detail.currentDate, );
            }, 100);
        })

        function initFlatPicker(currentDate) {
            if (dateInstance) {
                dateInstance.destroy();
            }

            let startOfWeek = JSON.parse(document.getElementById('data-container').getAttribute('data-start-of-week'));
            let config = {
                defaultDate: parseDateRange(currentDate),
                onChange: function(selectedDates, dateStr, instance) {
                    Livewire.emit('jumpToDate', dateStr);
                },
                disableMobile: true,
                minDate: "{{ Carbon\Carbon::now($timezone)->toDateString() }}",
                plugins: [
                    new weekSelect({
                        weekStart: startOfWeek
                    })
                ],
                dateFormat: 'Y-m-d',
                onReady: function(selectedDates, dateStr, instance) {
                    // Código adicional si es necesario
                }
            };
            dateInstance = flatpickr('#flat-picker', config);
        }

        function parseDateRange(dateRangeStr) {
            const [range, year] = dateRangeStr.split(' ');
            const [startStr, endStr] = range.split('-');

            const monthMap = {
                January: 0,
                February: 1,
                March: 2,
                April: 3,
                May: 4,
                June: 5,
                July: 6,
                August: 7,
                September: 8,
                October: 9,
                November: 10,
                December: 11
            };

            const parseDate = (str) => new Date(`${monthMap[str.split(' ')[0]]}/${str.split(' ')[1]}/${year}`);

            try {
                const startDate = parseDate(startStr);
                const endDate = parseDate(endStr);
                if (isNaN(startDate) || isNaN(endDate)) throw new Error('Invalid date');
                return {
                    start: startDate.toISOString().split('T')[0],
                    end: endDate.toISOString().split('T')[0]
                };
            } catch {
                return null;
            }
        }

        jQuery(document).on('click', '.am-view_schedule', function() {
            let _this = jQuery(this);
            if (_this.hasClass('am-showmore')) {
                _this.addClass('am-showless').removeClass('am-showmore');
                jQuery('.am-weekly-slots').find('.d-none').addClass('am-showless').removeClass('d-none');
                _this.text("{{ __('tutor.show_less') }}");
            } else {
                _this.addClass('am-showmore')
                jQuery('.am-weekly-slots').find('.am-showless').addClass('d-none').removeClass('am-showless');
                _this.text("{{ __('tutor.view_full_schedules') }}");
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const confirmationModal = document.getElementById('confirmationModal');
        confirmationModal.addEventListener('hidden.bs.modal', function() {
            Livewire.emit('resetImagePreview');
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener los datos del atributo data-* del contenedor
        let dataContainer = document.getElementById('data-container');
        let startOfWeek = JSON.parse(dataContainer.getAttribute('data-start-of-week'));

        // Usar Livewire.emit para manejar eventos correctamente
        document.getElementById('flat-picker').addEventListener('change', function(event) {
            let dateStr = event.target.value;
            Livewire.emit('jumpToDate', dateStr);
        });
    });
</script>
<script>
    document.addEventListener('closeConfirmationModal', function () {
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmationModal'));
        modal.hide();
    });
</script>
<script>
    let lastScrollY = 0;
    document.addEventListener('DOMContentLoaded', function () {
        // Captura la posición justo antes de la acción Livewire
        document.querySelectorAll('.btn-primary[wire\\:click]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                lastScrollY = window.scrollY;
                e.preventDefault();
                return false;
            });
        });
        document.addEventListener('showSuccessAndCloseModal', function () {
            setTimeout(function() {
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmationModal'));
                modal.hide();
                setTimeout(function() {
                    window.scrollTo({ top: lastScrollY, behavior: 'auto' });
                }, 300);
            }, 1500);
        });
    });
</script>
@endpush
</div>

