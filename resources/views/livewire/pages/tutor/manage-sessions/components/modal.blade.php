<div wire:ignore.self class="modal am-modal fade am-subject_modal" id="subject_modal" data-bs-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered" >
        <div class="modal-content" style="background-color: white!important;">
            <!-- Modal  Header-->
            <div class="am-modal-header">
                <template x-if="sessionData.edit_id">
                    <h2 style="color: black!important">{{ __('subject.edit_subject') }} </h2>
                </template>
                <template x-if="sessionData.edit_id == ''">
                    <h2 style="color: black!important">{{ __('subject.add_subject') }}</h2>
                </template>
                <span class="am-closepopup" wire:target="saveNewSubject" data-bs-dismiss="modal"
                    wire:loading.attr="disabled">
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
                                <select data-componentid="@this" class="am-select2" data-searchable="true"
                                    id="subjects" data-wiremodel="form.subject_id"
                                    data-placeholder="{{ __('subject.select_subject') }}"
                                    wire:model="form.subject_id" data-parent="#subject_modal">
                                    <option style="" value="">{{ __('subject.select_subject') }}</option>
                                </select>
                            </span>
                            <x-input-error field_name="form.subject_id" />
                        </div>
                        <div @class(['form-group', 'am-invalid'=> $errors->has('form.description')])>
                            <x-input-label class="am-important2" for="introduction"
                                :value="__('subject.breif_introduction')" />
                            <div class="am-custom-editor">
                                <textarea id="subject_desc" class="form-control"
                                    placeholder="{{ __('subject.add_introduction') }}"
                                    wire:model="form.description"></textarea>
                                <span class="characters-count"></span>
                            </div>
                            <x-input-error field_name="form.description" />
                        </div>

                        <div class="form-group am-mt-10 am-form-btn-wrap">
                            <button class="am-btn" wire:click.prevent="saveNewSubject"
                                wire:target="saveNewSubject" wire:loading.class="am-btn_disable">{{
                                __('general.save_update') }} </button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <!-- Modal formulario -->
        </div>
    </div>
</div>