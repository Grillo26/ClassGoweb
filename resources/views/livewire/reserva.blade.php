<div>
   <div class="tutor-availability-grid">
      <div>
         <h4 class="tutor-section-title">Selecciona un día</h4>
         <div class="tutor-calendar-box">
               <div class="tutor-calendar-header">
                  {{-- Botón para ir al mes anterior --}}
                  <button wire:click="goToPreviousMonth" class="tutor-calendar-nav-btn">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-calendar-nav-icon"><path d="m15 18-6-6 6-6"></path></svg>
                  </button>

                  {{-- Muestra el mes y año actual --}}
                  <h5 class="tutor-calendar-month">{{ $currentDate->translatedFormat('F Y') }}</h5>

                  {{-- Botón para ir al mes siguiente --}}
                  <button wire:click="goToNextMonth" class="tutor-calendar-nav-btn">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-calendar-nav-icon"><path d="m9 18 6-6-6-6"></path></svg>
                  </button>
               </div>

               <div class="tutor-calendar-grid">
                  {{-- Etiquetas de los días de la semana --}}
                  <div class="tutor-calendar-day-label">L</div>
                  <div class="tutor-calendar-day-label">M</div>
                  <div class="tutor-calendar-day-label">M</div>
                  <div class="tutor-calendar-day-label">J</div>
                  <div class="tutor-calendar-day-label">V</div>
                  <div class="tutor-calendar-day-label">S</div>
                  <div class="tutor-calendar-day-label">D</div>

                  {{-- Espacios en blanco para los días antes de que comience el mes --}}
                  @for ($i = 0; $i < $startDay; $i++)
                     <div></div>
                  @endfor

                  {{-- Itera sobre los días del mes --}}
                  @for ($day = 1; $day <= $daysInMonth; $day++)
                     @php
                           // Define las clases CSS para cada día
                           $isBooked = isset($bookedDates[$day]);
                           $isSelected = $selectedDay == $day;
                           $isPast = $this->isPastDay($day);

                           $dayClasses = 'tutor-calendar-day';
                           if ($isBooked) $dayClasses .= ' booked';
                           if ($isSelected) $dayClasses .= ' selected';
                           if ($isPast) $dayClasses .= ' past';
                     @endphp

                     <div wire:click="selectDay({{ $day }})" class="{{ $dayClasses }}">
                           {{ $day }}
                     </div>
                  @endfor
               </div>
         </div>
      </div>

      {{-- Esta columna solo se muestra si se ha seleccionado un día --}}
      @if ($selectedDay)
      <div id="time-selector-column" class="tutor-time-selector-col">
         <h4 class="tutor-section-title">Selecciona una hora</h4>
         <div class="tutor-time-selector-box">
               @if (!empty($availableSlots))
                  {{-- Muestra las horas disponibles --}}
                  <div class="tutor-time-slots">
                     @foreach ($availableSlots as $slot)
                           <button class="tutor-time-slot-btn">{{ $slot }}</button>
                     @endforeach
                  </div>
               @else
                  {{-- Muestra un mensaje si no hay horas --}}
                  <p class="tutor-no-availability">Día no disponible</p>
               @endif
         </div>
      </div>
      @endif
   </div>
   <!-- Botón para abrir el modal -->
    <div class="tutor-pay-btn-box"> 
        <button class="tutor-pay-btn" id="openModalBtn">Pagar y reservar</button>
    </div>
</div>