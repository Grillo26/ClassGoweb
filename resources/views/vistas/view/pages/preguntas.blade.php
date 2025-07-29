@extends('vistas.view.layouts.app')

@section('title', 'Class Go! | Preguntas')

@section('body-class', 'preguntas')

@section('content')
    <!--PREGUNTAS-->
    <section class="preguntas">
        <div class="preguntas-container">
            <div class="preguntas-header">
                <div class="preguntas-header-content">
                    <div class="preguntas-header-text align-left">
                        <nav class="breadcrumb">
                            <a href="{{ route('home') }}" class="breadcrumb-link">Inicio</a> / <span class="breadcrumb-current">Preguntas</span>
                        </nav>
                        <h1>Encuentra tu respuesta</h1>
                        <p>Empoderando a los estudiantes en todo el mundo</p>
                    </div>
                    <div class="preguntas-tabs tabs-centered">
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
                <div class="preguntas-section">
                    <div class="preguntas-content">
                        <div class="preguntas-faq">
                            <div class="faq-item active">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Cómo encontrar un tutor?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-up"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Utilice la barra de búsqueda y los filtros de la página "Buscar un tutor por materia, disponibilidad, calificación y más.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Cómo reservo una sesión?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Una vez que encuentres un tutor, consulta su perfil y selecciona un horario disponible que te convenga. Haz clic "Reservar ahora" y sigue las instrucciones para confirmar tu sesión.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Qué pasa si necesito cancelar o reprogramar una sesión?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Puedes cancelar o reprogramar una sesión hasta 24 horas antes de la hora programada sin penalización. Las cancelaciones dentro de las 24 horas pueden generar un cargo.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Cómo pago las sesiones?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Los pagos se realizan a través de nuestra pasarela de pago segura utilizando tarjetas de crédito/débito u otros métodos de pago disponibles.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Qué debo hacer si mi tutor no se presenta?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Si tu tutor no se presenta a una sesión programada, comuníquese con nuestro equipo de soporte de inmediato para obtener ayuda y agender una reprogramación o agendar un reembolso.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Cómo puedo dejar comentarios para mi tutor?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Después de la sesión, recibirás un correo electrónico en el que se te solicitará que califiques a tu tutor y le des tu opinión. También puedes hacerlo desde el panel de tu cuenta.</p>
                                </div>
                            </div>
                        </div>
                        <div class="preguntas-image">
                            <img src="{{ asset('images/home/TugoUniversitario.webp') }}" alt="Estudiante con preguntas">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tutores Tab Content -->
            <div class="tab-content" id="tutores-content">
                <div class="preguntas-section">
                    <div class="preguntas-content">
                        <div class="preguntas-faq">
                            <div class="faq-item active">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Cómo puedo ser tutor?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-up"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Haga clic en el enlace "Conviértete en tutor" y siga las instrucciones para registrarse, crear su perfil y enviar la documentación necesaria para su aprobación.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Qué cualificaciones necesito para ser tutor?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Los tutores deben tener la titulación académica pertinente y experiencia docente. Los requisitos específicos pueden variar según la materia.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Cómo configuro mi disponibilidad?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Inicia sesión en tu cuenta, ve a la sección "Disponibilidad" y actualiza tu calendario con tus franjas horarias disponibles.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <h3>¿Qué debo hacer si un estudiante cancela una sesión?</h3>
                                    <div class="faq-toggle">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="faq-answer">
                                    <p>Si un estudiante cancela una sesion dentro de 24 horas posteriores a las horas programada, es posible que tenga derecho a una tarifa de cancelacion. Consulta la politica de cancelacion de la plataforma para más detalles.</p>
                                </div>
                            </div>
                        </div>
                        <div class="preguntas-image">
                            <img src="{{ asset('images/home/Tugotecnológico.webp') }}" alt="Tutor con preguntas">
                        </div>
                    </div>
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

        // Funcionalidad del acordeón FAQ
        function toggleFaq(element) {
            const faqItem = element.parentElement;
            const answer = faqItem.querySelector('.faq-answer');
            const toggle = element.querySelector('.faq-toggle i');
            
            // Cerrar todos los otros items
            document.querySelectorAll('.faq-item').forEach(item => {
                if (item !== faqItem) {
                    item.classList.remove('active');
                    item.querySelector('.faq-answer').style.maxHeight = '0px';
                    item.querySelector('.faq-toggle i').className = 'fa-solid fa-chevron-down';
                }
            });
            
            // Toggle del item actual
            if (faqItem.classList.contains('active')) {
                faqItem.classList.remove('active');
                answer.style.maxHeight = '0px';
                toggle.className = 'fa-solid fa-chevron-down';
            } else {
                faqItem.classList.add('active');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                toggle.className = 'fa-solid fa-chevron-up';
            }
        }
    </script>
@endsection
