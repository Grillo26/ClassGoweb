<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal de Reserva con QR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Estilos para el input de archivo personalizado */
        .file-input-label {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            background-color: #f3f4f6;
            color: #374151;
            font-weight: 500;
            border: 1px solid #d1d5db;
            transition: background-color 0.2s;
        }
        .file-input-label:hover {
            background-color: #e5e7eb;
        }
        .file-input-label svg {
            margin-right: 0.5rem;
        }
        #fileName {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <!-- Botón para abrir el modal -->
    <button id="openModalBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
        Abrir Modal de Reserva
    </button>

    <!-- El Modal -->
    <div id="reservationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <!-- Contenedor del Modal -->
        <div id="modalContent" class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
            <div class="flex flex-col md:flex-row">
                <!-- Columna Izquierda: QR -->
                <div class="w-full md:w-1/3 bg-gray-50 p-6 flex items-center justify-center">
                    <img src="http://googleusercontent.com/file_content/0" alt="Código QR de Notion" class="w-full max-w-[250px] h-auto rounded-lg object-contain" onerror="this.onerror=null;this.src='https://placehold.co/250x250/e2e8f0/334155?text=QR+Code';">
                </div>

                <!-- Columna Derecha: Formulario -->
                <div class="w-full md:w-2/3 p-8 flex flex-col space-y-6">
                    <h2 class="text-2xl font-bold text-gray-800">Selecciona la materia</h2>

                    <!-- Input de Archivo -->
                    <div>
                        <label for="comprobante" class="text-sm font-medium text-gray-700 mb-2 block">Comprobante de pago</label>
                        <label for="comprobante" class="file-input-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            Subir archivo
                        </label>
                        <input type="file" id="comprobante" class="hidden">
                        <p id="fileName" class="text-sm text-gray-500 mt-1">Ningún archivo seleccionado</p>
                    </div>

                    <!-- Select de Materias -->
                    <div>
                        <label for="materia" class="text-sm font-medium text-gray-700 mb-2 block">Materia</label>
                        <select id="materia" name="materia" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">-- Elige una materia --</option>
                            <option value="calculo1">Cálculo I</option>
                            <option value="algebra">Álgebra Lineal</option>
                            <option value="fisica2">Física II</option>
                            <option value="programacion">Programación Avanzada</option>
                            <option value="basedatos">Bases de Datos</option>
                        </select>
                    </div>

                    <!-- Fecha y Hora -->
                    <div class="bg-gray-100 p-4 rounded-lg text-sm">
                        <p class="text-gray-600"><strong>Fecha:</strong> <span id="currentDate"></span></p>
                        <p class="text-gray-600 mt-1"><strong>Hora:</strong> <span id="currentTime"></span></p>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button id="cancelBtn" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg transition">
                            Cancelar
                        </button>
                        <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition">
                            Reservar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- MANEJO DEL MODAL ---
        const openModalBtn = document.getElementById('openModalBtn');
        const reservationModal = document.getElementById('reservationModal');
        const modalContent = document.getElementById('modalContent');
        const cancelBtn = document.getElementById('cancelBtn');

        // --- MANEJO DEL INPUT DE ARCHIVO ---
        const fileInput = document.getElementById('comprobante');
        const fileNameDisplay = document.getElementById('fileName');

        // --- MANEJO DE FECHA Y HORA ---
        const currentDateEl = document.getElementById('currentDate');
        const currentTimeEl = document.getElementById('currentTime');

        // Función para actualizar la fecha y hora
        function updateDateTime() {
            const now = new Date();
            const optionsDate = { year: 'numeric', month: 'long', day: 'numeric' };
            const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            
            currentDateEl.textContent = now.toLocaleDateString('es-ES', optionsDate);
            currentTimeEl.textContent = now.toLocaleTimeString('es-ES', optionsTime);
        }

        // Función para abrir el modal
        const openModal = () => {
            updateDateTime(); // Actualiza la fecha y hora al abrir
            reservationModal.classList.remove('hidden');
            // Pequeño delay para la animación de entrada
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
        };

        // Función para cerrar el modal
        const closeModal = () => {
            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
            // Espera a que termine la animación para ocultar el modal
            setTimeout(() => {
                reservationModal.classList.add('hidden');
            }, 300);
        };

        // Event Listeners
        openModalBtn.addEventListener('click', openModal);
        cancelBtn.addEventListener('click', closeModal);

        // Cerrar el modal al hacer clic fuera del contenido
        reservationModal.addEventListener('click', (event) => {
            if (event.target === reservationModal) {
                closeModal();
            }
        });

        // Mostrar el nombre del archivo seleccionado
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = fileInput.files[0].name;
            } else {
                fileNameDisplay.textContent = 'Ningún archivo seleccionado';
            }
        });

    </script>
</body>
</html>
