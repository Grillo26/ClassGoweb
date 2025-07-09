@extends('layouts.app')

@section('title', 'Tu código de invitación')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/promociones.css') }}">
<link rel="stylesheet" href="{{ asset('css/modal-compartir.css') }}">

@endpush

<div class="am-dbbox am-invoicelist_wrap">

    <div class="am-dbbox_content am-invoicelist">

    
        <!--Aquí va todo-->
        <div class="promociones-container">
            <div class="cupones-container">
                <h1 class="cupones-title">Mis cupones</h1>
                @if($cupones->isEmpty())
                    <p class="text-gray-500">No tienes cupones.</p>
                @else
                    <div class="cupon-lista">
                        @foreach($cupones as $cupon)
                            @php
                                $vencido = $cupon->fecha_caducidad && $cupon->fecha_caducidad < now();
                                $inactivo = isset($cupon->estado) && $cupon->estado === 'inactivo';
                                $canjeado = isset($cupon->pivot->cantidad) && $cupon->pivot->cantidad == 0;
                            @endphp
                            <div class="cupon-item {{ $vencido ? 'cupon-vencido' : '' }}">
                                <div class="cupon-text">
                                    <p class="cupon-fecha">
                                        Cupón válido hasta el 
                                        {{ $cupon->fecha_caducidad ? \Carbon\Carbon::parse($cupon->fecha_caducidad)->format('d/m/Y') : 'Sin fecha' }}
                                        @if($vencido)
                                            <span>(Vencido)</span>
                                        @endif
                                    </p>
                                    <p class="cupon-principal">Obtubiste un descuento del {{ $cupon->descuento }}%</p>
                                    <p class="cupon-detalle">en tu próxima tutoría - Cantidad: {{ $cupon->pivot->cantidad}}</p>
                                </div>
                                <div class="cupon-accion">
                                    @if($canjeado)
                                        <button class="cupon-usar-btn-disable" disabled>Canjeado</button>
                                    @elseif($inactivo)
                                        <button class="cupon-usar-btn-disable" disabled>Inactivo</button>
                                    @elseif($vencido)
                                        <button class="cupon-usar-btn-disable" disabled>Vencido</button>
                                    @else
                                        <a href="{{ route('find-tutors')}}"><button class="cupon-usar-btn">Usar</button></a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="inv-container">
    <div class="inv-box">
        <h2 class="inv-title">Tu Código de Invitación</h2>
        <div class="inv-code{{ isset($codigo) && $codigo->estado !== 'activo' ? ' inactivo' : '' }}" id="inv-code">
            {{ $codigo->codigo ?? 'Código Vencido' }}
        </div>

        <div class="inv-steps">
            <div class="inv-step">
                <span class="inv-badge">1</span>
                <p>Copia el código con el botón.</p>
            </div>
            <div class="inv-step">
                <span class="inv-badge">2</span>
                <p>Obten descuento al compartir </p>
            </div>
        </div>
        <div class="buttons-card-inv">
            <button class="inv-copy-button" id="btnCopiar"
                @if(!isset($codigo) || $codigo->estado !== 'activo') disabled @endif>Copiar Código</button>
            <button class="compartir-button" id="compartir-button"
                @if(!isset($codigo) || $codigo->estado !== 'activo') disabled @endif>Compartir</button>
            <x-modal-compartir />
        </div>

        <!-- Mensaje flotante -->
        <div id="mensajeCopiado" class="inv-toast">
            {{ (isset($codigo) && $codigo->estado !== 'activo') ? 'Código inactivo' : 'Código copiado' }}
        </div>
    </div>
</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btnCopiar');
    const codigo = document.getElementById('inv-code');
    const mensaje = document.getElementById('mensajeCopiado');

    btn.addEventListener('click', () => {
        // Crear elemento temporal
        const texto = codigo.textContent.trim();
        navigator.clipboard.writeText(texto).then(() => {
            // Mostrar mensaje
            mensaje.style.display = 'block';
            mensaje.style.opacity = '1';

            // Ocultar después de 3 segundos
            setTimeout(() => {
                mensaje.style.opacity = '0';
                setTimeout(() => {
                    mensaje.style.display = 'none';
                }, 500);
            }, 2000);
        });
    });
});


document.addEventListener('DOMContentLoaded', () => {
    const abrir = document.getElementById('compartir-button');
    const modal = document.getElementById('modalCompartir');
    const cerrar = document.getElementById('cerrarModal');

    abrir.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    cerrar.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Cerrar si hacen clic fuera del modal-box
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>


@endpush

@endsection




