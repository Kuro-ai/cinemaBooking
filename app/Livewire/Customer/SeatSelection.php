<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;

class SeatSelection extends Component
{
    public $scheduleId;
    public $seats = [];
    public $selectedSeats = [];
    public $purchaseMethod = null;

    public function mount($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        $this->loadSeats();
    }

    public function loadSeats()
    {
        $this->seats = Seat::whereHas('scheduleSeats', function($query) {
            $query->where('schedule_id', $this->scheduleId)->where('is_available', true);
        })->get();
    }

    public function toggleSeat($seatId)
    {
        if (count($this->selectedSeats) < 10) {
            if (in_array($seatId, $this->selectedSeats)) {
                $this->selectedSeats = array_diff($this->selectedSeats, [$seatId]);
            } else {
                $this->selectedSeats[] = $seatId;
            }
        }
    }

    public function bookSeats()
    {
        if (empty($this->selectedSeats)) {
            session()->flash('error', 'Please select at least one seat.');
            return;
        }

        // Perform booking logic here (checking seat availability, etc.)

        session()->flash('success', 'Seats booked successfully!');
    }

    public function render()
    {
        return view('livewire.customer.seat-selection');
    }
}

