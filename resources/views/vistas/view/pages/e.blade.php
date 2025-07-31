<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrusel de Tutores Mejorado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos para una apariencia más limpia y moderna */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .carousel-container {
            overflow: hidden;
            position: relative;
        }
        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel-card {
            flex: 0 0 100%; /* En móvil, 1 tarjeta */
        }
        @media (min-width: 768px) {
            .carousel-card {
                flex: 0 0 50%; /* En tablet, 2 tarjetas */
            }
        }
        @media (min-width: 1024px) {
            .carousel-card {
                flex: 0 0 33.3333%; /* En desktop, 3 tarjetas */
            }
        }
        .nav-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            color: #333;
        }
        .nav-button:hover {
            background-color: white;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }
        .nav-button.prev {
            left: -20px;
        }
        .nav-button.next {
            right: -20px;
        }
        .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <section class="w-full max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Título de la sección -->
        <div class="text-center mb-12">
            <p class="text-sm font-semibold text-cyan-600 uppercase tracking-wider">Tutores Destacados</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                Conoce a Nuestros Tutores
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-500">
                Descubre una variedad de temáticas académicas y prácticas para potenciar tu experiencia de aprendizaje.
            </p>
        </div>

        <!-- Contenedor principal del carrusel -->
        <div id="carousel-wrapper" class="relative">
            <div class="carousel-container">
                <div class="carousel-track">
                    <!-- Tarjeta de Tutor (Plantilla) -->
                    <!-- Se generarán dinámicamente con JavaScript -->
                </div>
            </div>

            <!-- Botones de Navegación -->
            <button id="prev-btn" class="nav-button prev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="next-btn" class="nav-button next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Datos de ejemplo para los tutores ---
            const tutors = [
                { name: 'Carlos Ríos', subject: 'Ciencias Exactas', reviews: 15, rating: 5, img: 'https://placehold.co/400x225/EBF4FF/333333?text=Tutor+1' },
                { name: 'Valeria Gómez', subject: 'Humanidades', reviews: 8, rating: 4, img: 'https://placehold.co/400x225/D6F6E6/333333?text=Tutor+2' },
                { name: 'Daniel Ortiz', subject: 'Programación', reviews: 25, rating: 5, img: 'https://placehold.co/400x225/FFF0E5/333333?text=Tutor+3' },
                { name: 'Ana Mendoza', subject: 'Idiomas', reviews: 12, rating: 4, img: 'https://placehold.co/400x225/F3E8FF/333333?text=Tutor+4' },
                { name: 'Jorge Luna', subject: 'Diseño Gráfico', reviews: 18, rating: 5, img: 'https://placehold.co/400x225/FFE8E8/333333?text=Tutor+5' },
                { name: 'Sofía Castro', subject: 'Música', reviews: 5, rating: 5, img: 'https://placehold.co/400x225/E5F9FF/333333?text=Tutor+6' },
                { name: 'Miguel Ángel', subject: 'Matemáticas Avanzadas', reviews: 30, rating: 5, img: 'https://placehold.co/400x225/FFFDE5/333333?text=Tutor+7' },
                { name: 'Lucía Fernández', subject: 'Biología', reviews: 10, rating: 4, img: 'https://placehold.co/400x225/E5FFF0/333333?text=Tutor+8' },
                { name: 'David Salas', subject: 'Física Cuántica', reviews: 22, rating: 5, img: 'https://placehold.co/400x225/F0F0F0/333333?text=Tutor+9' }
            ];

            const track = document.querySelector('.carousel-track');

            // --- Generar las tarjetas de los tutores ---
            tutors.forEach(tutor => {
                const cardHTML = `
                    <div class="carousel-card p-4">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden h-full flex flex-col transition-transform duration-300 hover:transform hover:-translate-y-1">
                            <div class="relative">
                                <img class="w-full h-48 object-cover" src="${tutor.img}" alt="Foto de ${tutor.name}">
                                <div class="absolute top-2 right-2 bg-white rounded-full p-2 cursor-pointer">
                                    <i class="fas fa-heart text-red-500"></i>
                                </div>
                            </div>
                            <div class="p-6 flex-grow flex flex-col">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                    ${tutor.name}
                                    <i class="fas fa-check-circle text-cyan-500 ml-2 text-lg"></i>
                                </h3>
                                <p class="text-gray-500 mt-1">Puedo enseñar: ${tutor.subject}</p>
                                <div class="flex justify-between items-center mt-4 text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="font-bold">${tutor.rating}</span>
                                        <span class="ml-1">(${tutor.reviews} reseñas)</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-briefcase text-cyan-500 mr-2"></i>
                                        <span class="font-bold">${tutor.reviews}</span>
                                        <span class="ml-1 text-sm">Tutorías</span>
                                    </div>
                                </div>
                                <div class="mt-6 pt-4 border-t border-gray-200 flex-grow"></div>
                                <div class="flex space-x-3 mt-auto">
                                    <button class="w-full bg-orange-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors">Ver Perfil</button>
                                    <button class="w-full bg-cyan-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-cyan-600 transition-colors">Reservar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                track.innerHTML += cardHTML;
            });

            // --- Lógica del Carrusel ---
            const prevButton = document.getElementById('prev-btn');
            const nextButton = document.getElementById('next-btn');
            const carouselWrapper = document.getElementById('carousel-wrapper');
            let currentIndex = 0;
            let slideInterval;

            function getVisibleSlides() {
                if (window.innerWidth >= 1024) return 3;
                if (window.innerWidth >= 768) return 2;
                return 1;
            }

            function updateCarousel() {
                const visibleSlides = getVisibleSlides();
                const totalSlides = tutors.length;
                const maxIndex = totalSlides - visibleSlides;

                if (currentIndex > maxIndex) {
                    currentIndex = maxIndex;
                }
                 if (currentIndex < 0) {
                    currentIndex = 0;
                }

                const offset = -currentIndex * (100 / visibleSlides);
                track.style.transform = `translateX(${offset}%)`;

                // Actualizar estado de los botones
                prevButton.classList.toggle('disabled', currentIndex === 0);
                nextButton.classList.toggle('disabled', currentIndex >= maxIndex);
            }

            function moveToNextSlide() {
                const visibleSlides = getVisibleSlides();
                const totalSlides = tutors.length;
                const maxIndex = totalSlides - visibleSlides;

                if (currentIndex >= maxIndex) {
                    currentIndex = 0; // Vuelve al inicio
                } else {
                    currentIndex++;
                }
                updateCarousel();
            }

            function startSlideShow() {
                stopSlideShow(); // Asegurarse de que no haya múltiples intervalos corriendo
                slideInterval = setInterval(moveToNextSlide, 5000); // Avanza cada 5 segundos
            }

            function stopSlideShow() {
                clearInterval(slideInterval);
            }

            nextButton.addEventListener('click', () => {
                const visibleSlides = getVisibleSlides();
                const totalSlides = tutors.length;
                if (currentIndex < totalSlides - visibleSlides) {
                    currentIndex++;
                    updateCarousel();
                }
            });

            prevButton.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            });

            // Pausar al pasar el ratón por encima
            carouselWrapper.addEventListener('mouseenter', stopSlideShow);
            carouselWrapper.addEventListener('mouseleave', startSlideShow);

            // Actualizar en el cambio de tamaño de la ventana
            window.addEventListener('resize', updateCarousel);

            // Iniciar todo
            updateCarousel();
            startSlideShow();
        });
    </script>
</body>
</html>
