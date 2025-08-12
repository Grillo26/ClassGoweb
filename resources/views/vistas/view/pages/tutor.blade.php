@extends('vistas.view.layouts.app')

@section('content')

<div class="tutor-bg">
    <!-- Contenido Principal -->
    <main class="tutor-main">
        <!-- Breadcrumbs -->
        <div class="tutor-breadcrumbs">
            <a href="#" class="tutor-breadcrumb-link">Tutores</a> / 
            <a href="{{ route('buscar.tutor')}}" class="tutor-breadcrumb-link">Encontrar tutor</a> / 
            <span class="tutor-breadcrumb-current">{{ $tutor->profile->first_name ?? '' }} {{ $tutor->profile->last_name ?? '' }}</span>
        </div>
        <div class="tutor-grid">
            <!-- Columna Izquierda (Información del Tutor) -->
            <div class="tutor-col tutor-col-main">
                <!-- Card Principal del Tutor -->
                <div class="tutor-card-main">
                    <div class="tutor-banner" id="tutor-banner-area">
                        <video id="tutor-bg-video"
                            class="tutor-banner-video"
                            preload="none"
                            {{-- poster="{{ $tutor->profile->image ? asset('storage/' . $tutor->profile->image) : asset('images/tutors/profile.jpg') }}" --}}
                            poster="{{ asset('images/classgo/banner.jpeg')}}"
                            src="{{ $tutor->profile->intro_video ? asset('storage/' . $tutor->profile->intro_video) : '' }}"
                            loop
                            muted
                            playsinline
                            style="object-fit: cover; width: 100%; height: 100%; position: absolute; left: 0; top: 0; z-index: 1;">
                        </video>
                        <div class="tutor-banner-overlay" id="tutor-banner-overlay" style="position: relative; z-index: 2; transition: opacity 0.3s;">
                            <button id="tutor-banner-play" class="tutor-banner-play">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-banner-play-icon">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                            </button>
                            <input id="tutor-banner-volume" type="range" min="0" max="1" step="0.01" value="0.5" style="display:none; width:100px; margin-left:20px;">
                        </div>
                    </div>
                    <div class="tutor-card-main-content">
                         <img src="{{ asset('storage/' . $tutor->profile->image) ? asset('storage/' . $tutor->profile->image): asset('images/tutors/profile.jpg') }}" alt="Foto de {{ $tutor->profile->first_name ?? '' }}" class="tutor-profile-img" style="background-color: white">
                         <div class="tutor-profile-info">
                            <h1 class="tutor-profile-name">{{ $tutor->profile->first_name ?? '' }} {{ $tutor->profile->last_name ?? '' }}</h1>
                            <div class="tutor-profile-meta">
                                <div class="tutor-profile-rating">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-star-icon">
                                        <path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                    <span>{{ number_format($tutor->avg_rating ?? 0, 1) }}</span>
                                    <span class="rating-count">( {{ $tutor->total_reviews }} reseñas)</span>
                                </div>
                                <div class="tutor-profile-students">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="tutor-student-icon">
                                        <rect x="3" y="5" width="18" height="14" rx="2" />
                                        <polyline points="3 7 12 13 21 7" />
                                    </svg>
                                    <span>{{$tutor->email}}</span>
                                </div>
                            </div>
                            
                         </div>
                         <p class="tutor-profile-quote">{{ $tutor->profile->description ?? '" Tutor verificado y aprobado por ClassGo!"' }}</p>
                    </div>
                </div>
                <!-- SECCIÓN DE PESTAÑAS PRINCIPAL -->
                <div class="tutor-tabs-card">
                    <div class="tutor-tabs-nav">
                        <nav class="tutor-tabs-list" aria-label="Tabs">
                            <button onclick="changeTab(event, 'introduccion')" class="tutor-tab-btn active">Tutoría</button>
                            <button onclick="changeTab(event, 'disponibilidad')" class="tutor-tab-btn">Disponibilidad</button>
                            <button onclick="changeTab(event, 'curriculum')" class="tutor-tab-btn">Aspectos Destacados</button>
                            <button onclick="changeTab(event, 'resenas')" class="tutor-tab-btn">Reseñas</button>
                        </nav>
                    </div>
                    <div class="tutor-tabs-content">
                        <div id="introduccion" class="tutor-tab-content">
                            {{-- <div>
                                <h3 class="tutor-section-title">Acerca de mí</h3>
                                <p class="tutor-section-text">{{ $tutor->profile->description ?? 'Sin descripción.' }}</p>                            </div>
                            <hr class="tutor-section-divider"> --}}
                            <div>
                                <h3 class="tutor-section-title">Puedo enseñar</h3>
                                @php
                                    // Agrupar materias por grupo (asumiendo que $tutor->userSubjects está disponible)
                                    $materiasPorGrupo = [];
                                    if(isset($tutor->userSubjects)) {
                                        foreach($tutor->userSubjects as $userSubject) {
                                            $grupo = $userSubject->subject->group->name ?? 'Otros';
                                            $materia = $userSubject->subject->name ?? null;
                                            if($materia) {
                                                $materiasPorGrupo[$grupo][] = $materia;
                                            }
                                        }
                                    }
                                @endphp
                                <div class="tutor-section-skills-grupos">
                                    @foreach($materiasPorGrupo as $grupo => $materiasGrupo)
                                        <div class="tutor-grupo-block" style="margin-bottom:2rem;">
                                            <div class="tutor-grupo-header" style="display:flex;align-items:center;gap:0.1rem;">
                                                <span class="tutor-skill-tag" style="color: var(--primary-color);font-weight:600;font-size:1.1rem;min-width:2.2em;min-height:2.2em;display:inline-flex;"> 
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="margin-right:0.3em;">
                                                    <!-- Tapa del cuaderno -->
                                                    <rect x="3" y="2" width="18" height="20" rx="2" ry="2" />
                                                    <!-- Línea de división entre tapa y hojas -->
                                                    <line x1="7" y1="2" x2="7" y2="22" />
                                                    <!-- Líneas interiores (simulan hojas) -->
                                                    <line x1="11" y1="6" x2="17" y2="6" />
                                                    <line x1="11" y1="10" x2="17" y2="10" />
                                                    <line x1="11" y1="14" x2="17" y2="14" />
                                                    </svg>
                                                    {{ $grupo }}</span>
                                            </div>
                                            <div class="tutor-section-skills-materias" style="display:flex;flex-wrap:wrap;gap:1.2rem;margin-top:0.5rem; margin-left:2.3rem;">
                                                @foreach($materiasGrupo as $materia)
                                                    <span class="tutor-skill-materia" style="font-size:1rem;color:#023047;">{{ $materia }}</span>
                                                @endforeach
                                                
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr class="tutor-section-divider">
                             <div>
                                <h3 class="tutor-section-title">Puedo hablar</h3>
                                @if($tutor->languages && count($tutor->languages))
                                    @foreach($tutor->languages as $lang)
                                        <span class="tutor-language-tag">{{ $lang->name }}</span>
                                    @endforeach
                                @else
                                    <span class="tutor-language-tag">No especificado</span>
                                @endif
                            </div>
                        </div>
                        <div id="disponibilidad" class="tutor-tab-content hidden">
                            <h3 class="tutor-section-title-lg">Reserva una sesión</h3>
                            {{-- <<<<======LOGICA PARA RESERVAR=======>>>>>>--}}
                            <livewire:reserva />
                            
                        </div>
                        <div id="curriculum" class="tutor-tab-content hidden">
                           <nav class="tutor-subtabs-nav"><button onclick="changeSubTab(event, 'educacion')" class="tutor-subtab-btn active">Educación</button><button onclick="changeSubTab(event, 'experiencia')" class="tutor-subtab-btn">Experiencia</button><button onclick="changeSubTab(event, 'certificaciones')" class="tutor-subtab-btn">Certificación</button></nav>
                            <div id="educacion" class="tutor-subtab-content">
                                <div class="tutor-empty-box">
                                    {{-- LOGICA PARA MOSTRAR DE BASE DE DATOS --}}
                                    <div class="am-norecord">
                                        @include('livewire.components.no-record')
                                    </div>
                                </div>
                            </div>
                            <div id="experiencia" class="tutor-subtab-content hidden">
                                <div class="tutor-empty-box">
                                    {{-- LOGICA PARA MOSTRAR DE BASE DE DATOS --}}
                                    <div class="am-norecord">
                                        @include('livewire.components.no-record')
                                    </div>
                                </div>
                            </div>
                            <div id="certificaciones" class="tutor-subtab-content hidden">
                                <div class="tutor-empty-box">
                                    {{-- LOGICA PARA MOSTRAR DE BASE DE DATOS --}}
                                    <div class="am-norecord">
                                        @include('livewire.components.no-record')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="resenas" class="tutor-tab-content hidden">
                            <h3 class="tutor-section-title" style="margin-bottom: 1.5rem;">Reseñas de estudiantes</h3>
                            <div class="tutor-reviews-box">
                                <!-- Resumen de calificación -->
                                <div class="tutor-reviews-summary">
                                    <div class="tutor-reviews-score" style="font-size:2.5rem;">0.0</div>
                                    <div class="tutor-reviews-stars" style="margin:1rem 0;">
                                        @for($i=0; $i<5; $i++)
                                            <svg class="tutor-star-icon" width="24" height="24" fill="#ccc" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <div class="tutor-reviews-count" style="color:#888;">Basado en 0 calificaciones</div>
                                </div>
                                <!-- Detalle de barras -->
                                <div class="tutor-reviews-details" style="width:67%;">
                                    @for($i=5; $i>=1; $i--)
                                    <div class="tutor-review-bar-row" style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
                                        <span style="color:#888;">{{ $i }}</span>
                                        <svg class="tutor-star-icon" width="18" height="18" fill="#FB8500" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        <div class="tutor-review-bar-bg" style="flex:1;background:#e0e0e0;border-radius:1rem;height:8px;">
                                            <div class="tutor-review-bar-fill" style="background:#FB8500;height:8px;border-radius:1rem;width:0%;"></div>
                                        </div>
                                        <span style="color:#888;font-weight:600;">0</span>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="tutor-empty-box tutor-reviews-empty" style="text-align:center;margin-top:2rem;padding-top:2rem;border-top:1px solid #e0e0e0;">
                                {{-- LOGICA PARA MOSTRAR DE BASE DE DATOS --}}
                                <div class="am-norecord">
                                    @include('livewire.components.no-record')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Columna Derecha (Acciones) -->
            <div class="tutor-col tutor-col-actions">
                <div class="tutor-actions-card">
                    <div class="tutor-actions-price-box">
                        <p class="tutor-actions-price">10 <span class="tutor-actions-price-unit">/ tutorías realizadas</span></p>
                        <div class="tutor-actions-meta">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-actions-meta-icon"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <span>20 min</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-actions-meta-icon tutor-actions-meta-icon-green"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <span class="tutor-actions-meta-verified">Tutor verificado</span>
                        </div>
                    </div>
                    <div class="tutor-actions-btns">
                        <button class="tutor-btn tutor-btn-now">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-btn-icon"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span>Tutoría ahora</span>
                        </button>
                        <button class="tutor-btn tutor-btn-reservar" id="btn-go-disponibilidad">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-btn-icon"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            <span>Reservar</span>
                        </button>
                        <button class="tutor-btn tutor-btn-share" id="btn-share-profile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-btn-icon"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line></svg>
                            <span>Compartir perfil</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Compartir -->
    <div id="modal-share-profile" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:9999;align-items:center;justify-content:center;">
        <div style="position: absolute; background:#fff;padding:2rem 1.5rem;border-radius:1rem;max-width:350px;width:90%; display:flex; justify-content:center; flex-flow: column wrap;">
            <button id="close-modal-share" style="position:absolute;top:10px;right:15px;background:none;border:none;font-size:1.5rem;cursor:pointer;">&times;</button>
            <img src="{{ asset('images/Tugo_With_Phone.png') }}" style="width: 300px; ">
            <h3 style="margin-bottom:1rem;">Compartir perfil</h3>
            <p style="margin-bottom:1.2rem;">Hecha un vistazo a mi perfil en ClassGo!</p>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <button id="btn-share-whatsapp" style="background:#25D366;color:#fff;font-weight:600;padding:0.7rem 1rem;border:none;border-radius:0.7rem;display:flex;align-items:center;gap:0.7rem;cursor:pointer;justify-content:center;">
                    <svg width="20" height="20" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 3C9.373 3 4 8.373 4 15c0 2.385.832 4.58 2.236 6.364L4 29l7.818-2.236A12.94 12.94 0 0 0 16 27c6.627 0 12-5.373 12-12S22.627 3 16 3Zm0 22c-1.77 0-3.484-.463-4.98-1.34l-.355-.21-4.646 1.33 1.33-4.646-.21-.355A9.956 9.956 0 0 1 6 15c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10Zm5.29-7.29c-.29-.145-1.71-.84-1.975-.935-.265-.095-.46-.145-.655.145-.195.29-.75.935-.92 1.13-.17.195-.34.22-.63.075-.29-.145-1.225-.45-2.335-1.435-.863-.77-1.445-1.72-1.615-2.01-.17-.29-.018-.447.127-.592.13-.13.29-.34.435-.51.145-.17.193-.29.29-.485.097-.195.048-.365-.024-.51-.073-.145-.655-1.58-.9-2.165-.237-.57-.48-.492-.655-.5-.17-.007-.365-.01-.56-.01-.195 0-.51.073-.78.365-.27.29-1.03 1.01-1.03 2.465 0 1.455 1.055 2.86 1.202 3.055.145.195 2.08 3.18 5.04 4.33.705.242 1.255.386 1.685.494.708.18 1.35.155 1.86.094.567-.067 1.71-.698 1.95-1.372.24-.673.24-1.25.17-1.372-.07-.122-.265-.195-.555-.34Z" fill="#fff"/></svg>
                    Compartir en WhatsApp
                </button>
                <button id="btn-share-facebook" style="background:#1877F3;color:#fff;font-weight:600;padding:0.7rem 1rem;border:none;border-radius:0.7rem;display:flex;align-items:center;gap:0.7rem;cursor:pointer;justify-content:center;">
                    <svg width="20" height="20" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M29 16C29 8.82 23.18 3 16 3S3 8.82 3 16c0 6.29 4.61 11.48 10.63 12.79v-9.05h-3.2V16h3.2v-2.27c0-3.16 1.88-4.89 4.76-4.89 1.38 0 2.82.25 2.82.25v3.1h-1.59c-1.57 0-2.06.98-2.06 1.98V16h3.5l-.56 3.74h-2.94v9.05C24.39 27.48 29 22.29 29 16Z" fill="#fff"/></svg>
                    Compartir en Facebook
                </button>
            </div>

            
        </div>
    </div>
    
    {{-- <!-- Modal Reserva -->
    <div id="reservationModal" class="modal-overlay">
        <div id="modalContent" class="modal-content">
            <div class="modal-body">
                <div class="modal-qr-column">
                    <img src="{{ asset('storage/qr/77b1a7da.jpg')}}" alt="Código QR de Notion" class="qr-image">
                </div>

                <div class="modal-form-column">
                    <h2 class="form-title">Selecciona la materia</h2>

                    <div>
                        <label for="comprobante" class="input-label">Comprobante de pago</label>
                        <label for="comprobante" class="file-input-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="upload-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            Subir archivo
                        </label>
                        <input type="file" id="comprobante" class="file-input-hidden">
                        <p id="fileName" class="file-name-display">Ningún archivo seleccionado</p>
                    </div>

                    <div>
                        <label for="materia" class="input-label">Materia</label>
                        <select id="materia" name="materia" class="select-input">
                            <option value="">-- Elige una materia --</option>
                            <option value="calculo1">Cálculo I</option>
                            <option value="algebra">Álgebra Lineal</option>
                            <option value="fisica2">Física II</option>
                            <option value="programacion">Programación Avanzada</option>
                            <option value="basedatos">Bases de Datos</option>
                        </select>
                    </div>

                    <div class="info-box">
                        <p><strong>Fecha:</strong> <span id="currentDate"></span></p>
                        <p><strong>Hora:</strong> <span id="currentTime"></span></p>
                    </div>

                    <div class="action-buttons">
                        <button id="cancelBtn" class="btn btn-secondary">Cancelar</button>
                        <button class="btn btn-primary">Reservar</button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <script>
        // --- SCRIPT PARA PESTAÑAS ---
        function changeTab(event, tabID) {
            let tabContents = document.querySelectorAll('.tutor-tab-content');
            tabContents.forEach(content => content.classList.add('hidden'));
            let tabButtons = document.querySelectorAll('.tutor-tab-btn');
            tabButtons.forEach(button => button.classList.remove('active'));
            document.getElementById(tabID).classList.remove('hidden');
            event.currentTarget.classList.add('active');
        }
        function changeSubTab(event, tabID) {
            let subTabContents = document.querySelectorAll('.tutor-subtab-content');
            subTabContents.forEach(content => content.classList.add('hidden'));
            let subTabButtons = document.querySelectorAll('.tutor-subtab-btn');
            subTabButtons.forEach(button => button.classList.remove('active'));
            document.getElementById(tabID).classList.remove('hidden');
            event.currentTarget.classList.add('active');
        }
        // --- SCRIPT PARA CALENDARIO Y HORA ---
        document.addEventListener('DOMContentLoaded', function() {
            const calendarGrid = document.getElementById('calendar-grid');
            const timeSelectorColumn = document.getElementById('time-selector-column');
            const timeSlotsContainer = document.getElementById('time-slots');
            if (!calendarGrid) return; // Salir si no estamos en la página correcta
            const month = 6; // Julio (0-indexed)
            const year = 2025;
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const startingDay = (firstDay === 0) ? 6 : firstDay - 1; 
            for (let i = 0; i < startingDay; i++) {
                calendarGrid.appendChild(document.createElement('div'));
            }
            for (let day = 1; day <= daysInMonth; day++) {
                const dayCell = document.createElement('button');
                dayCell.textContent = day;
                dayCell.classList.add('tutor-calendar-day');
                dayCell.dataset.day = day;
                dayCell.onclick = selectDate;
                calendarGrid.appendChild(dayCell);
            }
            const exampleTimes = ['16:00', '16:20', '16:40', '17:00', '17:20', '17:40', '18:00', '18:20', '19:00', '19:20', '19:40'];
            timeSlotsContainer.innerHTML = '';
            exampleTimes.forEach(time => {
                const timeButton = document.createElement('button');
                timeButton.textContent = time;
                timeButton.classList.add('tutor-time-slot');
                timeButton.onclick = selectTime;
                timeSlotsContainer.appendChild(timeButton);
            });
        });
        function selectDate(event) {
            const allDays = document.querySelectorAll('.tutor-calendar-day');
            allDays.forEach(d => d.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            document.getElementById('time-selector-column').classList.remove('hidden');
        }
        function selectTime(event) {
            const allTimes = document.querySelectorAll('.tutor-time-slot');
            allTimes.forEach(t => t.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('tutor-bg-video');
            const playBtn = document.getElementById('tutor-banner-play');
            const volumeSlider = document.getElementById('tutor-banner-volume');
            const overlay = document.getElementById('tutor-banner-overlay');
            const bannerArea = document.getElementById('tutor-banner-area');
            let isPlaying = false;
            let overlayTimeout = null;

            function showOverlay() {
                overlay.style.opacity = 1;
                overlay.style.pointerEvents = 'auto';
                if (overlayTimeout) clearTimeout(overlayTimeout);
            }
            function hideOverlay() {
                overlay.style.opacity = 0;
                overlay.style.pointerEvents = 'none';
            }

            // Mostrar controles al pasar el mouse o hacer click
            bannerArea.addEventListener('mouseenter', showOverlay);
            bannerArea.addEventListener('mousemove', showOverlay);
            bannerArea.addEventListener('mouseleave', function() {
                if (isPlaying) {
                    overlayTimeout = setTimeout(hideOverlay, 500); // espera breve para evitar parpadeo
                }
            });
            bannerArea.addEventListener('click', function() {
                showOverlay();
                if (isPlaying) {
                    overlayTimeout = setTimeout(hideOverlay, 2000); // oculta después de 2s si está reproduciendo
                }
            });

            playBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (!video.src) return;
                if (video.paused) {
                    video.muted = false;
                    video.play();
                    isPlaying = true;
                    playBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-banner-play-icon">
                            <rect x="6" y="4" width="4" height="16"></rect>
                            <rect x="14" y="4" width="4" height="16"></rect>
                        </svg>
                    `;
                    volumeSlider.style.display = 'inline-block';
                    overlayTimeout = setTimeout(hideOverlay, 2000);
                } else {
                    video.pause();
                    isPlaying = false;
                    playBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-banner-play-icon">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                    `;
                    volumeSlider.style.display = 'none';
                    showOverlay();
                }
            });

            // Volumen
            volumeSlider.addEventListener('input', function(e) {
                video.volume = this.value;
                e.stopPropagation();
            });

            // Al pausar el video manualmente (por el usuario)
            video.addEventListener('pause', function() {
                isPlaying = false;
                playBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tutor-banner-play-icon">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                `;
                volumeSlider.style.display = 'none';
                showOverlay();
            });

            // Inicialmente mostrar controles
            showOverlay();
        });

        // ================ Modal para compartir perfil ======================
        document.addEventListener('DOMContentLoaded', function() {
            const btnShare = document.getElementById('btn-share-profile');
            const modalShare = document.getElementById('modal-share-profile');
            const closeModal = document.getElementById('close-modal-share');
            const btnWhatsapp = document.getElementById('btn-share-whatsapp');
            const btnFacebook = document.getElementById('btn-share-facebook');
            const slug = @json($tutor->profile->slug ?? '');
            const shareUrl = `https://classgoapp.com/tutors/${slug}`;
            const shareMsg = 'Hecha un vistazo a mi perfil en ClassGo!';

            btnShare.addEventListener('click', function() {
                modalShare.style.display = 'flex';
            });
            closeModal.addEventListener('click', function() {
                modalShare.style.display = 'none';
            });
            // WhatsApp
            btnWhatsapp.addEventListener('click', function() {
                const url = `https://wa.me/?text=${encodeURIComponent(shareMsg + ' ' + shareUrl)}`;
                window.open(url, '_blank');
            });
            // Facebook
            btnFacebook.addEventListener('click', function() {
                const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}&quote=${encodeURIComponent(shareMsg)}`;
                window.open(url, '_blank');
            });
        });

        //===================== Modal para reserva ===================
        // document.addEventListener('DOMContentLoaded', () => {
        //     // --- Selección de Elementos del DOM ---
        //     const openModalBtn = document.getElementById('openModalBtn');
        //     const reservationModal = document.getElementById('reservationModal');
        //     const modalContent = document.getElementById('modalContent');
        //     const cancelBtn = document.getElementById('cancelBtn');
        //     const body = document.body; // Seleccionamos el body para manipularlo

        //     // Elementos del formulario
        //     const fileInput = document.getElementById('comprobante');
        //     const fileNameDisplay = document.getElementById('fileName');
        //     const dateSpan = document.getElementById('currentDate');
        //     const timeSpan = document.getElementById('currentTime');

        //     // --- Funciones ---

        //     /**
        //      * Actualiza la fecha y la hora en el modal.
        //      */
        //     const updateDateTime = () => {
        //         const now = new Date();
        //         const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
        //         // He ajustado las opciones de hora para usar la de Santa Cruz, Bolivia (GMT-4)
        //         const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true, timeZone: 'America/La_Paz' };
                
        //         // Usamos 'es-BO' para el formato de Bolivia
        //         dateSpan.textContent = now.toLocaleDateString('es-BO', dateOptions);
        //         timeSpan.textContent = now.toLocaleTimeString('es-BO', timeOptions);
        //     };

        //     /**
        //      * Abre el modal y bloquea el scroll del fondo.
        //      */
        //     const openModal = () => {
        //         updateDateTime(); // Actualiza la fecha y hora

        //         // 1. Calcula el ancho de la barra de scroll
        //         const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

        //         // 2. Aplica el padding-right al body para compensar el espacio de la barra
        //         body.style.paddingRight = `${scrollbarWidth}px`;

        //         // 3. Añade la clase que oculta el overflow (bloquea el scroll)
        //         body.classList.add('modal-open');
                
        //         // 4. Muestra el modal
        //         reservationModal.classList.add('is-visible');
        //     };

        //     /**
        //      * Cierra el modal y restaura el scroll del fondo.
        //      */
        //     const closeModal = () => {
        //         // 1. Oculta el modal
        //         reservationModal.classList.remove('is-visible');

        //         // 2. Elimina la clase que bloquea el scroll
        //         body.classList.remove('modal-open');
                
        //         // 3. Restaura el padding-right del body a su estado original
        //         body.style.paddingRight = '';
        //     };

        //     /**
        //      * Actualiza el nombre del archivo seleccionado.
        //      */
        //     const handleFileChange = (event) => {
        //         const file = event.target.files[0];
        //         if (file) {
        //             fileNameDisplay.textContent = file.name;
        //         } else {
        //             fileNameDisplay.textContent = 'Ningún archivo seleccionado';
        //         }
        //     };

        //     // --- Asignación de Eventos (sin cambios aquí) ---

        //     if (openModalBtn) {
        //         openModalBtn.addEventListener('click', openModal);
        //     }
            
        //     if (cancelBtn) {
        //         cancelBtn.addEventListener('click', closeModal);
        //     }

        //     if (reservationModal) {
        //         reservationModal.addEventListener('click', (event) => {
        //             if (event.target === reservationModal) {
        //                 closeModal();
        //             }
        //         });
        //     }

        //     document.addEventListener('keydown', (event) => {
        //         if (event.key === 'Escape' && reservationModal.classList.contains('is-visible')) {
        //             closeModal();
        //         }
        //     });

        //     if (fileInput) {
        //         fileInput.addEventListener('change', handleFileChange);
        //     }
        // });


    document.addEventListener('livewire:initialized', () => {
        // --- Selección de Elementos del DOM ---
        const reservationModal = document.getElementById('reservationModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const body = document.body;

        // Verificar que los elementos existen
        if (!reservationModal) {
            console.error('Modal element not found');
            return;
        }

        // --- Funciones ---
        const openModal = () => {
            try {
                // Calcular ancho de scrollbar para evitar saltos
                const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
                body.style.paddingRight = `${scrollbarWidth}px`;
                body.classList.add('modal-open');
                reservationModal.classList.add('is-visible');
                
                console.log('Modal opened successfully');
                
            } catch (error) {
                console.error('Error opening modal:', error);
            }
        };

        const closeModal = () => {
            try {
                reservationModal.classList.remove('is-visible');
                body.classList.remove('modal-open');
                body.style.paddingRight = '';
                
                console.log('Modal closed successfully');
            } catch (error) {
                console.error('Error closing modal:', error);
            }
        };

        // --- Asignación de Eventos Livewire ---
        
        // 1. Escucha el evento 'open-modal' que viene desde Livewire
        if (window.Livewire) {
            Livewire.on('open-modal', (event) => {
            console.log('Received open-modal event:', event);
            setTimeout(() => {
                openModal();
            }, 1); // Un retraso mínimo es suficiente
        });

            // 2. Escucha un evento de error (opcional pero recomendado)
            Livewire.on('show-error', (event) => {
                console.log('Received error event:', event);
                alert(event.message || 'Ha ocurrido un error');
            });
        } else {
            console.error('Livewire not found. Make sure Livewire is properly loaded.');
        }

        // --- Eventos de Cierre del Modal ---
        
        // Cierra el modal con el botón de cancelar
        if (cancelBtn) {
            cancelBtn.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal();
            });
        }

        // Cierra el modal al hacer clic en el fondo
        reservationModal.addEventListener('click', (event) => {
            if (event.target === reservationModal) {
                closeModal();
            }
        });

        // Cierra el modal con la tecla Escape
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && reservationModal.classList.contains('is-visible')) {
                closeModal();
            }
        });

        // --- Manejo del Input de Archivo ---
        const fileInput = document.getElementById('comprobante');
        const fileNameDisplay = document.getElementById('fileName');
        
        if (fileInput && fileNameDisplay) {
            fileInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                fileNameDisplay.textContent = file ? file.name : 'Ningún archivo seleccionado';
            });
        }

        // --- Debug: Función para probar el modal manualmente ---
        window.testModal = () => {
            console.log('Testing modal...');
            openModal();
        };
        
        console.log('Modal JavaScript initialized successfully');
    });

    
    
    
    </script>
</div>
@endsection