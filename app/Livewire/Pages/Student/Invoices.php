<?php

namespace App\Livewire\Pages\Student;

use App\Models\Profile;
use App\Models\SlotBooking;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\SlotBookingService;
class Invoices extends Component
{
    use WithPagination;

    public $search = '';
    public $sortby = 'desc';
    public $status = '';
    public $user;

    public $student;
    private ?SlotBookingService $slotBookingService = null;
    public $isLoading = true;

    public $tutorias_completadas = [];
    private ?OrderService $orderService = null;
    public function boot()
    {
        $this->user = Auth::user();
        $this->orderService = new OrderService();
        $this->slotBookingService = new SlotBookingService();
    }

    public function mount()
    {
        $this->dispatch('initSelect2', target: '.am-select2');
        $this->tutorias_completadas= $this->slotBookingService->getSlotBookingByUserId();
        //dd($this->tutorias_completadas);
        

        // Asignar el perfil del estudiante
        
       //$this->student= Profile ::where('user_id', $this->tutorias_completadas->student_id)->first();

    }

    public function loadData()
    {
        $this->isLoading = false;
        $this->dispatch('loadPageJs');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $orders = $this->orderService->getOrders($this->status, null, 'Desc', null, null, $this->user->id);


        return view('livewire.pages.student.invoices', compact('orders'));
    }
}
