<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UserOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()) {
            $user = Auth::user();
            if ($user instanceof \App\Models\User && $user->id) {
                try {
                    $expiresAt = Carbon::now()->addMinutes(30);
                    Cache::put('user-online-' . $user->id, true, $expiresAt);
                } catch (\Exception $e) {
                    \Log::error('Error en UserOnline middleware: ' . $e->getMessage());
                }
            }
        }

        try {
            return $next($request);
        } catch (\Exception $e) {
            \Log::error('Error en UserOnline middleware después de next(): ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Si hay un error relacionado con morph, intentar continuar
            if (strpos($e->getMessage(), 'getMorphClass') !== false) {
                \Log::warning('Error de morph detectado, continuando...');
                return response()->json(['error' => 'Error interno del servidor'], 500);
            }
            
            throw $e;
        }
    }
}
