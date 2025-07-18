@extends('layouts.app')

@section('title', 'Tu código de invitación')

@section('content')

@push('styles')
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="{{ asset('css/promociones.css') }}">

    <style>
        :root {
            --primary-color: #023047;
            --secondary-color: #219EBC;
            --secondary-color2: #CDD6DA;
            --tertiary-color: #8ECAE6;
            --tertiary-color2: #FB8500;
            --bg-color: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
        }
        .bg-primary { background-color: var(--primary-color); }
        .text-primary { color: var(--primary-color); }
        .text-secondary { color: var(--secondary-color); }
        .bg-secondary { background-color: var(--secondary-color); }
        .bg-tertiary-orange { background-color: var(--tertiary-color2); }
        .text-tertiary-orange { color: var(--tertiary-color2); }
        .border-tertiary-orange { border-color: var(--tertiary-color2); }
        .ring-secondary {
            --tw-ring-color: var(--secondary-color);
        }
        .sidebar-link.active {
            background-color: var(--secondary-color);
            color: white;
            font-weight: 600;
        }
        .sidebar-link.active svg {
            stroke: white;
        }
    </style>
@endpush

<main class="flex-1 p-6 lg:p-8 overflow-y-auto">
	<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
		<!-- Columna de Cupones -->
		<div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-md">
			<h3 class="text-xl font-bold mb-4 cupon-text-cupones">Mis cupones</h3>
			<div class="space-y-4">
                @if($cupones->isEmpty())
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
					    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
					    <p class="mt-2 text-sm text-gray-500">No tienes cupones activos en este momento.</p>
				    </div>
                
                @else
                    <div class="cupon-lista">
                        @foreach($cupones as $cupon)
                            @php
                                $vencido = $cupon->fecha_caducidad && $cupon->fecha_caducidad < now();
                                $inactivo = isset($cupon->estado) && $cupon->estado === 'inactivo';
                                $canjeado = isset($cupon->pivot->cantidad) && $cupon->pivot->cantidad == 0;
                            @endphp
                            <!-- Cupón  -->
                            <div class="{{ $vencido ? 'cupon-vencido' : '' }}">
                                <div class="cupon-item border-2 border-dashed border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row justify-between items-center sm:items-center gap-4">
                                    <div class="item-text">
                                        <p class="text-xs text-gray-500">Cupón válido hasta el 
                                            {{ $cupon->fecha_caducidad ? \Carbon\Carbon::parse($cupon->fecha_caducidad)->format('d/m/Y') : 'Sin fecha' }}
                                            @if($vencido)
                                                <span>(Vencido)</span>
                                            @endif</p>
                                        <h4 class="font-bold text-lg cupon-text">Obtubiste un descuento del {{ $cupon->descuento }}%</h4>
                                        <p class="text-sm text-gray-600">en tu próxima tutoría - Cantidad: {{ $cupon->pivot->cantidad}}</p>
                                    </div>
                                    @if($canjeado)
                                        <span class="bg-gray-200 text-gray-800 font-semibold px-4 py-1 rounded-full text-sm">Canjeado</span>
                                    @elseif($inactivo)
                                        <span class="bg-red-100 text-red-800 font-semibold px-4 py-1 rounded-full text-sm">Inactivo</span>
                                    @elseif($vencido)
                                        <span class="bg-red-100 text-red-800 font-semibold px-4 py-1 rounded-full text-sm">Vencido</span>
                                    @else
                                        <a href="{{ route('find-tutors')}}"><button class="button-cupon text-white font-semibold px-6 py-2 rounded-lg hover:opacity-90 transition-all">Usar</button></a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
				
			</div>
		</div>

		<!-- Columna de Código de Invitación -->
		<div class="card-fondo text-white p-6 rounded-2xl shadow-lg flex flex-col items-center text-center">
			<h3 class="text-xl font-bold">Tu Código de Invitación</h3>
			<p class="text-tertiary-color mt-1 text-sm">¡Comparte y obtén descuentos!</p>
			<div class="my-6">
				<div id="inv-code" class="text-4xl font-extrabold tracking-widest bg-white/20 border-2 border-dashed border-tertiary-color p-4 rounded-lg {{ isset($codigo) && $codigo->estado !== 'activo' ? 'inactivo' : '' }}">
					{{ $codigo->codigo ?? 'Código Vencido' }}
				</div>
			</div>
            
			<div class="w-full space-y-3">
				<button id="btnCopiar" type="button" class="w-full bg-white/90 text-primary font-bold py-3 rounded-lg hover:bg-white transition-all">Copiar Código</button>
				<button id="compartir-button" type="button" class="w-full bg-tertiary-orange font-bold py-3 rounded-lg hover:opacity-90 transition-all">Compartir</button>
				<x-modal-compartir />
			</div>
            <div id="copy-feedback" class="pt-3 transition-opacity" style="display:none;">¡Copiado!</div>
		</div>
	</div>
    
</main>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btnCopiar');
    const codigo = document.getElementById('inv-code');
    const feedback = document.getElementById('copy-feedback');

    if(btn && codigo && feedback) {
        btn.addEventListener('click', () => {
            const texto = codigo.textContent.trim();
            navigator.clipboard.writeText(texto).then(() => {
                feedback.style.display = 'block';
                feedback.style.opacity = '1';
                setTimeout(() => {
                    feedback.style.opacity = '0';
                    setTimeout(() => {
                        feedback.style.display = 'none';
                        feedback.style.opacity = '1';
                    }, 500);
                }, 2000);
            });
        });
    }

    // Modal compartir
    const abrir = document.getElementById('compartir-button');
    const modal = document.getElementById('modalCompartir');
    const cerrar = document.getElementById('cerrarModal');

    if(abrir && modal && cerrar) {
        abrir.addEventListener('click', () => {
            modal.style.display = 'flex';
        });
        cerrar.addEventListener('click', () => {
            modal.style.display = 'none';
        });
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
});
</script>
@endpush

@endsection




