@extends('vistas.view.layouts.app')

@section('title', 'Class Go! | Cómo trabajamos')

@section('body-class', 'trabajamos')

@section('content')
    <!--TRABAJAMOS-->
    <section class="trabajamos">
        <div class="trabajamos-container">
           
                <div class="trabajamos-header">
                    <div class="trabajamos-header-content">
                        <div class="trabajamos-header-text align-left">
                            <nav class="breadcrumb">
                                <a href="{{ route('home') }}" class="breadcrumb-link">Inicio</a> / <span class="breadcrumb-current">Cómo trabajamos</span>
                            </nav>
                            <h1>Únase a nuestra comunidad hoy</h1>
                            <p>
                                Únase a nuestra comunidad para compartir su experiencia como tutor o mejorar sus habilidades como estudiante. 
                                Conéctese, aprenda y crezca con nosotros hoy.
                            </p>
                        </div>
                        <div class="trabajamos-tabs tabs-centered">
                                <div class="tab-buttons">
                                    <button class="tab-button active" data-tab="estudiantes">
                                        <i class="fa-solid fa-book"></i>
                                        Para estudiantes
                                    </button>
                                    <button class="tab-button" data-tab="tutores">
                                        <i class="fa-solid fa-briefcase"></i>
                                        Para tutores
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>
                
           

            <!-- Estudiantes Tab Content -->
            <div class="tab-content active" id="estudiantes-content">
                <!-- Sección 1: Complete sus datos -->
                <div class="trabajamos-section-white">
                    <div class="trabajamos-section-content">
                        <div class="trabajamos-text">
                            <div class="trabajamos-icon">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                            <h2>Complete sus datos y establezca sus preferencias de aprendizaje</h2>
                            <p>
                                Proporcione sus datos personales y establezca sus preferencias de aprendizaje para crear un perfil adaptado a sus necesidades educativas. 
                                Esto le ayudará a encontrar los tutores más adecuados y optimizar su experiencia de aprendizaje.
                            </p>
                        </div>
                        <div class="trabajamos-image">
                            <img src="{{ asset('images/Tugo tecnológico2.png') }}" alt="Estudiante completando datos">
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Utilice filtros -->
                <div class="trabajamos-section-white">
                    <div class="trabajamos-section-content reverse">
                        
                        <div class="trabajamos-text">
                            <div class="trabajamos-icon">
                                <i class="fa-solid fa-search"></i>
                            </div>
                            <h2>Utilice filtros para refinar su búsqueda y ver perfiles de tutores detallados</h2>
                            <p>
                                Utilice filtros para limitar su búsqueda de tutores según la materia, el nivel, el precio, la ubicación y la disponibilidad. 
                                Esto le permite ver perfiles de tutores detallados que mejor se adaptan a sus necesidades de aprendizaje.
                            </p>
                        </div>
                        <div class="trabajamos-image">
                            <img src="{{ asset('images/TuGo con laptop.png') }}" alt="Estudiante usando filtros">
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Elija un horario -->
                <div class="trabajamos-section-primary">
                    <div class="trabajamos-section-content">
                        <div class="trabajamos-text">
                            <div class="trabajamos-icon">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <h3>Asiste a la lección</h3>
                            <h2>Elija un horario conveniente y reserve su lección al instante</h2>
                            <h4>Pasos para reservar una sesión de tutoría</h4>
                            <ol>
                                <li>Select an Available Time Slot</li>
                                <li>Click on the Desired Slot</li>  
                                <li>Choose Session Type</li>
                                <li>Confirm Booking Details</li>
                                <li>Proceed to Payment</li>
                                <li>Receive Confirmation</li>
                            </ol>
                        </div>
                        <div class="trabajamos-image">
                            <img src="{{ asset('images/Tugo profesor.png') }}" alt="Estudiante reservando">
                        </div>
                    </div>
                </div>

                <!-- Sección 4: Inicie sesión -->
                <div class="trabajamos-section-primary">
                    <div class="trabajamos-section-content reverse">
                        
                        <div class="trabajamos-text">
                            <div class="trabajamos-icon">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <h3>Asiste a la lección</h3>
                            <h2>Inicie sesión a la hora programada y comience a aprender</h2>
                            <p>
                                Inicie sesión a la hora programada y únase a la sesión para comenzar a aprender. 
                                Conéctese con su tutor a través de Zoom para disfrutar de una lección atractiva e interactiva.
                            </p>
                        </div>
                        <div class="trabajamos-image">
                            <img src="{{ asset('images/Tugo_With_Phone.png') }}" alt="Estudiante en clase">
                        </div>
                    </div>
                </div>

                <!-- Sección 5: Complete formulario -->
                <div class="trabajamos-section-white">
                    <div class="trabajamos-section-content">
                        <div class="trabajamos-text">
                            <div class="trabajamos-icon">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <h3>Asiste a la lección</h3>
                            <h2>Complete un formulario de comentarios rápido después de su lección</h2>
                            <p>
                                Después de su lección, complete un formulario de comentarios rápido para compartir sus pensamientos y calificar su experiencia. 
                                Sus comentarios nos ayudan a mejorar y garantizar el mejor entorno de aprendizaje para todos.
                            </p>
                        </div>
                        <div class="trabajamos-image">
                            <img src="{{ asset('images/TuGo con Megáfono.png') }}" alt="Estudiante dando feedback">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tutores Tab Content -->
            <div class="tab-content" id="tutores-content">
                <!-- Contenido para tutores (similar estructura) -->
                <div class="trabajamos-section-white">
                    <div class="trabajamos-section-content">
                        <div class="trabajamos-text">
                            <div class="trabajamos-icon">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                            <h2>Crea tu perfil y enumera tus calificaciones.</h2>
                            <p>
                                Cree su perfil para mostrar sus calificaciones, habilidades y experiencia. Resalte su experiencia, educación y las materias que enseña para atraer estudiantes potenciales y generar credibilidad en plataforma.
                            </p>
                        </div>
                        <div class="trabajamos-image">
                            <img src="{{ asset('images/tugoprofesor2.png') }}" alt="Tutor completando perfil">
                        </div>
                    </div>
                </div>
                <!-- Más secciones para tutores... -->

                <div class="trabajamos-section-white">
                    <div class="trabajamos-section-content">
                        <div class="trabajamos-image">
                            <img src="{{ asset('images/Tugo_With_Glasses2.png') }}" alt="Tutor completando perfil">
                        </div>
                        <div class="trabajamos-text">
                            <div class="trabajamos-icon">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                            <h2>Gestiona su horario para mostrar cuándo está disponible para enseñar</h2>
                            <p>
                                Administre fácilmente su disponibilidad actualizando su horario con los horarios en los que está abierto para enseñar. Esto ayuda a los estudiantes a saber cuándo pueden reservar sesiones con usted y mantiene organizado su calendario de enseñanza,
                            </p>
                        </div>
                        
                    </div>
                </div>

                <!-- Sección 3 Revisar -->
                <div class="trabajamos-section-primary">
                    <div class="trabajamos-section-content">
                        <div class="trabajamos-text">
                            <div class="trabajamos-icon">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <h3>Asiste a la lección</h3>
                            <h2>Revisar las solicitudes de los estudiantes y aceptar reservas</h2>
                            <p>
                                Revise las solicitudes entrantes de los estudiantes y administre sus reservas aceptando lecciones que se ajusten a su disponibilidad. Confirme las reservas para conectarse con los estudiantes y comenzar a enseñar según las sessiones programadas.
                            </p>
                        </div>
                        <div class="trabajamos-image">
                            <img src="{{ asset('images/Tugo profesor.png') }}" alt="Estudiante reservando">
                        </div>
                    </div>
                </div>

                <!-- Sección 4: Inicie sesión -->
                <div class="trabajamos-section-primary">
                    <div class="trabajamos-section-content reverse">
                        
                        <div class="trabajamos-text">
                            <div class="trabajamos-icon">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                            <h3>Dirija su clase con Meet</h3>
                            <h2>Dirija su clase con Meet</h2>
                            <p>
                                Conéctate a la hora programada y comienza a impartir tu sesión. Utiliza la herramienta de videoconferencia integrada en la plataforma para conectar con tus alumnos y ofrecer una experiencia de aprendizaje atractiva.
                            </p>
                        </div>
                        <div class="trabajamos-image">
                            <img src="{{ asset('images/Tugo_With_Phone.png') }}" alt="Estudiante en clase">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Section -->
            <div class="trabajamos-footer">
                <div class="trabajamos-footer-content">
                    <h3>Garantizamos un proceso de calidad</h3>
                    <h2>Únase a nuestra comunidad hoy</h2>
                    <p>
                        Únase a nuestra comunidad para compartir su experiencia como tutor o mejorar sus habilidades como estudiante. 
                        Conéctese, aprenda y crezca con nosotros hoy.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Funcionalidad de tabs
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Remover clase active de todos los botones y contenidos
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    // Agregar clase active al botón clickeado y su contenido
                    this.classList.add('active');
                    document.getElementById(targetTab + '-content').classList.add('active');
                });
            });
        });
    </script>
@endsection