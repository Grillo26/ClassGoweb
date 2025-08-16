@extends('vistas.view.layouts.app')

@section('content')
<!-- Solo agrega esto en la vista donde tienes la paginación -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<div class="container-buscartutor">
    <!-- Hero Section -->
    <section class="buscartutor-hero-section">
        <div class="buscartutor-container">
             <div class="buscartutor-hero-grid">
                <div>
                    <p class="buscartutor-hero-label">Tutores / Encontrar tutor</p>
                    <h1 class="buscartutor-hero-title">Descubra un tutor en línea capacitado para sus estudios</h1>
                    <p class="buscartutor-hero-desc">Domina tus estudios con tutorías personalizadas en línea impartidas por educadores expertos. Nuestros tutores capacitados están aquí para ayudarlo a construir bases sólidas y alcanzar sus objetivos académicos.</p>
                    <p class="mobile-only">
                        Nuestros tutores capacitados están aquí para ayudarte a construir bases sólidas y alcanzar tus objetivos académicos.
                    </p>
                </div>
                <div class="buscartutor-hero-img-col">
                     <img src="{{ asset('storage/optionbuilder/uploads/740102-17-2025_0859pmTugo-saludando.gif') }}" alt="Mascota de ClassGo" class="buscartutor-hero-img" onerror="this.onerror=null; this.src='https://placehold.co/300x300/ffffff/023047?text=ClassGo';">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Componente de búsqueda y listado de tutores -->
    <livewire:buscar-tutor /> 

</div>
@endsection
