<div>
    <main class="tb-main am-dispute-system am-user-system">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="tb-dhb-mainheading">
                    <h4> {{ __('Tutorías') .' ('. $tutorias->total() .')'}}</h4>
                    <div class="tb-sortby">
                        <form class="tb-themeform tb-displistform" wire:submit.prevent>
                            <fieldset>
                                <div class="tb-themeform__wrap">
                                    <div class="tb-actionselect">
                                        <input type="text" wire:model.live="tutor" class="form-control" placeholder="Buscar tutor">
                                    </div>
                                    <div class="tb-actionselect">
                                        <input type="text" wire:model.live="student" class="form-control" placeholder="Buscar estudiante">
                                    </div>
                                    <div class="tb-actionselect">
                                        <select wire:model.live="status" class="form-control">
                                            <option value="">Todos los estados</option>
                                            @foreach(['pendiente','rechazado','aceptado','no_completado','completado','cursando'] as $estado)
                                                <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tb-actionselect">
                                        <button class="tb-btn" type="submit">Filtrar</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="am-disputelist_wrap">
                    <div class="am-disputelist am-custom-scrollbar-y">
                        @if($tutorias->count())
                        <table class="tb-table @if(setting('_general.table_responsive') == 'yes') tb-table-responsive @endif">
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
                                </tr>
                            </thead>
                            <tbody>
                                @php
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
                                        'cursando' => '#4F8CFD', // color para cursando
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
                                        'cursando' => 'Cursando', // mostrar Cursando
                                    ];
                                @endphp
                                @foreach($tutorias as $tutoria)
                                <tr>
                                    <td style="text-align: center;">{{ $tutoria->id }}</td>
                                    <td style="text-align: left;">{{ $tutoria->tutor?->full_name ?? '-' }}</td>
                                    <td style="text-align: left;">{{ $tutoria->student?->full_name ?? '-' }}</td>
                                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($tutoria->start_time)->format('Y-m-d') }}</td>
                                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($tutoria->start_time)->format('H:i') }}</td>
                                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($tutoria->end_time)->format('H:i') }}</td>
                                    <td style="text-align: center;">
                                        <span style="display:inline-block; min-width:110px; text-align:center; font-weight:600; color:#222; background:{{ $statusColors[is_numeric($tutoria->status) ? intval($tutoria->status) : str_replace(' ', '_', strtolower($tutoria->status))] ?? '#FACC15' }}; border-radius:16px; padding:6px 18px; font-size:15px; letter-spacing:0.5px; box-shadow:0 1px 4px rgba(0,0,0,0.04); cursor:pointer;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEstadoTutoria"
                                            wire:click="abrirModalTutoria({{ $tutoria->id }}, '{{ $tutoria->status }}')">
                                            {{ $statusMap[is_numeric($tutoria->status) ? intval($tutoria->status) : str_replace(' ', '_', strtolower($tutoria->status))] ?? 'Pendiente' }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; align-items: center; justify-content: center; width: 100%;">
                                            @if (!empty($tutoria->paymentSlotBooking?->image_url))
                                                <a href="{{ Storage::url($tutoria->paymentSlotBooking->image_url) }}" target="_blank" style="margin-left: 12px;">
                                                    <img src="{{ Storage::url($tutoria->paymentSlotBooking->image_url) }}" alt="Comprobante" style="max-width: 60px; max-height: 60px; border-radius: 6px; display: block;" />
                                                </a>
                                            @else
                                                <span style="margin-left: 12px;">Sin comprobante</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $tutorias->links('pagination.custom') }}
                        @else
                            <x-no-record :image="asset('images/empty.png')"  :title="__('general.no_record_title')"/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
    @if($showModal)
        <div>
            DEBUG: Modal debería estar abierto para tutoría {{ $modalTutoriaId }} (status: {{ $modalStatus }})
        </div>
        @livewire('admin.tutoria-status-modal', ['tutoriaId' => $modalTutoriaId, 'status' => $modalStatus], key('modal-'.$modalTutoriaId))
    @endif
    <!-- Modal para cambiar estado de tutoría -->
    <div wire:ignore.self class="modal fade" id="modalEstadoTutoria" tabindex="-1" aria-labelledby="modalEstadoTutoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEstadoTutoriaLabel">Cambiar estado de la tutoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <select wire:model="modalStatus" class="form-control">
                        <option value="aceptado">Aceptado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="no_completado">No completado</option>
                        <option value="rechazado">Observado</option>
                        <option value="completado">Completado</option>
                        <option value="cursando">Cursando</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="updateStatus">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('cerrar-modal-tutoria', function () {
        var modal = bootstrap.Modal.getInstance(document.getElementById('modalEstadoTutoria'));
        if (modal) {
            modal.hide();
        }
    });
</script>
@endpush
