@extends('vistas.view.layouts.app')

@section('title', 'ClassGo - Aprende y Progresa')

@section('content')

<!-- INICIO: Inclusión de CSS responsivos para tablet y móvil -->
<link rel="stylesheet" href="{{ asset('css/estilos/home-tablet.css') }}">
<link rel="stylesheet" href="{{ asset('css/estilos/home-mobile.css') }}">
<!-- FIN: Inclusión de CSS responsivos para tablet y móvil -->

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
            <img src="{{ asset('storage/optionbuilder/uploads/740102-17-2025_0859pmTugo-saludando.gif') }}" alt="Mascota ClassGo">
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
    
        <div class="tutors" id="tutorsContainer">
            @foreach($profiles as $profile)
                @php
                    $data = $subjectsByUser[$profile->user_id] ?? ['materias' => [], 'grupos' => []];
                @endphp
                <div class="tutor-card">
                    <div class="tutor-card-img">
                        <video 
                        controls 
                        muted 
                        playsinline 
                        preload="none" 
                        poster="{{ $profile->image ? asset('storage/' . $profile->image) : asset('storage/' . $profile->image) }}" src="{{ $profile->intro_video ? asset('storage/' . $profile->intro_video) : asset('storage/' . $profile->image) }}"></video>
                    </div>
                    <div class="tutor-card-content">
                        <div class="tutor-card-header">
                            <div class="tutor-card-header-left">
                                <h3>{{ $profile->first_name }} {{ $profile->last_name }}</h3>
                                <span class="tutor-verified">✔️</span>
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
                                <span>{{ $profile->avg_rating}}</span>
                                <span class="rating-count">( {{ $profile->total_reviews}} reseñas)</span>
                            </div>
                            <div class="tutor-card-price">
                                <p class="price"><i class="fa-solid fa-book icon"></i>10</p>
                                <p class="price-desc">Tutorías realizadas</p>
                            </div>
                        </div>
                        {{-- <div class="tutor-card-tags" title="{{ implode(', ', $data['materias']) }}">
                            @foreach($data['materias'] as $materia)
                                <span class="tutor-card-tag">{{ $materia }}</span>
                            @endforeach
                            <span class="tutor-card-tag tutor-card-mas" style="display:none;">+más</span>
                        </div> --}}
                        <div class="tutor-card-actions">
                            <button class="btn-profile">Ver Perfil</button>
                            <button class="btn-reserve">Reservar</button>
                        </div>
                    </div>
                </div>
            @endforeach
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
            <p>Reserva fácilmente un horario conveniente para tu Sesión</p>
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
            <img src="{{ asset('images/tutorias.png') }}" alt="Mascota">
        </div>
    </div>
    <div class="alianzas">
        <h1 class="over-text"><div class="linea"></div>Juntos llegamos más lejos<div class="linea"></div></h1>
        <h1>Alianzas que potencian la educación</h1>
        <p>En ClassGo creemos en el poder de la colaboración para transformar el aprendizaje. Por eso, trabajamos junto a instituciones educativas, clubes y organizaciones comprometidas con la formación académica y el desarrollo personal.</p>
        <div class="steps-alianzas">
            <!-- Alianzas Cards DESDE BD -->
            @foreach($alianzas as $alianza)
                <div class="alianzas-card">
                    <img src="{{ $alianza->imagen ? asset('storage/' . $alianza->imagen) : asset('images/tutors/default.png') }}" alt="Imagen de {{ $alianza->imagen }}">
                    <p>{{ $alianza -> titulo }}</p>
                </div>
            @endforeach
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

document.addEventListener('DOMContentLoaded', function() {
    // Video lazy load: solo carga el src si el usuario da play
    document.querySelectorAll('.tutor-card video').forEach(video => {
        video.addEventListener('play', function() {
            if (!video.src) {
                video.src = video.getAttribute('data-src');
            }
        }, { once: true });
    });
});

</script>

<style>
.tutors-carousel-container {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  width: 100%;
  margin: 0 auto;
  padding: 0 2rem;
}
.carousel-btn {
  background: var(--secundary-color);
  color: #fff;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  font-size: 1.5rem;
  cursor: pointer;
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  transition: background 0.2s;
}
.carousel-btn-left {
  left: -60px;
}
.carousel-btn-right {
  right: -60px;
}
.tutors-carousel {
  display: flex;
  flex-direction: row;
  width: 100%;
  overflow: hidden;
  gap: 0;
}
@media (max-width: 1200px) {
  .carousel-btn-left {
    left: -30px;
  }
  .carousel-btn-right {
    right: -30px;
  }
}
@media (max-width: 1024px) {
  .tutors-carousel-container {
    padding: 0 1rem;
  }
  .carousel-btn-left {
    left: -18px;
  }
  .carousel-btn-right {
    right: -18px;
  }
}
@media (max-width: 768px) {
  .tutors-carousel-container {
    padding: 0 0.5rem;
  }
  .carousel-btn-left, .carousel-btn-right {
    top: 90%;
    left: 10px;
    right: 10px;
    transform: none;
    position: static;
    margin: 0 10px;
  }
  .tutors-carousel {
    padding: 1rem 0;
  }
}
</style>

@endsection

