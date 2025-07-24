<div class="am-profile-setting" style="background: rgb(243,244,246) ; padding:20px;">
    <!-- Pantalla de carga -->
    @include('livewire.pages.common.profile-settings.tabs')
    @if($isLoading)
    <div class="flex justify-center items-center h-64">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">{{ __('general.loading') }}...</span>
        </div>
    </div>
    @else
    @role('student')
    @include('livewire.pages.common.profile-settings.components.students')
    @else

    @include('livewire.pages.common.profile-settings.components.tutor')

    @endrole
    <div class="am-userperinfo">
        {{-- <h2 class="text-8xl fw-bold mb-8  text-white">{{ __('profile.personal_details') }}</h2><br>
        <form wire:submit.prevent="updateInfo" class="row g-4 am-themeform am-themeform_personalinfo"> --}}

            <!-- ...el resto de tu código sigue igual... -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Sección de información personal -->
                {{-- <div class="col-span-2">
                    <h3 style="color:white;" class="text-xl font-medium mb-4">{{ __('profile.basic_info') }}</h3>
                </div> --}}


                <!-- Nombre -->
                {{-- <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" for="first_name" class="form-label m-0">
                            {{ __('profile.first_name') }}
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <input type="text" id="first_name" class="form-control bg-white text-black"
                            wire:model="first_name">
                        @error('first_name') <span style="color:rgb(251,133,0); font-size: medium;">{{ $message
                            }}</span> @enderror
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div> --}}

                <!-- Apellido -->
                {{-- <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" for="last_name" class="form-label m-0">
                            {{ __('profile.last_name') }} <span class="text-red-500"></span>
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <input type="text" id="last_name" class="form-control bg-white text-black"
                            wire:model="last_name">
                        @error('last_name') <span style="color:rgb(251,133,0);font-size: medium;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div> --}}

                <!-- Email -->
                {{-- <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" for="email" class="form-label m-0">
                            {{ __('profile.email') }}
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <input type="email" id="email" class="form-control bg-white text-black " wire:model="email"
                            disabled>
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div> --}}

                <!-- Teléfono -->
                {{-- <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label style="color:white;" for="phone_number" class="form-label m-0">
                            {{ __('profile.phone_number') }}
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <input type="tel" id="phone_number" class="form-control bg-white text-black"
                            wire:model="phone_number">
                        @error('phone_number') <span style="color:rgb(251,133,0);font-size: medium;">{{ $message
                            }}</span> @enderror
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div> --}}

                {{-- genero --}}
                {{-- @include('livewire.pages.common.profile-settings.components.genero') --}}
                <!-- Sección de Descripción -->
                {{-- <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label for="introduction" style="color:white;" class="form-label m-0">
                            {{ __('profile.description') }}
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="am-editor-wrapper">
                            <textarea wire:model="description" class="form-control"
                                placeholder="{{ __('profile.description') }}"></textarea>
                            <x-input-error field_name="description" />
                        </div>
                    </div>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>

                </div>
                --}}
                <!-- Idioma nativo -->
                {{-- <div class="row align-items-center mb-4">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label for="native_language" class="form-label m-0 text-white">
                            {{ __('profile.native_language') }} <span class="text-red-500"></span>
                        </label>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="position-relative" style="max-width: 400px;">
                            <div class="custom-dropdown" tabindex="0">
                                <div class="custom-dropdown-toggle bg-white w-100 text-start p-2 rounded"
                                    onclick="this.parentNode.classList.toggle('open')" id="nativeDropdownLabel">
                                    {{ $native_language ? __('lenguajes.' . $native_language) : __('Selecciona un
                                    idioma') }}
                                </div>
                                <div class="custom-dropdown-menu bg-white border rounded shadow-sm mt-1 p-2"
                                    style="display:none; max-height: 300px; overflow-y: auto; position: absolute; width: 100%; z-index: 10;">
                                    <input type="text" class="form-control mb-2" placeholder="Buscar idioma..."
                                        onkeyup="filterNativeLanguage(this)">
                                    <div id="native-languages-list">
                                        <div class="form-check px-3 py-2">
                                            <input class="form-check-input" type="radio" name="native_language"
                                                wire:model="native_language" value="" id="lang0"
                                                onchange="closeNativeDropdown(this)">
                                            <label class="form-check-label" for="lang0">seleccione un idioma</label>
                                        </div>
                                        @foreach($languages as $id => $name)
                                        <div class="form-check px-3 py-2">
                                            <input class="form-check-input" type="radio" name="native_language"
                                                wire:model="native_language" value="{{ $name }}" id="lang{{ $id }}"
                                                onchange="closeNativeDropdown(this)">
                                            <label class="form-check-label" for="lang{{ $id }}">{{ __('lenguajes.' .
                                                $name) }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <style>
                                .custom-dropdown.open .custom-dropdown-menu {
                                    display: block !important;
                                }

                                .custom-dropdown-toggle {
                                    cursor: pointer;
                                }

                                .custom-dropdown:focus .custom-dropdown-menu {
                                    display: block !important;
                                }
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
                            @error('native_language') <span style="color:rgb(251,133,0);font-size: medium;">{{ $message
                                }}</span> @enderror
                        </div>
                    </div>
                    <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>
                </div> --}}

                <!-- Otros idiomas -->
                {{-- <div class="mb-3 position-relative" style="max-width: 400px;">
                    <label for="languages" class="form-label text-white mb-3">
                        {{ __('profile.other_languages') }} <span class="text-danger"></span>
                    </label>
                    <div class="custom-dropdown" tabindex="0">
                        <div class="custom-dropdown-toggle bg-white w-100 text-start p-2 rounded"
                            onclick="this.parentNode.classList.toggle('open')">
                            {{ __('Selecciona los idiomas') }}
                        </div>
                        <div class="p-2 custom-dropdown-menu bg-white border rounded shadow-sm mt-1"
                            style="display:none; max-height: 300px; overflow-y: auto; position: absolute; width: 100%; z-index: 10;">
                            <input type="text" class="form-control mb-2" placeholder="Buscar idioma..."
                                onkeyup="filterLanguages(this)">
                            <div id="languages-list">
                                @foreach($languages as $id => $name)
                                @if(!in_array($id, $user_languages) && $id != $native_language)
                                <div class="form-check px-3 py-2">
                                    <input class="form-check-input" type="checkbox" wire:model.live="selected_languages"
                                        value="{{ $id }}" id="lang{{ $id }}">
                                    <label class="form-check-label" for="lang{{ $id }}">{{ __('lenguajes.' . $name)
                                        }}</label>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <style>
                        .custom-dropdown.open .custom-dropdown-menu {
                            display: block !important;
                        }

                        .custom-dropdown-toggle {
                            cursor: pointer;
                        }

                        .custom-dropdown:focus .custom-dropdown-menu {
                            display: block !important;
                        }
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
                </div> --}}

                {{-- <div class="d-flex flex-wrap gap-2 mt-3">
                    @if(count($user_languages) > 0)
                    @foreach($user_languages as $langId)
                    @if(isset($languages[$langId]))
                    <div class="badge bg-primary p-2 d-flex align-items-center" style="font-size: 0.9rem;">
                        <span class="text-white">{{ __('lenguajes.' . $languages[$langId]) }}</span>
                        <button type="button" class="btn-close btn-close-white ms-2" style="font-size: 0.7rem;"
                            wire:click="removeLanguage({{ $langId }})">
                        </button>
                    </div>
                    @endif
                    @endforeach
                    @else
                    <div class="text-white-50">
                        {{ __('lenguajes Selecionados') }}
                    </div>
                    @endif
                </div>
                --}}
                @error('user_languages')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
                <div class="border-bottom border-1 border-gray-200 w-100 mt-2"></div>
                <!-- Foto de perfil -->
            </div>
    </div>
    </form>
</div>
@endif
</div>
@push('styles')

@endpush
@push('scripts')
<script>
    function validateVideoSize(input) {
    const maxMB = {{ $maxVideoSize ?? 10 }};
  
    const alertDiv = document.getElementById('video-size-alert');
    if (input.files && input.files[0]) {
         // console.log('Validating video size, max allowed:', maxMB, 'MB');
        const file = input.files[0];
        if (file.size > maxMB * 1024 *1024 ) {
            alertDiv.textContent = 'El video no se pudo cargar. El archivo supera el tamaño máximo permitido de ' + maxMB + 'MB.';
            alertDiv.classList.remove('d-none');
            input.value = '';
        } else {
            alertDiv.classList.add('d-none');
        }
    }
}
</script>
@endpush