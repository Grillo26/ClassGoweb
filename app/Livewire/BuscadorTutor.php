<?php

namespace App\Livewire;
use App\Services\SiteService;

use Livewire\Component;

class BuscadorTutor extends Component
{
    public $search = '';
    public $results = [];

    public function updatedSearch(SiteService $siteService)
    {
    $this->results = $siteService->getTutorBuscador($this->search);
    }
    public function render()
    {
        return view('livewire.buscador-tutor')
        ->layout('vistas.view.layouts.app');;
    }
}
