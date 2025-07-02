<?php

namespace App\Http\Controllers;
use App\Models\Code;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PromocionesController extends Controller
{
    public function index(){
        $user = Auth::user();

        // Obtener el primer código del usuario
        $codigo = $user->codes()->where('estado', 'activo')->latest()->first(); // puedes ajustar a tu lógica

        // Obtener los cupones como antes
        $cupones = $user->coupons;
        return view('livewire.pages.student.promociones', compact('codigo', 'cupones'));
    }
}
