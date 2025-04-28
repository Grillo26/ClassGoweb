<?php
namespace App\Livewire\Pages\Tutor\ManageAccount;


use App\Livewire\Forms\Tutor\Payout\PayoutForm;
use App\Services\PayoutService;
use App\Services\WalletService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\UserPayoutMethod;
use Livewire\WithPagination;

class ManageAccount extends Component
{
    use WithPagination;
    use WithFileUploads;
     
      public $qrImage; // Variable para almacenar la imagen
    public $currentQRPath; // Variable para la ruta de la imagen QR

    public $data      = [];
    public $status    = '';
    public $isLoading = true;
    public $earnedAmount, $pendingFunds;
    public $chart = false;
    public $payoutStatus;
    public $selectedDate;
    public $walletBalance;
    public $withdrawalsType;
    public $withdrawalBalance;

    public  PayoutForm $form;
    private ?WalletService $walletService   = null;
    private ?PayoutService $payoutService   = null;

    public function boot()
    {
        $this->walletService   = new WalletService();
        $this->payoutService   = new PayoutService();
    }

       public function mount()
    {
        $this->chart = true;
        $this->selectedDate = now(getUserTimezone());
        $this->data = $this->walletService->getUserEarnings(Auth::user()->id, $this->selectedDate);
        $this->earnedAmount = $this->walletService->getEarnedIncome(Auth::user()->id);
        $this->pendingFunds = $this->walletService->getPendingAvailableFunds(Auth::user()->id);
        $this->dispatch('initSelect2', target: '.am-select2');

        // Obtener la imagen QR actual del usuario si existe
        $payoutMethod = UserPayoutMethod::where('user_id', Auth::id())
            ->where('payout_method', 'QR')
            ->first();

        $this->currentQRPath = $payoutMethod ? $payoutMethod->img_qr : null;
    }


    #[On('refresh-payouts')]
    public function refresh(){
        $this->loadData();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $withdrawalDetails          = $this->payoutService->getWithdrawalDetail(Auth::user()->id,$this->status);
        return view('livewire.pages.tutor.manage-account.manage-account',compact('withdrawalDetails'));
    }

    public function loadData()
    {
        $this->isLoading      = true;
        if($this->chart){

            $this->dispatch('initChartJs', currentDate: parseToUserTz($this->selectedDate->copy())->format('F, Y'), data: $this->data);
        }
        $this->chart = false;
        $this->walletBalance        = $this->walletService->getWalletAmount(Auth::user()->id);
        $this->withdrawalBalance    = $this->payoutService->geWithdrawalBalance(Auth::user()->id)->toArray();
        $this->withdrawalsType      = $this->payoutService->getWithdrawalTypes(Auth::user()->id);
        $this->payoutStatus         = $this->payoutService->getPayoutStatus(Auth::user()->id);
        $this->dispatch('initSelect2', target: '.am-select2' );
        $this->isLoading            = false;
    }
    
    public function updateQR()
    {
        
            \Log::info('Inicio de la actualización del código QR para el usuario: ' . Auth::id());

            // Verificar si hay archivo antes de validar
              if (!$this->qrImage) {
                    \Log::info('No hay nadaaaaaaaa' );
            $this->dispatch('showAlertMessage', [
                'type' => 'error',
                'title' => __('general.error_title'),
                'message' => __('tutor.no_file_selected')
            ]);
            return;
        }

            // Validar que se suba una imagen válida
            $this->validate([
                'qrImage' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Formatos permitidos
            ]);

            \Log::info('Validación de imagen QR completada correctamente.');

            // Buscar el método de pago QR del usuario, incluyendo eliminados
            $existingPayout = UserPayoutMethod::withTrashed()
                ->where('user_id', Auth::id())
                ->where('payout_method', 'QR')
                ->first();

            // Si el registro existe pero está eliminado, restaurarlo
            if ($existingPayout && $existingPayout->trashed()) {
                $existingPayout->restore();
                \Log::info("Registro QR restaurado para el usuario: " . Auth::id());
            }

            // Si ya tiene un QR registrado, eliminar la imagen anterior
            if ($existingPayout && $existingPayout->img_qr) {
                Storage::disk('public')->delete($existingPayout->img_qr);
                \Log::info("Imagen QR anterior eliminada: " . $existingPayout->img_qr);
            }

            // Guardar la nueva imagen y obtener la ruta
            $path = $this->qrImage->store('qr_codes', 'public');

            if (!$path) {
                throw new \Exception(__('tutor.qr_upload_failed'));
            }

            \Log::info("Nueva imagen QR subida correctamente: " . $path);

            // **Actualizar o crear el método de pago**
            $payout = UserPayoutMethod::updateOrCreate(
                ['user_id' => Auth::id(), 'payout_method' => 'QR'], // Claves únicas
                ['img_qr' => $path, 'status' => 'active']
            );

            if (!$payout) {
                throw new \Exception(__('tutor.qr_save_failed'));
            }

            \Log::info("Código QR guardado en la base de datos para el usuario: " . Auth::id());

            // Actualizar la variable de la vista
            $this->currentQRPath = $path;

            // Enviar mensaje de éxito
            $this->dispatch('showAlertMessage', [
                'type' => 'success',
                'title' => __('general.success_title'),
                'message' => __('tutor.qr_updated')
            ]);

            // Cerrar el modal
            $this->dispatch('toggleModel', ['id' => 'setupqrpopup', 'action' => 'hide']);

            // 🔄 Recargar la página después de 1 segundo
        return redirect()->to(request()->header('Referer'));

       
    }

    public function updatedSelectedDate($date)
    {
        $date               = $date instanceof Carbon ? $date->format('F, Y') : $date;
        $this->selectedDate = Carbon::createFromFormat('d F, Y', "01 $date");
        $this->loadData();
    }

      public function updatePayout()
    {
        $data = $this->form->validatePayout();

        $response = isDemoSite();
        if ($response) {
            $this->dispatch('showAlertMessage', [
                'type' => 'error',
                'title' => __('general.demosite_res_title'),
                'message' => __('general.demosite_res_txt')
            ]);
            $this->dispatch('toggleModel', id: 'setupaccountpopup', action: 'hide');
            $this->dispatch('toggleModel', id: 'setuppayoneerpopup', action: 'hide');
            return;
        }

        // Si el método de pago es QR, validar y guardar la imagen
        if ($this->form->current_method === 'QR' && $this->qrImage) {
            $this->validate([
                'qrImage' => 'image|max:2048', // Solo imágenes, máximo 2MB
            ]);

            // Guardar la imagen en storage
            $path = $this->qrImage->store('qr_codes', 'public');
            $data['img_qr'] = $path; // Agregar la ruta de la imagen al array de datos
        }

        $payout = $this->payoutService->addPayoutDetail(Auth::user()->id, $this->form->current_method, $data);

        if ($payout) {
            $this->dispatch('showAlertMessage', [
                'type' => 'success',
                'title' => __('general.success_title'),
                'message' => __('general.payout_account_add')
            ]);
        }

        $this->form->reset();
        $this->dispatch('toggleModel', id: 'setupaccountpopup', action: 'hide');
        $this->dispatch('toggleModel', id: 'setuppayoneerpopup', action: 'hide');
        $this->dispatch('reload-balances');
        $this->loadData();
    }

    public function removePayout()
    {
        $response = isDemoSite();
        if( $response ){
            $this->dispatch('showAlertMessage', type: 'error', title:  __('general.demosite_res_title') , message: __('general.demosite_res_txt'));
            $this->dispatch('toggleModel', id:'deletepopup', action:'hide');
            return;
        }

        $payout = $this->payoutService->deletePayout(Auth::user()->id,$this->form->current_method);
        if( $payout){
            $this->dispatch('showAlertMessage', type: 'success', title: __('general.success_title') , message: __('general.payout_account_remove'));
        }
        $this->dispatch('toggleModel', id:'deletepopup', action:'hide');
        $this->dispatch('reload-balances');
        $this->loadData();

    }

    public function updateStatus($method)
    {

        $response = isDemoSite();
        if( $response ){
            $this->dispatch('showAlertMessage', type: 'error', title:  __('general.demosite_res_title') , message: __('general.demosite_res_txt'));
            return;
        }

        $this->payoutService->updatePayoutStatus(Auth::user()->id,$method);
        $this->dispatch('reload-balances');
        $this->loadData();

    }

    public function openPayout($method,$id)
    {
            $this->form->reset();
            $this->form->resetErrorBag();
            $this->form->current_method = $method;
            $this->dispatch('toggleModel', id: $id, action:'show');
    }

}
