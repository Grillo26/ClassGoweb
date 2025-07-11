@extends('layouts.frontend-app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/estilos/ficha.css') }}">
@endsection 
@section('content')
<section class="ficha-container">
    <div class="imagen-ficha">
        <img src="{{ url('tutor/ficha-img/' . $slug . '/' . $id) }}?t={{ time() }}" alt="Ficha de usuario" style="max-width:100%;">
    </div>
    <div class="info-buttons">
        <a href="{{ route('tutor.ficha.download', ['slug' => $slug, 'id' => $id]) }}" class="button1" download>Descargar</a>
        <button class="button2" onclick="navigator.share ? navigator.share({url: window.location.href}) : alert('No soportado');">Compartir</button>
    </div>
</section>
@endsection