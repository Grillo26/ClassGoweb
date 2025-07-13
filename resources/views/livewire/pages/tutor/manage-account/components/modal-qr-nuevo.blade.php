<!-- Modal QR -->
<div wire:ignore.self class="modal fade" id="modalQR" tabindex="-1" aria-labelledby="modalQRLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content qr-modal">
            <!-- Header -->
            <div class="modal-header qr-modal-header">
                <h5 class="modal-title qr-modal-title" id="modalQRLabel">
                    <i class="fas fa-qrcode me-2"></i>
                    Configurar Código QR de Pago
                </h5>
                <button type="button" class="btn-close qr-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body qr-modal-body">
                <form wire:submit.prevent="updatePayout" enctype="multipart/form-data">
                    
                  

                    <!-- QR Actual (si existe) -->
                    @if($currentQRPath)
                    <div class="current-qr-section">
                        <h6 class="section-title">Código QR Actual</h6>
                        <div class="current-qr-container">
                            <div class="qr-image-wrapper">
                                <img src="{{ asset('storage/' . $currentQRPath) }}" 
                                     alt="QR Actual" 
                                     class="current-qr-image">
                                <div class="qr-image-overlay">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                            <div class="qr-image-info">
                                <span class="qr-status-badge active">
                                    <i class="fas fa-check-circle"></i>
                                    Activo
                                </span>
                                <p class="qr-description">qrale.jpg (152.28 KB)</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Zona de Upload -->
                    <div class="upload-section">
                        <h6 class="section-title">
                            @if($currentQRPath)
                                Cambiar Código QR
                            @else
                                Subir Código QR
                            @endif
                        </h6>
                        
                        <div class="upload-zone" 
                             onclick="document.getElementById('qrImageInput').click()"
                             ondrop="dropHandler(event);" 
                             ondragover="dragOverHandler(event);">
                            
                            @if($qrImage)
                                <!-- Preview de nueva imagen -->
                                <div class="new-image-preview">
                                    <img src="{{ $qrImage->temporaryUrl() }}" 
                                         alt="Nueva imagen QR" 
                                         class="preview-image">
                                    <div class="preview-overlay">
                                        <div class="preview-info">
                                            <i class="fas fa-image"></i>
                                            <span class="preview-filename">{{ $qrImage->getClientOriginalName() }}</span>
                                            <span class="preview-size">{{ number_format($qrImage->getSize() / 1024, 2) }} KB</span>
                                        </div>
                                        <button type="button" 
                                                class="remove-preview-btn" 
                                                wire:click="$set('qrImage', null)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- Zona de upload vacía -->
                                <div class="upload-placeholder">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">
                                        <h6>Arrastra tu imagen aquí</h6>
                                        <p>o <span class="upload-link">haz clic para seleccionar</span></p>
                                    </div>
                                    <div class="upload-requirements">
                                        <small>PNG, JPG, GIF • Máximo 2MB</small>
                                    </div>
                                </div>
                            @endif

                            <!-- Input oculto -->
                            <input wire:model="qrImage" 
                                   id="qrImageInput" 
                                   type="file" 
                                   accept="image/*" 
                                   class="d-none">
                        </div>

                        <!-- Error de validación -->
                        @error('qrImage')
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Información adicional -->
                
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer qr-modal-footer">
                <button type="button" 
                        class="btn btn-secondary qr-cancel-btn" 
                        data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                
                <button type="button" 
                        wire:click="updatePayout"
                        wire:loading.attr="disabled"
                        wire:target="updatePayout"
                        class="btn btn-primary qr-save-btn"
                        {{ !$qrImage && !$currentQRPath ? 'disabled' : '' }}>
                    
                    <!-- Icono y texto normal -->
                    <span wire:loading.remove wire:target="updatePayout">
                        @if($currentQRPath && $qrImage)
                            <i class="fas fa-sync-alt me-2"></i>
                            Cambiar imagen
                        @elseif($currentQRPath)
                            <i class="fas fa-check me-2"></i>
                            Guardar QR
                        @else
                            <i class="fas fa-save me-2"></i>
                            Guardar QR
                        @endif
                    </span>

                    <!-- Estado de carga -->
                    <span wire:loading wire:target="updatePayout">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Procesando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos del Modal QR -->
<style>
    .qr-modal {
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        overflow: hidden;
    }

    .qr-modal-header {
        background: white;
        color: #333;
        border-bottom: 1px solid #e5e5e5;
        padding: 16px 24px;
    }

    .qr-modal-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: #333;
    }

    .qr-close-btn {
        background: none;
        border: none;
        color: #999;
        font-size: 24px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
        padding: 0;
    }

    .qr-close-btn:hover {
        color: #666;
    }

    .qr-modal-body {
        padding: 24px;
        background: white;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
        display: none; /* Ocultar títulos para diseño más limpio */
    }

    .current-qr-section {
        margin-bottom: 20px;
        text-align: center;
    }

    .current-qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e5e5e5;
    }

    .qr-image-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .current-qr-image {
        width: 200px;
        height: 200px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #e5e5e5;
        background: white;
        padding: 8px;
    }

    .qr-image-overlay {
        display: none; /* Quitar overlay para diseño más limpio */
    }

    .qr-status-badge {
        display: none; /* Quitar badge para diseño más limpio */
    }

    .qr-description {
        color: #666;
        margin: 8px 0 0 0;
        font-size: 13px;
        text-align: center;
    }

    .upload-section {
        margin-bottom: 20px;
        text-align: center;
    }

    .upload-zone {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fafafa;
        position: relative;
        overflow: hidden;
    }

    .upload-zone:hover {
        border-color: #ff6b35;
        background: #fff5f2;
    }

    .upload-zone.drag-over {
        border-color: #ff6b35;
        background: #fff5f2;
    }

    .upload-placeholder {
        pointer-events: none;
    }

    .upload-icon {
        font-size: 32px;
        color: #ccc;
        margin-bottom: 12px;
    }

    .upload-text h6 {
        color: #666;
        font-weight: 500;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .upload-text p {
        color: #999;
        margin: 0;
        font-size: 13px;
    }

    .upload-link {
        color: #ff6b35;
        font-weight: 500;
        text-decoration: none;
    }

    .upload-requirements {
        margin-top: 8px;
    }

    .upload-requirements small {
        color: #999;
        background: transparent;
        padding: 0;
        border: none;
        font-size: 12px;
    }

    .new-image-preview {
        position: relative;
        max-width: 150px;
        margin: 0 auto;
    }

    .preview-image {
        width: 100%;
        max-height: 150px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #e5e5e5;
        background: white;
        padding: 8px;
    }

    .preview-overlay {
        position: absolute;
        bottom: -25px;
        left: 0;
        right: 0;
        background: transparent;
        color: #666;
        padding: 5px;
        border-radius: 0;
        text-align: center;
    }

    .preview-info {
        text-align: center;
        font-size: 11px;
        color: #999;
    }

    .preview-filename {
        display: block;
        font-weight: 500;
        margin-bottom: 2px;
        word-break: break-all;
        color: #666;
    }

    .remove-preview-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ff6b35;
        border: none;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .remove-preview-btn:hover {
        background: #e55a2b;
        transform: scale(1.1);
    }

    .qr-modal-footer {
        background: white;
        border-top: 1px solid #e5e5e5;
        padding: 16px 24px;
        gap: 12px;
        display: flex;
        justify-content: flex-end;
    }

    .qr-cancel-btn, .qr-save-btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 10px 20px;
        transition: all 0.2s ease;
        font-size: 14px;
        border: none;
    }

    .qr-cancel-btn {
        background: #f5f5f5;
        color: #666;
    }

    .qr-cancel-btn:hover {
        background: #e8e8e8;
        color: #333;
    }

    .qr-save-btn {
        background: #ff6b35;
        color: white;
    }

    .qr-save-btn:hover:not(:disabled) {
        background: #e55a2b;
    }

    .qr-save-btn:disabled {
        background: #ccc;
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .qr-modal-body {
            padding: 16px;
        }

        .current-qr-container {
            padding: 16px;
        }

        .upload-zone {
            padding: 20px 15px;
        }

        .qr-modal-footer {
            padding: 12px 16px;
            flex-direction: column-reverse;
        }

        .qr-cancel-btn, .qr-save-btn {
            width: 100%;
            margin-bottom: 8px;
        }
    }

    /* Ocultar elementos innecesarios */
    .qr-additional-info,
    .info-card,
    .info-icon,
    .info-content {
        display: none;
    }
</style>

<script>
    // Función para limpiar completamente el modal y backdrop
    function cleanupModal() {
        // Remover cualquier backdrop que pueda quedar
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Limpiar clases del body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    // Drag and Drop handlers
    function dragOverHandler(ev) {
        ev.preventDefault();
        ev.currentTarget.classList.add('drag-over');
    }

    function dropHandler(ev) {
        ev.preventDefault();
        ev.currentTarget.classList.remove('drag-over');
        
        if (ev.dataTransfer.items) {
            for (var i = 0; i < ev.dataTransfer.items.length; i++) {
                if (ev.dataTransfer.items[i].kind === 'file') {
                    var file = ev.dataTransfer.items[i].getAsFile();
                    if (file.type.startsWith('image/')) {
                        // Simular que el archivo fue seleccionado
                        const input = document.getElementById('qrImageInput');
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        input.files = dt.files;
                        
                        // Disparar evento de change para Livewire
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    break;
                }
            }
        }
    }

    // Limpiar clase drag-over cuando se sale del área
    document.addEventListener('dragleave', function(e) {
        if (!e.relatedTarget || !e.currentTarget.contains(e.relatedTarget)) {
            document.querySelectorAll('.upload-zone').forEach(zone => {
                zone.classList.remove('drag-over');
            });
        }
    });

    // Event listeners para el modal
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('modalQR');
        if (modalElement) {
            // Cuando el modal se cierra completamente
            modalElement.addEventListener('hidden.bs.modal', function () {
                cleanupModal();
                
                // Reset del formulario Livewire
                if (window.Livewire) {
                    Livewire.dispatch('modalClosed');
                }
            });

            // Cuando el modal se muestra
            modalElement.addEventListener('shown.bs.modal', function () {
                // Asegurar que el modal está correctamente inicializado
                console.log('Modal QR abierto correctamente');
            });

            // Manejo de errores
            modalElement.addEventListener('hide.bs.modal', function (e) {
                // Permitir que el modal se cierre normalmente
                console.log('Cerrando modal QR...');
            });
        }
    });

    // Listener específico para Livewire navigation
    document.addEventListener('livewire:navigated', function() {
        cleanupModal();
    });
</script>
