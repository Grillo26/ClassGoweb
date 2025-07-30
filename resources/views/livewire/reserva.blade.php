<div>
   <div class="tutor-availability-grid">
      <!-- Columna del Calendario -->
      <div>
         <h4 class="tutor-section-title">Selecciona un día</h4>
         <div class="tutor-calendar-box">
            <div class="tutor-calendar-header">
               <button class="tutor-calendar-nav-btn">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-calendar-nav-icon">
                     <path d="m15 18-6-6 6-6"></path>
                  </svg>
               </button>
               <h5 class="tutor-calendar-month">Julio 2025</h5>
               <button class="tutor-calendar-nav-btn">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-calendar-nav-icon">
                     <path d="m9 18 6-6-6-6"></path>
                  </svg>
               </button>
            </div>
            <div id="calendar-grid" class="tutor-calendar-grid">
               <div class="tutor-calendar-day-label">L</div>
               <div class="tutor-calendar-day-label">M</div>
               <div class="tutor-calendar-day-label">M</div>
               <div class="tutor-calendar-day-label">J</div>
               <div class="tutor-calendar-day-label">V</div>
               <div class="tutor-calendar-day-label">S</div>
               <div class="tutor-calendar-day-label">D</div>
            </div>
         </div>
      </div>
      <!-- Columna del Selector de Hora -->
      <div id="time-selector-column" class="tutor-time-selector-col hidden">
         <h4 class="tutor-section-title">Selecciona una hora</h4>
         <div class="tutor-time-selector-box">
            <p class="tutor-time-range">Horario disponible: <span id="available-range">16:00 - 21:40</span></p>
            <div id="time-slots" class="tutor-time-slots"></div>
            {{-- <button class="tutor-time-exact-btn">Elegir hora exacta</button> --}}
         </div>
      </div>
   </div>
   <!-- Botón para abrir el modal -->
    <div class="tutor-pay-btn-box"> 
        <button class="tutor-pay-btn" id="openModalBtn">Pagar y reservar</button>

    </div>
</div>