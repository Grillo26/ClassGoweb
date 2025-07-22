<div class="profile-flex-columns">
        <div class="profile-col profile-col-1">
            @include('livewire.pages.common.profile-settings.components.imagenes', [
            'image' => $image,
            'imageName' => $imageName,
            'maxImageSize' => $maxImageSize,
            'allowImgFileExt' => $allowImgFileExt
            ])
            <!-- Columna 1: aquí puedes poner los campos que quieras en la primera columna -->
        </div>
        <div class="profile-col profile-col-2">

            <div class="profile-details-card">
                <div class="profile-details-header">
                    <h2 class="profile-details-title">{{ __('profile.personal_details') }}</h2>
                    <p class="profile-details-sub">Proporciona información básica para completar tu perfil.</p>
                </div>
                <form wire:submit.prevent="updateInfo" class="profile-details-form">
                    <div class="profile-details-grid">
                        <div class="profile-details-group">
                            <label for="first_name" class="profile-details-label">Nombre</label>
                            <input type="text" id="first_name" class="profile-details-inputs" wire:model="first_name">
                            @error('first_name') <span class="profile-details-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="profile-details-group">
                            <label for="last_name" class="profile-details-label">Apellido</label>
                            <input type="text" id="last_name" class="profile-details-inputs" wire:model="last_name">
                            @error('last_name') <span class="profile-details-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="profile-details-group">
                            <label for="email" class="profile-details-label">Email</label>
                            <input type="email" id="email" class="profile-details-inputs" wire:model="email" disabled>
                        </div>
                        <div class="profile-details-group">
                            <label for="phone_number" class="profile-details-label">Phone number</label>
                            <input type="tel" id="phone_number" class="profile-details-inputs"
                                wire:model="phone_number">
                            @error('phone_number') <span class="profile-details-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="profile-details-gender-row">
                        {{-- <span class="profile-details-label">Género</span> --}}
                        @include('livewire.pages.common.profile-settings.components.genero')
                    </div>
                    <div class="profile-details-actions">
                        <x-primary-button type="submit" wire:loading.class="am-btn_disable" wire:target="updateInfo">
                            {{ __('general.save_update') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

 @push('styles')
   <link rel="stylesheet" href="{{ asset('css/livewire/pages/common/profile-settings/components/student.css') }}">
    
 @endpush   