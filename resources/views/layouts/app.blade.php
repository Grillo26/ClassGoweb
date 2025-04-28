<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if( !empty(setting('_general.enable_rtl')) || !empty(session()->get('rtl')) ) dir="rtl" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $siteTitle        = setting('_general.site_name');
        @endphp
        <title>{{ $siteTitle }} {!! request()->is('messenger') ? ' | Messages' : (!empty($title) ? ' | ' . $title : '') !!}</title>
        <x-favicon />
        @vite([
            'public/css/bootstrap.min.css',
            'public/css/fonts.css',
            'public/css/icomoon/style.css',
            'public/css/select2.min.css',
        ])
        <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}?v={{ time() }}">
        @if( !empty(setting('_general.enable_rtl')) || !empty(session()->get('rtl')) )
            <link rel="stylesheet" type="text/css" href="{{ asset('css/rtl.css') }}?v={{ time() }}">
        @endif
        @stack('styles')
        <style>
            /* Select2 Custom Styles - Alineación Vertical */
            .select2-container--default .select2-selection--single {
                height: 48px; /* Mantener altura consistente */
                border: 1px solid #eee;
                border-radius: 4px;
                background-color: #fff;
                display: flex; /* Usar flexbox para centrar */
                align-items: center; /* Centrar verticalmente */
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: normal; /* Resetear line-height para que no interfiera con flex */
                padding-left: 15px;
                 padding-right: 25px; /* Espacio para la flecha */
                color: #1C1C1C;
                 width: 100%; /* Asegurar que ocupe el espacio */
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                 height: 100%; /* Que la flecha ocupe toda la altura */
                 position: absolute;
                 top: 0;
                 right: 10px;
                 width: 20px;
                 display: flex; /* Centrar el icono de la flecha */
                 align-items: center;
                 justify-content: center;
            }
            
            /* Ajustar icono de la flecha si es necesario (esto depende del icono específico) */
            .select2-container--default .select2-selection--single .select2-selection__arrow b {
                 margin-top: 0; /* Resetear margen si lo tenía */
            }

            /* --- Estilos anteriores sin cambios --- */
            .select2-container--default .select2-search--dropdown .select2-search__field {
                border: 1px solid #eee;
                border-radius: 4px;
                padding: 8px;
            }
            .select2-dropdown {
                border: 1px solid #eee;
                border-radius: 4px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .select2-results__option {
                padding: 8px 15px;
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #0d6efd; /* O el color primario de tu tema */
                color: white;
            }
            .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #6c757d;
            }
            .select2-container--default .select2-search--dropdown {
                padding: 10px;
            }
            .select2-container--default .select2-results__option[aria-selected=true] {
                background-color: #e9ecef;
            }
        </style>
        @livewire('livewire-ui-spotlight')
        @livewireStyles()
    </head>
    <body class="font-sans antialiased @if( !empty(setting('_general.enable_rtl')) || !empty(session()->get('rtl')) ) am-rtl @endif"
        x-data="{ isDragging: false }"
        x-on:dragover.prevent="isDragging = true"
        x-on:drop="isDragging = false">
        <div class="am-dashboardwrap">
            <livewire:pages.common.navigation />
            <div class="am-mainwrap">
                <livewire:header.header />
                <!-- Page Content -->
                <main class="am-main">
                    <div class="am-dashboard_box">
                        <div class="am-dashboard_box_wrap">
                            @yield('content')
                            {{ $slot ?? '' }}
                            @if (setting('_api.active_conference') == 'google_meet' && empty(isCalendarConnected(Auth::user())))
                                <div class="am-connect_google_calendar">
                                    <div class="am-connect_google_calendar_title">
                                        <figure>
                                            <img src="{{ asset('images/calendar.png') }}" alt="Image">
                                        </figure>
                                        <h4>{{ __('passwords.connect_google_calendar') }}</h4>
                                        <i class="am-icon-multiply-02" @click="jQuery('.am-connect_google_calendar').remove()"></i>
                                    </div>
                                    <p> {{ __('calendar.'.auth()->user()->role.'_calendar_alert_msg') }}</p>
                                    <a href="{{ route(auth()->user()->role.'.profile.account-settings') }}" class="am-btn">{{ __('general.connect') }}</a>
                                </div>
                            @endif   
                        </div>
                    </div>
                </main>
            </div>
            @if(session('impersonated_name'))
                <div class="am-impersonation-bar">
                    <span>{{ __('general.impersonating') }} <strong>{{ session('impersonated_name') }}</strong></span>
                    <a href="{{ route('exit-impersonate') }}" class="am-btn">{{ __('general.exit') }}</a>
                </div>
            @endif
        </div>
        <x-popups />
        @livewireScripts()
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/select2.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Inicializar Select2 en todos los elementos con la clase am-select2
                $('.am-select2').select2();
                
                // Reinicializar Select2 cuando Livewire actualice el DOM
                Livewire.on('initSelect2', params => {
                    $(params.target).select2();
                });
            });
        </script>
        @stack('scripts')
        @if(showAIWriter())
            <x-open_ai />
        @endif
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                Livewire.on('remove-cart', (event) => {
                    const currentRoute = '{{ request()->route()->getName() }}';

                    const { index, cartable_id, cartable_type } = event.params;
                    if (currentRoute != 'tutor-detail') {
                        fetch('/remove-cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ index, cartable_id, cartable_type })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const event = new CustomEvent('cart-updated', {
                                detail: {
                                    cart_data: data.cart_data,
                                    total: data.total,
                                    subTotal: data.subTotal,
                                    discount: data.discount,
                                    toggle_cart: data.toggle_cart
                                }
                            });
                            window.dispatchEvent(event);
                        } else {
                            console.error('Failed to update cart:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                    }
                });
            });
        </script>
    </body>
</html>
