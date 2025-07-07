<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\SlotBooking;

class CompleteSlotBookings extends Command
{
    /**
     * The name and signature of the console command cambio para subir.
     *
     * @var string
     */
    protected $signature = 'app:complete-slot-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Completa las reservas que han terminado';

    /**
     * Execute the console command. cambios 
     */
    public function handle()
    {
        Log::info('Comando CompleteSlotBookings ejecutado');
        $now = Carbon::now();
        $bookings = SlotBooking::where('status', 1) // 1 = Active
            ->whereNotNull('end_time')
            ->where('end_time', '<', $now->subMinutes(3)) // end_time + 2 minutos
            ->get();
        foreach ($bookings as $booking) {
            $booking->status = 5; // 5 = Completed
            $booking->save();
            Log::info("Reserva {$booking->id} completada");
        }
        $this->info('Proceso de completar reservas finalizado');
    }
}
