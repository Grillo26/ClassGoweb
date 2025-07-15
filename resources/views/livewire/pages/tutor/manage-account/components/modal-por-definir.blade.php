  <div wire:ignore.self class="modal fade am-setuppayoneerpopup" id="setuppayoneerpopup"
            data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="background-color: rgb(2, 48, 71)">
                <div class="modal-content">
                    <div class="am-modal-header">
                        <h2>{{ __('tutor.setup_account',['payout_method' => ucfirst($form?->current_method)]) }}</h2>
                        <span data-bs-dismiss="modal" class="am-closepopup">
                            <i class="am-icon-multiply-01"></i>
                        </span>
                    </div>
                    <div class="am-modal-body">
                        <form class="am-themeform">
                            <fieldset>
                                <div @class(['form-group', 'am-invalid'=> $errors->has('form.email')])>
                                    <x-input-label for="Email" class="am-important" :value="__('tutor.email_label')" />
                                    <x-text-input id="Email" wire:model="form.email" name="Email"
                                        placeholder="{{ __('tutor.enter_email') }}" type="text" />
                                    <x-input-error field_name="form.email" />
                                </div>
                                <div class="form-group am-form-btns">
                                    <button wire:target="updatePayout" wire:loading.class="am-btn_disable"
                                        wire:click="updatePayout" type="button" class="am-btn">{{
                                        __('tutor.save_update') }}</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>