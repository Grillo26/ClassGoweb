@extends('layouts.app')

@section('title', 'Class Go!')

@section('content')

<!--SECCIÓN HERO -->
<section class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-row-reverse md:flex-row items-center gap-10">
            
            <!-- Mascota -->
            <div class="w-full md:w-1/2 flex justify-center">
                <img src="{{ asset('images/Tugo_With_Glasses.png') }}" alt="Mascota" class="max-w-xs md:max-w-md w-full">
            </div>
            
            <!-- Contenido -->
            <div class="w-full md:w-1/2">
                <p class="text-lg text-gray-700 mb-4">Aprende y Progresa con.</p>
                <h1 class="text-4xl font-bold mb-4">Tutoría en Línea</h1>
                <p class="mb-6">
                    Alcanza tus metas con tutorías personalizadas de los mejores expertos.<br>
                    Conéctate con tutores dedicados para asegurar tu éxito.
                </p>

                <!-- Buscador -->
                <div class="flex w-full max-w-md">
                    <input type="text" placeholder="Buscar tutor..."
                        class="flex-1 px-4 py-2 border border-r-0 rounded-l-md shadow focus:outline-none">
                    <button class="bg-sky-600 text-white px-4 py-2 rounded-r-md">Buscar Tutor</button>
                </div>
            </div>  
        </div>
    </div>
</section>

<!-- 🔢 SECCIÓN CONTADORES -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-row-1 md:grid-row-4 gap-6 text-center">
            <div>
                <h2 class="text-4xl font-bold text-sky-600">+500</h2>
                <p class="text-gray-700">Usuarios Registrados</p>
            </div>
            <div>
                <h2 class="text-4xl font-bold text-sky-600">+230</h2>
                <p class="text-gray-700">Tutores Disponibles</p>
            </div>
            <div>
                <h2 class="text-4xl font-bold text-sky-600">+230</h2>
                <p class="text-gray-700">Estudiantes Registrados</p>
            </div>
            <div>
                <h2 class="text-4xl font-bold text-sky-600">4,5</h2>
                <p class="text-gray-700">En la App Store</p>
            </div>
        </div>
    </div>
</section>

<!-- 🎓 SECCIÓN TUTORES DESTACADOS (estructura base) -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-10">Tutores Destacados</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Tarjeta de tutor ejemplo -->
            <div class="bg-white rounded shadow p-6 text-center">
                <img src="{{ asset('images/tutor1.jpg') }}" alt="Tutor" class="w-24 h-24 rounded-full mx-auto mb-4">
                <h3 class="text-xl font-semibold">Laura Gutiérrez</h3>
                <p class="text-gray-600">Matemáticas - Secundaria</p>
                <button class="mt-4 bg-sky-600 text-white px-4 py-2 rounded">Ver Perfil</button>
            </div>
            <!-- Copiar más tutores aquí -->
        </div>
    </div>
</section>

<section class="bg-white py-16">
</section>

@endsection

