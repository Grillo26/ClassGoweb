<div class="claim-modal-overlay" wire:click="closeClaimModal">
                <div class="claim-modal-container" wire:click.stop>
                    <div class="claim-modal-header">
                        <h3 class="claim-modal-title">
                            <i class="fas fa-exclamation-triangle"></i>
                            Enviar Reclamo
                        </h3>
                        <button class="claim-modal-close" wire:click="closeClaimModal" aria-label="Cerrar modal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="claim-modal-body">
                        <div class="claim-input-group">
                            <label for="claimDescription" class="claim-label">
                                Describe detalladamente tu reclamo
                            </label>
                            <textarea id="claimDescription" wire:model="claimDescription" class="claim-textarea"
                                placeholder="Por favor, describe los detalles de tu reclamo. Incluye fechas, horarios y cualquier información relevante que nos ayude a resolver tu situación."
                                rows="6"></textarea>
                            <div class="claim-input-hint">
                                Mínimo 20 caracteres requeridos
                            </div>
                        </div>
                    </div>
                    <div class="claim-modal-footer">
                        <button class="claim-btn claim-btn-secondary" wire:click="closeClaimModal">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </button>
                        <button class="claim-btn claim-btn-primary" wire:click="submitClaim">
                            <i class="fas fa-paper-plane"></i>
                            Enviar Reclamo
                        </button>
                    </div>
                </div>
            </div>