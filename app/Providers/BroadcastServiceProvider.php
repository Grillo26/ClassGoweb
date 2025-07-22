<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Permitir acceso sin autenticación
        Broadcast::routes(['middleware' => []]);

        require base_path('routes/channels.php');
    }
} 