<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ClassGo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>
<body class="bg-white text-gray-900">

    @include('layouts.navbar')

    <main class="min-h-screen">
        @yield('content')
    </main>

</body>
</html>
