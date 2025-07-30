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
        // Permitir acceso sin autenticación real usando el middleware fake.broadcast.user
        Broadcast::routes(['middleware' => ['fake.broadcast.user']]);

        require base_path('routes/channels.php');
    }
} 