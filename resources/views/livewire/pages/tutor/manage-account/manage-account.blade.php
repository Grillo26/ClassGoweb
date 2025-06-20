<div class="am-accountwrap" wire:init="loadData">
    @slot('title')
    {{ __('general.dashboard') }}
    @endslot
    @if($isLoading)
    @include('skeletons.manage-account')
    @else
    <div class="am-section-load" wire:loading wire:target="refresh">
        @include('skeletons.manage-account')
    </div>
    <div>

        <div wire:loading.remove wire:target="refresh">
            <div class="am-dbbox">
                <div class="am-dbbox_title">
                    <h2>{{ __('tutor.setup_payouts_methods') }}</h2>
                </div>
                <div class="am-dbbox_content">
                    <div x-data="{current_method:@entangle('form.current_method')}" class="am-payout_wrap">
                        @php
                        $payout_method = [

                        'bank' => [
                        'id' => 'bank',
                        'title' => __('tutor.bank_transfer') ,
                        'image' => 'images/bank.svg',
                        'price' => $withdrawalsType['bank']['total_amount'] ?? 0,
                        'status' => isset($payoutStatus['bank']) ??[],
                        'remove_action' => isset($payoutStatus['bank']) ? 'deletepopup' : 'setupaccountpopup',
                        'btnTitle' => isset($payoutStatus['bank']) ?__('tutor.remove_account') : __('tutor.add_account')
                        ],
                        'QR' => [
                        'id' => 'QR',
                        'title' => __('tutor.QR_code') ,
                        'image' => 'images/QR.png',
                        'price' => $withdrawalsType['QR']['total_amount'] ?? 0,
                        'status' => isset($payoutStatus['QR']) ??[],
                        'remove_action' => isset($payoutStatus['QR']) ? 'deletepopup' : 'setupqrpopup',
                        'btnTitle' => isset($payoutStatus['QR']) ?__('tutor.remove_account') : __('tutor.add_account')
                        ],
                        ];


                        @endphp
                        @foreach ($payout_method as $method => $item)

                        <div wire:key=$method.'-'.time()}}" class="am-payout_item">
                            {{-- @php
                            dd($qr);

                            @endphp --}}
                            <figure class="am-payout_item_img" @if($item['id']==='QR' && !empty($qr?->img_qr))
                                style="cursor:pointer"
                                onclick="window.open('{{ asset('storage/' . $qr->img_qr) }}', '_blank')"
                                title="{{ __('tutor.view_qr_code') }}"
                                @endif
                                >
                                <img src="{{ asset($item['image']) }}" alt="img description">
                            </figure>
                            @if ($item['price'])
                            <strong>{!! formatAmount($item['price'], true) !!}</strong>
                            @endif
                            @if ($item['status'])
                            <span>{{ $item['title'] }}</span>
                            @endif
                            <div class="am-radio">
                                @if ($item['status'])
                                <input wire:click="updateStatus('{{ $method }}')" {{
                                    $payoutStatus[$method]['status']=='active' ? 'checked' : '' }} type="radio"
                                    id="default_{{ $method }}" name="method">
                                <label for="default_{{ $method }}">{{ __('tutor.make_default_method') }}</label>
                                @else
                                <strong>{{ $item['title'] }}</strong>
                                @if(!$item['price'] > 0)
                                <span>{{ __('tutor.no_account_added_yet') }}</span>
                                @endif
                                @endif
                            </div>
                            <div class="am-payout_item_remove">
                                @if ($item['status'])
                                <a href="javascript:void(0);"
                                    @click="current_method = @js($method); $wire.dispatch('toggleModel', { id: '{{ $item['remove_action'] }}', action: 'show' });">{{
                                    $item['btnTitle'] }}</a>
                                @else
                                <a href="javascript:void(0);"
                                    wire:click="openPayout('{{ $method }}', '{{ $item['remove_action'] }}')">{{
                                    $item['btnTitle'] }}</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="am-payout_description">
                        <p>{{__('tutor.detail')}} <a
                                href="{{ url('terms-condition') }}">{{__('tutor.transfer_policy')}}</a></p>
                    </div>
                </div>
            </div>
        </div>


        <!-- setup account popup modal -->
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


        <!-- Modal para el formulario de QR -->
        <div wire:ignore.self class="modal fade am-setupqrpopup" id="setupqrpopup" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgb(2, 48, 71)">
                    <div class="am-modal-header">
                        <h2>{{ __('tutor.setup_qr_code') }}</h2>
                        <span data-bs-dismiss="modal" class="am-closepopup">
                            <i class="am-icon-multiply-01"></i>
                        </span>
                    </div>
                    <div class="am-modal-body">
                        <form wire:submit.prevent="updateQR" enctype="multipart/form-data">
                            <!-- Asegura el enctype -->
                            <fieldset>
                                <!-- Campo para subir la imagen del QR -->
                                <div class="form-group">
                                    <label for="qr_image" class="am-important-bank">{{ __('tutor.upload_qr_code')
                                        }}</label>
                                    <input style="color: white" wire:model="qrImage" id="qr_image" name="qr_image"
                                        type="file" accept="image/*">
                                    @error('qrImage') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <!-- Boton para guardar -->
                                <div class="form-group am-form-btns" style="position:relative;">
                                    <button wire:target="updateQR" wire:loading.class="am-btn_disable"
                                        wire:click="updateQR" type="button" class="am-btn">{{ __('tutor.save_update')
                                        }}</button>
                                    <div wire:loading wire:target="updateQR" class="am-loader-overlay">
                                        <div class="am-loader"></div>
                                        <span style="color:white;display:block;margin-top:10px;">{{
                                            __('general.loading') }}</span>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>





        <!-- setup payoneer popup modal -->
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





        <!-- Delete modal -->
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
                            <h3>{{ __('tutor.confirm_title') }}</h3>
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

        <!-- Modal de verificación exitosa -->
        <div class="modal fade" id="verifiedModal" tabindex="-1" aria-labelledby="verifiedModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifiedModalLabel">¡Correo verificado!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p>Tu correo electrónico ha sido verificado exitosamente. ¡Bienvenido a ClassGo!</p>
                        <hr>
                        <p>¿Quieres compartir tu logro en tus redes sociales?</p>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-outline-primary" title="Compartir en Facebook">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=¡Me%20he%20registrado%20en%20ClassGo!%20{{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-outline-info" title="Compartir en X">
                                <i class="fab fa-x-twitter"></i> X
                            </a>
                            <a href="https://wa.me/?text=¡Me%20he%20registrado%20en%20ClassGo!%20{{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-outline-success" title="Compartir en WhatsApp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Omitir</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>



    </div>
    @endif
</div>
@push('styles')
@vite([
'public/css/flatpicker.css',
'public/css/flatpicker-month-year-plugin.css'
])
<style>
    .am-loader-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .am-loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #754FFE;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
@endpush







@push('scripts')
<script defer src="{{ asset('js/flatpicker.js') }}"></script>
<script defer src="{{ asset('js/flatpicker-month-year-plugin.js') }}"></script>
<script defer src="{{ asset('js/chart.js')}}"></script>
<script type="text/javascript" data-navigate-once>
    var earningsChart;
    var component = '';
    document.addEventListener('livewire:navigated', function() {
        component = @this;
    }, {
        once: true
    });

    document.addEventListener('initChartJs', (event) => {
        setTimeout(() => {
            initCalendarJs(event.detail.currentDate);
            renderChart(event.detail.data.earnings, event.detail.data.days);
        }, 500);
    })

    function initCalendarJs(defaultDate) {
        $("#calendar-month-year").flatpickr({
            defaultDate: defaultDate,
            disableMobile: true,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true, //defaults to false
                    dateFormat: "F, Y", //defaults to "F Y"
                })
            ],
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('selectedDate', dateStr);
            }
        });
    }

    function renderChart(earnigns, labels) {
        var canvas = document.getElementById('am-themechart');
        if (!canvas) return; // Evita el error si el canvas no existe
        let days = Object.values(labels).map(day => day.toString());
        var ctx = canvas.getContext('2d');
        if (earningsChart) {
            earningsChart.destroy();
        }
        var gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(117, 79, 254, 0.30)');
        gradient.addColorStop(1, 'rgba(255, 255, 255, 0.00)');

        earningsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: days,
                datasets: [{
                    label: 'Earning',
                    data: earnigns,
                    backgroundColor: gradient,
                    borderColor: '#754FFE',
                    tension: 0.5,
                    borderWidth: 1,
                    fill: true,
                    pointBackgroundColor: '#754FFE',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#754FFE'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            drawTicks: false,
                            // display:false,
                        },

                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawTicks: false,
                        },
                        border: {
                            display: false,
                            dash: [12, 12]
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `$${context.formattedValue} Earning`;
                            }
                        }
                    }
                }
            }
        });
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verifica si la URL tiene el parámetro ?verified=1
        const params = new URLSearchParams(window.location.search);
        if (params.get('verified') === '1') {
            const modal = document.getElementById('verifiedModal');
            if (modal) {
                $('#verifiedModal').modal('show');
            }
        }
    });
</script>
@endpush