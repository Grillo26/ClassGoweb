<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Tutor - William Espinoza</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Aplicamos la paleta de colores y la fuente base */
        :root {
            --primary-color: #023047;
            --secondary-color: #219EBC;
            --secondary-color2: #CDD6DA;
            --tertiary-color: #8ECAE6;
            --tertiary-color2: #FB8500;
            --bg-color: #fff;
            --footer-white: #d6f7fd;
            --transition: all 0.3s ease;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Un fondo ligeramente más suave */
            color: var(--primary-color);
        }
        /* Clases personalizadas para usar las variables de color */
        .bg-primary { background-color: var(--primary-color); }
        .bg-secondary { background-color: var(--secondary-color); }
        .bg-tertiary-orange { background-color: var(--tertiary-color2); }
        .bg-footer { background-color: var(--primary-color); }
        
        .text-primary { color: var(--primary-color); }
        .text-secondary { color: var(--secondary-color); }
        .text-tertiary-orange { color: var(--tertiary-color2); }

        .border-secondary { border-color: var(--secondary-color); }
        .border-tertiary { border-color: var(--tertiary-color); }
        
        .transition-all {
            transition: var(--transition);
        }

        /* Estilos para las pestañas */
        .tab-button.active {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
            font-weight: 600;
        }
        .subtab-button.active {
            background-color: var(--secondary-color);
            color: white;
        }
        /* Estilos para el calendario y selector de hora */
        .calendar-day.selected {
            background-color: var(--secondary-color);
            color: white;
            font-weight: bold;
        }
        .time-slot.selected {
            background-color: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header (Simulado) -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="Logo ClassGo azul.png" alt="Logo ClassGo" class="h-10 w-auto" onerror="this.onerror=null; this.src='https://placehold.co/100x40/023047/ffffff?text=ClassGo';">
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <a href="#" class="text-gray-600 hover:text-secondary transition-all">Encontrar Tutores</a>
                <a href="#" class="text-gray-600 hover:text-secondary transition-all">Mis Clases</a>
                <a href="#" class="bg-secondary text-white px-4 py-2 rounded-lg hover:opacity-90 transition-all">Mi Perfil</a>
            </div>
        </nav>
    </header>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <!-- Breadcrumbs -->
        <div class="text-sm text-gray-500 mb-6">
            <a href="#" class="hover:text-secondary transition-all">Hogar</a> / 
            <a href="#" class="hover:text-secondary transition-all">Encontrar tutor</a> / 
            <span class="font-semibold text-primary">William Espinoza</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Columna Izquierda (Información del Tutor) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Card Principal del Tutor -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="h-48 bg-gray-200 relative bg-cover bg-center" style="background-image: url('https://placehold.co/800x300/023047/8ECAE6?text=Video+Banner')">
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                            <button class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center group transition-all hover:bg-white/30">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-white transform group-hover:scale-110 transition-transform"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6 relative">
                         <img src="https://placehold.co/128x128/8ECAE6/023047?text=WE" alt="Foto de William Espinoza" class="w-32 h-32 rounded-full object-cover border-4 border-white absolute -top-16 left-6 shadow-lg">
                         <div class="ml-36 pl-2">
                            <h1 class="text-3xl font-bold text-primary">William Espinoza</h1>
                            <div class="mt-2 flex items-center space-x-4 text-gray-600">
                                <div class="flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-tertiary-orange h-5 w-5 fill-tertiary-orange"><path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                    <span>0.0 (0 reseñas)</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-secondary"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    <span>0 Estudiantes</span>
                                </div>
                            </div>
                         </div>
                         <p class="mt-4 text-gray-700 text-lg">"El país es grande porque sus ciudadanos son altamente preparados"</p>
                    </div>
                </div>

                <!-- SECCIÓN DE PESTAÑAS PRINCIPAL -->
                <div class="bg-white rounded-2xl shadow-md">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-6 px-6 overflow-x-auto" aria-label="Tabs">
                            <button onclick="changeTab(event, 'introduccion')" class="tab-button active shrink-0 border-b-2 border-transparent px-1 py-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition-all">Introducción</button>
                            <button onclick="changeTab(event, 'disponibilidad')" class="tab-button shrink-0 border-b-2 border-transparent px-1 py-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition-all">Disponibilidad</button>
                            <button onclick="changeTab(event, 'curriculum')" class="tab-button shrink-0 border-b-2 border-transparent px-1 py-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition-all">Aspectos Destacados</button>
                            <button onclick="changeTab(event, 'resenas')" class="tab-button shrink-0 border-b-2 border-transparent px-1 py-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition-all">Reseñas (0)</button>
                        </nav>
                    </div>
                    <div class="p-6">
                        <div id="introduccion" class="tab-content space-y-8">
                            <div>
                                <h3 class="text-xl font-bold text-primary mb-4">Acerca de mí</h3>
                                <p class="text-gray-600 leading-relaxed">Con una trayectoria de más de 25 años, he dedicado mi carrera a fortalecer las operaciones de organizaciones líderes en diversos sectores. Como auditor interno y externo, he evaluado exhaustivamente procesos, sistemas de control y riesgos, identificando oportunidades de mejora y asegurando el cumplimiento normativo. Mi experiencia en consultoría me permite diseñar soluciones personalizadas para optimizar la gestión de riesgos, mejorar la eficiencia...</p>
                                <button class="text-secondary font-semibold mt-2 hover:underline">Mostrar más</button>
                            </div>
                            <hr class="border-gray-200">
                            <div>
                                <h3 class="text-xl font-bold text-primary mb-4">Puedo enseñar</h3>
                                <div class="space-y-5 mt-5">
                                    <div class="flex items-start space-x-4"><div class="bg-tertiary-color/20 p-3 rounded-xl"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary h-6 w-6"><path d="M21.5 12c0-5.25-4.25-9.5-9.5-9.5S2.5 6.75 2.5 12s4.25 9.5 9.5 9.5s9.5-4.25 9.5-9.5Z"></path><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg></div><div><h4 class="font-semibold text-primary text-lg">Auditoría</h4><div class="flex flex-wrap gap-2 mt-2"><span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Fundamentos de Auditoría</span> <span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Normas Internacionales (ISA)</span> <span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Riesgos y Controles Internos</span> <span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Técnicas de Muestreo</span> <span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Informes y Recomendaciones</span> <span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Auditoría de Cumplimiento Legal</span> <span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Herramientas Automatizadas</span> <span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Auditorías Internas</span> <span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Ética Profesional</span></div></div></div>
                                    <div class="flex items-start space-x-4"><div class="bg-tertiary-color/20 p-3 rounded-xl"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary h-6 w-6"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect width="8" height="4" x="8" y="2" rx="1"></rect></svg></div><div><h4 class="font-semibold text-primary text-lg">Contabilidad</h4><div class="flex flex-wrap gap-2 mt-2"><span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Contabilidad Básica</span><span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Contabilidad Intermedia</span></div></div></div>
                                    <div class="flex items-start space-x-4"><div class="bg-tertiary-color/20 p-3 rounded-xl"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary h-6 w-6"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg></div><div><h4 class="font-semibold text-primary text-lg">Finanzas</h4><div class="flex flex-wrap gap-2 mt-2"><span class="bg-tertiary-color/30 text-primary text-sm font-medium px-3 py-1 rounded-full">Análisis de Estados Financieros</span></div></div></div>
                                </div>
                            </div>
                            <hr class="border-gray-200">
                             <div>
                                <h3 class="text-xl font-bold text-primary mb-3">Puedo hablar</h3>
                                <span class="bg-secondary-color2 text-primary font-medium px-4 py-1.5 rounded-full">Nativo</span>
                            </div>
                        </div>
                        <div id="disponibilidad" class="tab-content hidden">
                            <h3 class="text-2xl font-bold text-primary mb-6">Reserva una sesión</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Columna del Calendario -->
                                <div>
                                    <h4 class="text-lg font-semibold mb-4">Selecciona un día</h4>
                                    <div class="bg-white p-4 rounded-xl border">
                                        <div class="flex items-center justify-between mb-4">
                                            <button class="p-2 rounded-full hover:bg-gray-100 transition-all"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-gray-600"><path d="m15 18-6-6 6-6"></path></svg></button>
                                            <h5 class="font-semibold text-primary">Julio 2025</h5>
                                            <button class="p-2 rounded-full hover:bg-gray-100 transition-all"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-gray-600"><path d="m9 18 6-6-6-6"></path></svg></button>
                                        </div>
                                        <div id="calendar-grid" class="grid grid-cols-7 gap-1 text-center text-sm">
                                            <div class="font-semibold text-gray-400">L</div><div class="font-semibold text-gray-400">M</div><div class="font-semibold text-gray-400">M</div><div class="font-semibold text-gray-400">J</div><div class="font-semibold text-gray-400">V</div><div class="font-semibold text-gray-400">S</div><div class="font-semibold text-gray-400">D</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Columna del Selector de Hora -->
                                <div id="time-selector-column" class="hidden md:block">
                                    <h4 class="text-lg font-semibold mb-4">Selecciona una hora</h4>
                                    <div class="bg-white p-4 rounded-xl border h-full">
                                        <p class="text-sm text-gray-500 mb-2">Horario disponible: <span id="available-range">16:00 - 21:40</span></p>
                                        <div id="time-slots" class="grid grid-cols-3 gap-2"></div>
                                        <button class="w-full mt-4 bg-tertiary-color/30 text-secondary font-semibold py-2 px-4 rounded-lg hover:bg-tertiary-color/50 transition-all">Elegir hora exacta</button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 text-center">
                                <button class="w-full md:w-auto bg-tertiary-orange text-white font-bold py-3 px-12 rounded-xl text-lg transition-all hover:opacity-90 shadow-lg hover:shadow-xl">Pagar y reservar</button>
                            </div>
                        </div>
                        <div id="curriculum" class="tab-content hidden">
                           <nav class="flex space-x-1 sm:space-x-4 border-b border-gray-200 mb-6"><button onclick="changeSubTab(event, 'educacion')" class="subtab-button active font-semibold px-3 py-2 rounded-t-lg text-sm transition-all">Educación</button><button onclick="changeSubTab(event, 'experiencia')" class="subtab-button font-semibold px-3 py-2 rounded-t-lg text-sm text-gray-600 transition-all">Experiencia</button><button onclick="changeSubTab(event, 'certificaciones')" class="subtab-button font-semibold px-3 py-2 rounded-t-lg text-sm text-gray-600 transition-all">Certificaciones</button></nav>
                            <div id="educacion" class="subtab-content"><div class="text-center py-12"><img src="https://placehold.co/100x100/f0f7ff/8ECAE6?text=+" alt="Libro y birrete" class="mx-auto h-24 w-24"><h4 class="mt-4 text-lg font-semibold">¡Aún no se ha añadido ningún registro!</h4><p class="text-gray-500 mt-1">No hay registros disponibles para mostrar en este momento.</p></div></div>
                            <div id="experiencia" class="subtab-content hidden"><div class="text-center py-12"><img src="https://placehold.co/100x100/f0f7ff/8ECAE6?text=+" alt="Maletín" class="mx-auto h-24 w-24"><h4 class="mt-4 text-lg font-semibold">¡Aún no se ha añadido ningún registro!</h4><p class="text-gray-500 mt-1">No hay experiencia laboral para mostrar.</p></div></div>
                            <div id="certificaciones" class="subtab-content hidden"><div class="text-center py-12"><img src="https://placehold.co/100x100/f0f7ff/8ECAE6?text=+" alt="Medalla" class="mx-auto h-24 w-24"><h4 class="mt-4 text-lg font-semibold">¡Aún no se ha añadido ningún registro!</h4><p class="text-gray-500 mt-1">No hay certificaciones ni premios para mostrar.</p></div></div>
                        </div>
                        <div id="resenas" class="tab-content hidden">
                            <h3 class="text-xl font-bold text-primary mb-4">Reseñas de estudiantes</h3>
                            <div class="flex flex-col md:flex-row gap-8 items-start">
                                <div class="w-full md:w-1/3 bg-tertiary-color/20 p-4 rounded-lg text-center"><div class="text-5xl font-bold">0.0</div><div class="flex justify-center text-gray-300 my-2"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></div><div class="text-gray-600">Basado en 0 calificaciones</div></div>
                                <div class="w-full md:w-2/3 space-y-2">
                                    <div class="flex items-center gap-2 text-sm"><span class="text-gray-600">5</span><svg class="w-4 h-4 text-tertiary-orange" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-tertiary-orange h-2 rounded-full" style="width: 0%"></div></div><span class="text-gray-600 font-semibold">0</span></div>
                                    <div class="flex items-center gap-2 text-sm"><span class="text-gray-600">4</span><svg class="w-4 h-4 text-tertiary-orange" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-tertiary-orange h-2 rounded-full" style="width: 0%"></div></div><span class="text-gray-600 font-semibold">0</span></div>
                                    <div class="flex items-center gap-2 text-sm"><span class="text-gray-600">3</span><svg class="w-4 h-4 text-tertiary-orange" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-tertiary-orange h-2 rounded-full" style="width: 0%"></div></div><span class="text-gray-600 font-semibold">0</span></div>
                                    <div class="flex items-center gap-2 text-sm"><span class="text-gray-600">2</span><svg class="w-4 h-4 text-tertiary-orange" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-tertiary-orange h-2 rounded-full" style="width: 0%"></div></div><span class="text-gray-600 font-semibold">0</span></div>
                                    <div class="flex items-center gap-2 text-sm"><span class="text-gray-600">1</span><svg class="w-4 h-4 text-tertiary-orange" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-tertiary-orange h-2 rounded-full" style="width: 0%"></div></div><span class="text-gray-600 font-semibold">0</span></div>
                                </div>
                            </div>
                            <div class="text-center mt-8 border-t pt-8"><h4 class="text-lg font-semibold">¡Aún no hay reseñas!</h4><p class="text-gray-500 mt-1">Parece que no hay registros para mostrar en este momento.</p></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Columna Derecha (Acciones) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md sticky top-28">
                    <div class="mb-4">
                        <p class="text-3xl font-bold text-primary">15 Bs <span class="text-base font-normal text-gray-500">/ tutoría</span></p>
                        <div class="flex items-center text-sm text-gray-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <span>20 min</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 ml-3 mr-1 text-green-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <span class="text-green-600 font-semibold">Tutor verificado</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <button class="w-full bg-tertiary-orange text-white font-bold py-3 px-6 rounded-xl text-lg transition-all hover:opacity-90 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span>Tutoría ahora</span>
                        </button>
                        <button class="w-full bg-secondary text-white font-bold py-3 px-6 rounded-xl text-lg transition-all hover:opacity-90 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            <span>Reservar</span>
                        </button>
                        <button class="w-full bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-xl text-lg transition-all hover:bg-gray-300 flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line></svg>
                            <span>Compartir perfil</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-footer text-white mt-16">
        <!-- Contenido del Footer -->
    </footer>

    <script>
        // --- SCRIPT PARA PESTAÑAS ---
        function changeTab(event, tabID) {
            let tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.add('hidden'));
            let tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => button.classList.remove('active'));
            document.getElementById(tabID).classList.remove('hidden');
            event.currentTarget.classList.add('active');
        }

        function changeSubTab(event, tabID) {
            let subTabContents = document.querySelectorAll('.subtab-content');
            subTabContents.forEach(content => content.classList.add('hidden'));
            let subTabButtons = document.querySelectorAll('.subtab-button');
            subTabButtons.forEach(button => button.classList.remove('active', 'bg-secondary', 'text-white'));
            document.getElementById(tabID).classList.remove('hidden');
            event.currentTarget.classList.add('active', 'bg-secondary', 'text-white');
        }

        // --- SCRIPT PARA CALENDARIO Y HORA ---
        document.addEventListener('DOMContentLoaded', function() {
            const calendarGrid = document.getElementById('calendar-grid');
            const timeSelectorColumn = document.getElementById('time-selector-column');
            const timeSlotsContainer = document.getElementById('time-slots');
            if (!calendarGrid) return; // Salir si no estamos en la página correcta
            
            const month = 6; // Julio (0-indexed)
            const year = 2025;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const startingDay = (firstDay === 0) ? 6 : firstDay - 1; 

            for (let i = 0; i < startingDay; i++) {
                calendarGrid.appendChild(document.createElement('div'));
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dayCell = document.createElement('button');
                dayCell.textContent = day;
                dayCell.classList.add('calendar-day', 'w-10', 'h-10', 'flex', 'items-center', 'justify-center', 'rounded-full', 'hover:bg-tertiary-color/50', 'transition-all');
                dayCell.dataset.day = day;
                dayCell.onclick = selectDate;
                calendarGrid.appendChild(dayCell);
            }

            const exampleTimes = ['16:00', '16:20', '16:40', '17:00', '17:20', '17:40', '18:00', '18:20', '19:00', '19:20', '19:40'];
            timeSlotsContainer.innerHTML = ''; // Limpiar por si acaso
            exampleTimes.forEach(time => {
                const timeButton = document.createElement('button');
                timeButton.textContent = time;
                timeButton.classList.add('time-slot', 'p-2', 'border', 'rounded-lg', 'hover:border-secondary', 'transition-all');
                timeButton.onclick = selectTime;
                timeSlotsContainer.appendChild(timeButton);
            });
        });

        function selectDate(event) {
            const allDays = document.querySelectorAll('.calendar-day');
            allDays.forEach(d => d.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            document.getElementById('time-selector-column').classList.remove('hidden');
        }

        function selectTime(event) {
            const allTimes = document.querySelectorAll('.time-slot');
            allTimes.forEach(t => t.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
        }

    </script>

</body>
</html>
