@extends('layouts.app')

@section('title', 'Tu código de invitación')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/promociones.css') }}">
@endpush


<div class="am-dbbox am-invoicelist_wrap">

    <div class="am-dbbox_content am-invoicelist">
        <div class="am-dbbox_title">
            @slot('title')
            {{ __('promociones') }}
            @endslot
            <h2>{{ __('Promociones') }}</h2>
        </div>
    
        <!--Aquí va todo-->
        <div class="promociones-container">
            <div class="cupones-container">
                <h1 class="cupones-title">Mis cupones</h1>

                <div class="cupon-lista">
                    <!-- Este contenido será extraído de BD -->
                    @for($i = 0; $i < 10; $i++) 
                        <div class="cupon-item">
                            <p class="cupon-fecha">Válido hasta el 30/07/2025</p>
                            <p class="cupon-principal">Cupón {{ $i + 1 }}</p>
                            <p class="cupon-detalle">Cupón válido para compras sobre Bs. 100</p>
                            <span class="cupon-info-icon">i</span>
                        </div>
                    @endfor
                </div>

            </div>
            
            <div class="inv-container">
                <div class="inv-box">
                    <h2 class="inv-title">Tu Código de Invitación</h2>
                    <div class="inv-code">47640403</div>

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

                    <button class="inv-copy-button">Copiar Código</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
