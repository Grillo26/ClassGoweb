<div class="container" wire:init="loadData" wire:key="@this">
    @slot('title')
    {{ __('subject.subject_title') }}
    @endslot
    @include('livewire.pages.tutor.manage-sessions.tabs')
    <div x-data="{search:'', sessionData: @entangle('form')}" class="container-card">

        <div class="am-titulo-card">
            <div class="am-titulo">
                <h2 class="titulo_primario">{{ __('subject.subject_title') }}</h2>
                <p class="titulo_secundario">{{ __('subject.subject_title_desc') }}</p>
            </div>
        </div>
        <!-- Filtros - Siempre visibles -->
        @include('livewire.pages.tutor.manage-sessions.components.filtros')

        <!-- Contenido -->
        @if($isLoading)
        @include('skeletons.manage-subject')
        @else
        <div id="subjectList" wire:sortable="updateSubjectGroupOrder" wire:sortable-group="updateSubjectOrder"
            class="am-subjectlist">
            @if($subjectGroups->isEmpty())
            <div class="am-no-records"
                style="text-align: center; padding: 30px; background: #f8f9fa; border-radius: 8px; margin: 20px auto; max-width: 500px;">
                <img src="{{ asset('images/subjects.png') }}" alt="No records"
                    style="max-width: 120px; margin-bottom: 15px;">
                <h3 style="color: #004558; margin-bottom: 10px; font-size: 18px;">{{ __('general.no_record_title') }}
                </h3>
                <p style="color: #666; margin-bottom: 20px; font-size: 14px;">{{ __('general.no_record_desc') }}</p>
            </div>
            @else
            @foreach ($filteredGroups as $index => $group)
            <div class="am-group-card" wire:key="subject-group-{{ $group?->id }}">
                <div x-data="{ open: false }">
                    <div class="am-group-header" @click="open = !open">
                        <span class="am-group-title">{{ $group->name }}</span>
                        <span class="am-group-toggle" :class="{'open': open}"></span>
                    </div>
                    <div class="am-group-body" x-show="open">
                        @if($userSubjects->isNotEmpty())
                        @foreach($userSubjects as $userSubject)
                        @if($userSubject['subject']['subject_group_id'] == $group->id)
                        <div class="am-subject-card">
                            <div class="am-subject-info">
                                <span class="am-subject-name">{{ $userSubject['subject']['name'] }}</span>
                                @if($userSubject['description'])
                                <span class="am-subject-desc">{{ $userSubject['description'] }}</span>
                                @endif
                            </div>
                            <div class="am-subject-actions">
                                <a href="javascript:void(0);" @click="$wire.editUserSubject({{ $userSubject['id'] }})"
                                    class="am-btn-icon am-btn-edit" title="Editar">
                                    <i class="am-icon-pencil-02"></i>
                                </a>
                                <a href="javascript:void(0);"
                                    @click="$wire.dispatch('showConfirm', { subjectId: {{ $userSubject['id'] }}, action : 'delete-user-subject' })"
                                    class="am-btn-icon am-btn-delete" title="Eliminar">
                                    <i class="am-icon-trash-02"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif
                        <div class="am-add-topic-row">
                            <button class="am-btn-add-topic" @click="
                            sessionData.edit_id = null;
                            $wire.call('resetForm');
                            $wire.call('addNewSubject', {{ $group?->id }});
                            $nextTick(() => {
                                $wire.dispatch('initSummerNote', {target: '#subject_desc', wiremodel: 'form.description', componentId: @this})
                                $('.am-select2').prop('disabled', false);
                                clearFormErrors('#subject_modal form');
                            })">
                                + Agregar nuevo materia
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <!-- Controles de paginación -->
            @if($totalPages > 1)
            @include('livewire.pages.tutor.manage-sessions.components.paginacion')
            @endif
            @endif
        </div>
        @endif
        <!-- Modals crear o editar -->
        @include('livewire.pages.tutor.manage-sessions.components.modal')
    </div>
</div>

@push('styles')

<link rel="stylesheet" href="{{ asset('css/livewire/pages/tutor/manage-sessions/manage-subjects.css') }}">

@vite([
'public/summernote/summernote-lite.min.css',
])
@endpush
@push('scripts')
<script defer src="{{ asset('summernote/summernote-lite.min.js')}}"></script>
<script defer src="{{ asset('js/livewire-sortable.js')}}"></script>
@endpush