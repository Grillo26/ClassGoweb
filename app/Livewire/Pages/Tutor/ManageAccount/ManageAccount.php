<?php
namespace App\Livewire\Pages\Tutor\ManageAccount;


use App\Services\PayoutService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Livewire\Forms\Tutor\Payout\PayoutForm;

use App\Models\UserPayoutMethod;


class ManageAccount extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $qrImage;
    public $currentQRPath;
    public $data = [];
    public $status = '';
    public $isLoading = true;
    public $payoutStatus;

    public PayoutForm $form;
    private ?PayoutService $payoutService = null;
    public function boot()
    {
        $this->payoutService = new PayoutService();
    }
    
    public function mount()
    {
        $this->chart = true;
        $this->selectedDate = now(getUserTimezone());    
        $this->dispatch('initSelect2', target: '.am-select2');
        $this->loadCurrentQRImage();
    }

    private function loadCurrentQRImage()
    {
        $payoutMethod = UserPayoutMethod::where('user_id', Auth::id())
            ->where('payout_method', 'QR')
            ->where('status', 'active')
            ->first();
        if ($payoutMethod && $payoutMethod->img_qr) {
            // Normalizar la ruta de la imagen
            $imgPath = $payoutMethod->img_qr;
            // Remover el prefijo 'storage/' si existe para almacenarlo sin él
            $this->currentQRPath = str_replace('storage/', '', $imgPath);
        } else {
            $this->currentQRPath = null;
        }
    }

    #[On('refresh-payouts')]
    public function refresh()
    {
        $this->loadData();
    }

   
    #[Layout('layouts.app')]
    public function render()
    {
        $qr = UserPayoutMethod::withTrashed()
            ->where('user_id', Auth::id())
            ->where('payout_method', 'QR')
            ->first();
        return view('livewire.pages.tutor.manage-account.manage-account', compact('qr'));
    }

    public function loadData()
    {
        $this->isLoading = true;
        $this->payoutStatus = $this->payoutService->getPayoutStatus(Auth::user()->id);
        $this->dispatch('initSelect2', target: '.am-select2');
        $this->isLoading = false;
    }

   



    public function updatePayout()
    {
        try {
            // Validar según el tipo de método
            if ($this->form->current_method === 'QR') {
                // Para QR validamos la imagen solo si no hay QR existente
                if (!$this->currentQRPath && !$this->qrImage) {
                    $this->showErrorMessage(__('tutor.no_file_selected'));
                    return;
                }
                
                // Solo procesar nueva imagen si se seleccionó una
                if ($this->qrImage) {
                    $this->validate([
                        'qrImage' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    ]);
                    $imagePath = $this->handleQRImageUpload();
                    $this->deleteExistingQRImage();
                    $this->updateQRPayoutMethod($imagePath);
                    // Refrescar la imagen actual después de guardar
                    $this->loadCurrentQRImage();
                }
                
                $this->showSuccessMessage(__('tutor.qr_updated'));
                $this->closeModal('modalQR');
                
            } else {
                $data = $this->form->validatePayout();
                $payout = $this->payoutService->addPayoutDetail(
                    Auth::user()->id,
                    $this->form->current_method,
                    $data
                );
                if ($payout) {
                    $this->showSuccessMessage(__('general.payout_account_add'));
                }
                $this->resetFormAndCloseModals(['setupaccountpopup', 'setuppayoneerpopup',]);
            }

        } catch (\Exception $e) {
            $this->showErrorMessage($e->getMessage());
        }
    }

    private function validateQRImage(): bool
    {
        if (!$this->qrImage) {
        
            $this->showErrorMessage(__('tutor.no_file_selected'));
            
            return false;
        }
        $this->validate([
            'qrImage' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        return true;
    }


    private function handleQRImageUpload(bool $includeStoragePrefix = false): string
    {
        $this->validate([
            'qrImage' => 'image|max:2048',
        ]);
        $filename = time() . '_' . $this->qrImage->getClientOriginalName();
        $tempPath = $this->qrImage->storeAs('temp', $filename);
        $destinationPath = public_path('storage/qr_codes');
        // Crear directorio si no existe
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }
        // Mover archivo desde temp a destino final
        rename(storage_path('app/' . $tempPath), $destinationPath . '/' . $filename);
        $path = 'qr_codes/' . $filename;
        if (!$path) {
            throw new \Exception(__('tutor.qr_upload_failed'));
        }
        return $includeStoragePrefix ? 'storage/' . $path : $path;
    }


    private function deleteExistingQRImage(): void
    {
        $existingPayout = UserPayoutMethod::withTrashed()
            ->where('user_id', Auth::id())
            ->where('payout_method', 'QR')
            ->first();
        // Restaurar si está eliminado
        if ($existingPayout && $existingPayout->trashed()) {
            $existingPayout->restore();
        }
        // Eliminar imagen anterior
        if ($existingPayout && $existingPayout->img_qr) {
            $imgPath = $existingPayout->img_qr;
            // Limpiar prefijo 'storage/' si existe
            if (strpos($imgPath, 'storage/') === 0) {
                $imgPath = substr($imgPath, 8);
            }
            Storage::disk('public')->delete($imgPath);
        }
    }

    private function updateQRPayoutMethod(string $imagePath): void
    {
        $payout = UserPayoutMethod::updateOrCreate(
            ['user_id' => Auth::id(), 'payout_method' => 'QR'],
            ['img_qr' => $imagePath, 'status' => 'active']
        );
        if (!$payout) {
            throw new \Exception(__('tutor.qr_save_failed'));
        }
        // Actualizar la variable local sin el prefijo 'storage/'
        $this->currentQRPath = $imagePath;
    }






    public function removePayout()
    {
        $payout = UserPayoutMethod::where('user_id', Auth::user()->id)
            ->where('payout_method', $this->form->current_method)
            ->first();
        if ($payout) {
            $payout->forceDelete();
            
            // Si era un QR, limpiar la imagen actual
            if ($this->form->current_method === 'QR') {
                $this->currentQRPath = null;
            }
            
            $this->showSuccessMessage(__('general.payout_account_remove'));
        }
        $this->closeModal('deletepopup');
        $this->loadData();
    }


    public function updateStatus($method)
    {
        $this->payoutService->updatePayoutStatus(Auth::user()->id, $method);
        $this->dispatch('reload-balances');
        $this->loadData();
    }

    public function openPayout($method, $id)
    {
        $this->form->reset();
        $this->form->resetErrorBag();
        $this->form->current_method = $method;
        
        // Limpiar imagen temporal
        $this->qrImage = null;
        
        if ($method === 'QR') {
            // Cargar datos del QR actual
            $qrData = UserPayoutMethod::where('user_id', Auth::id())
                ->where('payout_method', 'QR')
                ->first();
            
            // Actualizar la ruta de la imagen actual
            if ($qrData && $qrData->img_qr) {
                // Asegurar que la ruta tenga el prefijo correcto
                $imgPath = $qrData->img_qr;
                if (strpos($imgPath, 'storage/') !== 0) {
                    $this->currentQRPath = $imgPath;
                } else {
                    $this->currentQRPath = str_replace('storage/', '', $imgPath);
                }
            } else {
                $this->currentQRPath = null;
            }
        }
        
        $this->dispatch('toggleModel', id: $id, action: 'show');
    }

    #[On('modalClosed')]
    public function handleModalClosed()
    {
        // Solo limpiar imagen temporal, no la imagen actual
        $this->qrImage = null;
        $this->resetErrorBag();
    }


    public function refreshQRData()
    {
        $this->loadCurrentQRImage();
        $this->qrImage = null;
        $this->resetErrorBag();
    }
    


    private function showSuccessMessage(string $message): void
    {

          $this->dispatch(
                'showAlertMessage', 
                type: 'success', 
                title: __('general.success_title') ,
                message:$message);

      /*   $this->dispatch('showAlertMessage', [
            'type' => 'success',
            'title' => __('general.success_title'),
            'message' => $message
        ]); */
    }


    private function showErrorMessage(string $message): void
    {

        $this->dispatch('showAlertMessage', [
            'type' => 'error',
            'title' => __('general.error_title'),
            'message' => $message
        ]);
    }


   
    private function resetFormAndCloseModals(array $modalIds): void
    {
        $this->form->reset();
        foreach ($modalIds as $modalId) {
            $this->closeModal($modalId);
        }
    }

     private function closeModal(string $modalId): void
    {
        $this->dispatch('toggleModel', id: $modalId, action: 'hide');
        // Solo limpiar imagen temporal si estamos cerrando el modal QR
        if ($modalId === 'modalQR') {
            $this->qrImage = null;
            $this->resetErrorBag();
        }
        
        // Dispatch del evento de cierre para limpieza del DOM
        $this->dispatch('modalClosed');
    }
}


