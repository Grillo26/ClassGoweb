<div>
    <div class="am-profile-setting am-managesessions_wrap">
        @slot('title')
            {{ __('calendar.title') }}
        @endslot
        @include('livewire.pages.tutor.manage-sessions.tabs')
        <div class="am-section-load" wire:loading.flex wire:target="updatedCurrentMonth,jumpToDate,updatedCurrentYear,previousMonthCalendar,nextMonthCalendar">
            <p>{{ __('general.loading') }}</p>
        </div>
        <div class="am-booking-wrapper">
            <div class="am-booking-calander">
                <div class="am-booking-calander_header">
                    <h1>{{ __('calendar.title') }} </h1>
                    <div>
                        <div class="am-booking-filters-wrapper">
                            <div class="am-booking-calander-day">
                                <i wire:click="previousMonthCalendar('{{ $currentDate }}')">
                                    <i class="am-icon-chevron-left"></i>
                                </i>
                                <span @if(parseToUserTz($currentDate)->isToday()) disabled @else wire:click="jumpToDate()" @endif>
                                    {{ __('calendar.today') }}
                                </span>
                                <i wire:click="nextMonthCalendar('{{ $currentDate }}')">
                                    <i class="am-icon-chevron-right"></i>
                                </i>
                            </div>
                            <div class="am-booking-calander-date flatpicker">
                                <input type="text" class="form-control" id="calendar-month-year">
                            </div>

                            <div class="am-booking-filter-wrapper">
                                <a class="am-booking-filter" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
                                    <i class="am-icon-sliders-horiz-01"></i>
                                </a>
                                <form class="am-itemdropdown_list am-filter-list dropdown-menu" aria-labelledby="dropdownMenuLink"  x-on:submit.prevent
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
                                            const selectElement = document.getElementById('filter_subject_group');
                                            @this.set('subjectGroupIds', $(selectElement).select2('val'));
                                        }
                                    }"
                                    >
                                    <fieldset>
                                        <div class="form-group">
                                            <label>{{ __('calendar.subject_placeholder') }}</label>
                                            <span class="am-select am-multiple-select am-filter-select" wire:ignore>
                                                <select id="filter_subject_group" data-componentid="@this" class="am-select2" data-class="subject-dropdown-select2" data-format="custom" data-searchable="true" data-wiremodel="subjectGroupIds" data-placeholder="{{ __('calendar.subject_placeholder') }}" multiple>
                                                    <option label="{{ __('calendar.subject_placeholder') }}"></option>
                                                    @foreach ($subjectGroups as $sbjGroup)
                                                        @if ($sbjGroup->subjects->isEmpty() || !$sbjGroup->group)
                                                            @continue
                                                        @endif
                                                        <optgroup label="{{ $sbjGroup->group->name }}">
                                                            @foreach ($sbjGroup->subjects as $sbj)
                                                                <option value="{{ $sbj->pivot->id }}" data-price="{{ formatAmount($sbj->pivot->hour_rate) }}">{{ $sbj->name }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </span>
                                        </div>
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
                                        <button class="am-btn" @click="submitFilter()">{{ __('general.apply_filter') }}</button>
                                    </fieldset>
                                </form>
                            </div>

                            <button class="am-btn" data-bs-toggle="modal" data-bs-target="#new-booking-modal">
                                {{ __('calendar.add_new_session') }}
                                <i class="am-icon-plus-02"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="am-booking-calander_body">
                    <table class="am-full-calander">
                        <thead>
                            <tr>
                                @foreach ($days as $day)
                                    <th class="{{ (setting('_lernen.start_of_week') ?? \Carbon\Carbon::SUNDAY) == $day['week_day'] ? 'am-calendar_offday' : '' }}">{{ $day['short_name'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @while ($startOfCalendar <= $endOfCalendar)
                                <tr>
                                    @for ($i = 0; $i < 7; $i++)
                                        <td>
                                            <a
                                                wire:key="{{ time() }}"
                                                @if(empty($availableSlots[parseToUserTz($startOfCalendar)->toDateString()]))
                                                    href="#"
                                                    x-on:click="$wire.dispatch('toggleModel', {id:'booking-modal',action:'show'})"
                                                @else
                                                    href="{{ route('tutor.bookings.session-detail', ['date' => parseToUserTz($startOfCalendar)->toDateString()]) }}"
                                                    wire:navigate.remove
                                                @endif
                                                @class([
                                                    'am-full-calander-days',
                                                    'am-active' => parseToUserTz($startOfCalendar)->isToday(),
                                                    'am-outside-calendar' => parseToUserTz($startOfCalendar)->format('m') != parseToUserTz($currentDate)->format('m'),
                                                    'am-empty-slots' => empty($availableSlots[$startOfCalendar->toDateString()])
                                                ])
                                            >
                                            @if(!empty($availableSlots[parseToUserTz($startOfCalendar)->toDateString()]))
                                                @php
                                                    $availableSeats = $availableSlots[parseToUserTz($startOfCalendar)->toDateString()]['all_slots'] - $availableSlots[parseToUserTz($startOfCalendar)->toDateString()]['booked_slots'];
                                                    $percentage = round(($availableSeats / $availableSlots[parseToUserTz($startOfCalendar)->toDateString()]['all_slots'] * 100), 2);
                                                @endphp
                                                <div class="am-slots-count">
                                                    <em> <strong>{{ $availableSeats  }}</strong>/ {{ $availableSlots[parseToUserTz($startOfCalendar)->toDateString()]['all_slots'] }} {{ __('calendar.spaces_left') }}</em>
                                                    <progress class="am-progress" value="{{ $percentage }}" max="100"></progress>
                                                </div>
                                                <span class="am-custom-tooltip">
                                                    {{ parseToUserTz($startOfCalendar)->format('j') }}
                                                    <div class="am-slots-count am-tooltip-text">
                                                        <em> <strong>{{ $availableSeats  }}</strong>/ {{ $availableSlots[parseToUserTz($startOfCalendar)->toDateString()]['all_slots'] }} {{ __('calendar.left') }}</em>
                                                        <progress class="am-progress" value="{{ $percentage }}" max="100"></progress>
                                                    </div>
                                                </span>
                                            @else
                                                <span class="am-custom-tooltip">
                                                    {{ parseToUserTz($startOfCalendar)->format('j') }}
                                                </span>
                                            @endif
                                            </a>
                                        </td>
                                        @php
                                            $startOfCalendar->addDay();
                                        @endphp
                                    @endfor
                                </tr>
                            @endwhile
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL NUEVO UNICO -->
            <div id="new-booking-modal" class="modal fade" tabindex="-1" aria-labelledby="newBookingModalLabel" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newBookingModalLabel">{{ __('calendar.add_session') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="addSession" autocomplete="off">
                                <div class="row">
                                    

                                    <!-- Campo de Fechas con calendario emergente -->
                                    <div class="col-md-6 mb-3">
                                        <label for="date_range_new" class="form-label">{{ __('calendar.start_end_date') }}</label>
                                        <input type="text" id="date_range_new" class="form-control flatpickr-date" wire:model="form.date_range" placeholder="Selecciona fecha" data-min-date="today">
                                        <x-input-error field_name="form.date_range" />
                                    </div>

                                    <!-- Campo de Hora de Inicio -->
                                    <div class="col-md-6 mb-3">
                                        <label for="start_time_new" class="form-label">Hora Inicio</label>
                                        <input type="text" id="start_time_new" class="form-control flatpickr-time" wire:model="form.start_time" placeholder="Selecciona hora de inicio">
                                        <x-input-error field_name="form.start_time" />
                                    </div>

                                    <!-- Campo de Hora de Fin -->
                                    <div class="col-md-6 mb-3">
                                        <label for="end_time_new" class="form-label">Hora Fin</label>
                                        <input type="text" id="end_time_new" class="form-control flatpickr-time" wire:model="form.end_time" placeholder="Selecciona hora de fin">
                                        <x-input-error field_name="form.end_time" />
                                    </div>

                                    

                                    <!-- Campo de Descripción -->
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">{{ __('calendar.session_description') }}</label>
                                        <textarea id="description" class="form-control" wire:model="form.description" rows="4" placeholder="{{ __('calendar.add_session_description') }}"></textarea>
                                        <x-input-error field_name="form.description" />
                                    </div>

                                   
                                </div>

                                <!-- Botones de Acción -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.close') }}</button>
                                    <button type="submit" class="btn btn-primary" wire:loading.class="btn-loading">
                                        {{ __('general.save_update') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    @vite([
        'public/css/flatpicker.css',
        'public/summernote/summernote-lite.min.css'
    ])
@endpush

@push('scripts')
    <script defer src="{{ asset('js/flatpicker.js') }}"></script>
    <script>
        document.addEventListener('shown.bs.modal', function (event) {
            if (event.target.id === 'new-booking-modal') {
                flatpickr('#date_range_new', {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                });
                flatpickr('#start_time_new', {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    minuteIncrement: 1,
                });
                flatpickr('#end_time_new', {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    minuteIncrement: 1,
                });
            }
        });
    </script>
@endpush