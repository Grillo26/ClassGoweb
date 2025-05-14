<div class="am-profile-setting">
    <!-- Pantalla de carga -->
    @if($isLoading)
    <div class="flex justify-center items-center h-64">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">{{ __('general.loading') }}...</span>
        </div>
    </div>
    @else
    <div class="am-userperinfo">
        <h2 class="text-2xl font-semibold mb-8 text-center text-gray-800">{{ __('profile.personal_details') }}</h2>

        <form wire:submit.prevent="updateInfo" class="am-themeform am-themeform_personalinfo">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Sección de información personal -->
                <div class="col-span-2">
                    <h3 style="color:white;" class="text-xl font-medium mb-4">{{ __('profile.basic_info') }}</h3>
                </div>

                <!-- Nombre -->
                <div>
                    <label style="color:white;" for="first_name" class="block text-sm font-medium text-gray-700">
                        {{ __('profile.first_name') }} <span class="text-red-500"></span>
                    </label>
                    <input type="text"
                        id="first_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        wire:model="first_name">
                    @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Apellido -->
                <div>
                    <label style="color:white;" for="last_name" class="block text-sm font-medium text-gray-700">
                        {{ __('profile.last_name') }} <span class="text-red-500"></span>
                    </label>
                    <input type="text"
                        id="last_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        wire:model="last_name">
                    @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email (deshabilitado) -->
                <div>
                    <label style="color:white;" for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('profile.email') }}
                    </label>
                    <input type="email"
                        id="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100"
                        wire:model="email"
                        disabled>
                </div>

                <!-- Teléfono -->
                <div>
                    <label style="color:white;" for="phone_number" class="block text-sm font-medium text-gray-700">
                        {{ __('profile.phone_number') }}
                    </label>
                    <input type="tel"
                        id="phone_number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        wire:model="phone_number">
                    @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Género -->
                <div>
                    <label style="color:white;" for="gender" class="block text-sm font-medium text-gray-700">
                        {{ __('profile.gender') }} <span class="text-red-500"></span>
                    </label>
                    <select id="gender"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        wire:model="gender">
                        <option value="masculino">{{ __('profile.male') }}</option>
                        <option value="femenino">{{ __('profile.female') }}</option>
                        <option value="no_especificado">{{ __('profile.not_specified') }}</option>
                    </select>
                    @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Tagline -->
                <div class="col-span-2">
                    <label style="color:white;" for="tagline" class="block text-sm font-medium text-gray-700">
                        {{ __('profile.tagline') }}
                    </label>
                    <input type="text"
                        id="tagline"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        wire:model="tagline"
                        placeholder="{{ __('profile.tagline_placeholder') }}">
                </div>

                <!-- Sección de Descripción -->
                <div>
                    <x-input-label for="introduction" style="color:white;" :value="__('profile.description')" />
                    <div class="am-editor-wrapper">
                        <textarea wire:model="description" class="form-control" placeholder="{{ __('profile.description') }}"></textarea>
                        <x-input-error field_name="description" />
                    </div>
                </div>

                <!-- Sección de Idiomas -->
                <!--   <div class="col-span-2 mt-6">
                    <h3 class="text-xl font-medium mb-4">{{ __('profile.languages') }}</h3>
                </div> -->

                <!-- Idioma nativo -->
                <div>
                    <label for="native_language" class="block text-sm font-medium text-white">
                        {{ __('profile.native_language') }} <span class="text-red-500"></span>
                    </label>
                    <select id="native_language"
                        class="choices-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        wire:model="native_language">
                        <option value="">seleccione un idioma</option>
                        @foreach($languages as $id => $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('native_language') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Otros idiomas -->
                <div>
                    <label for="languages" class="form-label text-white mb-3">
                        {{ __('profile.other_languages') }} <span class="text-danger"></span>
                    </label>
                    <div class="mb-3">
                        <div class="dropdown">
                            <button class="btn bg-white dropdown-toggle w-100 text-start" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                seleccion los idiomas
                            </button>
                            <ul style=" list-style-type: none;max-height: 300px; overflow-y: auto;" class="dropdown-menu w-100" aria-labelledby="languageDropdown">
                                @foreach($languages as $id => $name)
                                @if(!in_array($id, $user_languages) && $id != $native_language)
                                <li class="border-bottom">
                                    <div class="dropdown-item py-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                wire:model.live="selected_languages"
                                                value="{{ $id }}"
                                                id="lang{{ $id }}">
                                            <label class="form-check-label" for="lang{{ $id }}">
                                                {{ $name }}
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        <!-- <div class="form-text text-white-50 mt-2">
                            {{ __('profile.select_multiple') }}
                        </div> -->
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @if(count($user_languages) > 0)
                        @foreach($user_languages as $langId)
                        @if(isset($languages[$langId]))
                        <div class="badge bg-primary p-2 d-flex align-items-center" style="font-size: 0.9rem;">
                            <span class="text-white">{{ $languages[$langId] }}</span>
                            <button type="button"
                                class="btn-close btn-close-white ms-2"
                                style="font-size: 0.7rem;"
                                wire:click="removeLanguage({{ $langId }})">
                            </button>
                        </div>
                        @endif
                        @endforeach
                        @else
                        <div class="text-white-50">
                            {{ __('profile.no_languages_selected') }}
                        </div>
                        @endif
                    </div>
                    @error('user_languages')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Sección de Ubicación -->

                






                <!-- Foto de perfil -->
                <div class="col-12 mt-4 mt-md-5">
                    <div class="bg-white p-4 p-lg-5 rounded-3 shadow-sm border">
                        <h5 class="form-label text-black mb-4 fw-semibold fs-4">
                            {{ __('profile.profile_picture') }}
                        </h5>

                        <!-- Contenedor de imagen extra grande -->
                        <div class="d-flex justify-content-center mb-4">
                            <div class="overflow-hidden" style="width: 600px; height: 300px;">
                                @if($image && !$image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                <img src="{{ asset('storage/' . $image) }}"
                                    alt="Profile"
                                    class="img-fluid h-100 w-100 object-fit-cover border border-secondary p-2">
                                @elseif($image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                <img src="{{ $image->temporaryUrl() }}"
                                    alt="Profile"
                                    class="img-fluid h-100 w-100 object-fit-cover border border-secondary p-2">
                                @else
                                <div class="h-100 w-100 d-flex align-items-center justify-content-center bg-light border border-secondary">
                                    <i class="bi bi-person text-muted" style="font-size: 4rem;"></i>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Controles ampliados -->
                        <div class="text-center">
                            <div class="d-flex flex-column flex-md-row gap-3 justify-content-center mx-auto" style="max-width: 600px;">
                                <label for="image-upload" class="btn btn-primary btn-lg flex-grow-1 py-2">
                                    <i class="bi bi-cloud-arrow-up me-2"></i>
                                    {{ __('profile.upload_image') }}
                                    <input id="image-upload"
                                        type="file"
                                        class="d-none"
                                        wire:model="image"
                                        wire:loading.attr="disabled">
                                </label>

                                @if($image)
                                <button type="button"
                                    class="btn btn-outline-danger btn-lg flex-grow-1 py-2"
                                    wire:click="removeMedia('image')">
                                    <i class="bi bi-trash me-2"></i>
                                    {{ __('profile.remove') }}
                                </button>
                                @endif
                            </div>

                            <!-- Mensaje de carga mejorado -->
                            <div class="mt-4" wire:loading wire:target="image">
                                <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-primary mt-2 mb-0 fs-5">{{ __('profile.uploading') }}...</p>
                            </div>

                            @if($imageName)
                            <div class="mt-4 text-muted fs-5 mx-auto" style="max-width: 500px;">
                                <i class="bi bi-file-image-fill me-2"></i>
                                <span class="text-truncate d-inline-block" style="max-width: 80%; vertical-align: middle;">
                                    {{ $imageName }}
                                </span>
                            </div>
                            @endif

                            <div class="mt-4 text-muted fs-5">
                                {{ __('profile.allowed_formats') }}:
                                <span class="fw-bold">{{ implode(', ', $allowImgFileExt) }}</span>
                                <br>
                                <span class="fw-semibold">{{ __('profile.max') }} {{ $maxImageSize }}MB</span>
                            </div>

                            @error('image')
                            <div class="alert alert-danger mt-4 mb-0 fs-5 py-2">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>


            </div>

            <!-- Video de presentación -->
<div class="col-12 mt-4 mt-md-5">
    <div class="bg-white p-4 p-lg-5 rounded-3 shadow border">
        <div class="bg-white p-4 p-lg-5 rounded-3">
            <h5 class="form-label mb-4 fw-semibold fs-4 text-black">
                {{ __('profile.intro_video') }}
            </h5>

            <!-- Contenedor de video extra grande -->
            <div class="d-flex flex-column flex-lg-row gap-4 align-items-start">
                <!-- Preview del video ampliado -->
                <div class="flex-shrink-0">
                    <div class="position-relative bg-black bg-opacity-25 rounded-3 overflow-hidden border border-secondary" 
                         style="width: 300px; height: 200px;">
                        @if($intro_video instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                            <video class="w-100 h-100 object-fit-cover" controls>
                                <source src="{{ $intro_video->temporaryUrl() }}" type="video/mp4">
                            </video>
                        @elseif($intro_video)
                            <video class="w-100 h-100 object-fit-cover" controls>
                                <source src="{{ asset('storage/' . $intro_video) }}" type="video/mp4">
                            </video>
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-play-circle-fill text-white opacity-50" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Controles ampliados -->
                <div class="flex-grow-1 w-100">
                    <div class="d-flex flex-column flex-md-row gap-3 mb-3">
                        <label for="video-upload" class="btn btn-primary btn-lg flex-grow-1 py-2">
                            <i class="bi bi-cloud-arrow-up me-2"></i>
                            {{ __('profile.upload_video') }}
                            <input id="video-upload"
                                   type="file"
                                   class="d-none"
                                   wire:model="intro_video"
                                   wire:loading.attr="disabled">
                        </label>

                        @if($intro_video)
                            <button type="button"
                                    class="btn btn-outline-danger btn-lg flex-grow-1 py-2"
                                    wire:click="removeMedia('video')">
                                <i class="bi bi-trash me-2"></i>
                                {{ __('profile.remove') }}
                            </button>
                        @endif
                    </div>

                    <!-- Mensaje de carga mejorado -->
                    @if($isUploadingVideo)
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="text-primary fs-5">{{ __('profile.uploading') }}...</span>
                    </div>
                    @endif

                    @if($videoName)
                    <div class="mt-3 text-white fs-5">
                        <i class="bi bi-file-play-fill me-2"></i>
                        <span class="text-truncate d-inline-block" style="max-width: 80%">
                            {{ $videoName }}
                        </span>
                    </div>
                    @endif

                    <div class="mt-3 text-black fs-5">
                        {{ __('profile.allowed_formats') }}: 
                        <span class="fw-bold">{{ implode(', ', $allowVideoFileExt) }}</span>
                        <br>
                        <span class="fw-semibold">{{ __('profile.max') }} {{ $maxVideoSize }}MB</span>
                    </div>

                    @error('intro_video') 
                        <div class="alert alert-danger mt-3 mb-0 fs-5 py-2">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
    </div>

    <!-- Botones de acción -->
    {{-- Botones de acción --}}
    <div class="form-group am-form-btns">
        <span>{{ __('profile.latest_changes_the_live') }}</span>
        <x-primary-button type="submit" wire:loading.class="am-btn_disable" wire:target="updateInfo">
            {{ __('general.save_update') }}
        </x-primary-button>
    </div>

    </form>
</div>
@endif
</div>


@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('livewire:load', function() {
        const choices = new Choices('#native_language', {
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar idioma...',
            placeholder: true,
            placeholderValue: 'seleccione un idioma',
            searchResultLimit: 10,
            noResultsText: 'No se encontraron resultados',
            itemSelectText: 'Presione para seleccionar',
            loadingText: 'Cargando...',
            noChoicesText: 'No hay opciones disponibles',
            searchPlaceholderValue: 'Buscar...',
            shouldSort: false
        });

        // Actualizar el valor en Livewire cuando cambie la selección
        document.getElementById('native_language').addEventListener('change', function(e) {
            Livewire.emit('nativeLanguageUpdated', e.target.value);
        });
    });
   
     $( '#multiple-select-field' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );


</script>
@endpush