<div wire:ignore.self class="modal fade am-deletepopup" id="deletepopup" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="am-modal-body">
                        <span data-bs-dismiss="modal" class="am-closepopup">
                            <i class="am-icon-multiply-01"></i>
                        </span>
                        <div class="am-deletepopup_icon">
                            <span><i class="am-icon-trash-02"></i></span>
                        </div>
                        <div class="am-deletepopup_title">
                            <h3>{{ __('tutor.confirm_title') }} </h3>
                            <p>{{ __('tutor.confirm_message') }}</p>
                        </div>
                        <div class="am-deletepopup_btns">
                            <a href="javascript:void(0);" class="am-btn am-btnsmall" data-bs-dismiss="modal">{{
                                __('tutor.no_button') }}</a>
                            <a href="javascript:void(0);" wire:click="removePayout" wire:loading.class="am-btn_disable"
                                class="am-btn am-btn-del">
                                {{ __('tutor.yes_button') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>