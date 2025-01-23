<?php

namespace App\Http\Livewire\Customer\Bookings;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingList extends Component
{
    public $bookings;

    public function mount()
    {
        $this->bookings = Booking::with(['schedule.movie', 'seats'])
            ->where('user_id', Auth::user()->id)
            ->get();
    }

    public function render()
    {
        return view('livewire.customer.bookings.booking-list');
    }
}
