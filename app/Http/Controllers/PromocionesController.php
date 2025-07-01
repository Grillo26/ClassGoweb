<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromocionesController extends Controller
{
    public function index(){
        return view('livewire.pages.student.promociones');
    }
}
