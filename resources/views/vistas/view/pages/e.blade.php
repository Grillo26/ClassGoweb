<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador Estilo Google</title>
    <!-- Incluyendo Tailwind CSS para un diseño moderno -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <style>
        /* Usando la fuente Inter para todo el cuerpo del documento */
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Estilo para un scroll suave en la lista de resultados */
        #results-container::-webkit-scrollbar {
            width: 8px;
        }
        #results-container::-webkit-scrollbar-thumb {
            background-color: #cbd5e1; /* cool-gray-300 */
            border-radius: 4px;
        }
        #results-container::-webkit-scrollbar-track {
            background-color: #f1f5f9; /* cool-gray-100 */
        }
    </style>
</head>
<body class="bg-gray-100 pt-20">

    <div class="w-full max-w-lg mx-auto p-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Buscador de Productos</h1>

        <!-- Contenedor principal del buscador. La posición relativa es clave. -->
        <div id="search-wrapper" class="relative">
            <!-- Campo de búsqueda -->
            <input type="text" id="search-input" placeholder="Buscar productos..." class="w-full px-5 py-3 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow z-20">
            
            <!-- Contenedor para los resultados (inicialmente oculto) -->
            <!-- Se posiciona de forma absoluta con respecto al 'search-wrapper' -->
            <div id="results-container" class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-lg border border-gray-200 max-h-80 overflow-y-auto z-10 hidden">
                <!-- Los resultados se insertarán aquí -->
            </div>
        </div>
    </div>

    <script>
        // --- DATOS DE EJEMPLO ---
        const sampleData = [
            { name: 'Laptop Gamer', category: 'tecnologia' },
            { name: 'Mouse Inalámbrico', category: 'tecnologia' },
            { name: 'Teclado Mecánico', category: 'tecnologia' },
            { name: 'Monitor 4K', category: 'tecnologia' },
            { name: 'Cámara Web HD', category: 'tecnologia' },
            { name: 'Manzanas Frescas', category: 'alimentos' },
            { name: 'Pan Integral', category: 'alimentos' },
            { name: 'Leche Descremada', category: 'alimentos' },
            { name: 'Aguacate Hass', category: 'alimentos' },
            { name: 'Salmón Fresco', category: 'alimentos' },
            { name: 'Camiseta de Algodón', category: 'ropa' },
            { name: 'Pantalones Vaqueros', category: 'ropa' },
            { name: 'Zapatillas Deportivas', category: 'ropa' },
            { name: 'Sudadera con Capucha', category: 'ropa' },
            { name: 'Chaqueta Impermeable', category: 'ropa' },
        ];

        // --- REFERENCIAS A ELEMENTOS DEL DOM ---
        const searchInput = document.getElementById('search-input');
        const resultsContainer = document.getElementById('results-container');
        const searchWrapper = document.getElementById('search-wrapper');

        // --- FUNCIÓN PARA RENDERIZAR LOS RESULTADOS ---
        function renderResults(results) {
            // Limpiamos resultados anteriores
            resultsContainer.innerHTML = '';

            if (results.length === 0) {
                // Si no hay resultados, mostramos un mensaje
                resultsContainer.innerHTML = '<p class="text-center text-gray-500 p-4">No se encontraron resultados.</p>';
            } else {
                // Creamos y añadimos un elemento por cada resultado
                results.forEach(item => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer';
                    resultItem.innerHTML = `
                        <p class="font-semibold text-gray-800">${item.name}</p>
                        <p class="text-sm text-gray-500 capitalize">${item.category}</p>
                    `;
                    // Opcional: Al hacer clic, se puede poner el nombre en el buscador
                    resultItem.addEventListener('click', () => {
                        searchInput.value = item.name;
                        resultsContainer.classList.add('hidden');
                    });
                    resultsContainer.appendChild(resultItem);
                });
            }
        }

        // --- FUNCIÓN PARA FILTRAR Y MOSTRAR ---
        function handleSearch() {
            const searchTerm = searchInput.value.toLowerCase();

            // Si no hay texto en el buscador, ocultamos los resultados y terminamos
            if (searchTerm.length === 0) {
                resultsContainer.classList.add('hidden');
                return;
            }

            // Filtramos los datos
            const filteredData = sampleData.filter(item =>
                item.name.toLowerCase().includes(searchTerm)
            );

            // Mostramos el contenedor y renderizamos los resultados
            resultsContainer.classList.remove('hidden');
            renderResults(filteredData);
        }

        // --- EVENT LISTENERS ---

        // Se ejecuta cada vez que el usuario escribe en el buscador
        searchInput.addEventListener('input', handleSearch);

        // Muestra los resultados si el usuario hace clic en el input y ya hay texto
        searchInput.addEventListener('focus', handleSearch);

        // Oculta los resultados cuando el usuario hace clic fuera del buscador
        document.addEventListener('click', (event) => {
            // Si el clic NO fue dentro del 'search-wrapper', ocultamos los resultados
            if (!searchWrapper.contains(event.target)) {
                resultsContainer.classList.add('hidden');
            }
        });

    </script>

</body>
</html>
