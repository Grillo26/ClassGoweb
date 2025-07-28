 <div class="tb-themeform__wrap">
                                    <div class="" style="margin-right:10px ">
                                        <label>Por Tutor</label>
                                        <input type="text" wire:model.live="tutor" class="form-control"
                                            placeholder="Buscar tutor">
                                    </div>
                                    <div class="" style="margin-right:10px ">
                                        <label>Por Estudiante</label>
                                        <input type="text" wire:model.live="student" class="form-control"
                                            placeholder="Buscar estudiante">
                                    </div>
                                    {{-- <div class="" style="margin-right:10px ">
                                        <label>Por Fecha</label>
                                        <input type="date" wire:model.live="fecha" class="form-control" placeholder="">
                                    </div> --}}
                                    <div class="" style="margin-right:10px ">
                                        <label>Desde:</label>
                                        <input type="date" wire:model.lazy="fecha_inicio" class="form-control" />
                                    </div>

                                    <div class="" style="margin-right:10px ">
                                        <label>Hasta:</label>
                                        <input type="date" wire:model.lazy="fecha_fin" class="form-control" />
                                    </div>
                                    {{--   <div class="">
                                        <label>Por estado</label>
                                        <select wire:model.live="status" class="form-control">
                                            <option value="">estados</option>
                                            @foreach (['Pendiente', 'Rechazado', 'Aceptado', 'No Completado',
                                            'Completado'] as $estado)
                                            <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}


                                      <div class="align-self-end" style="margin-right:10px">
                                        <button wire:click="clearFilters" class="tb-btn">Limpiar Filtros</button>
                                    </div>

                                     
                                    <div class="">
                                        <label>Autenticar</label>
                                        <a href="{{ route('google.authenticate') }}" class="tb-btn tb-btn-primary">
                                            <i class="fab fa-google me-2"></i>
                                            Autenticar Google
                                        </a>
                                    </div>
                                  
                                    {{-- <div class="tb-actionselect">
                                        <button class="tb-btn" type="submit">Filtrar</button>
                                    </div> --}}

                                  
                                </div>