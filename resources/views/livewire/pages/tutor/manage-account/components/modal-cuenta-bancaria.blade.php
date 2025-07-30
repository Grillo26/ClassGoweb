<div wire:ignore.self class="modal fade" id="setupaccountpopup" tabindex="-1" aria-labelledby="setupaccountpopupLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-m">
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

