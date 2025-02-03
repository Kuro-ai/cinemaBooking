<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class CancelUnpaidBookings extends Command
{
    protected $signature = 'bookings:cancel-unpaid';
    protected $description = 'Cancel unpaid bookings 1 hour before showtime';

    public function handle()
    {
        $booking = Booking::where('status', 'pending')
                ->whereHas('schedule', function ($query) {
                    $query->where('start_time', '<=', Carbon::now()->addHour());
                })
                ->update(['status' => 'cancelled']);
                $this->info("$booking bookings have been cancelled.");
    }
}

