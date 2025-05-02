<div class="am-dropdown am-startend-date" x-data="{
    start_time: @entangle('form.start_time'),
    end_time: @entangle('form.end_time'),
    sessionTime: '',
    updateValues() {
        const startHour = $(this.$refs.select_start_hour).val();
        const startMin = $(this.$refs.select_start_min).val();
        const endHour = $(this.$refs.select_end_hour).val();
        const endMin = $(this.$refs.select_end_min).val();
        
        this.start_time = startHour + ':' + startMin;
        this.end_time = endHour + ':' + endMin;
        this.sessionTime = this.start_time + ' to ' + this.end_time;
        this.calculateDuration();
    },
    init() {
        // Actualizar valores cuando cambien los selectores
        $(this.$refs.select_start_hour).on('change', () => this.updateValues());
        $(this.$refs.select_start_min).on('change', () => this.updateValues());
        $(this.$refs.select_end_hour).on('change', () => this.updateValues());
        $(this.$refs.select_end_min).on('change', () => this.updateValues());
    }
}">
    <input type="text" id="session_time" x-model="sessionTime" data-bs-auto-close="outside" class="form-control am-input-field" placeholder="{{ __('calendar.time_placeholder') }}" data-bs-toggle="dropdown" readonly>
    <div class="dropdown-menu booking-time">
        <ul class="am-dropdownlist">
            <li>
                <label class="am-label-calendar am-important2">{{ __('calendar.start_time') }}</label>
                <div class="d-flex gap-2">
                    <span class="am-select" wire:ignore>
                        <select x-ref="select_start_hour" class="form-control" style="font-size: 14px; padding: 8px;">
                            <option value="">{{ __('calendar.hour_placeholder') }}</option>
                            @for ($i=0; $i < 24; $i++)
                                <option value="{{ sprintf("%02d", $i) }}" style="font-size: 14px;">{{ sprintf("%02d", $i) }}</option>
                            @endfor
                        </select>
                    </span>
                    <span class="am-select" wire:ignore>
                        <select x-ref="select_start_min" class="form-control" style="font-size: 14px; padding: 8px;">
                            <option value="">{{ __('calendar.minute_placeholder') }}</option>
                            @for ($i=0; $i < 60; $i++)
                                <option value="{{ sprintf("%02d", $i) }}" style="font-size: 14px;">{{ sprintf("%02d", $i) }}</option>
                            @endfor
                        </select>
                    </span>
                </div>
            </li>
            <li class="mt-3">
                <label class="am-label-calendar am-important2">{{ __('calendar.end_time') }}</label>
                <div class="d-flex gap-2">
                    <span class="am-select" wire:ignore>
                        <select x-ref="select_end_hour" class="form-control" style="font-size: 14px; padding: 8px;">
                            <option value="">{{ __('calendar.hour_placeholder') }}</option>
                            @for ($i=0; $i < 24; $i++)
                                <option value="{{ sprintf("%02d", $i) }}" style="font-size: 14px;">{{ sprintf("%02d", $i) }}</option>
                            @endfor
                        </select>
                    </span>
                    <span class="am-select" wire:ignore>
                        <select x-ref="select_end_min" class="form-control" style="font-size: 14px; padding: 8px;">
                            <option value="">{{ __('calendar.minute_placeholder') }}</option>
                            @for ($i=0; $i < 60; $i++)
                                <option value="{{ sprintf("%02d", $i) }}" style="font-size: 14px;">{{ sprintf("%02d", $i) }}</option>
                            @endfor
                        </select>
                    </span>
                </div>
            </li>
        </ul>
    </div>
</div> 