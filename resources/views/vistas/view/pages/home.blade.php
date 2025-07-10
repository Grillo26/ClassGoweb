@extends('vistas.view.layouts.app')

@section('title', 'ClassGo - Aprende y Progresa')

@section('content')

<!-- HERO -->
<section class="hero">
    <div class="hero-container">

        <!-- Columna izquierda: texto -->
        <div class="hero-text">
            <h1 class="hero-title-arriba">Aprende y Progresa con</h1>
            <h1 class="hero-title-abajo">Tutorías en Línea</h1>
            <p class="hero-subtext">
                Alcanza tus metas con tutorías personalizadas de los mejores expertos.<br>
                Conéctate con tutores dedicados para asegurar tu éxito.
            </p>

            <!-- Buscador -->
            <div class="search-box">
                <input type="text" placeholder="Buscar Tutor...">
                <button>
                    <i class="fa-solid fa-magnifying-glass icon-search"></i>
                </button>
            </div>

            <!--Botones-->
            <div class="hero-buttons">
                <button><i class="fa-solid fa-bolt-lightning"></i>Tutor al Instante</button>
                <button><i class="fa-solid fa-calendar"></i>Agendar Tutoría</button>
                <button><i class="fa-solid fa-compass"></i>Explorar Tutores</button>
            </div>
        </div>

        <!-- Columna derecha: imagen -->
        <div class="hero-image">
            <img src="{{ asset('images/Tugo_With_Glasses.png') }}" alt="Mascota ClassGo">
        </div>

       
    </div>
</section>


<!-- CONTADORES INFO -->
<section class="info-container">
    <!-- CONTADORES -->
    <div class="counters">
        <div class="counter-box">
            <div class="counter-number" data-target="500">+0</div>
            <h1>Usuarios registrados</h1>
        </div>
        <div class="box-sky">
        </div>
        <div class="counter-box">
            <div class="counter-number"data-target="230">+0</div>
            <h1>Tutores disponibles</h1>
        </div>
        <div class="box-sky"></div>
        <div class="counter-box">
            <div class="counter-number" data-target="230">0</div>
            <h1>Estudiantes registrados</h1>
        </div>
        <div class="box-sky"></div>
        <div class="counter-box">
            <div class="counter-numbe"><i class="fa fa-star"></i>4.5</div>
            <h1>En la App Store</h1>
        </div>
    </div>

    <!--TUTORES DESTACADOS-->
    <div class="tutors-container">
        <h1 class="over-text"><div class="linea"></div>Tutores Destacados<div class="linea"></div></h1>
        <h1>Conoce a Nuestros Tutores Cuidadosamente Seleccionados</h1>
        <p>Descubre una variedad de temáticas académicas y prácticas para potenciar tu experiencia de aprendizaje</p> 
    
        <div class="tutors">
            @foreach($profiles as $profile)
                @php
                    $data = $subjectsByUser[$profile->user_id] ?? ['materias' => [], 'grupos' => []];
                @endphp
                <!-- Card -->
                <div class="tutor-card">
                    <div class="tutor-card-img" >
                        <video controls muted playsinline loop src="{{ $profile->intro_video ? asset('storage/' . $profile->intro_video) : asset('images/tutors/default.png') }}"></video>
                    </div>
                    <div class="tutor-card-content">
                        <div class="tutor-card-header">
                            <div class="tutor-card-header-left">
                                <h3>{{ $profile->first_name }} {{ $profile->last_name }}</h3>
                                <span class="tutor-verified">✔️</span>
                                {{-- <span class="tutor-flag">{{ $profile->native_language }}</span> --}}
                            </div>
                            <button title="Favorito">❤️</button>
                        </div>
                        @php
                            $maxGrupos = 4;
                            $grupos = $data['grupos'];
                            $countGrupos = count($grupos);
                        @endphp
                        <p class="tutor-card-sub mas" title="{{ implode(', ', $grupos) }}">
                            {{ implode(', ', $grupos) }}<span class="tutor-card-mas" style="display:none;"> +más</span>
                        </p>
                        <div class="tutor-card-rating-row">
                            <div class="tutor-card-rating">
                                <span class="star">⭐</span>
                                <span>4.5</span>
                                <span class="rating-count">(7 reseñas)</span>
                            </div>
                            <div class="tutor-card-price">
                                <p class="price"><i class="fa-solid fa-book icon"></i>10</p>
                                <p class="price-desc">Tutorías realizadas</p>
                            </div>
                        </div>
                        <div class="tutor-card-tags" title="{{ implode(', ', $data['materias']) }}">
                            @foreach($data['materias'] as $materia)
                                <span class="tutor-card-tag">{{ $materia }}</span>
                            @endforeach
                            <span class="tutor-card-tag tutor-card-mas" style="display:none;">+más</span>
                        </div>
                        <script>
                        // Mostrar "+más" si tutor-card-sub o tutor-card-tags se desbordan
                        document.addEventListener('DOMContentLoaded', function() {
                            // Para grupos
                            document.querySelectorAll('.tutor-card-sub.mas').forEach(function(el) {
                                if (el.scrollHeight > el.clientHeight + 1) {
                                    el.querySelector('.tutor-card-mas').style.display = 'inline';
                                }
                            });
                            // Para tags
                        document.querySelectorAll('.tutor-card-tags').forEach(function(tags) {
                                if (tags.scrollHeight > tags.clientHeight + 1) {
                                    tags.querySelector('.tutor-card-mas').style.display = 'inline';
                                }
                            });
                        });
                        </script>
                        <div class="tutor-card-actions">
                            <button class="btn-profile">Ver Perfil</button>
                            <button class="btn-reserve">Reservar</button>
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- @foreach($profiles as $profile)
                @php
                    $data = $subjectsByUser[$profile->user_id] ?? ['materias' => [], 'grupos' => []];
                @endphp

                <div class="tutors-card">
                    <video controls muted playsinline loop src="{{ $profile->intro_video ? asset('storage/' . $profile->intro_video) : asset('images/tutors/default.png') }}"></video>

                    <div class="info">
                        <div class="info-header">
                            <img src="{{ $profile->image ? asset('storage/' . $profile->image) : asset('images/tutors/default.png') }}" alt="Imagen de {{ $profile->first_name }}">

                            <div class="info-name">
                                <div class="name">
                                    <h1>{{ $profile->first_name }} {{ $profile->last_name }}</h1>
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="tutor">
                                    <h1><span>Tutor:</span> {{ implode(', ', $data['grupos']) }}</h1>
                                </div>
                            </div>

                            <div class="icono-heart">
                                <i class="fa-solid fa-heart"></i>
                            </div>
                        </div>

                        <div class="info-resena">
                            <div class="info-puntuacion">
                                <div class="puntuacion-title">
                                    <i class="fa fa-star"></i>
                                    <p>4.5</p>
                                </div>
                                <p>7 reseñas</p>
                            </div>
                            <div class="info-tutorias">
                                <div class="tutorias-title">
                                    <i class="fa-solid fa-book"></i>
                                    <p class="price-title">10</p>
                                </div>
                                <p class="tutorias-details">Tutorías Realizadas</p>
                            </div>
                        </div>

                        <div class="info-details">
                            <div>
                                <i class="fa-solid fa-book-open"></i>
                                <p>{{ implode(', ', $data['materias']) }}</p>
                            </div>
                            <div>
                                <i class="fa-solid fa-users"></i>
                                <p>10 estudiantes activos · 30 Clases</p>
                            </div>
                            <div>
                                <i class="fa-solid fa-language"></i>
                                <p>{{ $profile->native_language }}</p>
                            </div>
                        </div>

                        <div class="info-buttons">
                            <button class="button2">Ver Perfil</button>
                            <button class="button1">Reservar</button>
                        </div>
                    </div>
                </div>
            @endforeach --}}
        </div>
    </div>
</section>

<!--GUIA PASO A PASO-->
<section class="potencial-container">
    <h1 class="over-text"><div class="linea"></div>Una guía paso a paso<div class="linea"></div></h1>
    <h1>Desbloquea Tu Potencial Con Pasos Sencillos</h1>
    <p>Descubra cómo nuestra plataforma simplifica la búsqueda y reserva de los mejores tutores para mejorar sus habilidades y alcanzar sus objetivos de aprendizaje.</p>
    <div class="steps">
        <!--CARD-->
        <div class="steps-card">
            <div class="numero-paso">Paso 1</div>
            <img src="{{ asset('images/paso1.png') }}" alt="Pasos">
            <h1>Inscríbete</h1>
            <p>Crea tu cuenta rápidamente para comenzar a utilizar nuestra plataforma</p>
            <button>Empezar</button>
        </div> <!--FIN CARD-->
        <!--CARD-->
        <div class="steps-card">
            <div class="numero-paso">Paso 2</div>
            <img src="{{ asset('images/paso2.png') }}" alt="Pasos">
            <h1>Encuentra un tutor</h1>
            <p>Busca y selecciona entre tutores calificados según tus necesidades</p>
            <button>Buscar Ahora</button>
        </div> <!--FIN CARD-->
        <!--CARD-->
        <div class="steps-card">
            <div class="numero-paso">Paso 3</div>
            <img src="{{ asset('images/paso3.png') }}" alt="Pasos">
            <h1>Programar una Sesión</h1>
            <p>Reserva fácilmente un horario conveniente para tu Sesións</p>
            <button>Empecemos</button>
        </div> <!--FIN CARD-->

        <!--COMIENZA TU JORNADA CARD-->
        <div class="go">
            <div class="numero-paso">
                <i class="fa-solid fa-person-running"></i>
            </div>
            <h1>Comienza tu jornada</h1>
            <p>Comienza tu viaje educativo con nosotros. ¡Encuentra un tutor y reserva tu primera sesión hoy mismo!</p>
            <button class="button-go">Empieza ahora</button>
        </div>
    </div>
</section>

<!--HERO TUTORIAS Y ALIANZAS-->
<section class="tutorias-container">
    <div class="tutorias">
        <!-- Texto -->
        <div class="tutores-text">
            <p class="tutores-text-encima">¿Buscas tutorías personalizadas?</p>
            <h1>En Classgo, te conectamos con los mejores tutores</h1>
            <p>Accede a sesiones cortas y prácticas, diseñadas por tutores expertos para ser pequeños salvavidas en el aprendizaje</p>
            <ul class="tutores-list">
                <li>Acceso 25/7</li>
                <li>Tutores Expertos</li>
                <li>Tarifas asequibles</li>
            </ul>
            <button class="button-comienza">Comienza Ahora</button>
        </div>
        <!-- Imagen -->
        <div class="tutores-img">
            {{-- <img src="{{ asset('images/tutorias.png') }}" alt="Mascota"> --}}
        </div>
    </div>
    <div class="alianzas">
        <h1 class="over-text"><div class="linea"></div>Juntos llegamos más lejos<div class="linea"></div></h1>
        <h1>Alianzas que potencian la educación</h1>
        <p>En ClassGo creemos en el poder de la colaboración para transformar el aprendizaje. Por eso, trabajamos junto a instituciones educativas, clubes y organizaciones comprometidas con la formación académica y el desarrollo personal.</p>
        <div class="steps-alianzas">
            <!-- Alianzas Cards DESDE BD -->
            <div class="alianzas-card">
                <img src="{{ asset('images/alianzas.png')}}"><!--img-->
                <p>Ingeniería Petrolera</p>
            </div>
            <div class="alianzas-card">
                <img src="{{ asset('images/alianzas.png')}}"><!--img-->
                <p>Ingeniería Petrolera</p>
            </div>
            <div class="alianzas-card">
                <img src="{{ asset('images/alianzas.png')}}"><!--img-->
                <p>Ingeniería Petrolera</p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll('.counter-number');

    const animateCounter = (el) => {
        const target = +el.getAttribute('data-target');
        const isDecimal = el.getAttribute('data-decimal') === 'true';
        let count = 0;
        const step = isDecimal ? 0.1 : Math.ceil(target / 100);

        const updateCounter = () => {
            count += step;
            if (count < target) {
                el.innerHTML = isDecimal ? `<i class="fa fa-star"></i> ${count.toFixed(1)}` : `+${Math.floor(count)}`;
                requestAnimationFrame(updateCounter);
            } else {
                el.innerHTML = isDecimal ? `<i class="fa fa-star"></i> ${target.toFixed(1)}` : `+${target}`;
            }
        };

        updateCounter();
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.6 });

    counters.forEach(counter => observer.observe(counter));
});

function scrollTutors(direction) {
    const container = document.getElementById('tutors-carousel');
    const cardWidth = container.querySelector('.tutors-card')?.offsetWidth || 300;
    container.scrollBy({ left: direction * (cardWidth + 18), behavior: 'smooth' });
}
</script>


@endsection

