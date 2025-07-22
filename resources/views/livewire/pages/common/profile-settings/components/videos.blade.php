<div style="width: 100%; height: 100%;">
    <div class="profile-video-card d-flex flex-column align-items-center justify-content-center" style="max-width: 800px; margin: 0 auto;">
        <h5 class="form-label mb-4 fw-semibold fs-4 text-black text-center w-100">
            {{ __('profile.intro_video') }}
        </h5>
        <div class="w-100 d-flex flex-column align-items-center">
            <!-- Preview del video ampliado -->
            <div class="profile-video-preview mb-4">
                @if($intro_video instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                    <video controls style="width: 500px; height: 300px; border-radius: 14px; object-fit: cover; background: #00384d;">
                        <source src="{{ $intro_video->temporaryUrl() }}" type="video/mp4">
                    </video>
                @elseif($intro_video)
                    <video controls style="width: 400px; height: 300px; border-radius: 14px; object-fit: cover; background: #00384d;">
                        <source src="{{ asset('storage/' . $intro_video) }}" type="video/mp4">
                    </video>
                @else
                    <div class="w-full aspect-video bg-gray-900 rounded-lg mb-4 flex items-center justify-center"><video controls="" class="w-full h-full rounded-lg" poster="https://placehold.co/400x225/023047/ffffff?text=Video"></video></div>
                @endif
            </div>
            <!-- Controles y formatos -->
            <div class="w-100 d-flex flex-column align-items-center justify-content-center">
                <div class="profile-video-btns">
                    <label for="video-upload" class="btn " style="background-color:#219EBC ;color:white;margin-top: 5px">
                        <i class="bi bi-cloud-arrow-up me-2"></i>
                        {{ __('profile.upload_video') }}
                        <input id="video-upload" type="file" class="d-none" wire:model="intro_video"
                            accept="video/*" onchange="validateVideoSize(this)" wire:loading.attr="disabled">
                    </label>
                    @if($intro_video)
                    <button type="button" class="btn "style="background-color:red ;color:white" wire:click="removeMedia('video')">
                        <i class="bi bi-trash me-2"></i>
                        {{ __('profile.remove') }}
                    </button>
                    @endif
                </div>
                @if($isUploadingVideo)
                <div class="d-flex align-items-center gap-2 mt-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="text-primary fs-5">{{ __('profile.uploading') }}...</span>
                </div>
                @endif
                <div class="profile-video-formats">
                    {{ __('profile.allowed_formats') }}: <span class="fw-bold">{{ implode(', ', $allowVideoFileExt) }}</span>. {{ __('profile.max') }} {{ $maxVideoSize }}MB
                </div>
                @error('intro_video')
                <div class="alert alert-danger mt-3 mb-0 fs-5 py-2 text-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $message }}
                </div>
                @enderror
                <div id="video-size-alert" class="alert alert-danger d-none mt-3 text-center" role="alert">
                    <!-- El mensaje se inserta por JS -->
                </div>
            </div>
        </div>
    </div>
</div>