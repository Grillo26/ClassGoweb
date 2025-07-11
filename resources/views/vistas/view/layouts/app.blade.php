<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @php
            $siteTitle        = setting('_general.site_name');
    @endphp 
    <title>{{ $siteTitle }} {!! request()->is('messenger') ? ' | Messages' : (!empty($title) ? ' | ' . $title : '') !!}</title>
    <x-favicon />
    <link rel="stylesheet" href="{{ asset('css/estilos/landing.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"></head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<body>
    @include('vistas.view.components.navbar')

    <main>
        @yield('content')
    </main>

    @include('vistas.view.components.footer')
</body>
</html>
