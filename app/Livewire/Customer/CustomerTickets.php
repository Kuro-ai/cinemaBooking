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
        $now = now();
    
        $this->tickets = Booking::with(['schedule.movie', 'schedule.theatre.cinema']) //remove seat
            ->where('user_id', Auth::id())
            ->whereIn('status', ['booked', 'purchased'])
            ->where(function ($query) use ($now) {
                $query->whereHas('schedule', function ($q) use ($now) {
                    $q->whereHas('movie', function ($m) use ($now) {
                        // Convert "HH:MM" format to minutes
                        $m->whereRaw("start_time + (EXTRACT(EPOCH FROM (movies.duration::interval)) / 60 || ' minutes')::interval >= ?", [$now]);
                    });
                })
                ->orWhereHas('schedule', function ($q) use ($now) {
                    // Only include booked tickets if they are still valid (1 hour before showtime)
                    $q->where('start_time', '>=', $now->addHour());
                });
            })
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
