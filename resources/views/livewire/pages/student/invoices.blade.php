@php
use Carbon\Carbon;
@endphp
<div class="container" wire:init="loadData">
    <div>
        <div class="container-table">
            <div class="titulo">
                {{ __('invoices.tutorials') }}
            </div>
            <div class="table-wrapper">
                <table class="modern-table">
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
                        {{-- @php
                        dd($tutorias)
                        @endphp --}}
                        @foreach($tutorias as $order)
                        <tr class="table-row">
                            <td><span class="table-cell-content">{{ $order?->start_time }}</span></td>
                            <td><span class="table-cell-content">{{ $order?->end_time }}</span></td>
                            @role('student')
                            <td>
                                <span class="table-cell-content">
                                    {{$order->tutor->first_name }} - {{$order->tutor->last_name}}
                                </span>
                            </td>
                            @elserole('tutor')



                            
                            <td>
                                <span class="table-cell-content">
                                    @if($order->booker && $order->booker->profile)
                                    {{ $order->booker->profile->first_name }} - {{ $order->booker->profile->last_name }}
                                    @endif
                                </span>
                            </td>



                            @endrole
                            <td>
                                @php
                                $status = $order['status'] ?? $order->status ?? '';
                                $status = ucfirst(strtolower($status));
                                $statusClass = match($status) {
                                'Aceptado' => 'status-accepted',
                                'Pendiente' => 'status-pending',
                                'No completado' => 'status-incomplete',
                                'Rechazado' => 'status-rejected',
                                'Completado' => 'status-completed',
                                default => 'status-default',
                                };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </td>
                            @role('student')
                            <td>
                                @php
                                $yaReclamo = $order->claims && $order->claims->count() > 0;
                                $pasada_hora= now()->greaterThan(Carbon::parse($order->end_time)->addMinutes(2));
                                @endphp
                                @if($order->status == "Aceptado" && !$pasada_hora && !$yaReclamo)
                                <button class="claim-btn-action btn-warning"
                                    wire:click="openClaimModal({{ $order->id }})">Reclamar</button>
                                @elseif($yaReclamo)
                                <span class="claim-status-sent">Reclamo enviado</span>
                                @else
                                <span class="claim-status-expired">Fuera de Tiempo Para hacer Reclamo</span>
                                @endif
                            </td>
                            @endrole
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @if($showClaimModal)
            @include('livewire.pages.admin.invoices.components.modal')
            @endif
            <!-- Resto del código del modal y paginación... -->
        </div>
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
    <link rel="stylesheet" href="{{ asset('css/estilos/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/livewire/pages/invoices/invoices.css') }}">
    <link rel="stylesheet" href="{{ asset('css/livewire/pages/invoices/components/modal.css') }}">
    @endpush