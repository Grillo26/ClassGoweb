<div>
    <main class="tb-main tb-subject-wrap">
        <div class="row">
            <div class="col-lg-4 col-md-12 tb-md-40">
                <div class="tb-dbholder tb-packege-setting">
                    <div class="tb-dbbox tb-dbboxtitle">
                        @if($editMode)
                            <h5>{{ __('taxonomy.update_course') }}</h5>
                        @else
                            <h5>{{ __('courses.add_course') }}</h5>
                        @endif
                    </div>
                    <div class="tb-dbbox">
                        <form class="tk-themeform">
                            <fieldset>
                                <div class="tk-themeform__wrap">
                                    <div class="form-group">
                                        <label class="tb-label">{{ __('courses.name') }}</label>
                                        <input type="text" class="form-control @error('name') tk-invalid @enderror" wire:model="name" required placeholder="{{ __('courses.name') }}">
                                        @error('name')
                                            <div class="tk-errormsg">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="tb-label">{{ __('courses.instructor') }}</label>
                                        <input type="text" class="form-control @error('instructor_name') tk-invalid @enderror" wire:model="instructor_name" required>
                                        @error('instructor_name')
                                            <div class="tk-errormsg">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="tb-label">{{ __('general.description') }}</label>
                                        <textarea class="form-control" placeholder="{{ __('general.description') }}" wire:model="description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="tb-label">{{ __('courses.video') }}</label>
                                        <input type="text" class="form-control @error('video_url') tk-invalid @enderror" wire:model="video_url" placeholder="Pega el enlace del video">
                                        @error('video_url')
                                            <div class="tk-errormsg">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Botón para abrir el modal de preguntas -->
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-outline-primary" wire:click="openQuestionModal"> {{__('courses.add_question')}} </button>
                                    </div>
                                    <!-- Tabla de preguntas agregadas -->
                                    @if(!empty($exam_questions))
                                        <div class="mb-4">
                                            <h5>Preguntas agregadas</h5>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Pregunta</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($exam_questions as $i => $q)
                                                        <tr>
                                                            <td>{{ $q['question'] }}</td>
                                                            </td>
                                                            <td><button type="button" class="btn btn-sm btn-danger" wire:click="removeExamQuestion({{ $i }})">Eliminar</button></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                    <!-- Modal de preguntas de examen -->
                                    <div class="modal fade @if($showQuestionModal) show d-block @endif" tabindex="-1" style="@if($showQuestionModal) display:block; background:rgba(0,0,0,0.5); @else display:none; @endif" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Agregar Pregunta de Examen</h5>
                                                    <button type="button" class="btn-close" wire:click="closeQuestionModal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Pregunta</label>
                                                        <input type="text" class="form-control" wire:model.defer="question_text">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Tipo</label>
                                                        <select class="form-control" wire:model.defer="question_type" wire:change="resetQuestionOptions">
                                                            <option value="opcion_unica">Opción única</option>
                                                            <option value="abierta">Abierta</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Puntaje</label>
                                                        <input type="number" class="form-control" wire:model.defer="question_score">
                                                    </div>
                                                    @if($question_type === 'opcion_unica')
                                                        <div class="mb-3 d-flex align-items-end">
                                                            <div class="flex-grow-1">
                                                                <label>Agregar opción</label>
                                                                <input type="text" class="form-control" wire:model.defer="question_option_input" placeholder="Escribe la opción">
                                                            </div>
                                                            <div class="ms-2">
                                                                <button type="button" class="btn btn-success" wire:click="addOption">Añadir</button>
                                                            </div>
                                                        </div>
                                                        @if(!empty($question_options_list))
                                                            <div class="mb-3">
                                                                <label>Opciones agregadas:</label>
                                                                <ol>
                                                                    @foreach($question_options_list as $idx => $opt)
                                                                        <li>
                                                                            {{ $opt }}
                                                                            <button type="button" class="btn btn-link btn-sm text-danger" wire:click="removeOption({{ $idx }})">Eliminar</button>
                                                                        </li>
                                                                    @endforeach
                                                                </ol>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Respuesta Correcta (número de opción)</label>
                                                                <select class="form-control" wire:model.defer="question_correct">
                                                                    <option value="">Selecciona la opción correcta</option>
                                                                    @foreach($question_options_list as $idx => $opt)
                                                                        <option value="{{ $idx+1 }}">Opción {{ $idx+1 }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="mb-3">
                                                            <label>Respuesta Correcta</label>
                                                            <input type="text" class="form-control" wire:model.defer="question_correct">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" wire:click="closeQuestionModal">Cancelar</button>
                                                    <button type="button" class="btn btn-primary" wire:click="addExamQuestion">Agregar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group tb-dbtnarea">
                                        <a href="javascript:void(0);" wire:click.prevent="save" class="tb-btn">
                                            @if($editMode)
                                                {{ __('courses.update') }}
                                            @else
                                                {{ __('courses.add_now') }}
                                            @endif
                                        </a>
                                        @if($editMode)
                                            <a href="javascript:void(0);" wire:click.prevent="resetForm" class="tb-btn tb-btnsecondary">{{ __('courses.cancel') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12 tb-md-60">
                <div class="tb-dhb-mainheading">
                    <h4>{{ __('courses.courses') }}</h4>
                    <div class="tb-sortby">
                        <form class="tb-themeform tb-displistform">
                            <fieldset>
                                <div class="tb-themeform__wrap">
                                    {{--  <div class="tb-actionselect">
                                        <a href="javascript:;" class="tb-btn btnred {{ $selectedCourses ? '' : 'd-none' }}" wire:click="deleteSelected">{{ __('courses.delete_selected') }}</a>
                                    </div> --}}
                                    <div class="tb-actionselect">
                                        <div class="tb-select">
                                            <select wire:model.live="sortby" class="form-control tk-select2">
                                                <option value="asc">{{ __('courses.asc') }}</option>
                                                <option value="desc">{{ __('courses.desc') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tb-actionselect">
                                        <div class="tb-select">
                                            <select wire:model.live="perPage" class="form-control tk-select2">
                                                @foreach($perPageOptions as $opt)
                                                    <option value="{{$opt}}">{{$opt}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group tb-inputicon tb-inputheight">
                                        <i class="icon-search"></i>
                                        <input type="text" class="form-control" wire:model.live.debounce.500ms="search" autocomplete="off" placeholder="{{ __('courses.search_here') }}">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>

                <div class="tb-disputetable tb-pageslanguage">
                    @if(!empty($courses) && $courses->count() > 0)
                        <table class="table tb-table tb-dbholder @if(setting('_courses.table_responsive') == 'yes') tb-table-responsive @endif">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="tb-checkbox">
                                            <input id="checkAll" wire:model.lazy="selectAll" type="checkbox">
                                            <label for="checkAll">{{ __('courses.name') }}</label>
                                        </div>
                                    </th>
                                    <th>{{ __('courses.instructor') }}</th>
                                    <th>{{ __('general.description') }}</th>
                                    <th>{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        <td data-label="{{ __('courses.name') }}">
                                            <div class="tb-checkboxwithimg">
                                                <div class="tb-checkbox">
                                                    <input id="course_id{{ $course->id }}" wire:model.lazy="selectedCourses" value="{{ $course->id }}" type="checkbox">
                                                    <label for="course_id{{ $course->id }}">
                                                        <span>{!! $course->name !!}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="{{ __('courses.instructor') }}"><span>{{ $course->instructor_name }}</span></td>
                                        <td data-label="{{ __('general.description') }}"><span>{!! Str::limit($course->description, 50) !!}</span></td>
                                        <td data-label="{{ __('general.actions') }}">
                                            <ul class="tb-action-icon">
                                                <li><a href="javascript:void(0);" wire:click.prevent="edit({{ $course->id }})"><i class="icon-edit-3"></i></a></li>
                                                <li><a href="javascript:void(0);" wire:click.prevent="delete({{ $course->id }})" class="tb-delete"><i class="icon-trash-2"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $courses->links('pagination.custom') }}
                    @else
                        <x-no-record :image="asset('images/empty.png')" :title="__('courses.no_record_title')"/>
                    @endif
                </div>
            </div>
        </div>
    </main>


    {{-- modal de preguntas  --}}
    <div class="modal fade @if($showQuestionModal) show d-block @endif" tabindex="-1" style="@if($showQuestionModal) display:block; background:rgba(0,0,0,0.5); @else display:none; @endif" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Pregunta de Examen</h5>
                    <button type="button" class="btn-close" wire:click="closeQuestionModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pregunta</label>
                        <input type="text" class="form-control" wire:model.defer="question_text">
                    </div>
                    <div class="mb-3">
                        <label>Tipo</label>
                        <select class="form-control" wire:model.defer="question_type" wire:change="resetQuestionOptions">
                            <option value="opcion_unica">Opción única</option>
                            {{-- <option value="abierta">Abierta</option> --}}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Puntaje</label>
                        <input type="number" class="form-control" wire:model.defer="question_score">
                    </div>
                    @if($question_type === 'opcion_unica')
                        <div class="mb-3 d-flex align-items-end">
                            <div class="flex-grow-1">
                                <label>Agregar opción</label>
                                <input type="text" class="form-control" wire:model.defer="question_option_input" placeholder="Escribe la opción">
                            </div>
                            <div class="ms-2">
                                <button type="button" class="btn btn-success" wire:click="addOption">Añadir</button>
                            </div>
                        </div>
                        @if(!empty($question_options_list))
                            <div class="mb-3">
                                <label>Opciones agregadas:</label>
                                <ol>
                                    @foreach($question_options_list as $idx => $opt)
                                        <li>
                                            {{ $opt }}
                                            <button type="button" class="btn btn-link btn-sm text-danger" wire:click="removeOption({{ $idx }})">Eliminar</button>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="mb-3">
                                <label>Respuesta Correcta (número de opción)</label>
                                <select class="form-control" wire:model.defer="question_correct">
                                    <option value="">Selecciona la opción correcta</option>
                                    @foreach($question_options_list as $idx => $opt)
                                        <option value="{{ $idx+1 }}">Opción {{ $idx+1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                   {{--  @else
                        <div class="mb-3">
                            <label>Respuesta Correcta</label>
                            <input type="text" class="form-control" wire:model.defer="question_correct">
                        </div> --}}
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeQuestionModal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="addExamQuestion">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>