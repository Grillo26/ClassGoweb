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
                <div >
                    <x-input-label for="introduction" class="am-important" :value="__('profile.description')" />
                    <div class="am-editor-wrapper">
                        <textarea wire:model="description" class="form-control" placeholder="{{ __('profile.description_placeholder') }}"></textarea>
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
                        {{ __('profile.other_languages') }} <span class="text-danger">*</span>
                    </label>
                    <div class="mb-3">
                        <div class="dropdown">
                            <button class="btn bg-white dropdown-toggle w-100 text-start" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                             seleccion los idiomas
                            </button>
                            <ul style=" list-style-type: none;max-height: 300px; overflow-y: auto;" class="dropdown-menu w-100" aria-labelledby="languageDropdown" >
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
                        <div class="form-text text-white-50 mt-2">
                            {{ __('profile.select_multiple') }}
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
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
                    </div>
                    @error('user_languages') 
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Sección de Ubicación -->







               
                <!-- Foto de perfil -->
                <div class="col-span-2">
                    <div style="border-radius: 20px; margin:20px" class="bg-white p-5  border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-4">
                            {{ __('profile.profile_picture') }}
                        </label>
                        <div class="flex items-start space-x-6">
                            <!-- Preview de imagen -->
                            <div class="flex-shrink-0" style="max-width: 100px; max-height: 100px;">
                                @if($image && !$image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                <img src="{{ Storage::url($image) }}" alt="Profile" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 100%; max-height: 100%;">
                                @elseif($image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                <img src="{{ $image->temporaryUrl() }}" alt="Profile" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 100%; max-height: 100%;">
                                @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background-color: #f8f9fa; border: 2px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 100%; max-height: 100%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; color: #6c757d" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <!-- Controles para subir/eliminar -->
                            <div class="flex-grow">
                                <div class="flex flex-wrap items-center gap-3">
                                    <label for="image-upload" class="cursor-pointer px-5 py-2.5 bg-blue-500 text-black text-sm font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                                        {{ __('profile.upload_image') }}
                                        <input id="image-upload"
                                            type="file"
                                            class="sr-only"
                                            wire:model="image"
                                            wire:loading.attr="disabled">
                                    </label>

                                    @if($image)
                                    <button type="button"
                                        style="margin:5px; background-color:red; color:white; border-radius:5px;padding:5px;"
                                        wire:click="removeMedia('image')">
                                        {{ __('profile.remove') }}
                                    </button>
                                    @endif
                                </div>

                                <!-- Mensaje de carga -->
                                @if($isUploadingImage)
                                <div class="mt-3">
                                    <span class="text-sm text-blue-500">{{ __('profile.uploading') }}...</span>
                                </div>
                                @endif

                                @if($imageName)
                                <div class="mt-2 text-sm text-gray-500 truncate max-w-xs">
                                    {{ $imageName }}
                                </div>
                                @endif

                                <div class="mt-4 text-xs text-gray-900">
                                    {{ __('profile.allowed_formats') }}: {{ implode(', ', $allowImgFileExt) }} ({{ __('profile.max') }} {{ $maxImageSize }}MB)
                                </div>

                                @error('image') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video de presentación -->
                <div style="border-radius: 20px; margin:20px" class="bg-white p-5  border-gray-200">
                    <div class="bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 mb-4">
                            {{ __('profile.intro_video') }}
                        </label>
                        <div class="flex items-start space-x-6">
                            <!-- Preview del video -->
                            <div class="flex-shrink-0">
                                @if($intro_video && !$intro_video instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                <div class="w-[100px] h-[100px] bg-gray-700 rounded-lg flex items-center justify-center relative overflow-hidden border-2 border-gray-600 shadow-md">
                                    <video class="absolute inset-0 w-full h-full object-cover">
                                        <source src="{{ Storage::url($intro_video) }}" type="video/mp4">
                                    </video>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                @else
                                <div class="w-[100px] h-[100px] bg-gray-700 rounded-lg flex items-center justify-center border-2 border-gray-600 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <!-- Controles para subir/eliminar -->
                            <div class="flex-grow">
                                <div class="flex items-center space-x-4">
                                    <label for="video-upload" class="cursor-pointer px-6 py-3 bg-blue-600 text-black text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition duration-150">
                                        {{ __('profile.upload_video') }}
                                        <input id="video-upload"
                                            type="file"
                                            class="sr-only"
                                            wire:model="intro_video"
                                            wire:loading.attr="disabled">
                                    </label>

                                    @if($intro_video)
                                    <button type="button"
                                        class="px-6 py-3 border border-gray-600 rounded-lg text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition duration-150"
                                        wire:click="removeMedia('video')">
                                        {{ __('profile.remove') }}
                                    </button>
                                    @endif
                                </div>

                                <!-- Mensaje de carga -->
                                @if($isUploadingVideo)
                                <div class="mt-3">
                                    <span class="text-sm text-blue-400">{{ __('profile.uploading') }}...</span>
                                </div>
                                @endif

                                @if($videoName)
                                <div class="mt-2 text-sm text-gray-400">
                                    {{ $videoName }}
                                </div>
                                @endif

                                <div class="mt-2 text-sm text-gray-800">
                                    {{ __('profile.allowed_formats') }}: {{ implode(', ', $allowVideoFileExt) }} ({{ __('profile.max') }} {{ $maxVideoSize }}MB)
                                </div>

                                @error('intro_video') <span class="text-red-400 text-sm mt-2">{{ $message }}</span> @enderror
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
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
</script>
@endpush