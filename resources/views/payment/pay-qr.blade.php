@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2 class="mb-4">Pago al Tutor</h2>

    <!-- Mostrar ID de la orden -->
    <h4>Orden ID: {{ $order->id }}</h4>

    <!-- Mostrar información del tutor -->
    @if(isset($tutor))
        <h4 class="mt-3">Tutor: {{ $tutor->first_name }} {{ $tutor->last_name }}</h4>
    @else
        <h4 class="text-danger mt-3">No se encontró el tutor</h4>
    @endif

    <!-- Mostrar el monto de pago -->
    <h5 class="mt-2">
    Monto a Pagar: {!! isset($order->amount) ? formatAmount($order->amount * 0.80) : 'No disponible' !!} {{ $order->currency }}
</h5>


    <!-- Mostrar el código QR centrado -->
    <div class="d-flex justify-content-center mt-4">
        @if(isset($qrImage))
            <img src="{{ $qrImage }}" alt="Código QR" width="600" class="rounded shadow">
        @else
            <p class="text-danger">No hay QR disponible para este tutor.</p>
        @endif
    </div>

    <p class="mt-3">Escanea el código QR para proceder con el pago.</p>

    <!-- Botón para volver a la página principal -->
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-4">Volver</a>
</div>
@endsection
