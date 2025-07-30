<div>
    <main class="tb-main am-dispute-system am-user-system">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="tb-dhb-mainheading">
                    <h4> {{ __('Tutorías') }}</h4>
                    <div class="tb-sortby">
                        <form class="tb-themeform tb-displistform" wire:submit.prevent>
                            <fieldset>
                                @include('livewire.admin.components.filtros_tutorias')
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="am-disputelist_wrap">
                    <div class="am-disputelist am-custom-scrollbar-y">
                        @if ($tutorias->count())
                        <table
                            class="tb-table @if (setting('_general.table_responsive') == 'yes') tb-table-responsive @endif">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;">#</th>
                                    <th style="text-align: left;">Tutor</th>
                                    <th style="text-align: left;">Estudiante</th>
                                    <th style="width: 110px; text-align: center;">Fecha</th>
                                    <th style="width: 90px; text-align: center;">Hora inicio</th>
                                    <th style="width: 90px; text-align: center;">Hora fin</th>
                                    <th style="width: 120px; text-align: center;">Estado</th>
                                    <th style="width: 110px; text-align: center;">Comprobante</th>
                                    <th style="width: 110px; text-align: center;">QR de Pago Tutor</th>
                                    <th style="width: 110px; text-align: center;">Pago Tutor</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                //dd($tutoria);
                                $statusColors = [
                                1 => '#22C55E', // aceptado
                                2 => '#FACC15', // pendiente
                                3 => '#64748B', // no_completado
                                4 => '#FF9800', // rechazado/observado
                                5 => '#3B82F6', // completado
                                'pendiente' => '#FACC15',
                                'aceptado' => '#22C55E',
                                'no_completado' => '#64748B',
                                'rechazado' => '#FF9800',
                                'completado' => '#3B82F6',
                                'no completado' => '#64748B',
                                ];
                                $statusMap = [
                                1 => 'Aceptado',
                                2 => 'Pendiente',
                                3 => 'No completado',
                                4 => 'Observado', // o 'Rechazado' según tu lógica
                                5 => 'Completado',
                                'pendiente' => 'Pendiente',
                                'rechazado' => 'Observado',
                                'aceptado' => 'Aceptado',
                                'no_completado' => 'No completado',
                                'no completado' => 'No completado',
                                'completado' => 'Completado',
                                ];
                                @endphp


                                @foreach ($tutorias as $tutoria)
                                {{-- @php
                                dd($tutoria->tutor->user->userPayouts);
                                @endphp --}}


                                <tr>
                                    <td style="text-align: center;">{{ $tutoria->id }}</td>
                                    <td style="text-align: left;">{{ $tutoria->tutor?->full_name ?? '-' }}</td>
                                    <td style="text-align: left;">{{ $tutoria->student?->full_name ?? '-' }}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ \Carbon\Carbon::parse($tutoria->start_time)->format('Y-m-d') }}</td>
                                    <td style="text-align: center;">
                                        {{ \Carbon\Carbon::parse($tutoria->start_time)->format('H:i') }}</td>
                                    <td style="text-align: center;">
                                        {{ \Carbon\Carbon::parse($tutoria->end_time)->format('H:i') }}</td>
                                    <td style="text-align: center;">
                                        <span
                                            style="display:inline-block; min-width:110px; text-align:center; font-weight:600; color:#222; background:{{ $statusColors[is_numeric($tutoria->status) ? intval($tutoria->status) : str_replace(' ', '_', strtolower($tutoria->status))] ?? '#FACC15' }}; border-radius:16px; padding:6px 18px; font-size:15px; letter-spacing:0.5px; box-shadow:0 1px 4px rgba(0,0,0,0.04); cursor:pointer;"
                                            data-bs-toggle="modal" data-bs-target="#modalEstadoTutoria"
                                            wire:click="abrirModalTutoria({{ $tutoria->id }}, '{{ $tutoria->status }}')">
                                            {{ $statusMap[is_numeric($tutoria->status) ? intval($tutoria->status) :
                                            str_replace(' ', '_', strtolower($tutoria->status))] ?? 'Pendiente' }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div
                                            style="display: flex; align-items: center; justify-content: center; width: 100%;">
                                            @if (!empty($tutoria->paymentSlotBooking?->image_url))
                                            <a href="{{ Storage::url($tutoria->paymentSlotBooking->image_url) }}"
                                                target="_blank" style="margin-left: 12px;">
                                                <img src="{{ Storage::url($tutoria->paymentSlotBooking->image_url) }}"
                                                    alt="Comprobante"
                                                    style="max-width: 60px; max-height: 60px; border-radius: 6px; display: block;" />
                                            </a>
                                            @else
                                            <span style="margin-left: 12px;">Sin comprobante</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td style="text-align: center;">
                                        @php
                                        $qrPayout = $tutoria->tutor->user->userPayouts->firstWhere('payout_method',
                                        'QR');
                                        $bankPayout = $tutoria->tutor->user->userPayouts->firstWhere('payout_method',
                                        'bank');
                                        $bankDetails = is_array($bankPayout?->payout_details)
                                        ? $bankPayout->payout_details
                                        : (is_string($bankPayout?->payout_details) ?
                                        json_decode($bankPayout->payout_details, true) : []);
                                        @endphp
                                        <div
                                            style="display: flex; align-items: center; justify-content: center; width: 100%;">
                                            @if (!empty($qrPayout?->img_qr))
                                            <a href="{{ Storage::url($qrPayout->img_qr) }}" target="_blank"
                                                style="margin-left: 12px;">
                                                <img src="{{ Storage::url($qrPayout->img_qr) }}" alt="QR de Pago Tutor"
                                                    style="max-width: 60px; max-height: 60px; border-radius: 6px; display: block;" />
                                            </a>
                                            @else
                                            @if(!empty($bankDetails))
                                            <span style="margin-left: 12px;">
                                                <strong>{{ $bankDetails['bankName'] ?? '-' }}</strong><br>
                                                Tipo: {{ $bankDetails['title'] ?? '-' }}<br>
                                                Cuenta: {{ $bankDetails['accountNumber'] ?? '-' }}<br>
                                                Ruta: {{ $bankDetails['bankRoutingNumber'] ?? '-' }}
                                            </span>
                                            @else
                                            <span style="margin-left: 12px;">Sin datos bancarios</span>
                                            @endif

                                            @endif
                                        </div>
                                    </td>


                                    @php
                                    $paymentStatusMap = [
                                    1 => 'Pendiente',
                                    2 => 'Pagado',
                                    3 => 'Observado',
                                    4 => 'Cancelado',
                                    ];
                                    $paymentStatusColors = [
                                    1 => '#FACC15', // Pendiente (amarillo)
                                    2 => '#22C55E', // Pagado (verde)
                                    3 => '#FF9800', // Observado (naranja)
                                    4 => '#64748B', // Cancelado (gris)
                                    ];
                                    $paymentStatus = $tutoria->payment?->status ?? 1;
                                    @endphp
                                    <td>
                                        <div
                                            style="display: flex; align-items: center; justify-content: center; width: 100%;">
                                            <button class="" data-bs-toggle="modal" data-bs-target="#modalPagoTutor"
                                                wire:click="abrirModalPagoTutor({{ $tutoria }})">
                                                <span class="badge"
                                                    style=" color: white !important; background: {{ $paymentStatusColors[$paymentStatus] ?? '#FACC15' }};">
                                                    {{ $paymentStatusMap[$paymentStatus] ?? 'Pendiente' }}
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $tutorias->links('pagination.custom') }}
                        @else
                        <x-no-record :image="asset('images/empty.png')" :title="__('general.no_record_title')" />
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal de éxito -->
            <div wire:ignore.self class="modal fade" id="modalSuccess" tabindex="-1" aria-labelledby="modalSuccessLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-success text-white">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalSuccessLabel">¡Éxito!</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            {{ $successMessage }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de error -->
            <div wire:ignore.self class="modal fade" id="modalError" tabindex="-1" aria-labelledby="modalErrorLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-danger text-white">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalErrorLabel">¡Error!</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">

                            {{ $errorMessage }}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        @include('livewire.admin.components.modal_pago_tutor')
    </main>
    @include('livewire.admin.components.modal_estado_tutoria')
    @push('scripts')
    <script>
        window.addEventListener('cerrar-modal-tutoria', function() {
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalEstadoTutoria'));
            if (modal) {
                modal.hide();
            }
        });
    </script>


    <script>
    window.addEventListener('cerrar-modal-pago-tutor', function() {
        console.log('cerrar-modal-pago-tutor llega');    
        var modal = bootstrap.Modal.getInstance(document.getElementById('modalPagoTutor'));
            if (modal) {
                modal.hide();
            }
        });
    </script>




    <script>
        window.addEventListener('mostrar-modal-success', function() {
        var modal = new bootstrap.Modal(document.getElementById('modalSuccess'));
        modal.show();
    });
    </script>
    @endpush

    @push('scripts')


    <script>
        window.addEventListener('mostrar-modal-error', function(event) {
            var message = event.detail.message;
            alert(message); // Puedes cambiar esto por un modal o notificación personalizada
        });
    @endpush