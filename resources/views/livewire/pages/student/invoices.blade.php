@php
use Carbon\Carbon;
@endphp
<div class="am-dbbox am-invoicelist_wrap" wire:init="loadData">
    @if($isLoading)
    @include('skeletons.invoices')
    @else
    <div class="am-dbbox_content am-invoicelist">
        <div class="am-dbbox_title">
            @slot('title')
            {{ __('invoices.tutorials') }}
            @endslot
            <h2>{{ __('invoices.tutorials') }}</h2>
        </div>
        <div class="am-invoicetable">
            <table class="am-table @if(setting('_general.table_responsive') == 'yes') am-table-responsive @endif">
                <thead>
                    <tr>
                        <th>{{ __('booking.start_date') }}</th>
                        <th>{{ __('booking.end_date') }}</th>
                        @role('tutor')
                        <th>{{ __('booking.student_name') }}</th>
                        <th>{{__('booking.status') }} </th>
                        @elserole('student')
                        <th>{{ __('booking.tutor_name') }}</th>
                        <th>{{__('booking.status') }} </th>
                        <th>Reclamos</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @if (!$tutorias->isEmpty())

                    @foreach($tutorias as $order)
                    <tr>
                        <td data-label="{{ __('booking.id') }}"><span>{{ $order?->start_time }}</span></td>
                        <td data-label="{{ __('booking.id') }}"><span>{{ $order?->end_time }}</span></td>
                        @role('student')
                        <td data-label="{{ __('booking.tutor_name' )}}">
                            <span>
                                {{$order->tutor->first_name }} - {{$order->tutor->last_name}}
                            </span>
                        </td>
                        @elserole('tutor')
                        <td>
                            <span>
                                {{$order->booker->profile->first_name }} - {{$order->booker->profile->last_name}}
                            </span>
                        </td>
                        @endrole
                        <td data-label="{{ __('booking.status') }}">
                            @php
                            $status = $order['status'] ?? $order->status ?? '';
                            // Normaliza el status por si acaso
                            $status = ucfirst(strtolower($status));
                            $statusClass = match($status) {
                            'Aceptado' => 'bg-primary text-white',
                            'Pendiente' => 'bg-warning text-dark',
                            'No completado' => 'bg-danger text-white',
                            'Rechazado' => 'bg-secondary text-white',
                            'Completado' => 'bg-success text-white',
                            default => 'bg-secondary text-white',
                            };
                            @endphp
                            <span class="tk-project-tag-two {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </td>
                        @role('student')
                        <td>
                            @php
                                
                                $yaReclamo = $order->claims && $order->claims->count() > 0;
                                $pasada_hora= now()->greaterThan(Carbon::parse($order->end_time)->addMinutes(2));
                                //dd(!$pasada_hora, "aver ")
                            @endphp
                            @if($order->status == "Aceptado" && !$pasada_hora && !$yaReclamo)
                                <button class="btn btn-warning" wire:click="openClaimModal({{ $order->id }})">Reclamar</button>
                            @elseif($yaReclamo)
                                <span class="text-success">Reclamo enviado</span>
                            @else
                                <p>Fuera de Tiempo Para hacer Reclamo</p>
                            @endif

                        </td>
                        @endrole
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            @if($showClaimModal)
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
            @endif
            @if ($tutorias->isEmpty())
            <x-no-record :image="asset('images/payouts.png')" :title="__('general.no_record_title')"
                :description="__('general.no_records_available')" />
            @else
            {{ $tutorias->links('pagination.pagination') }}
            @endif
        </div>
    </div>
    @endif




</div>
@push('scripts' )
<script type="text/javascript" data-navigate-once>
    var component = '';
    document.addEventListener('livewire:navigated', function() {
            component = @this;
    },{ once: true });
    document.addEventListener('loadPageJs', (event) => {
        component.dispatch('initSelect2', {target:'.am-select2'});
    })
</script>
@endpush

@push('styles')
<style>
    /* Overlay del modal */
    .claim-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
        animation: modalFadeIn 0.3s ease-out;
    }

    /* Contenedor principal del modal */
    .claim-modal-container {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        width: 100%;
        max-width: 540px;
        max-height: 90vh;
        overflow: hidden;
        position: relative;
        animation: modalSlideIn 0.3s ease-out;
    }

    /* Header del modal */
    .claim-modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 24px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .claim-modal-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .claim-modal-title i {
        color: #ffd700;
        font-size: 1.1rem;
    }

    .claim-modal-close {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .claim-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
    }

    /* Body del modal */
    .claim-modal-body {
        padding: 32px;
    }

    .claim-input-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .claim-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
        margin: 0;
    }

    .claim-textarea {
        width: 100%;
        padding: 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        line-height: 1.5;
        color: #374151;
        background: #f9fafb;
        transition: all 0.2s ease;
        resize: vertical;
        min-height: 120px;
        font-family: inherit;
    }

    .claim-textarea:focus {
        outline: none;
        border-color: #667eea;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .claim-textarea::placeholder {
        color: #9ca3af;
    }

    .claim-input-hint {
        font-size: 0.8rem;
        color: #6b7280;
        font-style: italic;
    }

    /* Footer del modal */
    .claim-modal-footer {
        background: #f9fafb;
        padding: 24px 32px;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        border-top: 1px solid #e5e7eb;
    }

    /* Botones */
    .claim-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 120px;
        justify-content: center;
    }

    .claim-btn-primary {
        background: linear-gradient(135deg, #084b7e 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .claim-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .claim-btn-secondary {
        background: #ffffff;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .claim-btn-secondary:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
        color: #374151;
    }

    /* Animaciones */
    @keyframes modalFadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 640px) {
        .claim-modal-overlay {
            padding: 16px;
        }

        .claim-modal-header,
        .claim-modal-body,
        .claim-modal-footer {
            padding-left: 20px;
            padding-right: 20px;
        }

        .claim-modal-footer {
            flex-direction: column;
        }

        .claim-btn {
            width: 100%;
        }

        .claim-modal-title {
            font-size: 1.1rem;
        }
    }

    /* Estados de carga */
    .claim-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Mejoras de accesibilidad */
    .claim-modal-container:focus {
        outline: 2px solid #667eea;
        outline-offset: 2px;
    }

    /* Efectos adicionales */
    .claim-modal-header {
        position: relative;
        overflow: hidden;
    }

    .claim-modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% {
            left: -100%;
        }

        100% {
            left: 100%;
        }
    }
</style>
@endpush