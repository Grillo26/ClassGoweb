<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ClassGo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    @vite('resources/css/app.css')
</head>
<body>
    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('layouts.footer')

</body>
</html>
