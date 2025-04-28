<?php

namespace App\Jobs;

use App\Services\BookingService;
use App\Services\WalletService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CompleteBookingJob implements ShouldQueue
{
    use Queueable;

    public $booking;
    /**
     * Create a new job instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     */
    public function handle(BookingService $bookingService, WalletService $walletService): void
    {
       if ($this->booking->status == 'active') {
            $bookingService->updateBooking($this->booking, ['status' => 'completed']);
            $walletService->makePendingFundsAvailable($this->booking->tutor_id, ($this->booking->session_fee - $this->booking?->orderItem?->platform_fee), $this->booking?->orderItem?->order_id);
            $template_id = $this->booking->slot?->meta_data['template_id'] ?? null;
                       
            if(\Nwidart\Modules\Facades\Module::has('upcertify') && \Nwidart\Modules\Facades\Module::isEnabled('upcertify') && !empty($template_id)){
                dispatch(new GenerateCertificateJob($this->booking));
            }
        }
    }
}
