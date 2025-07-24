<!-- Datos personales -->
<div class="tutor-profile-section">
    <form wire:submit.prevent="updateInfo" class="tutor-profile-section row g-4 am-themeform am-themeform_personalinfo">
        <div class="tutor-profile-data-card">
            <h2 class="tutor-profile-title">Detalles personales</h2>
            <p class="tutor-profile-sub">Proporciona información básica para completar su perfil.</p>
            <div class="tutor-profile-grid">
                <div class="tutor-profile-field">
                    <label>Nombre</label>
                    <input type="text" class="tutor-profile-input" wire:model="first_name">
                    @error('first_name') <span style="color:rgb(251,133,0); font-size: medium;">{{ $message
                        }}</span>
                    @enderror
                </div>
                <div class="tutor-profile-field">
                    <label>Apellido</label>
                    <input type="text" class="tutor-profile-input" wire:model="last_name">
                    @error('last_name') <span style="color:rgb(251,133,0);font-size: medium;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="tutor-profile-field">
                    <label>Email</label>
                    <input type="email" class="tutor-profile-input" wire:model="email" disabled>
                    @error('email') <span style="color:rgb(251,133,0);font-size: medium;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="tutor-profile-field">
                    <label>Phone number</label>
                    <input type="text" class="tutor-profile-input" wire:model="phone_number">
                    @error('phone_number') <span style="color:rgb(251,133,0);font-size: medium;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @include('livewire.pages.common.profile-settings.components.genero')
            <div class="tutor-profile-field">
                <label>Una breve introducción</label>
                <textarea class="tutor-profile-input-textarea" rows="3" wire:model="description"></textarea>
            </div>
            <div class="tutor-profile-grid">

                {{-- lengua nativa --}}
                <div class="tutor-profile-field" style="margin-bottom: 1.5rem;">
                    <label for="native_language" class="form-label m-2 text-black" style="margin-bottom: 0.5rem;">
                        {{ __('profile.native_language') }} <span class="text-red-500"></span>
                    </label>
                    <div class="modern-dropdown" tabindex="0">
                        <div class="modern-dropdown-toggle" onclick="toggleModernDropdown(this)"
                            id="nativeDropdownLabel">
                            <span class="{{ $native_language ? '' : 'modern-dropdown-placeholder' }}">
                                {{ $native_language ? __('lenguajes.' . $native_language) : __('Selecciona un idioma')
                                }}
                            </span>
                            <span class="modern-dropdown-arrow"></span>
                        </div>
                        <div class="modern-dropdown-menu">
                            <div class="modern-dropdown-search">
                                <input type="text" placeholder="Buscar idioma..." onkeyup="filterModernLanguage(this)">
                            </div>
                            <div class="modern-dropdown-options" id="native-languages-list">
                                <div class="modern-dropdown-option" onclick="document.getElementById('lang0').click()">
                                    <input type="radio" name="native_language" wire:model="native_language" value=""
                                        id="lang0" onchange="selectModernOption(this)">
                                    <label for="lang0" style="width:100%;cursor:pointer;">Seleccione un idioma</label>
                                </div>
                                @foreach($languages as $id => $name)
                                <div class="modern-dropdown-option {{ $native_language === $name ? 'selected' : '' }}"
                                    onclick="document.getElementById('lang{{ $id }}').click()">
                                    <input type="radio" name="native_language" wire:model="native_language"
                                        value="{{ $name }}" id="lang{{ $id }}" onchange="selectModernOption(this)">
                                    <label for="lang{{ $id }}" style="width:100%;cursor:pointer;">{{ __('lenguajes.' .
                                        $name) }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('native_language')
                    <span style="color: #ef4444; font-size: 14px; margin-top: 4px; display: block;">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                {{-- idiomas adicionales --}}
                <div class="tutor-profile-field" style="margin-bottom: 1.5rem;">
                    <label for="languages" class="form-label m-2 text-black" style="margin-bottom: 0.5rem;">
                        {{ __('profile.other_languages') }} <span class="text-danger"></span>
                    </label>
                    <div class="modern-dropdown" tabindex="0">
                        <div class="modern-dropdown-toggle" onclick="toggleModernDropdown(this)">
                            <span class="modern-dropdown-placeholder">{{ __('Selecciona los idiomas') }}</span>
                            <span class="modern-dropdown-arrow"></span>
                        </div>
                        <div class="modern-dropdown-menu">
                            <div class="modern-dropdown-search">
                                <input type="text" placeholder="Buscar idioma..."
                                    onkeyup="filterModernLanguage(this, 'languages-list')">
                            </div>
                            <div class="modern-dropdown-options" id="languages-list">
                                @foreach($languages as $id => $name)
                                @if(!in_array($id, $user_languages) && $id != $native_language)
                                <label class="modern-dropdown-option" for="lang{{ $id }}_chk"
                                    style="width:100%;cursor:pointer;">
                                    <input type="checkbox" wire:model.live="selected_languages" value="{{ $id }}"
                                        id="lang{{ $id }}_chk" onchange="selectModernMultiOption(this)">
                                    {{ __('lenguajes.' . $name) }}
                                </label>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @push('styles')
                <style>
                    .modern-dropdown-options input[type="checkbox"] {
                        accent-color: #3b82f6;
                        margin-right: 10px;
                        width: 18px;
                        height: 18px;
                        cursor: pointer;
                    }

                    .modern-dropdown-option.selected {
                        background-color: #eff6ff;
                        color: #1d4ed8;
                        font-weight: 500;
                    }

                    .modern-dropdown-option.selected::after {
                        content: '✓';
                        margin-left: auto;
                        font-weight: bold;
                        color: #3b82f6;
                    }
                </style>
                @endpush
                @push('scripts')
                <script>
                    // Filtrado reutilizable para ambos dropdowns
window.filterModernLanguage = function(input, listId = null) {
    const filter = input.value.toLowerCase();
    const listSelector = listId ? '#' + listId + ' .modern-dropdown-option' : '.modern-dropdown-option';
    const options = input.closest('.modern-dropdown-menu').querySelectorAll(listSelector);
    options.forEach(function(option) {
        const label = option.querySelector('label');
        if (label) {
            const text = label.textContent.toLowerCase();
            option.style.display = text.includes(filter) ? '' : 'none';
        }
    });
}
// Selección múltiple visual
window.selectModernMultiOption = function(input) {
    const option = input.closest('.modern-dropdown-option');
    if (input.checked) {
        option.classList.add('selected');
    } else {
        option.classList.remove('selected');
    }
}
                </script>
                @endpush

                <div class="d-flex flex-wrap gap-2 mt-3">
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



            </div>
        </div>
        <!-- Imagen y video -->
        <div class="tutor-profile-media-row">


            <div class="tutor-profile-media-card">
                @include('livewire.pages.common.profile-settings.components.imagenes')
            </div>

            <div class="tutor-profile-media-card">
                @include('livewire.pages.common.profile-settings.components.videos')
            </div>


        </div>
        <div class="profile-details-actions mt-4">
            <x-primary-button type="submit" wire:loading.class="am-btn_disable" wire:target="updateInfo">
                {{ __('general.save_update') }}
            </x-primary-button>
        </div>
    </form>

</div>

<!-- Botón guardar -->


@push('styles')
<link rel="stylesheet" href="{{ asset('css/livewire/pages/common/profile-settings/components/tutor.css') }}">
@endpush
@push('scripts')
<script>
    /*  window.filterNativeLanguage = function(input) {
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
}); */


// Función para alternar el dropdown
window.toggleModernDropdown = function(toggle) {
    const dropdown = toggle.closest('.modern-dropdown');
    const isOpen = dropdown.classList.contains('open');
    
    // Cerrar todos los otros dropdowns abiertos
    document.querySelectorAll('.modern-dropdown.open').forEach(function(openDropdown) {
        if (openDropdown !== dropdown) {
            openDropdown.classList.remove('open');
        }
    });
    
    // Alternar el dropdown actual
    dropdown.classList.toggle('open', !isOpen);
    
    // Focus en el input de búsqueda cuando se abre
    if (!isOpen) {
        setTimeout(() => {
            const searchInput = dropdown.querySelector('.modern-dropdown-search input');
            if (searchInput) {
                searchInput.focus();
            }
        }, 150);
    }
}

// Función para filtrar idiomas
window.filterModernLanguage = function(input) {
    const filter = input.value.toLowerCase();
    const options = input.closest('.modern-dropdown-menu').querySelectorAll('.modern-dropdown-option');
    let hasVisibleOptions = false;
    
    options.forEach(function(option) {
        const label = option.querySelector('label');
        if (label) {
            const text = label.textContent.toLowerCase();
            const isVisible = text.includes(filter);
            option.style.display = isVisible ? '' : 'none';
            if (isVisible) hasVisibleOptions = true;
        }
    });
    
    // Mostrar mensaje si no hay resultados (opcional)
    // Puedes agregar un div para "No se encontraron resultados" si lo deseas
}

// Función para seleccionar una opción
window.selectModernOption = function(input) {
    const dropdown = input.closest('.modern-dropdown');
    const toggle = dropdown.querySelector('.modern-dropdown-toggle span:first-child');
    const label = input.nextElementSibling;
    
    // Actualizar el texto del toggle
    if (input.value === '') {
        toggle.textContent = 'Selecciona un idioma';
        toggle.classList.add('modern-dropdown-placeholder');
    } else {
        toggle.textContent = label.textContent;
        toggle.classList.remove('modern-dropdown-placeholder');
    }
    
    // Remover clase 'selected' de todas las opciones y agregarla a la actual
    dropdown.querySelectorAll('.modern-dropdown-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    
    if (input.checked) {
        input.closest('.modern-dropdown-option').classList.add('selected');
    }
    
    // Cerrar el dropdown
    dropdown.classList.remove('open');
    
    // Limpiar el filtro de búsqueda
    const searchInput = dropdown.querySelector('.modern-dropdown-search input');
    if (searchInput) {
        searchInput.value = '';
        filterModernLanguage(searchInput);
    }
}

// Cerrar dropdown al hacer click fuera
document.addEventListener('click', function(e) {
    document.querySelectorAll('.modern-dropdown').forEach(function(dropdown) {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });
});

// Navegación con teclado
document.addEventListener('keydown', function(e) {
    const openDropdown = document.querySelector('.modern-dropdown.open');
    if (!openDropdown) return;
    
    const options = Array.from(openDropdown.querySelectorAll('.modern-dropdown-option:not([style*="display: none"])'));
    const currentSelected = openDropdown.querySelector('.modern-dropdown-option.selected');
    let currentIndex = options.indexOf(currentSelected);
    
    switch(e.key) {
        case 'Escape':
            openDropdown.classList.remove('open');
            openDropdown.querySelector('.modern-dropdown-toggle').focus();
            e.preventDefault();
            break;
            
        case 'ArrowDown':
            e.preventDefault();
            currentIndex = Math.min(currentIndex + 1, options.length - 1);
            if (options[currentIndex]) {
                const radio = options[currentIndex].querySelector('input[type="radio"]');
                radio.checked = true;
                selectModernOption(radio);
            }
            break;
            
        case 'ArrowUp':
            e.preventDefault();
            currentIndex = Math.max(currentIndex - 1, 0);
            if (options[currentIndex]) {
                const radio = options[currentIndex].querySelector('input[type="radio"]');
                radio.checked = true;
                selectModernOption(radio);
            }
            break;
            
        case 'Enter':
        case ' ':
            if (currentSelected) {
                e.preventDefault();
                const radio = currentSelected.querySelector('input[type="radio"]');
                radio.checked = true;
                selectModernOption(radio);
            }
            break;
    }
});

// Función para configurar el estado inicial
document.addEventListener('DOMContentLoaded', function() {
    // Marcar como seleccionada la opción que ya está checked
    document.querySelectorAll('.modern-dropdown').forEach(dropdown => {
        const checkedRadio = dropdown.querySelector('input[type="radio"]:checked');
        if (checkedRadio) {
            selectModernOption(checkedRadio);
        }
    });
});
</script>
@endpush