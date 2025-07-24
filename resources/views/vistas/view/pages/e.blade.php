<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassGo - Encontrar un Tutor</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #023047;
            --secondary-color: #219EBC;
            --secondary-color2: #CDD6DA;
            --tertiary-color: #8ECAE6;
            --tertiary-color2: #FB8500;
            --bg-color: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
        }
        .bg-primary { background-color: var(--primary-color); }
        .text-primary { color: var(--primary-color); }
        .text-secondary { color: var(--secondary-color); }
        .bg-tertiary-orange { background-color: var(--tertiary-color2); }
        .text-tertiary-orange { color: var(--tertiary-color2); }
        .border-tertiary-orange { border-color: var(--tertiary-color2); }
        .ring-tertiary-orange {
            --tw-ring-color: var(--tertiary-color2);
        }
        .hero-section {
            background-color: #023047;
            background-image: linear-gradient(135deg, #023047 0%, #219EBC 100%);
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="https://i.imgur.com/f8nL3gS.png" alt="Logo ClassGo" class="h-10 w-auto" onerror="this.onerror=null; this.src='https://placehold.co/150x50/023047/ffffff?text=ClassGo';">
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-gray-600 hover:text-secondary transition-all font-medium">Buscar Tutores</a>
                <a href="#" class="text-gray-600 hover:text-secondary transition-all font-medium">Sobre Nosotros</a>
                <a href="#" class="text-gray-600 hover:text-secondary transition-all font-medium">Cómo trabajamos</a>
            </div>
            <div class="flex items-center space-x-4">
                 <button class="bg-tertiary-orange text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90 transition-all">Empezar</button>
                 <button class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-gray-600"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                 </button>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
             <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-tertiary-color mb-2">Hogar / Encontrar tutor</p>
                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Descubra un tutor en línea capacitado para sus estudios</h1>
                    <p class="mt-4 text-lg text-gray-300">Domina tus estudios con tutorías personalizadas en línea impartidas por educadores expertos. Nuestros tutores capacitados están aquí para ayudarlo a construir bases sólidas y alcanzar sus objetivos académicos.</p>
                </div>
                <div class="hidden md:block">
                     <img src="https://i.imgur.com/zYf4zDf.png" alt="Mascota de ClassGo" class="w-full max-w-sm mx-auto" onerror="this.onerror=null; this.src='https://placehold.co/300x300/ffffff/023047?text=ClassGo';">
                </div>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10">
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-4">
                    <label for="keyword-search" class="sr-only">Buscar por palabra clave</label>
                    <div class="relative">
                        <input type="text" id="keyword-search" placeholder="Buscar por palabra clave" class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary">
                         <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                        </span>
                    </div>
                </div>
                <div class="md:col-span-3">
                    <label for="group-select" class="sr-only">Grupo de materias</label>
                    <select id="group-select" class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary">
                        <option>Elige grupo de materias</option>
                        <option>Ciencias Exactas</option>
                        <option>Humanidades</option>
                        <option>Idiomas</option>
                    </select>
                </div>
                <button class="w-full bg-tertiary-orange text-white font-bold py-3 rounded-lg text-lg hover:opacity-90 transition-all">Buscar</button>
            </div>
        </div>
    </section>
    
    <!-- Tutor List -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="space-y-8">
            <!-- Tutor Card 1 -->
            <div class="bg-white p-6 rounded-2xl shadow-md flex flex-col md:flex-row gap-6">
                <img src="https://placehold.co/128x128/8ECAE6/023047?text=AF" alt="Foto de Antonio Flores" class="w-32 h-32 rounded-full object-cover border-4 border-tertiary-color mx-auto md:mx-0">
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-primary">Antonio Alexander Sandoval Flores</h3>
                    <div class="flex items-center space-x-4 text-sm text-gray-500 mt-2">
                        <span>0.0/5.0 (0 reseñas)</span>
                        <span>•</span>
                        <span>1 Sesión</span>
                        <span>•</span>
                        <span>Idiomas que conozco: Inglés</span>
                    </div>
                    <p class="mt-3 text-gray-600">Apasionado por compartir conocimientos de manera clara y práctica. Mi objetivo es ayudarte a aprender de forma sencilla y efectiva.</p>
                </div>
                <div class="flex flex-col space-y-3 justify-center items-center md:w-48">
                    <button class="w-full bg-tertiary-orange text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition-all">Reservar una sesión</button>
                    <button class="w-full bg-secondary text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition-all">Enviar mensaje</button>
                </div>
            </div>
            
            <!-- Tutor Card 2 -->
            <div class="bg-white p-6 rounded-2xl shadow-md flex flex-col md:flex-row gap-6">
                <img src="https://placehold.co/128x128/219EBC/ffffff?text=ER" alt="Foto de Edward Rojas" class="w-32 h-32 rounded-full object-cover border-4 border-tertiary-color mx-auto md:mx-0">
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-primary">Edward Rojas Cespedes</h3>
                    <p class="text-sm text-gray-500 font-medium mt-1">Especialidad: Controla la información, controla el futuro</p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500 mt-2">
                        <span>0.0/5.0 (0 reseñas)</span>
                        <span>•</span>
                        <span>5 Materias</span>
                        <span>•</span>
                        <span>Idiomas que conozco: Inglés</span>
                    </div>
                    <p class="mt-3 text-gray-600">Joven profesional próximo a graduarme en Información y Control de Gestión, combinando formación académica con habilidades prácticas en análisis de datos y sistemas de gestión. Preparado para los desafíos del mundo...</p>
                </div>
                <div class="flex flex-col space-y-3 justify-center items-center md:w-48">
                    <button class="w-full bg-tertiary-orange text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition-all">Reservar una sesión</button>
                    <button class="w-full bg-secondary text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition-all">Enviar mensaje</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white mt-16">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <div class="lg:col-span-2">
                    <img src="https://i.imgur.com/f8nL3gS.png" alt="Logo ClassGo Blanco" class="h-12 w-auto mb-4" onerror="this.onerror=null; this.src='https://placehold.co/150x50/ffffff/023047?text=ClassGo';">
                    <p class="text-gray-300">classgobol@gmail.com</p>
                    <button class="mt-4 bg-tertiary-orange text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90 transition-all">Regístrate Gratis</button>
                </div>
                <div>
                    <h4 class="font-bold tracking-wider">Tutores</h4>
                    <ul class="mt-4 space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition-all">Acerca de</a></li>
                        <li><a href="#" class="hover:text-white transition-all">Términos y Condiciones</a></li>
                        <li><a href="#" class="hover:text-white transition-all">Encuentra un tutor</a></li>
                        <li><a href="#" class="hover:text-white transition-all">FAQs</a></li>
                        <li><a href="#" class="hover:text-white transition-all">Cómo funciona</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold tracking-wider">Tutorías Online</h4>
                    <ul class="mt-4 space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition-all">Contabilidad Básica</a></li>
                        <li><a href="#" class="hover:text-white transition-all">Estadística</a></li>
                        <li><a href="#" class="hover:text-white transition-all">Probabilidades</a></li>
                        <li><a href="#" class="hover:text-white transition-all">Conceptos básicos de redes</a></li>
                        <li><a href="#" class="hover:text-white transition-all">Computación</a></li>
                        <li><a href="#" class="hover:text-white transition-all">Presupuesto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold tracking-wider">Soporte</h4>
                    <ul class="mt-4 space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition-all">Contáctanos por WhatsApp</a></li>
                    </ul>
                    <h4 class="font-bold tracking-wider mt-6">Obtén la App</h4>
                    <p class="text-gray-300 mt-2 text-sm">¡Lleva tu educación a todas partes!</p>
                    <div class="flex space-x-2 mt-2">
                        <a href="#"><img src="https://placehold.co/120x40/000000/ffffff?text=App+Store" alt="App Store" class="h-10"></a>
                        <a href="#"><img src="https://placehold.co/135x40/000000/ffffff?text=Google+Play" alt="Google Play" class="h-10"></a>
                    </div>
                </div>
            </div>
            <div class="mt-12 border-t border-gray-700 pt-8 flex flex-col sm:flex-row justify-between items-center">
                <p class="text-sm text-gray-400">Copyright © 2025. Todos los derechos reservados.</p>
                <div class="flex space-x-4 mt-4 sm:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path></svg></a>
                    <a href="#" class="text-gray-400 hover:text-white"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.585-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.585-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.585.069-4.85c.149-3.225 1.664-4.771 4.919-4.919 1.266-.058 1.644-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.059-1.281.073-1.689.073-4.948s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.79 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.441 1.441 1.441 1.441-.645 1.441-1.441-.645-1.44-1.441-1.44z"></path></svg></a>
                    <a href="#" class="text-gray-400 hover:text-white"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"></path></svg></a>
                    <a href="#" class="text-gray-400 hover:text-white"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.533 5.568a2.79 2.79 0 0 0-1.947-1.947C18.832 3.14 12 3.14 12 3.14s-6.832 0-8.586.481A2.79 2.79 0 0 0 1.467 5.568C1 7.322 1 12 1 12s0 4.678.467 6.432a2.79 2.79 0 0 0 1.947 1.947c1.754.481 8.586.481 8.586.481s6.832 0 8.586-.481a2.79 2.79 0 0 0 1.947-1.947C23 16.678 23 12 23 12s0-4.678-.467-6.432zM9.75 15.5V8.5l6.5 3.5-6.5 3.5z"></path></svg></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
