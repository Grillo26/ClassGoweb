<div wire:ignore.self class="modal fade" id="setupaccountpopup" tabindex="-1" aria-labelledby="setupaccountpopupLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bank-modal">
            <!-- Header -->
            <div class="modal-header bank-modal-header">
                <h5 class="modal-title bank-modal-title" id="setupaccountpopupLabel">
                    <i class="fas fa-university me-2"></i>
                    Configurar cuenta bancaria
                </h5>
                <button type="button" class="btn-close bank-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body bank-modal-body">
                <form wire:submit.prevent="updatePayout">
                    
                    <!-- Título de la cuenta -->
                    <div class="form-group">
                        <label for="accounttitle" class="form-label">
                            Título de la cuenta bancaria
                        </label>
                        <input wire:model.blur="bankTitle" 
                               id="accounttitle" 
                               name="accounttitle"
                               placeholder="Ej: Cuenta de Ahorros" 
                               type="text"
                               class="form-control bank-input @error('bankTitle') is-invalid @enderror" />
                        @error('bankTitle')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Número de cuenta -->
                    <div class="form-group">
                        <label for="account" class="form-label">
                            Número de cuenta bancaria
                        </label>
                        <input wire:model.blur="bankAccountNumber" 
                               id="account" 
                               name="account"
                               placeholder="Ingrese el número de cuenta" 
                               type="text"
                               class="form-control bank-input @error('bankAccountNumber') is-invalid @enderror" />
                        @error('bankAccountNumber')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Nombre del banco -->
                    <div class="form-group">
                        <label for="bankname" class="form-label">
                            Nombre del banco
                        </label>
                        <input wire:model.blur="bankName" 
                               id="bankname" 
                               name="bankname"
                               placeholder="Introduzca el nombre del banco" 
                               type="text"
                               class="form-control bank-input @error('bankName') is-invalid @enderror" />
                        @error('bankName')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Número de routing -->
                    <div class="form-group">
                        <label for="routingnum" class="form-label">
                            Número de routing <span class="optional-text">(opcional)</span>
                        </label>
                        <input wire:model.blur="bankRoutingNumber" 
                               id="routingnum" 
                               name="routingnum"
                               placeholder="Ingrese el número de routing" 
                               type="text"
                               class="form-control bank-input @error('bankRoutingNumber') is-invalid @enderror" />
                        @error('bankRoutingNumber')
                        
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer bank-modal-footer">
                <button type="button" 
                        class="btn btn-secondary bank-cancel-btn" 
                        data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                
                <button type="button" 
                        wire:click="updatePayout"
                        wire:loading.attr="disabled"
                        wire:target="updatePayout"
                        class="btn btn-primary bank-save-btn">
                    
                    <!-- Icono y texto normal -->
                    <span wire:loading.remove wire:target="updatePayout">
                        <i class="fas fa-save me-2"></i>
                        Guardar cuenta
                    </span>

                    <!-- Estado de carga -->
                    <span wire:loading wire:target="updatePayout">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Guardando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos del Modal Banco (basado en modal QR) -->
<style>
    .bank-modal {
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        overflow: hidden;
    }

    .bank-modal-header {
        background: white;
        color: #333;
        border-bottom: 1px solid #e5e5e5;
        padding: 16px 24px;
    }

    .bank-modal-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: #333;
    }

    .bank-close-btn {
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

    .bank-close-btn:hover {
        color: #666;
    }

    .bank-modal-body {
        padding: 24px;
        background: white;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }

    .optional-text {
        color: #999;
        font-weight: 400;
        font-size: 12px;
    }

    .bank-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #504f4f !important;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: white;
    }

    .bank-input:focus {
        outline: none;
        border-color: #ff6b35;
        box-shadow: 0 0 0 3px rgba(119, 118, 118, 0.1);
    }

    .bank-input.is-invalid {
        border-color: #dc3545;
    }

    .bank-input.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .bank-modal-footer {
        background: white;
        border-top: 1px solid #e5e5e5;
        padding: 16px 24px;
        gap: 12px;
        display: flex;
        justify-content: flex-end;
    }

    .bank-cancel-btn, .bank-save-btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 10px 20px;
        transition: all 0.2s ease;
        font-size: 14px;
        border: none;
    }

    .bank-cancel-btn {
        background: #f5f5f5;
        color: #666;
    }

    .bank-cancel-btn:hover {
        background: #e8e8e8;
        color: #333;
    }

    .bank-save-btn {
        background: #ff6b35;
        color: white;
    }

    .bank-save-btn:hover:not(:disabled) {
        background: #e55a2b;
    }

    .bank-save-btn:disabled {
        background: #ccc;
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .bank-modal-body {
            padding: 16px;
        }

        .bank-modal-footer {
            padding: 12px 16px;
            flex-direction: column-reverse;
        }

        .bank-cancel-btn, .bank-save-btn {
            width: 100%;
            margin-bottom: 8px;
        }

        .bank-save-btn {
            margin-bottom: 0;
        }
    }
</style>