<div class="am-profile-setting" wire:init="loadData" wire:key="@this">
    @slot('title')
    {{ __('subject.subject_title') }}
    @endslot
    @include('livewire.pages.tutor.manage-sessions.tabs')
    <div x-data="{search:'', sessionData: @entangle('form')}" class="am-userperinfo">
        @if($isLoading)
        @include('skeletons.manage-subject')
        @else
        @if($subjectGroups->isNotEmpty())
        <div class="am-title_wrap">
            <div class="am-title">
                <h2>{{ __('subject.subject_title') }}</h2>
                <p>{{ __('subject.subject_title_desc') }}</p>
            </div>
        </div>

        <!-- Filtros -->
        <div class="am-filters" style="margin-bottom: 20px; display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <!-- Búsqueda -->
            <div class="am-search" style="flex: 1; min-width: 200px;">
                <div class="am-search-input" style="position: relative;">
                    <input type="text" 
                           wire:model.live.debounce.300ms="searchQuery" 
                           placeholder="{{ __('general.search') }}..." 
                           style="width: 100%; padding: 8px 15px; border: 1px solid #ddd; border-radius: 4px;">
                    <i class="am-icon-search" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #666;"></i>
                </div>
            </div>

            <!-- Filtro de grupos con materias -->
            <div class="am-filter-checkbox" style="display: flex; align-items: center; gap: 8px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" 
                           wire:model.live="showOnlyWithSubjects" 
                           style="width: 16px; height: 16px;">
                    <span>{{ __('subject.show_only_with_subjects') }}</span>
                </label>
            </div>

            <!-- Botón reset -->
            <button wire:click="resetFilters" 
                    class="am-btn" 
                    style="padding: 8px 15px; background-color: #f8f9fa; border: 1px solid #ddd;">
                <i class="am-icon-refresh"></i>
                {{ __('general.reset_filters') }}
            </button>
        </div>

        <div id="subjectList" wire:sortable="updateSubjectGroupOrder" wire:sortable-group="updateSubjectOrder" class="am-subjectlist">
            <!-- for de grupos  -->
            @foreach ($filteredGroups as $index => $group)
            <div class="am-subject" wire:sortable.item="{{ $group?->id }}" wire:key="subject-group-{{ $group?->id }}">
                <div class="am-subject-heading">

                    <!-- icono de grupo -->
                    <div class="am-sotingitem" wire:sortable.handle>
                        <i class="am-icon-youtube-1"></i>
                    </div>

                    <!-- tarjeta con nombre de grupo -->
                    <div wire:ignore.self @class(['am-subject-title', 'collapsed'=> $index != 0]) id="heading-{{ $group?->id }}" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $group?->id }}" aria-expanded="{{ $index == 0 ? 'true': 'false' }}">
                        <h3>{{ $group->name }}</h3>
                        <span class="am-subject-title-icon">
                            <i class="am-icon-minus-02 am-subject-title-icon-open"></i>
                            <i class="am-icon-plus-02 am-subject-title-icon-close"></i>
                        </span>
                    </div>
                </div>






                <div wire:ignore.self id="collapse-{{ $group?->id }}" @class(['collapse', 'show'=> $index == 0]) data-bs-parent="#subjectList">
                    <div class="am-subject-body">
                        <!-- Mostrar UserSubjects del grupo actual -->
                        @if($userSubjects->isNotEmpty())
                        <div class="am-user-subjects">
                            @foreach($userSubjects as $userSubject)
                            @if($userSubject['subject']['subject_group_id'] == $group->id)
                            <div class="am-subject-item" style="display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; border-radius: 8px; margin-bottom: 12px; padding: 15px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                <!-- Ajustar espacio para el nombre y la descripción -->
                                <div class="am-subject-info" style="flex-grow: 0; flex-shrink: 1; flex-basis: 60%;">
                                    <h2 style="color: #004558; font-size: 16px; margin: 0; font-weight: 600;">{{ $userSubject['subject']['name'] }}</h2>
                                    @if($userSubject['description'])
                                    <p style="color: #555; margin: 5px 0 0 0; font-size: 14px;">{{ $userSubject['description'] }}</p>
                                    @endif
                                </div>

                                <!-- Asegurarse de que las acciones se queden al lado derecho -->
                                <div class="am-subject-actions" style="display: flex; gap: 15px; flex-grow: 0; flex-shrink: 0; padding-right: 15px; width: 200px; justify-content: space-between;">
                                    <!-- Botón Editar -->
                                    <div class="am-itemdropdown" style="flex: 1;">
                                        <a href="javascript:void(0);" @click="$wire.editUserSubject({{ $userSubject['id'] }})"
                                            style="display: flex; align-items: center; justify-content: center; color: #004558; text-decoration: none; padding: 5px 10px; border: 1px solid #004558; border-radius: 4px; width: 100%;">
                                            <i class="am-icon-pencil-02"></i>
                                            {{ __('general.edit') }}
                                        </a>
                                    </div>
                                    <!-- Botón Eliminar -->
                                    <div class="am-itemdropdown" style="flex: 1;">
                                        <a href="javascript:void(0);" @click="$wire.dispatch('showConfirm', { subjectId: {{ $userSubject['id'] }}, action : 'delete-user-subject' })"
                                            style="display: flex; align-items: center; justify-content: center; color: #d9534f; text-decoration: none; padding: 5px 10px; border: 1px solid #d9534f; border-radius: 4px; width: 100%;">
                                            <i class="am-icon-trash-02"></i>
                                            {{ __('general.delete') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>




                        @endif

                        <div class="am-addclasses-wrapper">
                            <button
                                class="am-add-class"
                                @click="
                                            sessionData.edit_id = null;
                                            $wire.call('resetForm');
                                            $wire.call('addNewSubject', {{ $group?->id }});
                                            $nextTick(() => {
                                                $wire.dispatch('initSummerNote', {target: '#subject_desc', wiremodel: 'form.description', componentId: @this})
                                                $('.am-select2').prop('disabled', false);
                                                clearFormErrors('#subject_modal form');
                                            })">
                                {{ __('subject.add_new_subject') }}
                                <i class="am-icon-plus-01"></i>
                                <svg>
                                    <rect width="100%" height="100%" rx="10"></rect>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Controles de paginación -->
        @if($totalPages > 1)
        <div class="am-pagination" style="margin-top: 20px; display: flex; justify-content: center; align-items: center; gap: 10px;">
            <!-- Botón Anterior -->
            <button wire:click="previousPage" 
                    @if($currentPage == 1) disabled @endif
                    class="am-btn" 
                    style="padding: 8px 15px; @if($currentPage == 1) opacity: 0.5; @endif">
                <i class="am-icon-arrow-left"></i>
                {{ __('general.previous') }}
            </button>

            <!-- Números de página -->
            <div class="am-pagination-numbers" style="display: flex; gap: 5px;">
                @for($i = 1; $i <= $totalPages; $i++)
                    <button wire:click="goToPage({{ $i }})" 
                            class="am-btn" 
                            style="padding: 8px 15px; @if($currentPage == $i) background-color: #004558; color: white; @endif">
                        {{ $i }}
                    </button>
                @endfor
            </div>

            <!-- Botón Siguiente -->
            <button wire:click="nextPage" 
                    @if($currentPage == $totalPages) disabled @endif
                    class="am-btn" 
                    style="padding: 8px 15px; @if($currentPage == $totalPages) opacity: 0.5; @endif">
                {{ __('general.next') }}
                <i class="am-icon-arrow-right"></i>
            </button>
        </div>
        @endif

        @else


        <x-no-record :image="asset('images/subjects.png')" :title="__('general.no_record_title')" :description="__('general.no_record_desc')" :btn_text="__('subject.add_new_subject')" @click="search = ''; $nextTick(() => $wire.call('addNewSubjectGroup'))" wire:target="addNewSubjectGroup" />
       
       
        @endif
        @endif

        <!-- Modals -->
        <div wire:ignore.self class="modal am-modal fade am-subject_modal" id="subject_modal" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">


                    <div class="am-modal-header">
                        <template x-if="sessionData.edit_id">
                            <h2>{{ __('subject.edit_subject') }} </h2>
                        </template>
                        <template x-if="sessionData.edit_id == ''">
                            <h2>{{ __('subject.add_subject') }}</h2>
                        </template>
                        <span class="am-closepopup" wire:target="saveNewSubject" data-bs-dismiss="modal" wire:loading.attr="disabled">
                            <i class="am-icon-multiply-01"></i>
                        </span>
                    </div>



                    <div class="am-modal-body">
                        <form class="am-themeform am-modal-form">
                            <fieldset>
                                <div @class(['form-group', 'am-invalid'=> $errors->has('form.subject_id')])>
                                    <label class="am-label am-important2" for="subjects">
                                        {{ __('subject.choose_subject') }}
                                    </label>
                                    <span class="am-select" wire:ignore>
                                        <select
                                            data-componentid="@this"
                                            class="am-select2"
                                            data-searchable="true"
                                            id="subjects"
                                            data-wiremodel="form.subject_id"
                                            data-placeholder="{{ __('subject.select_subject') }}"
                                            wire:model="form.subject_id"
                                            data-parent="#subject_modal">
                                            <option value="">{{ __('subject.select_subject') }}</option>
                                        </select>
                                    </span>
                                    <x-input-error field_name="form.subject_id" />
                                </div>



                                <div @class(['form-group', 'am-invalid'=> $errors->has('form.description')])>
                                    <x-input-label class="am-important2" for="introduction" :value="__('subject.breif_introduction')" />
                                    <div class="am-custom-editor" wire:ignore>
                                        <textarea id="subject_desc" class="form-control" placeholder="{{ __('subject.add_introduction') }}">{{ $form->description }}</textarea>
                                        <span class="characters-count"></span>
                                    </div>
                                    <x-input-error field_name="form.description" />
                                </div>




                                <div class="form-group">
                                    <x-input-label for="Profile1" :value="__('general.upload_image')" />
                                    <div class="am-uploadoption" x-data="{isUploading:false}" wire:key="uploading-profile-{{ time() }}">


                                        <div class="tk-draganddrop"
                                            x-bind:class="{ 'am-dragfile' : isDragging, 'am-uploading' : isUploading }"
                                            x-on:drop.prevent="isUploading = true; isDragging = false"
                                            wire:drop.prevent="$upload('form.image', $event.dataTransfer.files[0])">
                                            <x-text-input
                                                name="file"
                                                type="file"
                                                id="at_upload_cover_photo"
                                                x-ref="file_upload"
                                                accept="{{ !empty($allowImgFileExt) ?  join(',', array_map(function($ex){return('.'.$ex);}, $allowImgFileExt)) : '*' }}"
                                                x-on:change="isUploading = true; $wire.upload('form.image', $refs.file_upload.files[0])" />

                                            <label for="at_upload_cover_photo" class="am-uploadfile">
                                                <span class="am-dropfileshadow">
                                                    <svg class="am-border-svg ">
                                                        <rect width="100%" height="100%" rx="12"></rect>
                                                    </svg>
                                                    <i class="am-icon-plus-02"></i>
                                                    <span class="am-uploadiconanimation">
                                                        <i class="am-icon-upload-03"></i>
                                                    </span>
                                                    {{ __('general.drop_file_here') }}
                                                </span>
                                                <em>
                                                    <i class="am-icon-export-03"></i>
                                                </em>
                                                <span>{{ __('general.drop_file') }} <i>{{ __('general.click_here_file') }}</i> {{ __('general.to_upload') }} <em>{{ allowFileExt($allowImgFileExt)  }} ({{ __('general.max') .$allowImageSize.'MB' }})</em></span>
                                            </label>
                                        </div>



                                        @if(!empty($form->image))
                                        <div class="am-uploadedfile">
                                            @if( !empty($form->image) && method_exists($form->image,'temporaryUrl'))
                                            <img src="{{ $form->image->temporaryUrl() }}">
                                            @else
                                            <img src="{{ setting('_general.default_avatar_for_user') ? url(Storage::url(setting('_general.default_avatar_for_user')[0]['path'])) : url(Storage::url($form->image)) }}">
                                            @endif

                                            @if ( !empty($form->image) && method_exists($form->image,'getClientOriginalName'))
                                            <span>{{ $form->image->getClientOriginalName() }}</span>
                                            @else
                                            <span>{{ basename(parse_url(url(Storage::url($form->image)), PHP_URL_PATH)) }}</span>
                                            @endif

                                            <a href="#" wire:click.prevent="removeImage" class="am-delitem">
                                                <i class="am-icon-trash-02"></i>
                                            </a>
                                        </div>
                                        @endif
                                        <x-input-error field_name="form.image" />
                                    </div>
                                </div>




                                <div class="form-group am-mt-10 am-form-btn-wrap">
                                    <button class="am-btn" wire:click.prevent="saveNewSubject" wire:target="saveNewSubject" wire:loading.class="am-btn_disable">{{ __('general.save_update') }} </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>





                </div>
            </div>
        </div>

    </div>
</div>

@push('styles')
@vite([
'public/summernote/summernote-lite.min.css',
])
@endpush
@push('scripts')
<script defer src="{{ asset('summernote/summernote-lite.min.js')}}"></script>
<script defer src="{{ asset('js/livewire-sortable.js')}}"></script>
@endpush