@extends('layouts.app')

@section('title', 'Class Go!')

@section('content')

<!-- HERO -->
<section class="hero">
    <div class="hero-container">

        <!-- Columna izquierda: texto -->
        <div class="hero-text">
            <h1>Aprende y Progresa con <br><span class="highlight">Tutorías en Línea</span></h1>
            <p class="hero-subtext">
                Alcanza tus metas con tutorías personalizadas de los mejores expertos.<br>
                Conéctate con tutores dedicados para asegurar tu éxito.
            </p>

            <!-- Buscador -->
            <div class="search-box">
                <input type="text" placeholder="Buscar Tutor">
                <button><img src="/images/search-icon.svg" alt="Buscar"></button>
            </div>
        </div>

        <!-- Columna derecha: imagen -->
        <div class="hero-image">
            <img src="{{ asset('images/Tugo_With_Glasses.png') }}" alt="Mascota ClassGo">
        </div>

    </div>
</section>


<!-- CONTADORES INFO -->
<section class="info container">
    <!--CONTADORES-->
    <div class="counters">
        <div>
            <div class="counter-number">+500</div>
            <p>Usuarios Registrados</p>
        </div>
        <div>
            <div class="counter-number">+230</div>
            <p>Tutores Disponibles</p>
        </div>
        <div>
            <div class="counter-number">+230</div>
            <p>Estudiantes Registrados</p>
        </div>
        <div>
            <div class="counter-number">4.5</div>
            <p>En la App Store</p>
        </div>
    </div>

    
    <!--TUTORES DESTACADOS-->
    <div class="outstanding-tutors">
        <h2 style="text-align: center;">Tutores Destacados</h2>
        <h1>Conoce a Nuestros Tutores Cuidadosamente Seleccionados</h1>
        <p>Descubre una variedad de temáticas académicas y prácticas para potenciar tu experiencia de aprendizaje</p>
        <div class="tutors">
            <div class="tutors-card">
                <div class="video-tutor">
                    <img src="{{ asset('images/tutor1.jpg') }}" alt="Tutor">
                </div>
                <div class="info">
                    <div class="info-header">
                        <img src="{{ asset('images/tutor1.jpg') }}" alt="Tutor">
                        <div class="info-name">
                            <div class="name">
                                <h1>Norely Bonilla</h1>
                                <img src="" alt=""> <!--Icono de verificación-->
                                <img src="" alt=""><!--Bandera pais-->
                            </div>
                        </div>
                        <div class="icono-heart">
                            <img src="" alt=""><!--Icono Corazon-->
                        </div>
                    </div>
                    <div class="info-resena">
                        <div class="info-puntuacion">
                            <div class="puntuacion-title">
                                <img src="" alt=""><!--icono estrella-->
                                <p>4.5</p>
                            </div>
                            <p>7 reseñas</p>
                        </div>
                        <div class="info-price">
                            <p class="price-title">Bs.15</p>
                            <p class="price-details">Clases de 20 minutos</p>
                        </div>
                    </div>
                    <div class="info-details">
                        <div>
                            <img src="" alt=""><!--icono-->
                            <p>Finanzas, Presupuesto, Estadística</p>
                        </div>
                        <div>
                            <img src="" alt=""><!--icono-->
                            <p>10 estudiantes activos ° 30 Clases</p>
                        </div>
                        <div>
                            <img src="" alt=""><!--icono-->
                            <p>Ingles</p>
                        </div>
                    </div>
                    <div class="info-buttons">
                        <button>Ver Perfil</button>
                        <button>Reservar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--GUIA PASO A PASO-->
<section class="guide container">
    <p>Una guía paso a paso</p>
    <h1>Desbloquea Tu Potencial Con Pasos Sencillos</h1>
    <p>Descubra como nuestra plataforma simplifica la búsqueda y reserva de los mejores tutores para mejorar sus habilidades y alcanzar objetivos de aprendizaje</p>
    <div class="steps">
        <!--CARD-->
        <div class="steps-card">
            <button>Paso 1</button>
            <img src="" alt=""><!--Imagen Representativa-->
            <h1>Incribete</h1>
            <p>Crea tu cuenta rápidamente para comenzar a utilizar nuestra plataforma</p>
            <button>Empezar</button>
        </div>
        <!--COMIENZA TU JORNADA CARD-->
        <div class="go">
            <img src="" alt=""><!--Icono-->
            <h1>Comienza tu jornada</h1>
            <p>Comienza tu viaje educativo con nosotros. ¡Encuentra un tutor y reserva tu primera sesión hoy mismo!</p>
            <button>Empeza ahora</button>
        </div>
    </div>
</section>

<!--HERO TUTORIAS Y ALIANZAS-->
<section class="tutorias container">
    <div class="tutorias">
        <!-- Imagen -->
        <div class="tutores-img">
            <img src="{{ asset('images/Tugo_With_Glasses.png') }}" alt="Mascota">
        </div>
        <!-- Texto -->
        <div class="tutores-text">
            <p>¿Buscas tutorías personalizadas?</p>
            <h1>En Classgo, te conectamos con los mejores tutores</h1>
            <p>Accede a sesiones cortas y prácticas, diseñadas por tutores expertos para ser pequeños salvavidas en el aprendizaje</p>
            <ul class="tutores-list">
                <li>Acceso 25/7</li>
                <li>Tutores Expertos</li>
                <li>Tarifas asequibles</li>
            </ul>
            <button>Comienza Ahora</button>
        </div>
    </div>
    <div class="alianzas">
        <p>Juntos llegamos más lejos</p>
        <h1>Alianzas que potencian la educación</h1>
        <p>En ClassGo creemos en el poder de la colaboración para transformar el aprendizaje. Por eso, trabajamos junto a instituciones educativas, clubes y organizaciones comprometidas con la formación académica y el desarrollo personal.</p>
        <div class="aliazas">
            <div class="alianzas-card">
                <img src="" alt=""><!--img-->
                <p>Ingeniería Petrolera</p>
            </div>
        </div>
    </div>
</section>

@endsection
