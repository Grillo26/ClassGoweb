<div>
    {{-- Mensaje de éxito tras reservar --}}
    @if (session()->has('success_message'))
        <div class="alert-success">{{ session('success_message') }}</div>
    @endif

    <div class="tutor-availability-grid">
        {{-- CALENDARIO --}}
        <div>
            <h4 class="tutor-section-title">Selecciona un día</h4>
            <div class="tutor-calendar-box">
                <div class="tutor-calendar-header">
                  <button wire:click="goToPreviousMonth" class="tutor-calendar-nav-btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-calendar-nav-icon"><path d="m15 18-6-6 6-6"></path></svg></button>
                  <h5 class="tutor-calendar-month">{{ $currentDate->translatedFormat('F Y') }}</h5>
                  <button  wire:click="goToNextMonth" class="tutor-calendar-nav-btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-calendar-nav-icon"><path d="m9 18 6-6-6-6"></path></svg></button>
                </div>
                <div class="tutor-calendar-grid">
                    <div class="tutor-calendar-day-label">D</div> 
                    <div class="tutor-calendar-day-label">L</div> 
                    <div class="tutor-calendar-day-label">M</div> 
                    <div class="tutor-calendar-day-label">M</div> 
                    <div class="tutor-calendar-day-label">J</div> 
                    <div class="tutor-calendar-day-label">V</div> 
                    <div class="tutor-calendar-day-label">S</div> 
                    @for ($i = 0; $i < $startDay; $i++) <div></div> @endfor
                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $isAvailable = in_array($day, $daysWithAvailability);
                            $isSelected = $selectedDay == $day;
                            $isPast = $this->isPastDay($day);
                            $dayClasses = 'tutor-calendar-day';
                            if ($isAvailable) $dayClasses .= ' has-availability';
                            if ($isSelected) $dayClasses .= ' selected';
                            if ($isPast) $dayClasses .= ' past';
                        @endphp
                        <div wire:click="selectDay({{ $day }})" class="{{ $dayClasses }}">{{ $day }}</div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- SELECTOR DE HORA --}}
        @if ($selectedDay)
        <div class="tutor-time-selector-col">
            <h4 class="tutor-section-title">Selecciona una hora</h4>
            <div class="tutor-time-selector-box">
                @if (!empty($availableTimeSlots))
                    <div class="tutor-time-slots">
                        @foreach ($availableTimeSlots as $slot)
                            @php
                                $isOccupied = $slot['status'] === 'occupied';
                                $isTimeSelected = $selectedTime === $slot['time'];
                                $slotClasses = 'tutor-time-slot-btn';
                                if ($isOccupied) $slotClasses .= ' occupied';
                                if ($isTimeSelected) $slotClasses .= ' selected';
                            @endphp
                            <button wire:click="selectTime('{{ $slot['time'] }}')" class="{{ $slotClasses }}" @if($isOccupied) disabled @endif>
                                {{ $slot['time'] }}
                            </button>
                        @endforeach
                    </div>
                @else
                    <p class="tutor-no-availability">Horas no disponible</p>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Botón para abrir el modal -->
   <div class="tutor-pay-btn-box">
      <button class="tutor-pay-btn" id="openModalBtn">Pagar y reservar</button>
   </div>


   <!-- ========================== MODAL RESERVA =========================-->
    <div id="reservationModal" class="modal-overlay">
        <div id="modalContent" class="modal-content">
            <form wire:submit="makeReservation" class="modal-body">
                <div class="modal-qr-column">
                    <img src="{{ asset('storage/qr/77b1a7da.jpg')}}" alt="Código QR" class="qr-image">
                </div>
                <div class="modal-form-column">
                    <h2 class="form-title">Confirmar Reserva modal 2 kkk</h2>
                    <div> 
                        <label class="input-label">Comprobante de pago</label>
                        <label for="comprobante" class="file-input-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="upload-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            Subir archivo
                        </label>
                        <input type="file" id="comprobante" wire:model="paymentReceipt" class="file-input-hidden">
                        @error('paymentReceipt') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="materia" class="input-label">Materia</label>
                        <select id="materia" wire:model="selectedSubject" class="select-input">
                            <option value="">-- Elige una materia --</option>
                            <option value="calculo1">Cálculo I</option>
                            
                        </select>
                        @error('selectedSubject') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    @if ($selectedDay && $selectedTime)
                        <div class="info-box">
                            <p><strong>Fecha:</strong> <span>{{ $currentDate->copy()->setDay($selectedDay)->translatedFormat('j \de F \de Y') }}</span></p>
                            <p><strong>Hora:</strong> <span>{{ \Carbon\Carbon::parse($selectedTime)->format('h:i a') }}</span></p>
                        </div>
                    @endif
                    <div class="action-buttons">
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Reservar</button>
                    </div>
                </div>
            </form>
        </div>
    </div> 

   {{--  --}}
</div>