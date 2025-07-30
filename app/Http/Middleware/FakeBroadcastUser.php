<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FakeBroadcastUser
{
    public function handle($request, Closure $next)
    {
        // Si no hay usuario autenticado, simula uno (solo para pruebas)
        if (!Auth::check()) {
            $user = \App\Models\User::first(); // Usa el primer usuario real
            if ($user) {
                Auth::login($user);
            }
        }
        return $next($request);
    }
} 