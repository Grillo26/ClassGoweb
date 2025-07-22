@extends('vistas.view.layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="buscartutor-hero-section">
        <div class="buscartutor-container">
             <div class="buscartutor-hero-grid">
                <div>
                    <p class="buscartutor-hero-label">Tutores / Encontrar tutor</p>
                    <h1 class="buscartutor-hero-title">Descubra un tutor en línea capacitado para sus estudios</h1>
                    <p class="buscartutor-hero-desc">Domina tus estudios con tutorías personalizadas en línea impartidas por educadores expertos. Nuestros tutores capacitados están aquí para ayudarlo a construir bases sólidas y alcanzar sus objetivos académicos.</p>
                </div>
                <div class="buscartutor-hero-img-col">
                     <img src="{{ asset('storage/optionbuilder/uploads/740102-17-2025_0859pmTugo-saludando.gif') }}" alt="Mascota de ClassGo" class="buscartutor-hero-img" onerror="this.onerror=null; this.src='https://placehold.co/300x300/ffffff/023047?text=ClassGo';">
                </div>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="buscartutor-search-section">
        <div class="buscartutor-search-box">
            <div class="buscartutor-search-grid">
                <div class="buscartutor-search-keyword">
                    <label for="keyword-search" class="sr-only">Buscar por palabra clave</label>
                    <div class="buscartutor-search-input-wrap">
                        <input type="text" id="keyword-search" placeholder="Buscar por palabra clave" class="buscartutor-search-input">
                         <span class="buscartutor-search-icon">
                            <svg class="buscartutor-search-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                        </span>
                    </div>
                </div>
                <div class="buscartutor-search-group">
                    <label for="group-select" class="sr-only">Grupo de materias</label>
                    <select id="group-select" class="buscartutor-search-select">
                        <option>Elige grupo de materias</option>
                        <option>Ciencias Exactas</option>
                        <option>Humanidades</option>
                        <option>Idiomas</option>
                    </select>
                </div>
                <button class="buscartutor-search-btn">Buscar</button>
            </div>
        </div>
    </section>
    
    <!-- Tutor List -->
    <section class="buscartutor-tutorlist-section">
        <div class="buscartutor-tutorlist-space">
            <!-- Tutor Card 1 -->
            <div class="buscartutor-tutor-card">
                <img src="https://placehold.co/128x128/8ECAE6/023047?text=AF" alt="Foto de Antonio Flores" class="buscartutor-tutor-img">
                <div class="buscartutor-tutor-info">
                    <h3 class="buscartutor-tutor-name">Antonio Alexander Sandoval Flores</h3>
                    <div class="buscartutor-tutor-meta">
                        <span>0.0/5.0 (0 reseñas)</span>
                        <span>•</span>
                        <span>1 Sesión</span>
                        <span>•</span>
                        <span>Idiomas que conozco: Inglés</span>
                    </div>
                    <p class="buscartutor-tutor-desc">Apasionado por compartir conocimientos de manera clara y práctica. Mi objetivo es ayudarte a aprender de forma sencilla y efectiva.</p>
                </div>
                <div class="buscartutor-tutor-actions">
                    <button class="buscartutor-tutor-btn buscartutor-tutor-btn-orange">Reservar una sesión</button>
                    <button class="buscartutor-tutor-btn buscartutor-tutor-btn-blue">Enviar mensaje</button>
                </div>
            </div>
            <!-- Tutor Card 2 -->
            <div class="buscartutor-tutor-card">
                <img src="https://placehold.co/128x128/219EBC/ffffff?text=ER" alt="Foto de Edward Rojas" class="buscartutor-tutor-img">
                <div class="buscartutor-tutor-info">
                    <h3 class="buscartutor-tutor-name">Edward Rojas Cespedes</h3>
                    <p class="buscartutor-tutor-special">Especialidad: Controla la información, controla el futuro</p>
                    <div class="buscartutor-tutor-meta">
                        <span>0.0/5.0 (0 reseñas)</span>
                        <span>•</span>
                        <span>5 Materias</span>
                        <span>•</span>
                        <span>Idiomas que conozco: Inglés</span>
                    </div>
                    <p class="buscartutor-tutor-desc">Joven profesional próximo a graduarme en Información y Control de Gestión, combinando formación académica con habilidades prácticas en análisis de datos y sistemas de gestión. Preparado para los desafíos del mundo...</p>
                </div>
                <div class="buscartutor-tutor-actions">
                    <button class="buscartutor-tutor-btn buscartutor-tutor-btn-orange">Reservar una sesión</button>
                    <button class="buscartutor-tutor-btn buscartutor-tutor-btn-blue">Enviar mensaje</button>
                </div>
            </div>
        </div>
    </section>
@endsection
