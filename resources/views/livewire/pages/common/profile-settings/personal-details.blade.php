{{-- Contenedor principal del componente --}}
<div class="am-profile-setting">
    {{-- Título de la sección --}}
    @slot('title')
        {{ __('profile.personal_details') }}
    @endslot

    {{-- Incluye las pestañas de navegación --}}
    @include('livewire.pages.common.profile-settings.tabs')

    {{-- Contenedor principal de la información del usuario --}}
    <div class="am-userperinfo">
        {{-- Encabezado con título y descripción --}}
        <div class="am-title_wrap">
            <div class="am-title">
                <h2>{{ __('profile.personal_details') }}</h2>
                <p>{{ __('profile.personal_detail_desc') }}</p>
            </div>
        </div>

        {{-- Formulario principal --}}
        <form wire:submit="updateInfo" class="am-themeform am-themeform_personalinfo">
            @if($isLoading)
                @include('skeletons.personal-details')
            @else
                <fieldset>
                    {{-- Nombre completo --}}
                    <div class="form-group">
                        <x-input-label for="name" class="am-important" :value="__('profile.full_name')" />
                        <div class="form-group-two-wrap">
                            <div @class(['form-control_wrap', 'am-invalid' => $errors->has('first_name')])>
                                <x-text-input wire:model="first_name" placeholder="{{ __('profile.first_name') }}" type="text" />
                                <x-input-error field_name="first_name" />
                            </div>
                            <div @class(['form-control_wrap', 'am-invalid' => $errors->has('last_name')])>
                                <x-text-input wire:model="last_name" placeholder="{{ __('profile.last_name') }}" type="text" />
                                <x-input-error field_name="last_name" />
                            </div>
                        </div>
                    </div>

                    {{-- Correo electrónico --}}
                    <div class="form-group @error('email') am-invalid @enderror">
                        <x-input-label for="email" class="am-important" :value="__('general.email')" />
                        <x-text-input wire:model="email" disabled type="email" />
                        <x-input-error field_name="email" />
                    </div>

                    {{-- Número de teléfono --}}
                    <div class="form-group @error('phone_number') am-invalid @enderror">
                        <x-input-label for="phone_number" :value="__('general.phone_number')" />
                        <div class="form-control_wrap">
                            <x-text-input wire:model="phone_number" placeholder="{{ __('general.enter_phone_number') }}" type="text" />
                            <x-input-error field_name="phone_number" />
                        </div>
                    </div>

                    {{-- Género --}}
                    <div class="form-group @error('gender') am-invalid @enderror">
                        <x-input-label for="gender" class="am-important" :value="__('profile.gender')" />
                        <div class="am-radiowrap">
                            <div class="am-radio">
                                <input wire:model="gender" type="radio" id="male" name="gender" value="male">
                                <label for="male">{{ __('profile.male') }}</label>
                            </div>
                            <div class="am-radio">
                                <input wire:model="gender" type="radio" id="female" name="gender" value="female">
                                <label for="female">{{__('profile.female')}}</label>
                            </div>
                            <div class="am-radio">
                                <input wire:model="gender" type="radio" id="not_specified" name="gender" value="not_specified">
                                <label for="not_specified">{{__('profile.not_specified')}}</label>
                            </div>
                        </div>
                        <x-input-error field_name="gender" />
                    </div>

                    {{-- Idioma nativo --}}
                    <div class="form-group @error('native_language') am-invalid @enderror">
                        <x-input-label for="language" class="am-important" :value="__('profile.native_language')" />
                        <div class="form-group-two-wrap am-nativelang">
                            <span class="am-select" wire:ignore>
                                <select class="am-select2" data-searchable="true" id="native_language" data-wiremodel="native_language">
                                    <option value="">{{ __('profile.select_a_native_language') }}</option>
                                    @foreach ($languages as $id => $language)
                                        <option value="{{ $id }}" {{ $id == $this->native_language ? 'selected' : '' }}>{{ $language }}</option>
                                    @endforeach
                                </select>
                            </span>
                            <x-input-error field_name="native_language" />
                        </div>
                    </div>

                    {{-- Idiomas conocidos --}}
                    <div class="form-group am-knowlanguages @error('user_languages') am-invalid @enderror">
                        <x-input-label for="Languages" class="am-important" :value="__('profile.language')" />
                        <div class="form-group-two-wrap am-nativelang">
                            <div id="user_lang" wire:ignore>
                                <select class="languages am-select2" 
                                        id="user_languages" 
                                        multiple="multiple" 
                                        data-placeholder="{{ __('profile.language_placeholder') }}">
                                    @foreach ($languages as $id => $language)
                                        <option value="{{ $id }}" 
                                            {{ in_array($id, $user_languages) ? 'selected' : '' }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-input-error field_name="user_languages" />
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group @error('description') am-invalid @enderror">
                        <x-input-label for="introduction" class="am-important" :value="__('profile.description')" />
                        <div class="am-editor-wrapper">
                            <div class="am-custom-editor am-custom-textarea" wire:ignore>
                                <textarea id="profile_desc" class="form-control" placeholder="{{ __('profile.description_placeholder') }}" data-textarea="profile_desc">{{ $description }}</textarea>
                                <span class="characters-count"></span>
                            </div>
                            <x-input-error field_name="description" />
                        </div>
                    </div>

                    {{-- Foto de perfil --}}
                    <div class="form-group">
                        <x-input-label class="am-important" :value="__('profile.profile_photo')" />
                        <div class="am-uploadoption" x-data="{isUploading:false, isDragging:false}">
                            <div class="upload-section" x-data="{ isDragging: false }"
                                x-on:dragover.prevent="isDragging = true"
                                x-on:dragleave.prevent="isDragging = false"
                                x-on:drop.prevent="isDragging = false; $wire.upload('image', $event.dataTransfer.files[0])"
                                :class="{ 'dragging': isDragging }">
                                <input type="file" 
                                    x-on:change="$wire.upload('image', $event.target.files[0])"
                                    accept="image/*"
                                    class="hidden" 
                                    id="profileImage">
                                <label for="profileImage" class="cursor-pointer">
                                    <div class="text-center">
                                        <i class="fas fa-camera text-4xl mb-2"></i>
                                        <p class="text-sm text-gray-600">
                                            @if($isUploadingImage)
                                                <span class="text-primary">Subiendo imagen...</span>
                                            @else
                                                {{ __('profile.drag_drop_image') }}
                                            @endif
                                        </p>
                                    </div>
                                </label>
                            </div>

                            @if($image)
                                <div class="am-uploadedfile">
                                    <img src="{{ $image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile ? $image->temporaryUrl() : Storage::url($image) }}" alt="{{ $imageName }}">
                                    <span>{{ $imageName ?: basename($image) }}</span>
                                    <a href="#" wire:click.prevent="removeMedia('image')" class="am-delitem">
                                        <i class="am-icon-trash-02"></i>
                                    </a>
                                </div>
                            @endif
                            <x-input-error field_name="image" />
                        </div>
                    </div>

                    {{-- Video de introducción (solo para tutores) --}}
                    @role('tutor')
                        <div class="form-group">
                            <x-input-label for="intro_video" :value="__('profile.intro_video')" />
                            <div class="am-uploadoption" x-data="{isUploading:false, isDragging:false}">
                                <div class="upload-section" x-data="{ isDragging: false }"
                                    x-on:dragover.prevent="isDragging = true"
                                    x-on:dragleave.prevent="isDragging = false"
                                    x-on:drop.prevent="isDragging = false; $wire.upload('intro_video', $event.dataTransfer.files[0])"
                                    :class="{ 'dragging': isDragging }">
                                    <input type="file" 
                                        x-on:change="$wire.upload('intro_video', $event.target.files[0])"
                                        accept="{{ implode(',', array_map(fn($ext) => '.'.$ext, $allowVideoFileExt)) }}"
                                        class="hidden" 
                                        id="introVideo">
                                    <label for="introVideo" class="cursor-pointer">
                                        <div class="text-center">
                                            <i class="fas fa-video text-4xl mb-2"></i>
                                            <p class="text-sm text-gray-600">
                                                @if($isUploadingVideo)
                                                    <span class="text-primary">Subiendo video...</span>
                                                @else
                                                    {{ __('profile.drag_drop_video') }}
                                                @endif
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                @if($intro_video)
                                    <div class="am-uploadedfile">
                                        <a href="{{ $intro_video instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile ? $intro_video->temporaryUrl() : Storage::url($intro_video) }}" 
                                           data-vbtype="iframe" 
                                           data-gall="gall" 
                                           class="tu-themegallery tu-thumbnails_content">
                                            <figure>
                                                <img src="{{ asset('images/video.jpg') }}" alt="{{ __('profile.intro_video') }}">
                                            </figure>
                                            <i class="fas fa-play"></i>
                                        </a>
                                        <span>{{ $videoName ?: basename($intro_video) }}</span>
                                        <a href="#" wire:click.prevent="removeMedia('video')" class="am-delitem">
                                            <i class="am-icon-trash-02"></i>
                                        </a>
                                    </div>
                                @endif
                                <x-input-error field_name="intro_video" />
                            </div>
                        </div>
                    @endrole

                    {{-- Botones de acción --}}
                    <div class="form-group am-form-btns">
                        <span>{{ __('profile.latest_changes_the_live') }}</span>
                        <x-primary-button wire:loading.class="am-btn_disable" wire:target="updateInfo">
                            {{ __('general.save_update') }}
                        </x-primary-button>
                    </div>
                </fieldset>
            @endif
        </form>
    </div>
</div>

{{-- Estilos necesarios --}}
@push('styles')
    @vite([
        'public/css/croppie.css',
        'public/summernote/summernote-lite.min.css',
        'public/css/venobox.min.css',
    ])
@endpush

{{-- Scripts necesarios --}}
@push('scripts')
    <script defer src="{{ asset('js/croppie.min.js')}}"></script>
    <script defer src="{{ asset('js/venobox.min.js')}}"></script>
    <script defer src="{{ asset('summernote/summernote-lite.min.js')}}"></script>

    {{-- Script para Google Places --}}
    @if($enableGooglePlaces)
        <script>
            function initializePlaceApi() {
                const tutorAddress = document.getElementById('user_address');
                if (tutorAddress) {
                    tutorAddress.addEventListener('input', function(e) {
                        if (e.target.value == '') {
                            Livewire.dispatch('address-cleared');
                        }
                    });

                    if(typeof google != 'undefined' && typeof google.maps.places != 'undefined'){
                        const autocompleteTutor = new google.maps.places.Autocomplete(tutorAddress);
                        google.maps.event.addListener(autocompleteTutor, 'place_changed', function () {
                            const place = autocompleteTutor.getPlace();
                            const address = place.formatted_address;
                            const lat = place.geometry.location.lat();
                            const lng = place.geometry.location.lng();
                            
                            let countryCode = '';
                            place.address_components?.forEach((item) => {
                                if(item.types.includes('country')){
                                    countryCode = item.short_name;
                                }
                            });

                            Livewire.dispatch('address-updated', {
                                address: address,
                                lat: lat,
                                lng: lng,
                                countryCode: countryCode
                            });
                        });
                    }
                }
            }

            if(document.readyState === 'complete') {
                initializePlaceApi();
            } else {
                window.addEventListener('load', initializePlaceApi);
            }
        </script>
    @endif

    {{-- Script principal --}}
    <script type="text/javascript" data-navigate-once>
        const livewireComponentId = "{{ $this->getId() }}";
        let livewireComponent = null;
        
        // Función para inicializar cualquier select con Select2
        function initializeSingleSelect(selector, options, livewireModel, isMultiple = false) {
            const element = $(selector);
            if (element.length) {
                if (element.data('select2')) { 
                    element.select2('destroy'); 
                }
                
                try {
                    const defaultOptions = {
                        width: '100%',
                        placeholder: element.data('placeholder') || '',
                        allowClear: true,
                        language: { 
                            noResults: function() { return "{{ __('general.no_results_found') }}"; }, 
                            searching: function() { return "{{ __('general.searching') }}..."; } 
                        }
                    };

                    const selectOptions = {
                        ...defaultOptions,
                        ...options,
                        multiple: isMultiple,
                        closeOnSelect: !isMultiple
                    };

                    element.select2(selectOptions).on('change', function(e) {
                        if(livewireComponent) {
                            const value = $(this).val();
                            livewireComponent.set(livewireModel, value);
                        }
                    });
                    return true;
                } catch (error) {
                    console.error(`Select2 Init Error ${selector}:`, error);
                }
            }
            return false;
        }
        
        // Inicializa todos los selectores
        function initializeAllSelects() {
            if (!livewireComponent) { 
                try { 
                    livewireComponent = window.Livewire.find(livewireComponentId); 
                } catch(e) { 
                    console.error('Cannot find Livewire component:', e);
                    return; 
                }
            }
            
            // País
            initializeSingleSelect('#user_country', {
                dropdownParent: $('#user_country').closest('.form-group-half'), 
                placeholder: "{{ __('profile.select_a_country') }}"
            }, 'country');

            // Estado
            initializeSingleSelect('#country_state', {
                dropdownParent: $('#country_state').closest('.form-group-half'),
                placeholder: "{{ __('profile.select_a_state') }}"
            }, 'state');

            // Idioma Nativo
            initializeSingleSelect('#native_language', {
                dropdownParent: $('#native_language').closest('.am-nativelang'), 
                placeholder: "{{ __('profile.select_a_native_language') }}"
            }, 'native_language');
            
            // Idiomas Conocidos (Multiple)
            initializeSingleSelect('#user_languages', {
                dropdownParent: $('#user_languages').closest('.am-nativelang'),
                placeholder: "{{ __('profile.language_placeholder') }}",
                tags: false,
                tokenSeparators: [',', ' '],
                maximumSelectionLength: 5
            }, 'user_languages', true);
        }

        // Eventos de Livewire
        document.addEventListener('livewire:init', ({ component }) => {
            if (component && component.id === livewireComponentId) {
                livewireComponent = component;
                requestAnimationFrame(initializeAllSelects);
            }
        });
        
        document.addEventListener('livewire:update', ({ component }) => {
            if (component && component.id === livewireComponentId) {
                livewireComponent = component;
                initializeAllSelects();
            }
        });

        // Eventos de Google Places
        document.addEventListener('address-cleared', () => {
            if (livewireComponent) {
                livewireComponent.set('address', '');
            }
        });

        document.addEventListener('address-updated', (event) => {
            if (livewireComponent) {
                livewireComponent.set('address', event.detail.address);
                livewireComponent.set('lat', event.detail.lat);
                livewireComponent.set('long', event.detail.lng);
                livewireComponent.set('countryName', event.detail.countryCode);
            }
        });
    </script>
@endpush
