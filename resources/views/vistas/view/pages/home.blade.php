@extends('vistas.view.layouts.app')

@section('title', 'Class Go!')

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
            <div class="tutors-card">
                <div class="video-tutor">
                    <img src="{{ asset('images/tutors/video1.gif') }}" alt="Tutor"> <!--Video cambiar-->
                </div>
                <div class="info">
                    <div class="info-header">
                        <img src="{{ asset('images/tugo-negativo.png') }}" alt="Tutor">
                        <div class="info-name">
                            <div class="name">
                                <h1>Norely Bonilla</h1>
                                <i class="fa-solid fa-circle-check"></i> <!--Check Verificación-->
                                <img src="" alt=""><!--Bandera pais-->
                            </div>
                            <div class="tutor">
                                <h1><spam>Tutor:</spam> Adm. de Empresas</h1>
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
                            <div class=tutorias-title>
                                <i class="fa-solid fa-book"></i>
                                <p class="price-title">10</p>
                            </div>
                            <p class="tutorias-details">Tutorías Realizadas</p>
                        </div>
                    </div>
                    <div class="info-details">
                        <div>
                            <i class="fa-solid fa-book-open"></i>
                            <p>Finanzas, Presupuesto, Estadística</p>
                        </div>
                        <div>
                            <i class="fa-solid fa-users"></i>
                            <p>10 estudiantes activos · 30 Clases</p>
                        </div>
                        <div>
                            <i class="fa-solid fa-language"></i>
                            <p>Ingles</p>
                        </div>
                    </div>
                    <div class="info-buttons">
                        <button class="button2">Ver Perfil</button>
                        <button class="button1">Reservar</button>
                    </div>
                </div>
            </div> <!--End card-->
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
</script>


@endsection

