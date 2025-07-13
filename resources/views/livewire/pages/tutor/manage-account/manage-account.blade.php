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
   {{--  <div> --}}


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
                <div class="stat-label">Próximo pago</div>
                <div class="stat-value date">15 Jul, 2025</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Tutorías completadas</div>
                <div class="stat-value">82</div>
            </div>
        </div> 

        <!-- Payment Methods Section -->
         <div class="payment-methods-section">
            <div class="section-header">
                <h2 class="section-title">Configurar métodos de pago</h2>
            </div>

            <div class="payment-methods-grid">

                <div class="payment-method-card">
                    <div class="method-header">
                        <div class="method-icon bank">🏦</div>
                        <div class="method-info">
                            <h3>Transferencia bancaria</h3>
                            <div class="no-account-message">Aún no se ha agregado ninguna cuenta.</div>
                        </div>
                    </div>
                    <div class="method-controls">
                        <div class="method-actions">
                            <button class="btn btn-primary">Configurar cuenta</button>
                        </div>
                    </div>
                </div>


                <div class="payment-method-card active">
                    <div class="method-header">
                        <div class="method-icon qr">📱</div>
                        <div class="method-info">
                            <h3>Pago con QR</h3>
                            <span class="method-status active">Activo</span>
                            <div style="margin-top: 8px;">
                                <span style="font-size: 14px; color: #64748b;">William E. - Banco FASSIL</span>
                            </div>
                        </div>
                    </div>
                    <div class="method-controls">
                       {{--  <div class="radio-group">
                            <input type="radio" id="default_qr" name="payment_method" checked>
                            <label for="default_qr">Método de pago predeterminado</label>
                        </div> --}}
                        <div class="method-actions">
                            <button class="btn btn-secondary" wire:click="openPayout('QR', 'modalQR')">Gestionar QR</button>
                            <button class="btn btn-danger" wire:click="openPayout('QR', 'deletepopup')">Eliminar cuenta</button>
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
                    <tr>
                        <td>10 Jun, 2025</td>
                        <td class="transaction-amount">150.00 Bs</td>
                        <td class="transaction-method">Pago con QR</td>
                        <td><span class="transaction-status">Completado</span></td>
                    </tr>
                    <tr>
                        <td>25 May, 2025</td>
                        <td class="transaction-amount">230.50 Bs</td>
                        <td class="transaction-method">Pago con QR</td>
                        <td><span class="transaction-status">Completado</span></td>
                    </tr>
                    <tr>
                        <td>10 May, 2025</td>
                        <td class="transaction-amount">95.00 Bs</td>
                        <td class="transaction-method">Pago con QR</td>
                        <td><span class="transaction-status">Completado</span></td>
                    </tr>
                    <tr>
                        <td>24 Abr, 2025</td>
                        <td class="transaction-amount">310.00 Bs</td>
                        <td class="transaction-method">Pago con QR</td>
                        <td><span class="transaction-status">Completado</span></td>
                    </tr>
                </tbody>
            </table>
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
@endpush

@push('styles')

<style>
    

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: rgb(243, 244, 246);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-value.amount {
        color: #059669;
    }

    .stat-value.date {
        color: #3b82f6;
    }

    /* Payment Methods Section */
    .payment-methods-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        margin-bottom: 30px;
    }

    .section-header {
        padding: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
    }

    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 20px;
        padding: 24px;
    }

    .payment-method-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        background: white;
    }

    .payment-method-card.active {
        border-color: #3b82f6;
        background: #f8faff;
    }

    .payment-method-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .method-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }

    .method-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .method-icon:hover {
        transform: scale(1.1);
    }

    .method-icon.bank {
        background: #dbeafe;
        color: #3b82f6;
    }

    .method-icon.qr {
        background: #dcfce7;
        color: #059669;
    }

    .method-info h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .method-status {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 500;
        display: inline-block;
    }

    .method-status.active {
        background: #dcfce7;
        color: #059669;
    }

    .method-status.inactive {
        background: #fef3c7;
        color: #d97706;
    }

    .method-amount {
        font-size: 18px;
        font-weight: 600;
        color: #059669;
        margin-bottom: 12px;
    }

    .method-controls {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .radio-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .radio-group input[type="radio"] {
        width: 16px;
        height: 16px;
        accent-color: #3b82f6;
    }

    .radio-group label {
        font-size: 14px;
        color: #64748b;
        cursor: pointer;
    }

    .method-actions {
        display: flex;
        gap: 8px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-danger:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    /* Transaction History */
    .transaction-history {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }

    .transaction-table {
        width: 100%;
        border-collapse: collapse;
    }

    .transaction-table th,
    .transaction-table td {
        padding: 16px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .transaction-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .transaction-table td {
        color: #1e293b;
    }

    .transaction-amount {
        font-weight: 600;
        color: #059669;
    }

    .transaction-method {
        color: #3b82f6;
        font-weight: 500;
    }

    .transaction-status {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        background: #dcfce7;
        color: #059669;
    }

    .policy-note {
        margin-top: 20px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
    }

    .policy-note p {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    .policy-note a {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 500;
    }

    .policy-note a:hover {
        text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .payment-methods-grid {
            grid-template-columns: 1fr;
        }

        .method-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .transaction-table {
            font-size: 14px;
        }

        .transaction-table th,
        .transaction-table td {
            padding: 12px 8px;
        }
    }

    /* Loading Animation */
    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .no-account-message {
        font-size: 14px;
        color: #9ca3af;
        font-style: italic;
    }
</style>
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


