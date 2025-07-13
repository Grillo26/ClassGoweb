 <div wire:ignore.self class="modal fade am-setupaccountpopup" id="setupaccountpopup" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="am-modal-header">
                        <h2>{{ __('tutor.setup_bank_account') }}</h2>
                        <span data-bs-dismiss="modal" class="am-closepopup">
                            <i class="am-icon-multiply-01"></i>
                        </span>
                    </div>
                    <div class="am-modal-body">
                        <form class="am-themeform">
                            <fieldset>
                                <div @class(['form-group', 'am-invalid'=> $errors->has('form.title')])>
                                    <label for="accounttitle" class="am-important-bank">{{
                                        __('tutor.bank_account_title') }}</label>
                                    <x-text-input wire:model="form.title" id="accounttitle" name="accounttitle"
                                        placeholder="{{ __('tutor.enter_bank_account_title') }}" type="text" />
                                    <x-input-error field_name="form.title" />
                                </div>
                                <div @class(['form-group', 'am-invalid'=> $errors->has('form.accountNumber')])>
                                    <label for="account" class="am-important-bank">{{ __('tutor.bank_account_number')
                                        }}</label>
                                    <x-text-input wire:model="form.accountNumber" id="account" name="account"
                                        placeholder="{{ __('tutor.enter_bank_account_number') }}" type="text" />
                                    <x-input-error field_name="form.accountNumber" />
                                </div>
                                <div @class(['form-group', 'am-invalid'=> $errors->has('form.bankName')])>
                                    <label for="bankname" class="am-important-bank">{{ __('tutor.bank_name') }}</label>
                                    <x-text-input wire:model="form.bankName" id="bankname" name="bankname"
                                        placeholder="{{ __('tutor.enter_bank_name') }}" type="text" />
                                    <x-input-error field_name="form.bankName" />
                                </div>
                                <div @class(['form-group', 'am-invalid'=> $errors->has('form.bankRoutingNumber')])>
                                    <label for="routingnum" class="am-important-bank">{{ __('tutor.bank_routing_number')
                                        }}</label>
                                    <x-text-input wire:model="form.bankRoutingNumber" id="routingnum" name="routingnum"
                                        placeholder="{{ __('tutor.enter_bank_routing_number') }}" type="text" />
                                    <x-input-error field_name="form.bankRoutingNumber" />
                                </div>
                                <!--<div @class(['form-group', 'am-invalid'=> $errors->has('form.bankIban')])>-->
                                <!--    <label for="bankiban" class="am-important-bank">{{ __('tutor.bank_iban') }}</label>-->
                                <!--    <x-text-input wire:model="form.bankIban" id="bankiban" name="bankiban"-->
                                <!--        placeholder="{{ __('tutor.enter_bank_iban') }}" type="text" />-->
                                <!--    <x-input-error field_name="form.bankIban" />-->
                                <!--</div>-->

                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
