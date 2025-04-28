<?php

namespace App\Livewire\Pages\Admin\Bookings;

use App\Models\SlotBooking;
use App\Services\OrderService;
use App\Services\SubjectService;
use App\Models\UserPayoutMethod; // Importar el modelo
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\Order;
use Livewire\Component;
use App\Jobs\CompletePurchaseJob;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class Bookings extends Component
{
    use WithPagination;

    public      $search             = '';
    public      $sortby             = 'desc';
    public      $status             = '';
    public      $user;
    public      $subjects;
    public      $selectedSubject;
    public      $subjectGroups;
    public      $selectedSubGroup;



    private ?OrderService  $orderService        = null;
    private ?SubjectService  $subjectService    = null;


    public function boot()
    {
        $this->user             = Auth::user();
        $this->orderService     = new OrderService();
        $this->subjectService   = new SubjectService();
    }

    public function mount()
    {

        
        $this->subjects         = $this->subjectService->getSubjects();
        $this->subjectGroups    = $this->subjectService->getSubjectGroups();

        $this->dispatch('initSelect2', target: '.am-select2');
    }

    #[Layout('layouts.admin-app')]
    public function render()
    {
        $orders = $this->orderService->getBookings($this->status, $this->search, $this->sortby, $this->selectedSubject, $this->selectedSubGroup);
        return view('livewire.pages.admin.bookings.bookings', compact('orders'));
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['status', 'search', 'sortby', 'selectedSubject', 'selectedSubGroup'])) {
            $this->resetPage();
        }
    }

    public function completeOrder($orderId)
{
    try {
        DB::beginTransaction();

        // Verificar que el ID es válido
        if (empty($orderId) || !is_numeric($orderId)) {
            \Log::error('ID de orden inválido.', ['orderId' => $orderId]);
            session()->flash('error', 'ID de orden inválido.');
            return;
        }

        // Intentar recuperar la orden con relaciones corregidas
        \Log::info('Buscando orden con ID:', ['orderId' => $orderId]);
        $order = Order::with(['items'])->where('id', $orderId)->first();


        if (!$order) {
            \Log::error('Orden no encontrada en la base de datos.', ['orderId' => $orderId]);
            session()->flash('error', 'Orden no encontrada.');
            return;
        }

        // Actualizar estado a 'complete'
        $order->update(['status' => 'complete']);
        $order->refresh();

        \Log::info('Orden después de la actualización', ['order' => $order->toArray()]);

        // Despachar el Job
        dispatch(new CompletePurchaseJob($order));
        \Log::info('Se realizo con exito el JOB');
        DB::commit();

      
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error en completeOrder', ['error' => $e->getMessage()]);
        session()->flash('error', 'Error al completar la orden: ' . $e->getMessage());
    }
}



public function getOrders()
{
    return Order::with(['orderable.tutor', 'orders'])
        ->get()
        ->map(function ($order) {
            // Buscar el QR del tutor en `user_payout_methods`
            $qrImage = UserPayoutMethod::where('user_id', $order->orderable?->tutor?->id)
                ->where('payout_method', 'QR')
                ->value('img_qr');

            $order->qr_image = $qrImage ? asset('storage/' . $qrImage) : null; // Si hay QR, generar la URL
            return $order;
        });
}

}
