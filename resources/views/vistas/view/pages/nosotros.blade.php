@extends('vistas.view.layouts.app')

@section('title', 'Class Go! | ¿Quiénes somos?')

@section('content')
    <!--NOSOTROS-->
    <section class="nosotros">
        <div class="nosotros-container">
            <div class="nosotros-header">
                <div class="nosotros-header-content">
                    <div class="nosotros-header-text">
                        <nav class="breadcrumb">
                            <a href="{{ route('home') }}" class="breadcrumb-link">Inicio</a> / <span class="breadcrumb-current">Nosotros</span>
                        </nav>
                        <h1>¿Quiénes Somos?</h1>
                        <p>
                            Somos una plataforma de tutorías en línea que conecta a estudiantes de todas las edades con
                            tutores expertos.
                            Te proporcionamos una experiencia accesible y de calidad, independientemente de tu ubicación u
                            horario.
                        </p>
                    </div>
                    <div class="nosotros-header-image">
                        <img src="{{ asset('storage/optionbuilder/uploads/72002-15-2025_1048pmTugo 2.png') }}"
                            alt="Misión ClassGo" class="tugo-image">
                    </div>
                </div>
            </div>


            <div class="nosotros-mision">
                <div class="nosotros-mision-text">
                    <h2 class="nosotros-mision-title">Misión</h2>
                    <p class="nosotros-mision-text-general1">
                        Plataforma educativa de tutorías virtuales para compartir conocimientos.
                    </p>
                    <p class="nosotros-mision-text-general2">
                        Proporcionamos una plataforma educativa de tutorías virtuales accesibles las 24 horas, dirigida a
                        toda
                        persona que quiera compartir su conocimiento, con contenido que abarca desde nivel universitario
                        hasta
                        habilidades técnicas.
                    </p>
                </div>



                <div class="nosotros-mision-image">
                    <p class="nosotros-mision-porcentaje">
                        <span class="nosotros-mision-porcentaje-text">
                            +200 <!-- Porcentaje de Tutores Disponibles -->
                        </span>
                        <span class="nosotros-porcentaje-subtext">
                            Tutorías disponibles
                        </span>
                    </p>
                    <img src="{{ asset('images/mision.png') }}" alt="Misión ClassGo" class="tugo-image">
                </div>
            </div>

            <div class="nosotros-vision">
                <div class="vision-image">
                    <img src="{{ asset('images/tutorias.png') }}"
                        alt="Visión ClassGo" class="tugo-image">
                </div>
                <div class="nosotros-vision-text">
                    <h2 class="nosotros-vision-title">Visión</h2>
                    <p class="nosotros-vision-subtext">
                        Nuestra visión: Impulsar el crecimiento del aprendizaje.
                    </p>
                    <p class="nosotros-vision-subtext2">
                        Ser la plataforma líder en tutorías virtuales, fomentando el aprendizaje continuo y la accesibilidad
                        educativa en todas las áreas del conocimiento.
                    </p>
                </div>


            </div>
            <div class="nosotros-logros">
                <div class="nosotros-logros-text">
                    <h3 class="nosotros-logros-title">Logros clave</h3>
                    <h2 class="nosotros-logros-text">Conoce nuestros logros más destacados</h2>
                    <p class="nosotros-logros-subtext">
                        Estas cifras resaltan nuestros esfuerzos continuos para mantener altos estándares y una mejora
                        constante
                        en
                        todo lo que hacemos.
                    </p>
                </div>
                
                @include('components.counters')
            </div>
            
            @include('components.alianzas', ['alianzas' => $alianzas])

            

        </div>
    </section>
@endsection
