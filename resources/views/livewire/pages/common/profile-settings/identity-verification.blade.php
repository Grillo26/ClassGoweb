{{--
    Vista Blade para la verificación de identidad del usuario.
    Permite a usuarios (tutores y estudiantes) cargar información personal, dirección, documentos y fotos para su verificación.
    Integra select2, carga dinámica de estados según país, subida de archivos y validaciones Livewire.
    - Si $enableGooglePlaces == '1', usa Google Places para autocompletar dirección.
    - Si el usuario ya está verificado, muestra mensaje de éxito.
    - Si la verificación está pendiente, muestra mensaje de espera.
    - Si no hay verificación, muestra el formulario completo.
    - Incluye scripts para select2, flatpickr y Google Places.
--}}
<div class="am-profile-setting" wire:init="loadData">
    {{-- Título de la página --}}
    @slot('title')
        {{ __('identity.title') }}
    @endslot
    {{-- Tabs de navegación del perfil --}}
    @include('livewire.pages.common.profile-settings.tabs')
    <div class="am-userperinfo">
        {{-- Si el usuario no tiene verificación de identidad, muestra el formulario --}}
        @if(empty($identity))
            <div class="am-userid">
                <div class="am-title_wrap">
                    <div class="am-title">
                        <h2 style="color: black">{{ __('profile.identity_verification') }}</h2> {{-- Título principal --}}
                        <p style="color: black">{{ __('profile.identity_detail_desc') }}</p> {{-- Descripción --}}
                    </div>
                </div>
                <form wire:submit="updateInfo" class="am-themeform am-themeform_personalinfo">
                    @if($isLoading)
                        {{-- Skeleton de carga mientras se obtienen los datos --}}
                        @include('skeletons.identity-verification')
                    @else
                        <fieldset>
 

                           
                            {{-- Campo: Fecha de nacimiento --}}
                            <div class="form-group">
                                <x-input-label style="color: black"  for="name" class="am-important" :value="__('profile.date_of_birth')" />
                                <div class="form-group-two-wrap">
                                    <div @class(['form-control_wrap', 'am-invalid' => $errors->has('form.dateOfBirth')])>
                                        <x-text-input class="flat-date" id="dof" data-format="F-d-Y" wire:model="form.dateOfBirth" placeholder="{{ __('profile.date_of_birth') }}" type="text" id="datepicker"  autofocus autocomplete="name" />
                                        <x-input-error field_name="form.dateOfBirth" />
                                    </div>
                                </div>
                            </div>

                              {{-- Campo: Foto personal --}}
                            <div class="form-group">
                                <x-input-label style="color: black" class="am-important" for="profile_photo" :value="__('profile.personal_photo')" />
                                <div class="am-uploadoption" x-data="{isUploading:false}" wire:key="uploading-profile-photo-{{ time() }}" >
                                    <div class="tk-draganddrop"
                                        x-bind:class="{ 'am-dragfile' : isDragging, 'am-uploading' : isUploading }"
                                        x-on:drop.prevent="isUploading = true; isDragging = false"
                                        wire:drop.prevent="$upload('form.image', $event.dataTransfer.files[0])">
                                        <x-text-input
                                            name="file"
                                            type="file"
                                            id="at_upload_photo"
                                            x-ref="file_upload_image"
                                            accept="{{ !empty($allowImgFileExt) ? join(',', array_map(function($ex){return('.'.$ex);}, $allowImgFileExt)) : '*' }}"
                                            x-on:change="isUploading = true; $wire.upload('form.image', $refs.file_upload_image.files[0])" />
                                        <label style="color: black" for="at_upload_photo" class="am-uploadfile">
                                            <span class="am-dropfileshadow">
                                                <svg class="am-border-svg "><rect width="100%" height="100%" rx="12"></rect></svg>
                                                <i class="am-icon-plus-02"></i>
                                                <span class="am-uploadiconanimation">
                                                    <i class="am-icon-upload-03"></i>
                                                </span>
                                                {{ __('general.drop_file_here') }}
                                            </span>
                                            <em>
                                                <i class="am-icon-export-03"></i>
                                            </em>
                                            <span>{{ __('general.drop_file_here_or') }} <i>{{ __('general.click_here_file') }}</i> {{ __('general.to_upload') }} <em>{{ $fileExt }} (max. {{ $allowImageSize }} MB)</em></span>
                                        </label>
                                    </div>
                                    {{-- Vista previa de la imagen subida --}}
                                    @if(!empty($form->image))
                                        <div class="am-uploadedfile">
                                            @if (method_exists($form?->image,'temporaryUrl'))
                                                <img src="{{ $form?->image->temporaryUrl() }}">
                                            @else
                                                <img src="{{ setting('_general.default_avatar_for_user') ? url(Storage::url(setting('_general.default_avatar_for_user')[0]['path'])) : url(Storage::url($form?->image)) }}" />
                                            @endif
                                            @if (method_exists($form->image,'temporaryUrl'))
                                                <span>{{ basename(parse_url($form?->image->temporaryUrl(), PHP_URL_PATH)) }}</span>
                                            @endif
                                            <a href="#" wire:click.prevent="removeMedia('personal_photo')" class="am-delitem">
                                                <i class="am-icon-trash-02"></i>
                                            </a>
                                        </div>
                                    @endif
                                    <x-input-error field_name="form.image" />
                                </div>
                            </div>
                            {{-- Documentos adicionales según rol (tutor/estudiante) --}}
                            @if($user->hasRole('tutor'))
                                {{-- Documento: Identificación tutor --}}
                                <div class="form-group">
                                    <x-input-label style="color: black" for="coverphoto1" class="am-important" :value="__('profile.identification_card')" />
                                    <div class="am-uploadoption" x-data="{isUploading:false}" wire:key="uploading-identification-card-{{ time() }}">
                                        <div class="tk-draganddrop"
                                            x-bind:class="{ 'am-dragfile' : isDragging, 'am-uploading' : isUploading }"
                                            x-on:drop.prevent="isUploading = true; isDragging = false"
                                            wire:drop.prevent="$upload('form.identificationCard', $event.dataTransfer.files[0])">
                                            <x-text-input
                                                name="file"
                                                type="file"
                                                id="at_upload_identification_card"
                                                x-ref="file_upload"
                                                accept="{{ !empty($allowImgFileExt) ?  join(',', array_map(function($ex){return('.'.$ex);}, $allowImgFileExt)) : '*' }}"
                                                x-on:change="isUploading = true; $wire.upload('form.identificationCard', $refs.file_upload.files[0])"/>
                                            <label for="at_upload_identification_card" class="am-uploadfile">
                                                <span class="am-dropfileshadow">
                                                    <svg class="am-border-svg "><rect width="100%" height="100%" rx="12"></rect></svg>
                                                    <i class="am-icon-plus-02"></i>
                                                    <span class="am-uploadiconanimation">
                                                        <i class="am-icon-upload-03"></i>
                                                    </span>
                                                    {{ __('general.drop_file_here') }}
                                                </span>
                                                <em>
                                                    <i class="am-icon-export-03"></i>
                                                </em>
                                                <span>{{ __('general.drop_file_here_or')}} <i> {{ __('general.click_here_file')}} </i> {{ __('general.to_upload') }} <em>{{ $fileExt }} (max. {{ $allowImageSize }} MB)</em></span>
                                            </label>
                                        </div>
                                        @if(!empty($form->identificationCard))
                                            <div class="am-uploadedfile">
                                                @if (method_exists($form->identificationCard,'temporaryUrl'))
                                                    <img src="{{ $form->identificationCard->temporaryUrl() }}">
                                                @else
                                                    <img src="{{ url(Storage::url($form->identificationCard)) }}">
                                                @endif
                                                @if (method_exists($form->identificationCard,'temporaryUrl'))
                                                    <span>{{ basename(parse_url($form->identificationCard->temporaryUrl(), PHP_URL_PATH)) }}</span>
                                                @endif
                                                <a href="#" wire:click.prevent="removeMedia('identificationCard')" class="am-delitem">
                                                    <i class="am-icon-trash-02"></i>
                                                </a>
                                            </div>
                                        @endif
                                        <x-input-error field_name="form.identificationCard" />
                                    </div>
                                </div>
                            @endif
                            @if($user->hasRole('student'))
                               
                                {{-- Documento: Identificación estudiante --}}
                                <div class="form-group">
                                    <x-input-label class="am-important" for="coverphoto1" :value="__('profile.identification_card')" />
                                    <div class="am-uploadoption" x-data="{isUploading:false}" wire:key="uploading-transcript-{{ time() }}">
                                        <div class="tk-draganddrop"
                                            x-bind:class="{ 'am-dragfile' : isDragging, 'am-uploading' : isUploading }"
                                            x-on:drop.prevent="isUploading = true; isDragging = false"
                                            wire:drop.prevent="$upload('form.transcript', $event.dataTransfer.files[0])">
                                            <x-text-input
                                                name="file"
                                                type="file"
                                                id="at_upload_transcript"
                                                x-ref="file_upload"
                                                accept="{{ !empty($allowImgFileExt) ?  join(',', array_map(function($ex){return('.'.$ex);}, $allowImgFileExt)) : '*' }}"
                                                x-on:change=" isUploading = true; $wire.upload('form.transcript', $refs.file_upload.files[0])"/>
                                            <label style="color: black" for="at_upload_transcript" class="am-uploadfile">
                                                <span class="am-dropfileshadow">
                                                    <svg class="am-border-svg "><rect width="100%" height="100%" rx="12"></rect></svg>
                                                    <i class="am-icon-plus-02"></i>
                                                    <span class="am-uploadiconanimation">
                                                        <i class="am-icon-upload-03"></i>
                                                    </span>
                                                    {{ __('general.drop_file_here') }}
                                                </span>
                                                <em>
                                                    <i class="am-icon-export-03"></i>
                                                </em>
                                                <span>{{ __('general.drop_file_here_or')}} <i>{{ __('general.click_here_file')}}</i> {{ __('general.to_upload') }}<em>{{ $fileExt }} (max. {{ $allowImageSize }} MB)</em></span>
                                            </label>
                                        </div>
                                        @if(!empty($form->transcript))
                                            <div class="am-uploadedfile">
                                                @if (method_exists($form->transcript,'temporaryUrl'))
                                                    <img src="{{ $form->transcript->temporaryUrl() }}">
                                                @else
                                                    <img src="{{ url(Storage::url($form->transcript)) }}">
                                                @endif
                                                @if (method_exists($form->transcript,'temporaryUrl'))
                                                    <span>{{ basename(parse_url($form->transcript->temporaryUrl(), PHP_URL_PATH)) }}</span>
                                                @endif
                                                <a href="#" wire:click.prevent="removeMedia('transcript')" class="am-delitem">
                                                    <i class="am-icon-trash-02"></i>
                                                </a>
                                            </div>
                                        @endif
                                        <x-input-error field_name="form.transcript" />
                                    </div>
                                </div>
                              
                               
                               
                            @endif




                            {{-- Campo: Dirección y país/estado/ciudad/código postal --}}                          
                            <div class="form-group am-addressform">
                                <x-input-label style="color: black" for="address" class="am-important" :value="__('profile.address')" />
                                <div class="am-user-location">
                                    @if($enableGooglePlaces == '1')
                                        {{-- Autocompletado con Google Places --}}
                                        <div class="form-group">
                                            <div @class(['form-control_wrap', 'am-invalid' => $errors->has('form.address')])>
                                                <x-text-input  id="tutor_location_field" placeholder="{{ __('profile.address_placeholder') }}" type="text"  autofocus autocomplete="name" />
                                                <x-input-error field_name="form.address" />
                                            </div>
                                        </div>
                                    @else
                                        {{-- Selección de país --}}
                                        @if (!empty($countries))
                                            <div class="form-group-half">
                                                <x-input-label style="color: black !important" for="country" :value="__('profile.country')" />
                                                <div @class(['form-control_wrap', 'am-invalid' => $errors->has('form.country')])>
                                                    <span class="am-select">
                                                      <select id="user_country" wire:model.live="form.country">
                                                              <option value="">{{ __('profile.select_a_country') }}</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country?->id }}">{{ $country?->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </span>
                                                </div>
                                                <x-input-error field_name="form.country" />
                                            </div>
                                        @endif
                                        {{-- Selección de estado (solo si hay país seleccionado y existen estados) --}}
                                        @if(!empty($form->country) && !empty($states) && count($states) > 0)
                                            <div class="form-group-half">
                                                <x-input-label style="color: black !important" for="state" :value="__('profile.state')" />
                                                <div @class(['form-control_wrap', 'am-invalid' => $errors->has('form.state')])>
                                                    <span class="am-select">
                                                        <select class="am-select2" id="country_state" wire:model="form.state">
                                                            <option value="">{{ __('profile.select_a_state') }}</option>
                                                            @foreach ($states as $state)
                                                                <option value="{{ $state?->id }}">{{ $state?->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </span>
                                                </div>
                                                <x-input-error field_name="form.state" />
                                            </div>
                                        @endif 
                                    @endif
                                </div>
                            </div>
                           
                            {{-- Botón para guardar cambios --}}
                            <div class="form-group am-form-btns">
                                <span>{{ __('profile.latest_changes_the_live') }}</span>
                                <x-primary-button wire:target="updateInfo" wire:loading.class="am-btn_disable" >{{ __('profile.save_update') }}</x-primary-button>
                            </div>
                        </fieldset>
                    @endif
                </form>
            </div>
        {{-- Si el usuario ya está verificado --}}
        @elseif(!empty($profile->verified_at))
            <div class="am-successmsg-wrap">
                <div class="am-success-msg">
                    <h5>{{ __('identity.hurray') }}</h5>
                    <p>{{ __('identity.complete_verification') }}</p>
                </div>
            </div>
        {{-- Si la verificación está pendiente --}}
        @else
            <div class="am-submitsmsg-wrap">
                <div class="am-success-msg">
                    <h5>{{ __('identity.woohoo') }}</h5>
                    <p>{{ __('identity.pending_submit_doc') }}</p>
                    <a href="javascript:void(0);" @click="$wire.dispatch('showConfirm', { content: `{{ __('identity.action_warning') }}`,  icon: 'warning', action : `cancel-identity` })">{{ __('identity.cancel_reupload') }}</a>
                </div>
            </div>
        @endif
    </div>
</div>
{{-- Scripts para Google Places, flatpickr y select2 --}}
@push('scripts')
@if($enableGooglePlaces == '1')
<script async src="https://maps.googleapis.com/maps/api/js?key={{ $googleApiKey }}&libraries=places&loading=async&callback=initializePlaceApi"></script>
@endif
@endpush
@push('scripts')
    <script defer src="{{ asset('js/flatpicker.js') }}"></script>
    <script defer src="{{ asset('js/flatpicker-month-year-plugin.js') }}"></script>
    <script>
        // Script para inicializar Google Places y flatpickr
        document.addEventListener("DOMContentLoaded", function () {
            @this.set('form.lat', null);
            @this.set('form.lng', null);
            if (typeof google !== 'undefined' && typeof google.maps.places !== 'undefined') {
                var tutorAddress = document.getElementById('tutor_location_field');
                if (tutorAddress) {
                    var autocompleteTutor = new google.maps.places.Autocomplete(tutorAddress);
                    google.maps.event.addListener(autocompleteTutor, 'place_changed', function () {
                        var place = autocompleteTutor.getPlace();
                        var address = null;
                        var lat = null;
                        var lng = null;
                        var countryName = null;
                        if (place.formatted_address) {
                            address = place.formatted_address;
                        }
                        if (place.geometry && place.geometry.location) {
                            lat = place.geometry.location.lat();
                            lng = place.geometry.location.lng();
                        }
                        place.address_components?.forEach((item) => {
                            if (item.types.includes('country')) {
                                countryName = item.short_name;
                            }
                        });
                        @this.set('form.address', address);
                        @this.set('form.lat', lat);
                        @this.set('form.lng', lng);
                        @this.set('form.countryName', countryName);
                    });
                }
            }
        });
    </script>
    @if($enableGooglePlaces == '1')
        <script>
            function initializePlaceApi() {
                var tutorAddress = document.getElementById('tutor_location_field');
                if (tutorAddress) {
                    tutorAddress.addEventListener('input', function(e) {
                        if (e.target.value == '') {
                            @this.set('form.address', '');
                        }
                    });
                    if (typeof google !== 'undefined' && typeof google.maps.places !== 'undefined') {
                        var autocompleteTutor = new google.maps.places.Autocomplete(tutorAddress);
                        google.maps.event.addListener(autocompleteTutor, 'place_changed', function () {
                            var place = autocompleteTutor.getPlace();
                            var address = place.formatted_address ?? null;
                            var lat = place.geometry?.location?.lat() ?? null;
                            var lng = place.geometry?.location?.lng() ?? null;
                            var countryName = null;
                            place.address_components?.forEach((item) => {
                                if (item.types.includes('country')) {
                                    countryName = item.short_name;
                                }
                            });
                            if (address) {
                                @this.set('form.address', address);
                            }
                            if (lat !== null && lng !== null) {
                                @this.set('form.lat', lat);
                                @this.set('form.lng', lng);
                            } else {
                                @this.set('form.lat', null);
                                @this.set('form.lng', null);
                            }
                            if (countryName) {
                                @this.set('form.countryName', countryName);
                            }
                        });
                    }
                }
            }
            @if($enableGooglePlaces == '1')
                initializePlaceApi()
            @endif
        </script>
    @endif

    <script type="text/javascript">
        var component = '';
        document.addEventListener('livewire:navigated', function() {
            component = @this;
        },{ once: true });
        document.addEventListener('loadPageJs', (event) => {
            console.log('loadPageJs');
            component.dispatch('initSelect2', {target:'.am-select2'});
            setTimeout(() => {
                initializeDatePicker()
                @if($enableGooglePlaces == '1')
                    initializePlaceApi()
                @endif
            }, 1000);
        })
    </script>
@endpush
@push('scripts')
 <script>
    
    // Script para reinicializar select2 en selects de país y estado tras navegación Livewire
    document.addEventListener('livewire:message.processed', function(event) {
    if ($('#user_country').length) {
        $('#user_country').select2();
        $('#user_country').off('change').on('change', function (e) {
            window.livewire.find('@this').set('form.country', $(this).val());
        });
    }
    if ($('#country_state').length) {
       
        $('#country_state').select2();
        $('#country_state').off('change').on('change', function (e) {
            window.livewire.find('@this').set('form.state', $(this).val());
        });
    }
});
</script> 


@endpush
@push('styles')
    @vite([
        'public/css/flatpicker.css',
        'public/css/flatpicker-month-year-plugin.css'
    ])
@endpush


