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

        return $next($request);
    }
}
