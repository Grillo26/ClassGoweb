@extends('vistas.view.layouts.app')

@section('content')
<div class="container-buscartutor">
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
            <!-- Tutor Card de base de datos -->
            @foreach ($profiles as $profile)
                <div class="buscartutor-tutor-card">
                    <img 
                        src="{{ $profile['image'] ? asset('storage/' . $profile['image']) : asset('images/tutors/profile.jpg') }}" 
                        alt="Foto de {{ $profile['full_name'] }}" 
                        class="buscartutor-tutor-img">

                    <div class="buscartutor-tutor-info">
                        <h3 class="buscartutor-tutor-name">{{ $profile['full_name'] }}</h3>

                        <div class="buscartutor-tutor-meta">
                            <span>⭐ {{ $profile['avg_rating'] }} ({{ $profile['total_reviews'] }} reseñas)</span>
                            <span>•</span>
                            <span>1 Sesión</span>
                            <span>•</span>
                            <span>Idioma: {{ $profile['native_language'] }}</span>
                        </div>

                        <p class="buscartutor-tutor-desc">
                            {{ $profile['description'] }}
                        </p>
                    </div>

                    <div class="buscartutor-tutor-actions">
                        <button class="buscartutor-tutor-btn buscartutor-tutor-btn-orange">Reservar una sesión</button>
                        <a href="{{ route('tutor', ['slug' => $profile['slug']]) }}" class="buscartutor-tutor-btn buscartutor-tutor-btn-blue">
                            Ver Perfil
                        </a>


                    </div>
                </div>
            @endforeach

            
            
        </div>
    </section>
</div>
@endsection
