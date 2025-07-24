
<div class="profile-photo-card">
    <div class="profile-photo-content">
        <h3 class="profile-photo-title">Foto de perfil</h3>
        <p class="profile-photo-sub">Una imagen vale más que mil palabras.</p>
        <div class="profile-photo-img-row">
            @if($image && !$image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <img src="{{ asset('storage/' . $image) }}" alt="Profile" class="profile-photo-img">
            @elseif($image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <img src="{{ $image->temporaryUrl() }}" alt="Profile" class="profile-photo-img">
            @else
                <div class="profile-photo-placeholder">
                    <span class="profile-photo-initials">ER</span>
                </div>
            @endif
        </div>
        <div class="profile-photo-btn-row">
            <label for="image-upload" class="profile-photo-btn profile-photo-btn-main">
                Cambiar foto
                <input id="image-upload" type="file" class="d-none" wire:model="image" wire:loading.attr="disabled">
            </label>
            @if($image)
            <button type="button" class="profile-photo-btn profile-photo-btn-remove" wire:click="removeMedia('image')">
                Quitar
            </button>
            @endif
        </div>
        @error('image')
        <div class="profile-photo-error">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>



@push('styles')
 <style>
    .profile-photo-card {
   
   
   
    padding: 2.5rem 2rem;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
}
.profile-photo-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.profile-photo-title {
    color: #1a2a36;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}
.profile-photo-sub {
    color: #6b7a87;
    font-size: 1rem;
    margin-bottom: 1.5rem;
}
.profile-photo-img-row {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 2rem;
}
.profile-photo-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    background: #eaeaea;
    border: none;
    display: block;
}
.profile-photo-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #ededed;
    display: flex;
    align-items: center;
    justify-content: center;
}
.profile-photo-initials {
    color: #444;
    font-size: 2.5rem;
    font-weight: 700;
}
.profile-photo-btn-row {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
    margin-bottom: 1rem;
}
.profile-photo-btn {
    width: 100%;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 500;
    padding: 0.5rem;
    cursor: pointer;
    transition: background 0.2s;
    text-align: center;
}
.profile-photo-btn-main {
    background: #22a6c3;
    color: #fff;
    margin-bottom: 0.5rem;
}
.profile-photo-btn-main:hover {
    background: #1b8ca0;
}
.profile-photo-btn-remove {
    background: #eaeaea;
    color: #444;
}
.profile-photo-btn-remove:hover {
    background: #d1d1d1;
}
.profile-photo-error {
    color: #e53e3e;
    font-size: 1rem;
    margin-top: 1rem;
    text-align: center;
}
 </style>
@endpush