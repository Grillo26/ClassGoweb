<div wire:ignore.self class="modal fade" id="setupqrpopup" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content upload-qr-modal">
            <!-- Header del modal -->
            <div class="upload-qr-header">
                <h3 class="upload-qr-title">Configurar código QR</h3>
                <button type="button" class="upload-qr-close" data-bs-dismiss="modal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <!-- Cuerpo del modal -->

            <div class="upload-qr-body">
                <form wire:submit.prevent="updateQR" enctype="multipart/form-data">

                    <!-- Zona de subida de archivo -->
                    <div class="upload-file-zone" onclick="document.getElementById('qr_image').click()">
                        @if($currentQRPath)
                        <!-- Mostrar imagen existente dentro del área de drop -->
                        <div class="existing-image-display">
                            <img src="{{ asset('storage/' . $currentQRPath) }}" alt="QR actual"
                                class="existing-qr-image">
                            <div class="existing-image-overlay">
                                <div class="existing-image-actions">
                                    <div class="upload-file-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21 15V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V15"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M17 8L12 3L7 8" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 3V15" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <h4 class="upload-file-title">Cambiar imagen QR</h4>
                                    <p class="upload-file-text">Haz clic o arrastra una nueva imagen</p>
                                    <span class="upload-file-format">PNG, JPG, GIF hasta 10MB</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Mostrar área de upload vacía -->
                        <div class="upload-file-content">
                            <div class="upload-file-icon">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 15V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V15"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M17 8L12 3L7 8" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12 3V15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h4 class="upload-file-title">Sube un archivo</h4>
                            <p class="upload-file-text">o arrástralo y suéltalo aquí</p>
                            <span class="upload-file-format">PNG, JPG, GIF hasta 10MB</span>
                        </div>
                        @endif

                        <!-- Input oculto -->
                        <input wire:model="qrImage" id="qr_image" name="qr_image" type="file" accept="image/*"
                            class="upload-file-input" style="display: none;">
                    </div>

                    <!-- Preview de nueva imagen seleccionada -->
                    @if($qrImage)
                    <div class="new-image-preview">
                        <h5 class="new-image-title">Nueva imagen seleccionada:</h5>
                        <div class="new-image-container">
                            <img src="{{ $qrImage->temporaryUrl() }}" alt="Nueva imagen QR"
                                class="new-image-preview-img">
                            <div class="new-image-info">
                                <span class="new-image-filename">{{ $qrImage->getClientOriginalName() }}</span>
                                <span class="new-image-size">{{ number_format($qrImage->getSize() / 1024, 2) }}
                                    KB</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Mostrar error si existe -->
                    @error('qrImage')
                    <div class="upload-error-message">{{ $message }}</div>
                    @enderror

                      <!-- Botones de acción -->
                    <div class="upload-modal-actions">
                        <button type="button" class="upload-btn-cancel" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button wire:click="updatePayout" wire:loading.attr="disabled" wire:target="updatePayout"
                            type="button" class="upload-btn-save" {{ !$qrImage && !$currentQRPath ? 'disabled' : '' }}>

                            <!-- Texto del botón que cambia según el estado -->
                            <span wire:loading.remove wire:target="updatePayout">
                                @if($currentQRPath && $qrImage)
                                Actualizar QR
                                @elseif($currentQRPath)
                                Mantener QR actual
                                @else
                                Guardar QR
                                @endif
                            </span>

                            <!-- Texto mientras carga -->
                            <span wire:loading wire:target="updatePayout">
                                Procesando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script>
// Script simplificado - el cierre del modal se maneja directamente en el onclick del botón
console.log('Modal QR script cargado');
</script>