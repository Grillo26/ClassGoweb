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
            <!-- ...el resto de tu código sigue igual... -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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