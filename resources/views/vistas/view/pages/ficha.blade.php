@extends('layouts.frontend-app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/estilos/ficha.css') }}">
@endsection 
@section('content')
@yield('styles')
<section class="ficha-container">
    <div class="imagen-ficha">
        <img src="{{ asset('images/ficha_base.jpeg') }}" alt="Pasos">
    </div>

    <div class="info-buttons">
        <button class="button2">Compartir</button>
        <button class="button1">Descargar</button>
    </div>
</section>
@endsection
