<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class CustomerTickets extends Component
{
    public $tickets = [];

    public function mount()
    {
        $this->fetchTickets();
    }

    public function fetchTickets()
    {
        $this->tickets = Booking::with(['schedule.movie', 'schedule.theatre.cinema'])
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();

    }
    
    public function deleteExpiredTickets()
    {
        foreach ($this->tickets as $ticket) {
            $schedule = $ticket->schedule;
            $expireTime = null;

            if ($ticket->status === 'booked') {
                $expireTime = Carbon::parse($schedule->date . ' ' . $schedule->start_time)->subHour();
            } elseif ($ticket->status === 'purchased') {
                $expireTime = Carbon::parse($schedule->date . ' ' . $schedule->start_time)->addMinutes($schedule->duration);
            }            // change this part

            if ($expireTime && now()->greaterThan($expireTime)) {
                $ticket->delete();
            }
        }

        $this->fetchTickets(); 
    }

    public function pollExpiredTickets()
    {
        $this->deleteExpiredTickets();
    }

    public function render()
    {
        return view('livewire.customer.customer-tickets');
    }
}
