<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;

class ManageBookings extends Component
{
    use WithPagination;

    public $search = ''; 
    public $perPage = 10; 

    protected $queryString = ['search']; 

    public function updatingSearch()
    {
        $this->resetPage(); 
    }

    public function refund($bookingId)
    {
        $booking = Booking::find($bookingId);
        if ($booking) {
            $booking->update(['status' => 'refunded']);
        }
    }

    public function render()
    {
        $bookings = Booking::with(['user', 'schedule.movie'])
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('schedule.movie', function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orWhere('booking_code', 'like', '%' . $this->search . '%')
            ->orWhere('status', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);

        return view('livewire.admin.manage-bookings', compact('bookings'));
    }
}
