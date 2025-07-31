<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrusel de Tutores Rediseñado</title>
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
            top: 40%; /* Ajustado para alinear con las imágenes */
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
            z-index: 10;
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
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
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
                    <!-- Las tarjetas de tutor se generan dinámicamente aquí -->
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
            // --- Datos de ejemplo para los tutores (con descripción) ---
            const tutors = [
                { name: 'Carlos Ríos', subject: 'Ciencias Exactas', description: 'Experto en cálculo, álgebra y física. Apasionado por hacer las matemáticas comprensibles.', reviews: 15, rating: 5, img: 'https://placehold.co/400x250/0D1B2A/FFFFFF?text=Carlos' },
                { name: 'Valeria Gómez', subject: 'Humanidades', description: 'Especialista en historia del arte y literatura. Disfruto compartiendo la riqueza cultural.', reviews: 8, rating: 4, img: 'https://placehold.co/400x250/FF6B6B/FFFFFF?text=Valeria' },
                { name: 'Daniel Ortiz', subject: 'Programación', description: 'Desarrollador Full-Stack con experiencia en Python y JavaScript. Ayudo a construir proyectos desde cero.', reviews: 25, rating: 5, img: 'https://placehold.co/400x250/4ECDC4/FFFFFF?text=Daniel' },
                { name: 'Ana Mendoza', subject: 'Idiomas', description: 'Profesora de inglés y francés. Clases dinámicas y enfocadas en la conversación fluida.', reviews: 12, rating: 4, img: 'https://placehold.co/400x250/F9C80E/FFFFFF?text=Ana' },
                { name: 'Jorge Luna', subject: 'Diseño Gráfico', description: 'Creativo y detallista, enseño a dominar herramientas como Photoshop e Illustrator para crear diseños impactantes.', reviews: 18, rating: 5, img: 'https://placehold.co/400x250/5F4B8B/FFFFFF?text=Jorge' },
                { name: 'Sofía Castro', subject: 'Música', description: 'Pianista y compositora. Ofrezco lecciones de piano y teoría musical para todos los niveles.', reviews: 5, rating: 5, img: 'https://placehold.co/400x250/9B5DE5/FFFFFF?text=Sofía' }
            ];

            const track = document.querySelector('.carousel-track');

            // --- Generar las tarjetas de los tutores con el nuevo estilo ---
            tutors.forEach(tutor => {
                const cardHTML = `
                    <div class="carousel-card p-3">
                        <div class="bg-white rounded-lg overflow-hidden h-full flex flex-col border border-gray-200/80 shadow-sm hover:shadow-xl transition-shadow duration-300">
                            <img class="w-full h-48 object-cover" src="${tutor.img}" alt="Foto de ${tutor.name}">
                            <div class="p-5 flex-grow flex flex-col">
                                <div class="flex justify-between items-center text-xs text-gray-500 mb-3">
                                    <span class="inline-block rounded-full bg-cyan-100 text-cyan-800 px-3 py-1 font-medium">${tutor.subject}</span>
                                    <span>${new Date().toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">${tutor.name}</h3>
                                <p class="text-gray-600 text-sm flex-grow mb-4">${tutor.description}</p>
                                <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center text-sm text-gray-500">
                                    <a href="#" class="font-semibold text-cyan-600 hover:text-cyan-700 transition-colors">Ver Perfil</a>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="font-semibold text-gray-700">${tutor.rating}</span>
                                        <span class="ml-1">(${tutor.reviews} reseñas)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                track.innerHTML += cardHTML;
            });

            // --- Lógica del Carrusel (sin cambios) ---
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
                const maxIndex = Math.max(0, totalSlides - visibleSlides);

                if (currentIndex > maxIndex) currentIndex = maxIndex;
                if (currentIndex < 0) currentIndex = 0;

                const cardWidth = 100 / visibleSlides;
                const offset = -currentIndex * cardWidth;
                track.style.transform = `translateX(${offset}%)`;

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
                stopSlideShow();
                slideInterval = setInterval(moveToNextSlide, 5000);
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

            carouselWrapper.addEventListener('mouseenter', stopSlideShow);
            carouselWrapper.addEventListener('mouseleave', startSlideShow);
            window.addEventListener('resize', updateCarousel);

            updateCarousel();
            startSlideShow();
        });
    </script>
</body>
</html>
