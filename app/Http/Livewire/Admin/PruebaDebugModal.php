<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class PruebaDebugModal extends Component
{
    public function render()
    {
        $this->dispatchBrowserEvent('modal-debug', ['msg' => 'Render PruebaDebugModal']);
        return view('livewire.admin.prueba-debug-modal');
    }
} 