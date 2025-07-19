<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestPusherConfig extends Command
{
    protected $signature = 'test:pusher-config';
    protected $description = 'Test Pusher configuration';

    public function handle()
    {
        $this->info('=== Configuración de Pusher ===');
        $this->info('BROADCAST_CONNECTION: ' . env('BROADCAST_CONNECTION', 'no configurado'));
        $this->info('PUSHER_APP_KEY: ' . env('PUSHER_APP_KEY', 'no configurado'));
        $this->info('PUSHER_APP_SECRET: ' . (env('PUSHER_APP_SECRET') ? 'configurado' : 'no configurado'));
        $this->info('PUSHER_APP_ID: ' . env('PUSHER_APP_ID', 'no configurado'));
        $this->info('PUSHER_APP_CLUSTER: ' . env('PUSHER_APP_CLUSTER', 'no configurado'));
        
        $this->info('');
        $this->info('=== Configuración de Broadcasting ===');
        $this->info('Default driver: ' . config('broadcasting.default'));
        $this->info('Pusher driver: ' . config('broadcasting.connections.pusher.driver'));
        $this->info('Pusher key: ' . config('broadcasting.connections.pusher.key'));
        $this->info('Pusher cluster: ' . config('broadcasting.connections.pusher.options.cluster'));
        
        $this->info('');
        $this->info('=== Test de Evento ===');
        
        try {
            event(new \App\Events\SlotBookingStatusChanged(1, 'test'));
            $this->info('✅ Evento disparado correctamente');
        } catch (\Exception $e) {
            $this->error('❌ Error al disparar evento: ' . $e->getMessage());
        }
    }
} 