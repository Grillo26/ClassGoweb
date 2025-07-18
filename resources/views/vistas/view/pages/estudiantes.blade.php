<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Perfil - ClassGo</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide-icons@latest/dist/lucide.min.js"></script>

    <style>
        /* Paleta de colores y fuente base del diseño moderno */
        :root {
            --primary-color: #023047;
            --secondary-color: #219EBC;
            --tertiary-color: #8ECAE6;
            --tertiary-color2: #FB8500;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        /* Estilos para las pestañas de configuración */
        .config-tab.active {
            background-color: var(--secondary-color);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        <!-- Barra Lateral de Navegación -->
        <aside class="w-64 bg-white shadow-md flex-shrink-0 flex flex-col">
            <div class="h-16 flex items-center justify-center text-2xl font-bold" style="color: var(--primary-color);">
                ClassGo!
            </div>
            <nav class="flex-grow p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-2 font-semibold rounded-lg" style="background-color: #eef7ff; color: var(--secondary-color);">
                            <i data-lucide="user-cog"></i>
                            Configuración de perfil
                        </a>
                    </li>
                    <li><a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"><i data-lucide="calendar"></i>Reservas</a></li>
                    <li><a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"><i data-lucide="search"></i>Buscar tutores</a></li>
                    <li><a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"><i data-lucide="star"></i>Favoritos</a></li>
                    <li><a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"><i data-lucide="history"></i>Historial de Tutorías</a></li>
                    <li><a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"><i data-lucide="tag"></i>Promociones</a></li>
                </ul>
            </nav>
            <div class="p-4 border-t">
                 <a href="#" class="flex items-center gap-3 px-4 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg">
                    <i data-lucide="log-out"></i>
                    Desconectar
                </a>
            </div>
        </aside>

        <!-- Contenido Principal -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            <!-- Cabecera del Contenido -->
            <header class="bg-white border-b flex items-center justify-between px-8 py-4">
                <div>
                    <h1 class="text-xl font-bold" style="color: var(--primary-color);">Configuración de perfil</h1>
                </div>
            </header>

            <!-- Pestañas y Formulario -->
            <div class="p-8">
                <div class="mb-6">
                    <div class="inline-flex rounded-lg shadow-sm">
                        <button id="tab-details" onclick="switchTab('details')" class="config-tab active px-5 py-2 text-sm font-medium border border-gray-200 rounded-l-lg focus:z-10 focus:ring-2 focus:ring-blue-500" style="color: var(--primary-color);">Detalles personales</button>
                        <button id="tab-account" onclick="switchTab('account')" class="config-tab px-5 py-2 text-sm font-medium border-t border-b border-r border-gray-200 hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-blue-500" style="color: var(--primary-color);">Configuraciones de la cuenta</button>
                    </div>
                </div>

                <!-- Contenido de la pestaña "Detalles personales" -->
                <div id="content-details">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Columna Izquierda: Multimedia -->
                        <div class="lg:col-span-1">
                            <div class="bg-white p-8 rounded-xl shadow-sm h-full">
                                <h2 class="text-lg font-bold mb-1" style="color: var(--primary-color);">Foto de perfil</h2>
                                <p class="text-sm text-gray-500 mb-6">Una imagen vale más que mil palabras.</p>
                                <div class="flex flex-col items-center text-center">
                                    <img class="h-32 w-32 rounded-full object-cover mb-4" src="https://placehold.co/200x200/EFEFEF/3A3A3A?text=ER" alt="Foto de perfil actual">
                                    <div class="flex flex-col gap-2 w-full max-w-xs">
                                         <button class="w-full px-4 py-2 text-white text-sm font-semibold rounded-lg hover:opacity-90" style="background-color: var(--secondary-color);">Cambiar foto</button>
                                         <button class="w-full px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-300">Quitar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Detalles Personales -->
                        <div class="lg:col-span-2">
                            <div class="bg-white p-8 rounded-xl shadow-sm">
                                <h2 class="text-lg font-bold mb-1" style="color: var(--primary-color);">Detalles personales</h2>
                                <p class="text-sm text-gray-500 mb-6">Proporciona información básica para completar tu perfil.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                        <input type="text" id="nombre" value="Edward" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-1" style="--tw-ring-color: var(--secondary-color);">
                                    </div>
                                    <div>
                                        <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                                        <input type="text" id="apellido" value="Rojas Cespedes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-1" style="--tw-ring-color: var(--secondary-color);">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" id="email" value="edwardrojas1603@gmail.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone number</label>
                                        <input type="tel" id="phone" value="70491982" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-1" style="--tw-ring-color: var(--secondary-color);">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Género</label>
                                        <div class="flex items-center gap-6">
                                            <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="genero" class="h-4 w-4 border-gray-300" style="color: var(--secondary-color);" checked><span class="text-gray-700">Masculino</span></label>
                                            <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="genero" class="h-4 w-4 border-gray-300" style="color: var(--secondary-color);"><span class="text-gray-700">Femenino</span></label>
                                            <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="genero" class="h-4 w-4 border-gray-300" style="color: var(--secondary-color);"><span class="text-gray-700">No especificado</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Botón de Guardar -->
                    <div class="mt-8 flex justify-end">
                        <button class="px-8 py-3 text-white font-semibold rounded-lg shadow-md hover:opacity-90 transition-all" style="background-color: var(--tertiary-color2);">
                            Guardar y actualizar
                        </button>
                    </div>
                </div>

                <!-- Contenido de la pestaña "Configuraciones de la cuenta" -->
                <div id="content-account" class="hidden space-y-8">
                    <!-- Tarjeta: Cambia tu contraseña -->
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <h2 class="text-lg font-bold mb-1" style="color: var(--primary-color);">Cambia tu contraseña</h2>
                        <p class="text-sm text-gray-500 mb-6">Puede restablecer su contraseña desde aquí. Elige la mejor contraseña.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                            <div>
                                <label for="new-password" class="block text-sm font-medium text-gray-700 mb-1">Ingrese una nueva contraseña</label>
                                <input type="password" id="new-password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-1" style="--tw-ring-color: var(--secondary-color);">
                            </div>
                            <div>
                                <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Vuelva a escribir la nueva contraseña</label>
                                <input type="password" id="confirm-password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-offset-1" style="--tw-ring-color: var(--secondary-color);">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button class="px-6 py-2 text-white font-semibold rounded-lg shadow-md hover:opacity-90 transition-all" style="background-color: var(--tertiary-color2);">
                                Actualizar contraseña
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Inicializa los íconos de Lucide
        lucide.createIcons();

        function switchTab(tab) {
            const tabDetails = document.getElementById('tab-details');
            const tabAccount = document.getElementById('tab-account');
            const contentDetails = document.getElementById('content-details');
            const contentAccount = document.getElementById('content-account');

            if (tab === 'details') {
                // Activar pestaña de detalles
                tabDetails.classList.add('active');
                tabAccount.classList.remove('active');
                // Mostrar contenido de detalles
                contentDetails.classList.remove('hidden');
                contentAccount.classList.add('hidden');
            } else if (tab === 'account') {
                // Activar pestaña de cuenta
                tabDetails.classList.remove('active');
                tabAccount.classList.add('active');
                // Mostrar contenido de cuenta
                contentDetails.classList.add('hidden');
                contentAccount.classList.remove('hidden');
            }
        }
        
        // Inicializar con la primera pestaña activa
        document.addEventListener('DOMContentLoaded', () => {
            switchTab('details');
        });
    </script>

</body>
</html>
