<div class="am-profile-setting">
    @slot('title')
        {{ __('sidebar.bookings') }}
    @endslot
    @role('tutor')
        @include('livewire.pages.tutor.manage-sessions.tabs')
    @endrole
    <div wire:target="switchShow,jumpToDate,nextBookings,previousBookings,filter"
         class="am-booking-wrapper am-upcomming-booking @role('student') am-student-booking @endrole" x-data="{
            form:@entangle('form'),
            charLeft:500,
            init(){
                this.updateCharLeft();
            },
            tutorInfo:{},
            updateCharLeft() {
                let maxLength = 500;
                if (this.form.comment.length > maxLength) {
                    this.form.comment = this.form.comment.substring(0, maxLength);
                }
                this.charLeft = maxLength - this.form.comment.length;
            },
            showModal: false,
            selectedTutoria: {},
            openModal(tutoria) {
                this.selectedTutoria = tutoria;
                this.showModal = true;
            },
            closeModal() {
                this.showModal = false;
            }
        }">
        <div class="am-booking-calander">
            <div class="am-booking-calander_header">
                <div class="am-booking-dates-slot">
                    <div class="am-booking-calander-day">
                        <a href="#" @if($disablePrevious) disabled @else wire:click="previousBookings" @endif>
                            <i class="am-icon-chevron-left"></i>
                        </a>
                        <span @if($isCurrent) disabled @else wire:click="jumpToDate()" @endif>
                            {{ __('calendar.current_'.$showBy) }}
                        </span>
                        <a href="#" wire:click="nextBookings">
                            <i class="am-icon-chevron-right"></i>
                        </a>
                    </div>
                    <div class="am-booking-calander-date flatpicker" wire:ignore>
                        <x-text-input id="flat-picker" />
                    </div>
                </div>
                <div class="am-booking-filters-wrapper">
                    <div class="am-inputicon">
                        <input type="text" wire:model.live.debounce.250ms="filter.keyword"
                            placeholder="{{ __('taxonomy.search_here') }}" class="form-control" />
                        <a href="#">
                            <i class="am-icon-search-02"></i>
                        </a>
                    </div>
                    <div class="am-booking-filter-wrapper">
                        <a class="am-booking-filter" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" data-bs-auto-close="outside">
                            <i class="am-icon-sliders-horiz-01"></i>
                        </a>
                        <form class="am-itemdropdown_list am-filter-list dropdown-menu"
                            aria-labelledby="dropdownMenuLink" x-on:submit.prevent x-data="{
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
                                        <select id="type_fiter" data-componentid="@this" data-parent=".am-filter-list"
                                            class="am-select2" data-searchable="false" data-wiremodel="filter.type">
                                            <option value="*">{{ __('calendar.show_all_type') }}</option>
                                            <option value="one">{{ __('calendar.one') }}</option>
                                        
                                        </select>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('calendar.subject_placeholder') }}</label>
                                    <span class="am-select am-multiple-select am-filter-select" wire:ignore>
                                        <select id="filter_subject_group" data-componentid="@this"
                                            data-parent=".am-filter-list" class="am-select2"
                                            data-class="subject-dropdown-select2" data-format="custom"
                                            data-searchable="true" data-wiremodel="filter.subject_group_ids"
                                            data-placeholder="{{ __('calendar.subject_placeholder') }}" multiple>
                                            <option label="{{ __('calendar.subject_placeholder') }}"></option>
                                            @if(!empty($subjectGroups))
                                            @foreach ($subjectGroups as $group)
                                            <optgroup label="{{ $group['group_name'] }}">
                                                @foreach ($group['subjects'] as $subject)
                                                <option value="{{ $subject['id'] }}"
                                                    data-price="{{ isset($subject['hour_rate']) ? formatAmount($subject['hour_rate']) : '' }}">
                                                    {{ $subject['subject_name'] }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                            @endif
                                        </select>
                                    </span>
                                </div>
                                <template x-if="selectedValues.length > 0">
                                    <ul class="am-subject-tag-list">
                                        <template x-for="(subject, index) in selectedValues">
                                            <li>
                                                <a href="javascript:void(0)" class="am-subject-tag"
                                                    @click="removeValue(subject.value)">
                                                    <span x-text="`${subject.text} (${subject.price})`"></span>
                                                    <i class="am-icon-multiply-02"></i>
                                                </a>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                                <button class="am-btn" @click="submitFilter()">{{ __('general.apply_filter') }}</button>
                            </fieldset>
                        </form>
                    </div>
                    <ul class="am-session-slots am-session-slots-sm" role="tablist">
                        <li>
                            <button @class(['active'=> $showBy == 'daily']) @if($showBy != 'daily')
                                wire:click="switchShow('daily')" @endif aria-selected="true"
                                wire:loading.class="am-btn_disable">{{ __('calendar.daily') }}</button>
                        </li>
                        <li>
                            <button @class(['active'=> $showBy == 'weekly']) @if($showBy != 'weekly')
                                wire:click="switchShow('weekly')" @endif aria-selected="false"
                                wire:loading.class="am-btn_disable">{{ __('calendar.weekly') }}</button>
                        </li>
                        <li>
                            <button @class(['active'=> $showBy == 'monthly']) @if($showBy != 'monthly')
                                wire:click="switchShow('monthly')" @endif aria-selected="false"
                                wire:loading.class="am-btn_disable">{{ __('calendar.monthly') }}</button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="am-section-load" wire:loading.flex wire:target="switchShow,jumpToDate,nextBookings,previousBookings,filter">
                <p>{{ __('general.loading') }}</p>
            </div>
            <div wire:loading.class="d-none" class="am-booking-calander_body" wire:target="switchShow,jumpToDate,nextBookings,previousBookings,filter">
                <div class="tab-content">
                    @php
                        $statusColors = [
                            'pendiente' => '#FACC15', // amarillo
                            'rechazado' => '#EF4444', // rojo
                            'aceptado' => '#22C55E', // verde
                            'no_completado' => '#64748B', // gris
                            'completado' => '#3B82F6', // azul
                        ];
                        $statusTranslations = [
                            'active' => 'Activo',
                            'rescheduled' => 'Reprogramada',
                            'refunded' => 'Reembolsada',
                            'reserved' => 'Reservada',
                            'completed' => 'Completada',
                            'disputed' => 'En disputa',
                        ];
                        $statusMap = [
                            1 => 'Pendiente',
                            2 => 'Observada',
                            3 => 'Aceptada',
                            4 => 'Completada',
                            5 => 'No completada',
                        ];
                    @endphp
                    @if($showBy == 'daily')
                    @php
                        $selectedDay = parseToUserTz($currentDate)->toDateString();
                        $bookingsSelectedDay = $upcomingBookings[$selectedDay] ?? [];
                    @endphp
                    <div class="tab-pane fade show active" id="dailytab" wire:key="dailytab-{{ $selectedDay }}">
                        <table class="am-booking-clander-daily" wire:key="table-{{ $selectedDay }}">
                            <thead>
                                <tr>
                                    <th>{{ __('calendar.time') }}</th>
                                    <th>{{ parseToUserTz($currentDate)->format('F j, Y \G\M\T P') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $startTime = \Carbon\Carbon::parse($selectedDay)->setTime(0, 0, 0);
                                    $endTime = \Carbon\Carbon::parse($selectedDay)->setTime(23, 59, 0);
                                @endphp
                                @while ($startTime <= $endTime)
                                    @php
                                        $slotStart = $startTime->copy();
                                        $slotEnd = $startTime->copy()->addMinutes(30);
                                    @endphp
                                    <tr>
                                        <td>{{ $startTime->format('h:i A') }}</td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 4px; width: 100%;">
                                            @foreach($bookingsSelectedDay as $booking)
                                                @if(is_array($booking) && isset($booking['start_time']))
                                                    @php
                                                        $bookingStart = \Carbon\Carbon::parse($booking['start_time']);
                                                    @endphp
                                                    @if($bookingStart >= $slotStart && $bookingStart < $slotEnd)
                                                        <div style="background: {{ $statusColors[strtolower(trim($booking['status']))] ?? '#FACC15' }} !important; color:black;padding:5px;border-radius:5px; cursor:pointer; width: 100%;"
                                                            @click="openModal({
                                                                estado: '{{ $statusMap[$booking['status_num']] ?? $booking['status_num'] }}',
                                                                hora_inicio: '{{ \Carbon\Carbon::parse($booking['start_time'])->format('H:i') }}',
                                                                hora_fin: '{{ \Carbon\Carbon::parse($booking['end_time'])->format('H:i') }}',
                                                                fecha: '{{ \Carbon\Carbon::parse($booking['start_time'])->format('Y-m-d') }}',
                                                                materia: '{{ $booking['subject_name'] }}',
                                                                meeting_link: '{{ $booking['meeting_link'] ?? '' }}'
                                                            })">
                                                            {{ $statusMap[$booking['status_num']] ?? $booking['status_num'] }}
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                    @php $startTime = $startTime->copy()->addMinutes(30); @endphp
                                @endwhile
                            </tbody>
                        </table>
                    </div>
                    @elseif($showBy == 'weekly')
                    <div class="tab-pane fade show active" id="weeklytab">
                        <div style="overflow-x:auto; width:100%;">
                            <table class="am-booking-weekly-clander" style="min-width:900px; width:100%;">
                            <thead>
                                <tr>
                                    @for ($date = $currentDate->copy()->startOfWeek($startOfWeek);
                                    $date->lte($currentDate->copy()->endOfWeek(getEndOfWeek($startOfWeek)));
                                    $date->addDay())
                                        <th style="min-width:120px;">
                                        <div class="am-booking-calander-title">
                                            <strong>{{ $date->format('j F') }}</strong>
                                            <span>{{ $date->format('D') }}</span>
                                        </div>
                                    </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @for ($date = $currentDate->copy()->startOfWeek($startOfWeek);
                                    $date->lte($currentDate->copy()->endOfWeek(getEndOfWeek($startOfWeek)));
                                    $date->addDay())
                                        <td style="min-width:120px; vertical-align:top;">
                                        <div class="am-weekly-slots_wrap">
                                            <div class="am-weekly-slots">
                                                @if (isset($upcomingBookings[$date->toDateString()]))
                                                @foreach ($upcomingBookings[$date->toDateString()] as $booking)
                                                            <div style="background:{{ $statusColors[strtolower(trim($booking['status']))] ?? '#FACC15' }} !important;color:black;padding:5px 8px;border-radius:5px;margin-bottom:5px; font-size:14px; cursor:pointer;"
                                                                @click="openModal({
                                                                    estado: '{{ $statusMap[$booking['status_num']] ?? $booking['status_num'] }}',
                                                                    hora_inicio: '{{ \Carbon\Carbon::parse($booking['start_time'])->format('H:i') }}',
                                                                    hora_fin: '{{ \Carbon\Carbon::parse($booking['end_time'])->format('H:i') }}',
                                                                    fecha: '{{ \Carbon\Carbon::parse($booking['start_time'])->format('Y-m-d') }}',
                                                                    materia: '{{ $booking['subject_name'] }}',
                                                                    meeting_link: '{{ $booking['meeting_link'] ?? '' }}'
                                                                })">
                                                                Estado: <b>{{ $statusMap[$booking['status_num']] ?? $booking['status_num'] }}</b><br>
                                                                {{ \Carbon\Carbon::parse($booking['start_time'])->format('h:i a') }} - {{ \Carbon\Carbon::parse($booking['end_time'])->format('h:i a') }}
                                                            </div>
                                                @endforeach
                                                @else
                                                <span class="am-emptyslot">{{ __('calendar.no_sessions') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    @elseif($showBy == 'monthly')
                    <div class="tab-pane fade show active" id="monthlytab">
                        <table class="am-monthly-session-table">
                            <thead>
                                <tr>
                                    @foreach ($days as $day)
                                    <th>{{ $day['short_name'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $startOfCalendar = $currentDate->copy()->firstOfMonth()->startOfWeek($startOfWeek);
                                $endOfCalendar = $currentDate->copy()->lastOfMonth()->endOfWeek(getEndOfWeek($startOfWeek));
                                @endphp
                                @while ($startOfCalendar <= $endOfCalendar)
                                <tr>
                                    @for ($i = 0; $i < 7; $i++)
                                    @php $totalBookings=0; @endphp
                                    <td @class(['am-outside-calendar'=> $startOfCalendar->format('m') != $currentDate->format('m')])>
                                        <div class="am-monthly-session-title">
                                            <span @class(['current-date'=> $startOfCalendar->isToday()])>{{ parseToUserTz($startOfCalendar)->format('j') }}</span>
                                            @if (isset($upcomingBookings[$startOfCalendar->toDateString()]))
                                            @foreach ($upcomingBookings[$startOfCalendar->toDateString()] as $booking)
                                            @php $totalBookings += 1; @endphp
                                            @endforeach
                                            <em> {{ $totalBookings }} Tutorías </em>
                                            @endif
                                        </div>
                                        @if (isset($upcomingBookings[$startOfCalendar->toDateString()]))
                                        <div style="display: flex; flex-direction: column; gap: 4px; margin-bottom: 4px;">
                                            @foreach ($upcomingBookings[$startOfCalendar->toDateString()] as $index => $booking)
                                                <div style="background: {{ $statusColors[strtolower(trim($booking['status']))] ?? '#FACC15' }} !important; color: #222; padding:5px; border-radius:5px; cursor:pointer;"
                                                    @click="openModal({
                                                        estado: '{{ $statusMap[$booking['status_num']] ?? $booking['status_num'] }}',
                                                        hora_inicio: '{{ \Carbon\Carbon::parse($booking['start_time'])->format('H:i') }}',
                                                        hora_fin: '{{ \Carbon\Carbon::parse($booking['end_time'])->format('H:i') }}',
                                                        fecha: '{{ \Carbon\Carbon::parse($booking['start_time'])->format('Y-m-d') }}',
                                                        materia: '{{ $booking['subject_name'] }}',
                                                        meeting_link: '{{ $booking['meeting_link'] ?? '' }}'
                                                    })">
                                                    Estado: <b>{{ $statusMap[$booking['status_num']] ?? $booking['status_num'] }}</b><br>
                                                    {{ \Carbon\Carbon::parse($booking['start_time'])->format('h:i a') }} - {{ \Carbon\Carbon::parse($booking['end_time'])->format('h:i a') }}
                                                </div>
                                            @endforeach
                                        </div>
                                        @endif
                                        </td>
                                    @php $startOfCalendar->addDay(); @endphp
                                        @endfor
                                        </tr>
                                        @endwhile
                            </tbody>
                        </table>
                    </div>
                    @else
                    <x-no-record :image="asset('images/session.png')" :title="__('calendar.no_sessions')"
                        :description="__('calendar.no_session_desc')" />
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal de detalles de tutoría -->
       <div 
  x-show="showModal" 
  class="am-modal-overlay"
  x-transition
>
  <div 
    style="
      background: #fff; 
      border-radius: 12px; 
      padding: 24px 30px; 
      max-width: 400px; 
      width: 100%; 
      box-shadow: 0 10px 25px rgba(0,0,0,0.15); 
      display: flex; 
      flex-direction: column; 
      align-items: stretch;
      position: relative;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #333;
    "
  >
    <h3 style="font-size: 1.4rem; font-weight: 600; margin-bottom: 20px; text-align: center; color: #222;">
      Detalles de la tutoría
    </h3>

    <p style="margin: 8px 0; font-size: 1rem;">
      <strong>Estado:</strong> <span x-text="selectedTutoria.estado" style="color: #007BFF;"></span>
    </p>
    <p style="margin: 8px 0; font-size: 1rem;">
      <strong>Fecha:</strong> <span x-text="selectedTutoria.fecha"></span>
    </p>
    <p style="margin: 8px 0; font-size: 1rem;">
      <strong>Hora inicio:</strong> <span x-text="selectedTutoria.hora_inicio"></span>
    </p>
    <p style="margin: 8px 0; font-size: 1rem;">
      <strong>Hora fin:</strong> <span x-text="selectedTutoria.hora_fin"></span>
    </p>
    <p style="margin: 8px 0; font-size: 1rem;">
        <strong>Materia:</strong> <span x-text="selectedTutoria.materia"></span>
      </p>
      <p style="margin: 8px 0; font-size: 1rem;">
        <strong>Link de la tutoría:</strong> 
        <template x-if="selectedTutoria.meeting_link">
            <a :href="selectedTutoria.meeting_link" target="_blank" style="color: #007BFF; text-decoration: underline; word-break: break-all;">
                <span x-text="selectedTutoria.meeting_link"></span>
            </a>
        </template>
        <template x-if="!selectedTutoria.meeting_link">
            <span style="color: #888;">No disponible</span>
        </template>
    </p>
    <!-- Más campos aquí -->

    <button 
      @click="closeModal" 
      style="
        margin-top: 24px; 
        padding: 10px 20px; 
        background-color: #007BFF; 
        color: white; 
        border: none; 
        border-radius: 6px; 
        cursor: pointer; 
        font-weight: 600;
        transition: background-color 0.3s ease;
      "
      onmouseover="this.style.backgroundColor='#0056b3'"
      onmouseout="this.style.backgroundColor='#007BFF'"
    >
      Cerrar
    </button>
  </div>
</div>

        <x-completion/>
        <x-dispute-reason-popup :booking="$userBooking" :disputeReason="$disputeReason" :description="$description" :selectedReason="$selectedReason"/>
        <x-booking-detail-modal :currentBooking="$currentBooking" wire:key="{{ time() }}" />
    </div>
</div>
@push('styles')
@vite([
    'public/css/flatpicker.css',
    'public/css/flatpicker-month-year-plugin.css'
    ])
<style>
.am-modal-overlay {
    background: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.am-booking-clander-daily td {
    height: auto !important;
    vertical-align: top;
    padding-top: 6px;
    padding-bottom: 6px;
}
</style>
@endpush
@push('scripts')
<script defer src="{{ asset('js/flatpicker.js') }}"></script>
<script defer src="{{ asset('js/weekSelect.min.js') }}"></script>
<script defer src="{{ asset('js/flatpicker-month-year-plugin.js') }}"></script>
@endpush

@script
<script>
    let flatpickrInstance = null;
        initFlatPicker('daily', 'today');
        $wire.dispatch('initSelect2', {target:'.am-select2'})
        document.addEventListener('initCalendarJs', (event)=>{
            setTimeout(() => {
                initFlatPicker(event.detail.showBy, event.detail.currentDate, event.detail.range);
            }, 100);
        })
        function initFlatPicker(showBy, currentDate, range=[]) {
            if (flatpickrInstance) {
                flatpickrInstance.destroy();
            }
            let config = {
                defaultDate : currentDate,
                disableMobile: true,
                onChange : function(selectedDates, dateStr, instance) {
                    @this.call('jumpToDate', dateStr);
                }
            }
            if(showBy == 'daily') {
                config = {
                    ...config,
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d"
                };
                @role('tutor')
                    config = {...config, minDate: @js(\Carbon\Carbon::now(getUserTimezone())->toDateString())}
                @endrole()
            } else if (showBy == 'weekly') {
                config = {
                    ...config,
                    defaultDate: parseDateRange(currentDate),
                    minDate: @js(\Carbon\Carbon::now(getUserTimezone())->toDateString()),
                    plugins: [
                        new weekSelect({
                            weekStart: @js($startOfWeek)
                        })
                    ],
                    dateFormat: 'Y-m-d',
                    onReady: function(selectedDates, dateStr, instance) {
                        instance.input.value = currentDate
                    }
                };
            } else {
                config = {
                    ...config,
                        plugins: [
                            new monthSelectPlugin({
                                shorthand: true,
                                dateFormat: "F, Y",
                            })
                        ],
                };
            }
            flatpickrInstance = flatpickr($(`#flat-picker`), config);
        }

        function parseDateRange(dateRangeStr) {
            const [range, year] = dateRangeStr.split(' ');
            const [startStr, endStr] = range.split('-');

            const monthMap = {
                January: 0, February: 1, March: 2, April: 3, May: 4, June: 5,
                July: 6, August: 7, September: 8, October: 9, November: 10, December: 11
            };

            const parseDate = (str) => new Date(`${monthMap[str.split(' ')[0]]}/${str.split(' ')[1]}/${year}`);

            try {
                const startDate = parseDate(startStr);
                const endDate = parseDate(endStr);
                if (isNaN(startDate) || isNaN(endDate)) throw new Error('Invalid date');
                return { start: startDate.toISOString().split('T')[0], end: endDate.toISOString().split('T')[0] };
            } catch {
                return null;
            }
        }

</script>
@endscript
