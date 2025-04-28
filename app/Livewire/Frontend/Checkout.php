<?php

namespace App\Livewire\Frontend;
use App\Models\User;
use Modules\LaraPayease\Facades\PaymentDriver;
use App\Livewire\Forms\Frontend\OrderForm;
use App\Models\Country;
use App\Jobs\SendNotificationJob;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Facades\Cart;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Services\SiteService;
use Illuminate\Support\Str;
use App\Models\SlotBooking;
use App\Services\BillingService;
use App\Services\BookingService;
use App\Services\OrderService;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;

class Checkout extends Component
{
    public OrderForm $form;

    public $user;
    public $methods             = [];
    public $address;
    public $content;
    public $countries           = [];
    public $payAmount, $discount, $subTotal;
    public $totalAmount         = '';
    public $walletBalance       = '';
    public $billingDetail;
    public $payment_methods     = [];
    public $useWalletBalance    = false;
    public $checkoutReady       = true;
    public $subscriptions, $chosenSubscription, $invalidCartItem;
    public $orderDetail;
    public $coupon;
    public  $available_payment_methods           = [];
    private ?OrderService $orderService   = null;
    private ?WalletService $walletService   = null;
    private ?BillingService $billingService = null;
    private ?ProfileService $profileService = null;
    private ?SiteService $siteService = null;
    public function boot() {
        $this->user            = Auth::user();
        $this->orderService   = new OrderService();
        $this->siteService   = new SiteService();
        $this->profileService   = new ProfileService(Auth::user()?->id);
        $this->walletService   = new WalletService();
        $this->billingService   = new BillingService(Auth::user());

    }

    public function mount()
    {
        try {
            // Inicializa Select2 en el frontend
            $this->dispatch('initSelect2', target: '.am-select2');
    
            // Obtener ID de la orden desde la sesión
            $order_id = session('order_id') ?? '';
    
            if ($order_id) {
                $this->orderDetail = $this->orderService->getOrderDetail($order_id);
            } else {
                $this->billingDetail = $this->billingService->getBillingDetail();
                $this->address = $this->billingService->getUserAddress();    
            }
    
            // Cargar métodos de pago disponibles
            $gateways = $this->rearrangeArray(PaymentDriver::supportedGateways());
            $this->methods = array_merge($this->methods, $gateways);
    
            // Obtener saldo de billetera del usuario autenticado
            $this->walletBalance        = $this->walletService->getWalletAmount(Auth::user()->id);
            $this->countries = $this->siteService->getCountries();
    
            // Cargar suscripciones si el módulo está habilitado
            if(Module::has('subscriptions') && Module::isEnabled('subscriptions')){
                $this->subscriptions = (new \Modules\Subscriptions\Services\SubscriptionService())->getUserSubscription(userId: Auth::user()->id);
            }
    
            // Si hay detalles de la orden, llenar los datos del formulario
            if (!empty($this->orderDetail)) {
                $billingData = (object) [
                    "first_name"     => $this->orderDetail->first_name ?? '',
                    "last_name"      => $this->orderDetail->last_name ?? '',
                    "company"        => $this->orderDetail->company ?? 'None',
                    "phone"          => $this->orderDetail->phone ?? '',
                    "payment_method" => $this->orderDetail->payment_method ?? '',
                    "email"          => $this->orderDetail->email ?? ''
                ];
    
                $address = (object) [
                    "country_id" => $this->orderDetail->country ?? '',
                    "state"      => $this->orderDetail->state ?? 'None',
                    "zipcode"    => $this->orderDetail->postal_code ?? '00000',
                    "city"       => $this->orderDetail->city ?? ''
                ];
    
                $this->form->setInfo($billingData);
                $this->form->setUserAddress($address);
                $this->chosenSubscription = $this->orderDetail->subscription_id;
    
            } elseif (!empty($this->billingDetail) && !empty($this->address)) {
                // Si hay detalles de facturación previos, usarlos
                $this->form->setInfo($this->billingDetail);
                $this->form->setUserAddress($this->address, false);
                $this->form->paymentMethod = setting('admin_settings.default_payment_method') ?? '';
    
            } else {
                // Si no hay información, obtener datos del perfil del usuario
                $this->address = $this->profileService->getUserAddress();
    
                $profileData = (object) [
                    "first_name" => $this->user->profile->first_name ?? '',
                    "last_name"  => $this->user->profile->last_name ?? '',
                    "email"      => $this->user->email ?? '',
                    "company"   => $this->user->profile->company ?? 'None',
                    "description " => $this->user->profile->description ?? 'Nada que comentar por ahora',
                ];
    
                $state = $this->siteService->getState($this->address?->state_id);
    
                $addressData = (object) [
                    "country_id" => $this->address?->country_id ?? '',
                    "state"      => $state->name ?? 'None',
                    "zipcode"    => $this->address?->zipcode ?? '00000',
                    "city"       => $this->address?->city ?? ''
                ];
    
                $this->form->setInfo($profileData);
                $this->form->setUserAddress($addressData);
            }
    
            // Preparar el monto del carrito
            $this->prepareCartAmount();
    
            // Si hay una suscripción elegida, actualizarla
            if (!empty($this->chosenSubscription)) {
                $this->updatedChosenSubscription($this->chosenSubscription);
            }
    
            // Obtener métodos de pago disponibles
            $this->getavailablePaymentMethods();
        
        } catch (\Exception $e) {
            // Registrar errores en laravel.log
            Log::error('Error en mount(): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function getAvailablePaymentMethods()
    {
        $payment_methods = setting('admin_settings.payment_method');
        if (!is_array($payment_methods)) {
            $payment_methods = [];
        }
        if (!empty($payment_methods)) {
            foreach ($payment_methods as $type => $value) {
                if (array_key_exists($type, $this->methods)) {
                    $this->available_payment_methods[$type] = $value;
                }
            }
        }
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        $this->form->walletBalance      = $this->walletBalance;
        $this->form->useWalletBalance   = $this->useWalletBalance;

        if ($this->content->count() == 0) {
            redirect()->route('find-tutors');
        }

        return view('livewire.frontend.checkout');
    }

    protected function prepareCartAmount()
    {
        $this->content                  = Cart::content();
        $this->subTotal                 = Cart::subtotal();
        $this->discount                 = Cart::discount();
        $this->totalAmount              = Cart::total();
        $this->form->totalAmount        = $this->totalAmount;
        $this->payAmount                = $this->totalAmount;
    }

    public function updatedUseWalletBalance($value)
    {
        if($value){
            $this->payAmount =  $this->totalAmount - $this->walletBalance ;
        } else {
            $this->payAmount = $this->totalAmount;
        }
    }

    public function updatedChosenSubscription($value)
    {
        $this->useWalletBalance = false;
        $this->walletBalance = 0;
        $subscriptionDiscount = 0;
        $this->form->paymentMethod = 'subscription';
        if (Module::has('subscriptions') && Module::isEnabled('subscriptions') && !empty($value)) {
            $choosedSubscription = $this->subscriptions->where('subscription_id','=',$value)->first();
            if(!empty($choosedSubscription)){
               foreach($this->content as $item){
                    if ($item['cartable_type'] == SlotBooking::class &&
                        ($item['options']['allowed_for_subscriptions'] ?? 0) == 1 &&
                        ($choosedSubscription?->remaining_credits['sessions'] ?? 0) > 0 
                    ){
                        $subscriptionDiscount += $item['price'];
                        $this->checkoutReady   = true;
                        if (!empty($item['options']['discount_code']) ) {
                            $this->removeCoupon($item['options']['discount_code']);
                        }
                    } elseif (Module::has('courses') && Module::isEnabled('courses') && $item['cartable_type'] == \Modules\Courses\Models\Course::class && 
                        ($choosedSubscription?->remaining_credits['courses'] ?? 0) > 0
                    ){
                        $this->checkoutReady   = true;
                        $subscriptionDiscount += $item['price'];
                        if (!empty($item['options']['discount_code']) ) {
                            $this->removeCoupon($item['options']['discount_code']);
                        }
                    } else {
                        $this->checkoutReady = false;
                        $this->invalidCartItem = $item;
                        break;
                    }
               }
            }
        }
        if($subscriptionDiscount <= $this->payAmount){
            $this->payAmount = $this->payAmount - $subscriptionDiscount;
        } else {
            $this->payAmount = 0;
        }
    }

    public function rearrangeArray($array) {
        return array_map(function($details) {
            if (isset($details['keys'])) {
                $details = array_merge($details, $details['keys']);
                unset($details['keys']);
            }
            if (isset($details['ipn_url_type'])) {
                unset($details['ipn_url_type']);
            }
            return $details;
        }, $array);
    }

    public function updateInfo()
    {
        try {
            DB::beginTransaction();
            $orderItems = [];
            
            $data = $this->form->updateInfo();
    
         
          
            if(Module::has('subscriptions') && Module::isEnabled('subscriptions') && !empty($this->chosenSubscription)){
                $data['subscription_id'] = $this->chosenSubscription;
            }
            if(!empty($this->orderDetail)){
                $orderDetail = $this->orderService->updateOrder($this->orderDetail,$data);
            } else {
                $orderDetail = $this->orderService->createOrder($data);
            }
         
            $orderDetail2 = $orderDetail;
            session(['order_id' => $orderDetail->id]);

            foreach ($this->content as $item) {
                $orderItemData = [
                    'order_id'       => $orderDetail->id,
                    'title'          => $item['name'],
                    'quantity'       => $item['qty'],
                    'options'        => $item['options'],
                    'price'          => $item['price'],
                    'total'          => (float)$item['qty'] * (float)$item['price'],
                    'orderable_id'   => $item['cartable_id'],
                    'orderable_type' => $item['cartable_type'],
                ];
                if(Module::has('kupondeal') && Module::isEnabled('kupondeal') && !empty($item['discount_amount'])){
                    $orderItemData['discount_amount'] = $item['discount_amount'];
                }
                $orderItems[] = $orderItemData;
            }

            $this->orderService->storeOrderItems($orderDetail->id,$orderItems);
            if ($this->useWalletBalance ) {
                if ($this->walletBalance >= $this->totalAmount) {
                    $this->walletService->deductFunds(Auth::user()->id, $this->totalAmount, 'deduct_booking', $orderDetail->id);
                } else {
                    $this->walletService->deductFunds(Auth::user()->id, $this->walletBalance, 'deduct_booking', $orderDetail->id);
                }
            }
            if(Module::has('subscriptions') && Module::isEnabled('subscriptions') && !empty($this->chosenSubscription)){
                $this->orderService->updateOrder($orderDetail,['subscription_id' => $this->chosenSubscription]);
            }
           
                $orderDetail = $this->orderService->updateOrder($orderDetail,['status'=>'pending']);
                
                $orderDetail->refresh(); // Recarga los datos de la base de datos
                DB::commit();
                session()->forget('order_id');
                try {
                    // Despachar el job
                  //  dispatch(new CompletePurchaseJob($orderDetail));

                  $emailData = ['userName' => $orderDetail2->email, 
                  'studentName' => $orderDetail2->first_name, 
                  'studentEmail' => $orderDetail2->email,
                  'sessionType' => $orderDetail2->payment_method,
                  'message' => 'Revision en espera'
                ];
             
                 dispatch(new SendNotificationJob('sessionRequest', User::admin(), $emailData))->delay(now()->addSeconds(5));
                 dispatch(new SendNotificationJob('sessionRequest', 'alvarosonco123@gmail.com', $emailData))->delay(now()->addSeconds(15));
                 dispatch(new SendNotificationJob('sessionRequest', 'gabriel.alpiry@gmail.com', $emailData))->delay(now()->addSeconds(25));
                 dispatch(new SendNotificationJob('sessionRequest', 'kevinurdaniviaespinoza@gmail.com', $emailData))->delay(now()->addSeconds(35));
                 dispatch(new SendNotificationJob('sessionRequest', 'cgguachallab@gmail.com', $emailData))->delay(now()->addSeconds(45));
                 dispatch(new SendNotificationJob('sessionRequest', 'melanydorado398@gmail.com', $emailData))->delay(now()->addSeconds(55));


                    Cart::clear();
                    redirect()->route('thank-you', ['id' => $orderDetail->id]);    
                    // Mensaje de éxito
                    session()->flash('message', 'Compra completada con éxito.');
                } catch (\Exception $ex) {
                    // Capturar el error y devolverlo como parte de la respuesta
                    session()->flash('error', 'Ocurrió un error al procesar la compra: ' . $ex->getMessage());
                }
               
                    
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function removeCart($id, $type)
    {
        if($type == 'App\Models\SlotBooking'){
            (new BookingService($this->user))->removeReservedBooking($id);
        }
        Cart::remove($id, $type);
        $this->prepareCartAmount();
    }

    public function removeCoupon($couponCode)
    {
        $response = \Modules\KuponDeal\Facades\KuponDeal::removeCoupon($couponCode);
        $this->dispatch('showAlertMessage', type: $response['status'],  message: $response['message']);
        $this->prepareCartAmount();
        $this->dispatch('cart-updated', cart_data: Cart::content(), discount: formatAmount(Cart::discount(), true), total: formatAmount(Cart::total(), true), subTotal: formatAmount(Cart::subtotal(), true));
    }

    public function applyCoupon()
    {
        $this->validate([
            'coupon' => 'required|string|max:30',
        ]);

        if (!empty($this->chosenSubscription)) {
            $this->dispatch('showAlertMessage', type: 'error',  message: __('subscriptions::subscription.cupon_not_applicable_with_subscription'));
        }

        if (Module::has('kupondeal') && Module::isEnabled('kupondeal')) {
            $response = \Modules\KuponDeal\Facades\KuponDeal::applyCoupon($this->coupon);
            $this->dispatch('showAlertMessage', type: $response['status'],  message: $response['message']);
        } else {
            $this->dispatch('showAlertMessage', type: 'error',  message: __('kupondeal::kupondeal.kupondeal_not_active'));
        }
        $this->reset('coupon');
        $this->prepareCartAmount();
    }
}
