<div class="dashboard-container" wire:init="loadData">
    @slot('title')
    {{ __('general.dashboard') }}
    @endslot
    @if($isLoading)
    @include('skeletons.manage-account')
    @else
    <div class="am-section-load" wire:loading wire:target="refresh">
        @include('skeletons.manage-account')
    </div>
    <div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Ganancias totales</div>
                <div class="stat-value amount">1,250 Bs</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Ganancias este mes</div>
                <div class="stat-value amount">380 Bs</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Tutorías completadas</div>
                <div class="stat-value">82</div>
            </div>
        </div>

        <!-- Payment Methods Section -->
        <div class="payment-methods-section">
            <div class="section-header">
                Configurar métodos de pago
            </div>
            <div class="payment-methods-grid">

                <div class="payment-method-card">
                    <div class="method-left">
                        <div class="method-icon bank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-8 w-8 text-secondary">
                                <path
                                    d="M12 21v-5.172a2 2 0 0 0-.586-1.414L5.414 8.414A2 2 0 0 1 5 7V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v2a2 2 0 0 1-.414 1.414l-6 6A2 2 0 0 0 12 21z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="method-right">
                        <div class="method-header">
                            <div class="method-info">
                                <h3>Transferencia bancaria</h3>
                                {{-- <div class="no-account-message">Aún no se ha agregado ninguna cuenta.</div> --}}
                            </div>
                        </div>
                        <div class="method-controls">
                            <div class="method-actions">
                                <button class="btn btn-primary"
                                    wire:click="openPayout('cuentabancaria', 'setupaccountpopup')">Configurar
                                    cuenta</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="payment-method-card active">
                    <div class="method-left">
                        <div class="method-icon qr">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-8 w-8 text-green-600">
                                <rect width="5" height="5" x="3" y="3" rx="1"></rect>
                                <rect width="5" height="5" x="16" y="3" rx="1"></rect>
                                <rect width="5" height="5" x="3" y="16" rx="1"></rect>
                                <path d="M21 16h-3a2 2 0 0 0-2 2v3"></path>
                                <path d="M21 21v.01"></path>
                                <path d="M12 7v3a2 2 0 0 1-2 2H7"></path>
                                <path d="M3 12h.01"></path>
                                <path d="M12 12h.01"></path>
                                <path d="M12 18h.01"></path>
                                <path d="M7 12h.01"></path>
                                <path d="M7 18h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="method-right">
                        <div class="method-header">
                            <div class="method-info">
                                <h3>Pago con QR</h3>
                                {{-- <span class="method-status active">Activo</span> --}}
                                {{-- <div style="margin-top: 8px;">
                                    <span style="font-size: 14px; color: #64748b;">William E. - Banco FASSIL</span>
                                </div> --}}
                            </div>
                        </div>
                        <div class="method-controls">
                            {{-- <div class="radio-group">
                                <input type="radio" id="default_qr" name="payment_method" checked>
                                <label for="default_qr">Método de pago predeterminado</label>
                            </div> --}}
                            <div class="method-actions">
                                <button class="btn btn-secondary" wire:click="openPayout('QR', 'modalQR')">Gestionar
                                    QR</button>
                                <button class="btn btn-danger" wire:click="openPayout('QR', 'deletepopup')">Eliminar
                                    cuenta</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="transaction-history">
            <div class="section-header">
                <h2 class="section-title">Historial de Transacciones</h2>
            </div>

            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr>
                            <td>10 Jun, 2025</td>
                            <td class="transaction-amount">150.00 Bs</td>
                            <td class="transaction-method">Pago con QR</td>
                            <td><span class="transaction-status">Completado</span></td>
                        </tr> --}}
                        
                    </tbody>
                    
                </table>
                <p style="display: flex; justify-content: center; align-items: center; font-weight: bold;padding:2rem">Muy pronto...</p>
            </div>
        </div>
        @include('livewire.pages.tutor.manage-account.components.modal-por-definir')
        @include('livewire.pages.tutor.manage-account.components.modal-cuenta-bancaria')
        @include('livewire.pages.tutor.manage-account.components.modal-qr-nuevo')
        @include('livewire.pages.tutor.manage-account.components.delete-modal')
        @include('livewire.pages.tutor.manage-account.components.verified-modal', [
        'title' => '¡Cuenta verificada!',
        'message' => 'Tu cuenta ha sido verificada exitosamente.',
        'showSocialShare' => true
        ])
    </div>
    @endif
</div>
@push('styles')
<link rel="stylesheet" href="{{ asset('css/livewire/pages/tutor/manage-account/components/modal-qr-fixed.css') }}">
<link rel="stylesheet" href="{{ asset('css/livewire/pages/tutor/manage-account/manage-account.css') }}">
<link rel="stylesheet" href="{{ asset('css/livewire/pages/tutor/manage-account/components/modal-cuenta-bancaria.css') }}">
@endpush
@push('scripts')

<script type="text/javascript" data-navigate-once>
    // Función para limpiar completamente cualquier modal
    function forceCleanupModals() {
        // Remover todos los backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Limpiar clases del body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Cerrar todos los modales abiertos
        const openModals = document.querySelectorAll('.modal.show');
        openModals.forEach(modal => {
            modal.classList.remove('show');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        });
    }

    // Listener para manejar modales
    Livewire.on('toggleModel', (data) => {
        console.log('toggleModel event received:', data);
        
        // Manejar tanto si viene como objeto directo o como array con el objeto
        let event = data;
        if (Array.isArray(data) && data.length > 0) {
            event = data[0];
        }
        
        console.log('Processed event:', event);
        
        const modalId = event.id;
        const action = event.action;
        
        console.log('Modal ID:', modalId, 'Action:', action);
        
        const modal = document.getElementById(modalId);
        
        console.log('Modal element found:', modal);
        
        if (modal) {
            if (action === 'show') {
                // Limpiar antes de abrir
                forceCleanupModals();
                
                setTimeout(() => {
                    const bootstrapModal = new bootstrap.Modal(modal, {
                        backdrop: true,
                        keyboard: true,
                        focus: true
                    });
                    bootstrapModal.show();
                    console.log('Modal shown:', modalId);
                }, 100);
                
            } else if (action === 'hide') {
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                    console.log('Modal hidden:', modalId);
                } else {
                    // Forzar cierre si no hay instancia
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                    console.log('Modal force hidden:', modalId);
                }
                
                // Limpiar después de cerrar
                setTimeout(forceCleanupModals, 300);
            }
        } else {
            console.error('Modal not found:', modalId);
        }
    });

    // Listener para cuando se cierra un modal desde Livewire
    Livewire.on('modalClosed', () => {
        console.log('Modal closed event from Livewire');
        forceCleanupModals();
    });

    function showVerifiedModalIfNeeded() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('verified') === '1') {
            const modal = document.getElementById('verifiedModal');
            if (modal) {
                $('#verifiedModal').modal('show');
            }
        }
    }

    // Limpiar al navegar
    document.addEventListener('livewire:navigated', function() {
        forceCleanupModals();
        showVerifiedModalIfNeeded();
    });

    document.addEventListener('DOMContentLoaded', function() {
        showVerifiedModalIfNeeded();
        
        // Agregar event listeners a todos los modales para cleanup
        const allModals = document.querySelectorAll('.modal');
        allModals.forEach(modal => {
            modal.addEventListener('hidden.bs.modal', forceCleanupModals);
        });
    });
</script>
@endpush