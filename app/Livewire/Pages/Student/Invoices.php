<?php

namespace App\Livewire\Pages\Student;

use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\SlotBookingService;
use App\Models\Claim;
class Invoices extends Component
{
    use WithPagination;
    public $search = '';
    public $sortby = 'desc';
    public $status = '';
    public $user;
    private ?SlotBookingService $slotBookingService = null;
    public $isLoading = true;

    
    private ?OrderService $orderService = null;
    public function boot()
    {
        $this->slotBookingService = new SlotBookingService();
    }

    public function mount()
    {
        $this->dispatch('initSelect2', target: '.am-select2');
    }

    public function loadData()
    {
        $this->isLoading = false;
        $this->dispatch('loadPageJs');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $tutorias = $this->slotBookingService->getSlotBookingByUserId()->paginate(10);
        return view('livewire.pages.student.invoices', compact('tutorias'));
    }

    public $showClaimModal = false;
    public $claimDescription = '';
    public $selectedSlotBookingId = null;

    public function openClaimModal($slotBookingId)
    {
        $this->selectedSlotBookingId = $slotBookingId;
        $this->showClaimModal = true;
    }

    public function closeClaimModal()
    {
        $this->showClaimModal = false;
        $this->claimDescription = '';
        $this->selectedSlotBookingId = null;
    }

    public function submitClaim()
    {
        $this->validate([
            'claimDescription' => 'required|string|max:1000',
        ]);

        $claim = Claim::create([
            'slot_booking_id' => $this->selectedSlotBookingId,
            'description' => $this->claimDescription,
            'status' => 'pending',
        ]);

        // Enviar correo al admin
        $student = auth()->user();
        $slot = $claim->slotBooking;
        $tutor = $slot?->tutor?->profile?->full_name ?? '';
        $subject = $slot?->subject?->name ?? '';
     /*    $start = $slot?->start_time ? $slot->start_time->format('d/m/Y H:i') : '';
        $end = $slot?->end_time ? $slot->end_time->format('H:i') : ''; */
        $contenido = "Se ha registrado un reclamo sobre una Tutoria el ".now()->format('d/m/Y H:i').".\n\n";
        $contenido .= "Detalles de la reserva:\n";
        $contenido .= "Estudiante: {$student->name} ({$student->email})\n";
        $contenido .= "Tutor: {$tutor}\n";
        $contenido .= "Materia: {$subject}\n";
        //$contenido .= "Horario: {$start} - {$end}\n";
        $contenido .= "\nDescripción del reclamo:\n{$this->claimDescription}\n\n";
        $contenido .= "Por favor, revise el panel de administración para más detalles.";
        \Mail::raw($contenido, function ($message) {
            $message->to(env('MAIL_ADMIN'))
                ->subject('Nuevo reclamo de Tutoria registrado');
        });

        $this->closeClaimModal();
        session()->flash('message', 'Reclamo enviado exitosamente.');
    }
}
