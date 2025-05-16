<div class="am-profile-setting">
    <!-- Pantalla de carga -->

    @include('livewire.pages.common.profile-settings.tabs')
    @if($isLoading)
    <div class="flex justify-center items-center h-64">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">{{ __('general.loading') }}...</span>
        </div>
    </div>
    @else
    <div class="am-userperinfo">
        <h2 class="text-8xl fw-bold mb-8  text-white">{{ __('profile.personal_details') }}</h2><br>
        <form wire:submit.prevent="updateInfo" class="row g-4 am-themeform am-themeform_personalinfo">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Sección de información personal -->
                <div class="col-span-2">
                    <h3 style="color:white;" class="text-xl font-medium mb-4">{{ __('profile.basic_info') }}</h3>
                </div>

                <!-- Nombre -->
                <!-- Nombre -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" for="first_name" class="form-label m-0">
                            {{ __('profile.first_name') }} <span class="text-red-500"></span>
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <input type="text"
                            id="first_name"
                            class="form-control bg-white text-black"
                            wire:model="first_name">
                        @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div>

                <!-- Apellido -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" for="last_name" class="form-label m-0">
                            {{ __('profile.last_name') }} <span class="text-red-500"></span>
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <input type="text"
                            id="last_name"
                            class="form-control bg-white text-black"
                            wire:model="last_name">
                        @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div>

                <!-- Email -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" for="email" class="form-label m-0">
                            {{ __('profile.email') }}
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <input type="email"
                            id="email"
                            class="form-control bg-white text-black "
                            wire:model="email"
                            disabled>
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div>

                <!-- Teléfono -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" for="phone_number" class="form-label m-0">
                            {{ __('profile.phone_number') }}
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <input type="tel"
                            id="phone_number"
                            class="form-control bg-white text-black"
                            wire:model="phone_number">
                        @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>


                </div>


                {{-- genero   --}}
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" class="form-label mb-0 me-3">
                            {{ __('profile.gender') }} <span class="text-red-500"></span>
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="d-flex align-items-center gap-3 flex-wrap ps-0 ms-0">
                            <div class="form-check form-check-inline mb-0 ms-0 ps-0">
                                <input class="form-check-input" type="radio" name="gender" id="gender-male" value="1" wire:model="gender">
                                <label class="form-check-label text-white" for="gender-male">
                                    {{ __('profile.male') }}
                                </label>
                            </div>
                            <div class="form-check form-check-inline mb-0 ms-0 ps-0">
                                <input class="form-check-input" type="radio" name="gender" id="gender-female" value="2" wire:model="gender">
                                <label class="form-check-label text-white" for="gender-female">
                                    {{ __('profile.female') }}
                                </label>
                            </div>
                            <div class="form-check form-check-inline mb-0 ms-0 ps-0">
                                <input class="form-check-input" type="radio" name="gender" id="gender-unspecified" value="3" wire:model="gender">
                                <label class="form-check-label text-white" for="gender-unspecified">
                                    {{ __('profile.not_specified') }}
                                </label>
                            </div>
                        </div>
                        @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div>

                <!-- Tagline -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" for="tagline" class="form-label m-0">
                            {{ __('profile.tagline') }}
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <input type="text"
                            id="tagline"
                            class="form-control bg-white text-black"
                            wire:model="tagline"
                            placeholder="{{ __('profile.tagline_placeholder') }}">
                    </div>
                    @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div>

                <!-- Sección de Descripción -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label for="introduction" style="color:white;" class="form-label m-0">
                            {{ __('profile.description') }}
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="am-editor-wrapper">
                            <textarea wire:model="description" class="form-control" placeholder="{{ __('profile.description') }}"></textarea>
                            <x-input-error field_name="description" />
                        </div>
                    </div>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div>

                <!-- Idioma nativo -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label for="native_language" class="form-label m-0 text-white">
                            {{ __('profile.native_language') }} <span class="text-red-500"></span>
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="position-relative" style="max-width: 400px;">
                            <div class="custom-dropdown" tabindex="0">
                                <div class="custom-dropdown-toggle bg-white w-100 text-start p-2 rounded" onclick="this.parentNode.classList.toggle('open')" id="nativeDropdownLabel">
                                    {{ $native_language ? $native_language : __('Selecciona un idioma') }}
                                </div>
                                <div class="custom-dropdown-menu bg-white border rounded shadow-sm mt-1 p-2" style="display:none; max-height: 300px; overflow-y: auto; position: absolute; width: 100%; z-index: 10;">
                                    <input type="text" class="form-control mb-2" placeholder="Buscar idioma..." onkeyup="filterNativeLanguage(this)">
                                    <div id="native-languages-list">
                                        <div class="form-check px-3 py-2">
                                            <input class="form-check-input" type="radio" name="native_language" wire:model="native_language" value="" id="lang0" onchange="closeNativeDropdown(this)">
                                            <label class="form-check-label" for="lang0">seleccione un idioma</label>
                                        </div>
                                        @foreach($languages as $id => $name)
                                        <div class="form-check px-3 py-2">
                                            <input class="form-check-input" type="radio" name="native_language" wire:model="native_language" value="{{ $name }}" id="lang{{ $id }}" onchange="closeNativeDropdown(this)">
                                            <label class="form-check-label" for="lang{{ $id }}">{{ $name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <style>
                                .custom-dropdown.open .custom-dropdown-menu { display: block !important; }
                                .custom-dropdown-toggle { cursor: pointer; }
                                .custom-dropdown:focus .custom-dropdown-menu { display: block !important; }
                            </style>
                            <script>
                                window.filterNativeLanguage = function(input) {
                                    var filter = input.value.toLowerCase();
                                    var list = input.parentNode.querySelectorAll('#native-languages-list .form-check');
                                    list.forEach(function(item) {
                                        var label = item.querySelector('label').innerText.toLowerCase();
                                        item.style.display = label.includes(filter) ? '' : 'none';
                                    });
                                }
                                window.closeNativeDropdown = function(input) {
                                    var dropdown = input.closest('.custom-dropdown');
                                    dropdown.classList.remove('open');
                                    // Cambia el label del toggle visualmente (Livewire lo actualizará en el siguiente render)
                                    var label = dropdown.querySelector('.custom-dropdown-toggle');
                                    var selected = dropdown.querySelector('input[type=radio]:checked + label');
                                    if(selected) {
                                        label.textContent = selected.textContent;
                                    }
                                }
                                document.addEventListener('click', function(e) {
                                    document.querySelectorAll('.custom-dropdown').forEach(function(drop) {
                                        if (!drop.contains(e.target)) drop.classList.remove('open');
                                    });
                                });
                            </script>
                            @error('native_language') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>
                </div>





                
                
                <!-- Otros idiomas -->
                <div class="mb-3 position-relative" style="max-width: 400px;">
                    <label for="languages" class="form-label text-white mb-3">
                        {{ __('profile.other_languages') }} <span class="text-danger"></span>
                    </label>
                    <div class="custom-dropdown" tabindex="0">
                        <div class="custom-dropdown-toggle bg-white w-100 text-start p-2 rounded" onclick="this.parentNode.classList.toggle('open')">
                            {{ __('Selecciona los idiomas') }}
                        </div>
                        <div class="p-2 custom-dropdown-menu bg-white border rounded shadow-sm mt-1" style="display:none; max-height: 300px; overflow-y: auto; position: absolute; width: 100%; z-index: 10;">
                            <input type="text" class="form-control mb-2" placeholder="Buscar idioma..." onkeyup="filterLanguages(this)">
                            <div id="languages-list">
                                @foreach($languages as $id => $name)
                                    @if(!in_array($id, $user_languages) && $id != $native_language)
                                    <div class="form-check px-3 py-2">
                                        <input class="form-check-input" type="checkbox" wire:model.live="selected_languages" value="{{ $id }}" id="lang{{ $id }}">
                                        <label class="form-check-label" for="lang{{ $id }}">{{ $name }}</label>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <style>
                        .custom-dropdown.open .custom-dropdown-menu { display: block !important; }
                        .custom-dropdown-toggle { cursor: pointer; }
                        .custom-dropdown:focus .custom-dropdown-menu { display: block !important; }
                    </style>
                    <script>
                        document.addEventListener('click', function(e) {
                            document.querySelectorAll('.custom-dropdown').forEach(function(drop) {
                                if (!drop.contains(e.target)) drop.classList.remove('open');
                            });
                        });
                        window.filterLanguages = function(input) {
                            var filter = input.value.toLowerCase();
                            var list = input.parentNode.querySelectorAll('#languages-list .form-check');
                            list.forEach(function(item) {
                                var label = item.querySelector('label').innerText.toLowerCase();
                                item.style.display = label.includes(filter) ? '' : 'none';
                            });
                        }
                    </script>
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
                <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>





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

<script>
  console.log(typeof bootstrap);

   
</script>
@endpush