@extends('vistas.view.layouts.app')

@section('content')
    <section class="error-page-container">
        <div class="error-content">
            <div class="error-text-section">

                <h1 class="main-title">Oops!</h1>
                <h2 class="subtitle">¡Algo salió mal!</h2>
                <!-- SOLO EN MOBILE-->
                <img src="{{ asset('images/home/Tugotecnológico.webp')}}" 
                alt="Ilustración de error 404" 
                class="error-image mobile">
                <p class="description">No te preocupes, nuestro equipo está aquí para ayudarte.</p>
                
                
                <ul class="options-list">
                    <a href=" {{ route('preguntas') }}">
                        <li class="option-item">
                            <svg class="option-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 9V8a1 1 0 012 0v3a1 1 0 01-2 0zm1-5a1 1 0 100-2 1 1 0 000 2z" />
                            </svg>
                            <span>Preguntas y respuestas</span>
                        </li>
                    </a>
                    <a href="#">
                        <li class="option-item">
                            <svg class="option-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 00.117 1.139l1.83 2.135a1 1 0 00.99 0l6.5-5.5a1 1 0 000-1.5l-6.5-5.5a1 1 0 00-.99 0z" />
                            </svg>
                            <span>Contáctate con Soporte</span>
                        </li>
                    </a>
                    
                </ul>
                <div class="support-container">
                    <a href=" {{ route('home')}}">
                        <button class="support-button">
                            Pantalla Principal
                        </button>
                    </a>
                </div>
                
            </div>

            <img src="{{ asset('images/home/Tugotecnológico.webp')}}" 
                alt="Ilustración de error 404" 
                class="error-image escritorio">
        
            </div>
        </div>
    </section>
@endsection
