<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        try {
            // Verificar el rol de forma segura
            if ($user->roles instanceof \Illuminate\Database\Eloquent\Collection && $user->roles->count() > 0) {
                $userRole = $user->roles->first()->name;
                if ($userRole === 'student') {
                    return $next($request);
                }
            }
            
            // Si no es estudiante, redirigir
            return redirect()->route('login')->with('error', 'Acceso no autorizado.');
            
        } catch (\Exception $e) {
            \Log::error('Error verificando rol de estudiante: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Error verificando permisos.');
        }
    }
} 