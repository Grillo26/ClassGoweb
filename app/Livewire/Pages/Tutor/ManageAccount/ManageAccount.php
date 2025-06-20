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
    public $data = [];
    public $status = '';
    public $isLoading = true;
    public $earnedAmount, $pendingFunds;
    public $chart = false;
    public $payoutStatus;
    public $selectedDate;
    public $walletBalance;
    public $withdrawalsType;
    public $withdrawalBalance;
    public PayoutForm $form;
    private ?WalletService $walletService = null;
    private ?PayoutService $payoutService = null;

    public function boot()
    {
        $this->walletService = new WalletService();
        $this->payoutService = new PayoutService();
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
            ->where('status', 'active') // Solo métodos activos
            ->first();
        $this->currentQRPath = $payoutMethod ? $payoutMethod->img_qr : null;
    }


    #[On('refresh-payouts')]
    public function refresh()
    {
        $this->loadData();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $withdrawalDetails = $this->payoutService->getWithdrawalDetail(Auth::user()->id, $this->status);
        $qr = UserPayoutMethod::withTrashed()
            ->where('user_id', Auth::id())
            ->where('payout_method', 'QR')
            ->first();
        return view('livewire.pages.tutor.manage-account.manage-account', compact('withdrawalDetails', 'qr'));
    }

    public function loadData()
    {
        $this->isLoading = true;
        if ($this->chart) {
            $this->dispatch('initChartJs', currentDate: parseToUserTz($this->selectedDate->copy())->format('F, Y'), data: $this->data);
        }
        $this->chart = false;
        $this->walletBalance = $this->walletService->getWalletAmount(Auth::user()->id);
        $this->withdrawalBalance = $this->payoutService->geWithdrawalBalance(Auth::user()->id)->toArray();
        $this->withdrawalsType = $this->payoutService->getWithdrawalTypes(Auth::user()->id);
        $this->payoutStatus = $this->payoutService->getPayoutStatus(Auth::user()->id);
        $this->dispatch('initSelect2', target: '.am-select2');
        $this->isLoading = false;
    }

    public function updateQR()
    {
        // Verificar si hay archivo antes de validar
        if (!$this->qrImage) {
            $this->dispatch('showAlertMessage', [
                'type' => 'error',
                'title' => __('general.error_title'),
                'message' => __('tutor.no_file_selected')
            ]);
            return;
        }
        // Simular carga de 3 segundos
        sleep(3);
        $this->validate([
            'qrImage' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Formatos permitidos
        ]);
        // Buscar el método de pago QR del usuario, incluyendo eliminados
        $existingPayout = UserPayoutMethod::withTrashed()
            ->where('user_id', Auth::id())
            ->where('payout_method', 'QR')
            ->first();
        // Si el registro existe pero está eliminado, restaurarlo
        if ($existingPayout && $existingPayout->trashed()) {
            $existingPayout->restore();
        }
        if ($existingPayout && $existingPayout->img_qr) {
            $imgPath = $existingPayout->img_qr;
            if (strpos($imgPath, 'storage/') === 0) {
                $imgPath = substr($imgPath, 8); // Quita 'storage/'
            }
            Storage::disk('public')->delete($imgPath);
        }
        $filename = time() . '_' . $this->qrImage->getClientOriginalName();
        $tempPath = $this->qrImage->storeAs('temp', $filename);
        $destinationPath = public_path('storage/qr_codes');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }
        rename(storage_path('app/' . $tempPath), $destinationPath . '/' . $filename);
        $path = 'qr_codes/' . $filename;
        if (!$path) {
            throw new \Exception(__('tutor.qr_upload_failed'));
        }
        // **Actualizar o crear el método de pago**
        $payout = UserPayoutMethod::updateOrCreate(
            ['user_id' => Auth::id(), 'payout_method' => 'QR'], // Claves únicas
            ['img_qr' => $path, 'status' => 'active']
        );
        if (!$payout) {
            throw new \Exception(__('tutor.qr_save_failed'));
        }
        $this->currentQRPath = 'storage/' . $path;
        $this->dispatch('showAlertMessage', [
            'type' => 'success',
            'title' => __('general.success_title'),
            'message' => __('tutor.qr_updated')
        ]);
        $this->dispatch('toggleModel', ['id' => 'setupqrpopup', 'action' => 'hide']);// Cerrar el modal
        return redirect()->to(request()->header('Referer'));
    }

    public function updatedSelectedDate($date)
    {
        $date = $date instanceof Carbon ? $date->format('F, Y') : $date;
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
        if ($this->form->current_method === 'QR' && $this->qrImage) {
            $this->validate([
                'qrImage' => 'image|max:2048', // Solo imágenes, máximo 2MB
            ]);
            $filename = time() . '_' . $this->qrImage->getClientOriginalName();
            $tempPath = $this->qrImage->storeAs('temp', $filename);
            $destinationPath = public_path('storage/qr_codes');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }
            rename(storage_path('app/' . $tempPath), $destinationPath . '/' . $filename);
            $data['img_qr'] = 'storage/qr_codes/' . $filename;
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
        if ($response) {
            $this->dispatch('showAlertMessage', type: 'error', title: __('general.demosite_res_title'), message: __('general.demosite_res_txt'));
            $this->dispatch('toggleModel', id: 'deletepopup', action: 'hide');
            return;
        }
        $payout = UserPayoutMethod::where('user_id', Auth::user()->id)
            ->where('payout_method', $this->form->current_method)
            ->first();
        if ($payout) {
            $payout->forceDelete();
            $this->dispatch('showAlertMessage', type: 'success', title: __('general.success_title'), message: __('general.payout_account_remove'));
        }
        $this->dispatch('toggleModel', id: 'deletepopup', action: 'hide');
        $this->loadData();
    }
    public function updateStatus($method)
    {
        $response = isDemoSite();
        if ($response) {
            $this->dispatch('showAlertMessage', type: 'error', title: __('general.demosite_res_title'), message: __('general.demosite_res_txt'));
            return;
        }
        $this->payoutService->updatePayoutStatus(Auth::user()->id, $method);
        $this->dispatch('reload-balances');
        $this->loadData();
    }

    public function openPayout($method, $id)
    {
        $this->form->reset();
        $this->form->resetErrorBag();
        $this->form->current_method = $method;
        $this->dispatch('toggleModel', id: $id, action: 'show');
    }

}
