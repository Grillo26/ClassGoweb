
<div class="profile-gender-row">
    <label class="profile-gender-label">{{ __('profile.gender') }}</label>
    <div class="profile-gender-options">
        <label class="profile-gender-radio">
            <input type="radio" name="gender" id="gender-male" value="1" wire:model="gender">
            <span>{{ __('profile.male') }}</span>
        </label>
        <label class="profile-gender-radio">
            <input type="radio" name="gender" id="gender-female" value="2" wire:model="gender">
            <span>{{ __('profile.female') }}</span>
        </label>
        <label class="profile-gender-radio">
            <input type="radio" name="gender" id="gender-unspecified" value="3" wire:model="gender">
            <span>{{ __('profile.not_specified') }}</span>
        </label>
    </div>
    @error('gender') <span class="profile-gender-error">{{ $message }}</span> @enderror
</div>


@push('styles')

<style>
.profile-gender-row {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
}
.profile-gender-label {
    color: #1a2a36;
    font-size: 1rem;
    font-weight: 500;
    margin-right: 1.5rem;
}
.profile-gender-options {
    display: flex;
    gap: 2rem;
}
.profile-gender-radio {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    color: #444;
}
.profile-gender-radio input[type=\"radio\"] {
    accent-color: #0d4ed1;
    width: 18px;
    height: 18px;
}
.profile-gender-error {
    color: #e53e3e;
    font-size: 0.80rem;
    margin-left: 1.5rem;
}


/* Agrega esto al final de tu bloque de estilos */
@media (max-width: 600px) {
    .profile-gender-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    .profile-gender-label {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
    .profile-gender-options {
        flex-wrap: wrap;
        gap: 1rem;
    }
    .profile-gender-error {
        margin-left: 0;
        margin-top: 0.5rem;
    }
}


</style>
@endpush