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
                                            @foreach(['pendiente','rechazado','aceptado','no_completado','completado'] as $estado)
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
                                    <th>#</th>
                                    <th>Tutor</th>
                                    <th>Estudiante</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tutorias as $tutoria)
                                <tr>
                                    <td data-label="#"><span>{{ $tutoria->id }}</span></td>
                                    <td data-label="Tutor">
                                        <div class="tb-varification_userinfo">
                                            <strong class="tb-adminhead__img">
                                                @if (!empty($tutoria->tutor?->image) && Storage::disk(getStorageDisk())->exists($tutoria->tutor?->image))
                                                    <img src="{{ resizedImage($tutoria->tutor->image,34,34) }}" alt="{{$tutoria->tutor->image}}" />
                                                @else
                                                    <img src="{{ setting('_general.default_avatar_for_user') ? url(Storage::url(setting('_general.default_avatar_for_user')[0]['path'])) : resizedImage('placeholder.png', 34, 34) }}" alt="{{ $tutoria->tutor?->image }}" />
                                                @endif
                                            </strong>
                                            <span>{{ $tutoria->tutor?->full_name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td data-label="Estudiante">
                                        <div class="tb-varification_userinfo">
                                            <strong class="tb-adminhead__img">
                                                @if (!empty($tutoria->student?->image) && Storage::disk(getStorageDisk())->exists($tutoria->student?->image))
                                                    <img src="{{ resizedImage($tutoria->student->image,34,34) }}" alt="{{$tutoria->student->image}}" />
                                                @else
                                                    <img src="{{ setting('_general.default_avatar_for_user') ? url(Storage::url(setting('_general.default_avatar_for_user')[0]['path'])) : resizedImage('placeholder.png', 34, 34) }}" alt="{{ $tutoria->student?->image }}" />
                                                @endif
                                            </strong>
                                            <span>{{ $tutoria->student?->full_name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td data-label="Inicio"><span>{{ $tutoria->start_time }}</span></td>
                                    <td data-label="Fin"><span>{{ $tutoria->end_time }}</span></td>
                                    <td data-label="Estado">
                                        <em class="tk-project-tag {{
                                            $tutoria->status == 'aceptado' ? 'tk-hourly-tag' :
                                            ($tutoria->status == 'pendiente' ? 'tk-fixed-tag' :
                                            ($tutoria->status == 'rechazado' ? 'tk-canceled' :
                                            ($tutoria->status == 'completado' ? 'tk-success-tag' : 'tk-drafted')))
                                        }}"
                                        style="cursor:pointer; display: inline-flex; align-items: center; gap: 7px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEstadoTutoria"
                                        wire:click="abrirModalTutoria({{ $tutoria->id }}, '{{ $tutoria->status }}')">
                                        <span class="estado-dot estado-dot-{{ $tutoria->status }}"></span>
                                        <span>{{ ucfirst($tutoria->status) }}</span>
                                        </em>
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
                        <option value="pendiente">Pendiente</option>
                        <option value="rechazado">Rechazado</option>
                        <option value="aceptado">Aceptado</option>
                        <option value="no_completado">No completado</option>
                        <option value="completado">Completado</option>
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
