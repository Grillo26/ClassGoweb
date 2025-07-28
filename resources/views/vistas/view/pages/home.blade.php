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
            <p class="hero-subtext mobile">
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
                <button>
                    <i class="fa-solid fa-bolt-lightning"></i>
                    Tutor al Instante
                </button>
                <a href=" {{ route('buscar.tutor')}}"><button><i class="fa-solid fa-calendar"></i>Agendar Tutoría</button></a>
                <button><i class="fa-solid fa-compass"></i>Explorar Tutores</button>
            </div>
        </div>

        <!-- Columna derecha: imagen -->
       
        <img src="{{ asset('storage/optionbuilder/uploads/740102-17-2025_0859pmTugo-saludando.gif') }}" alt="Mascota ClassGo">


       
    </div>
</section>


<!-- CONTADORES INFO -->
<section class="info-container">
    <!-- CONTADORES -->
    @include('components.counters', ['color' => 'text-dark'])

    <!--TUTORES DESTACADOS-->
    <div class="tutors-container">
        <h1 class="over-text"><div class="linea"></div>Tutores Destacados<div class="linea"></div></h1>
        <h1>Conoce a Nuestros Tutores Cuidadosamente Seleccionados</h1>
        <p>Descubre una variedad de temáticas académicas y prácticas para potenciar tu experiencia de aprendizaje</p> 
    
        <!--Componente tutor destacado-->
        <div class="tutors-carousel-viewport">
            <div class="tutors" id="tutorsContainer">
                @include('components.tutors', [
                    'profiles' => $profiles,
                    'subjectsByUser' => $subjectsByUser,
                ])
            </div>
        </div>
        <div class="carousel-controls">
            <button class="carousel-nav prev" onclick="prevSlide()">‹</button>
            <button class="carousel-nav next" onclick="nextSlide()">›</button>
        </div>
        <div class="carousel-indicators" id="indicators"></div>

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
    @include('components.alianzas', ['alianzas' => $alianzas])
</section>

<script>
let currentSlide = 0;
        const cardsPerView = 3;
        const tutorsContainer = document.getElementById('tutorsContainer');
        const cards = document.querySelectorAll('.tutor-card');
        const totalCards = cards.length;
        const totalSlides = Math.ceil(totalCards / cardsPerView);

        // Crear indicadores
        function createIndicators() {
            const indicatorsContainer = document.getElementById('indicators');
            indicatorsContainer.innerHTML = '';
            
            for (let i = 0; i < totalSlides; i++) {
                const indicator = document.createElement('div');
                indicator.className = 'indicator';
                if (i === 0) indicator.classList.add('active');
                indicator.onclick = () => goToSlide(i);
                indicatorsContainer.appendChild(indicator);
            }
        }

        // Ir a slide específico
        function goToSlide(slideIndex) {
            if (slideIndex < 0 || slideIndex >= totalSlides) return;
            
            currentSlide = slideIndex;
            const translateX = -currentSlide * 100;
            tutorsContainer.style.transform = `translateX(${translateX}%)`;
            
            updateIndicators();
            updateButtons();
        }

        // Siguiente slide
        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1);
            }
        }

        // Slide anterior
        function prevSlide() {
            if (currentSlide > 0) {
                goToSlide(currentSlide - 1);
            }
        }

        // Actualizar indicadores
        function updateIndicators() {
            const indicators = document.querySelectorAll('.indicator');
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentSlide);
            });
        }

        // Actualizar botones
        function updateButtons() {
            const prevBtn = document.querySelector('.carousel-nav.prev');
            const nextBtn = document.querySelector('.carousel-nav.next');
            
            prevBtn.disabled = currentSlide === 0;
            nextBtn.disabled = currentSlide === totalSlides - 1;
        }

        // Inicializar carrusel
        function initCarousel() {
            createIndicators();
            updateButtons();
            
            // Ajustar ancho del contenedor
            tutorsContainer.style.width = `${totalSlides * 100}%`;
        }

        // Navegación con teclado
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') prevSlide();
            if (e.key === 'ArrowRight') nextSlide();
        });

        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', initCarousel);

        // Responsive: ajustar cards por vista según el tamaño de pantalla
        function updateCardsPerView() {
            const width = window.innerWidth;
            let newCardsPerView;
            
            if (width <= 480) {
                newCardsPerView = 1;
            } else if (width <= 768) {
                newCardsPerView = 2;
            } else {
                newCardsPerView = 3;
            }
            
            if (newCardsPerView !== cardsPerView) {
                // Recalcular slides si es necesario
                location.reload(); // Simplificado para el ejemplo
            }
        }

        window.addEventListener('resize', updateCardsPerView);



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

@endsection

