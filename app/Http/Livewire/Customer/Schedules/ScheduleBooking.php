<?php

namespace App\Http\Livewire\Customer\Schedules;

use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Booking;
use Livewire\Component;
use Illuminate\Support\Str;

class ScheduleBooking extends Component
{
    public $movie_id;
    public $theatre_id;
    public $schedules;
    public $selectedSchedule;
    public $availableSeats = [];
    public $selectedSeats = [];
    public $paymentType; 

    public function mount($theatre_id, $movie_id)
    {
        $this->movie_id = $movie_id;
        $this->theatre_id = $theatre_id;

        // Load schedules for the specific movie and theatre
        $this->schedules = Schedule::where('movie_id', $movie_id)
            ->where('theatre_id', $theatre_id)
            ->where('is_active', true)
            ->get();
    }

    public function loadSeats($schedule_id)
    {
        $this->selectedSchedule = $schedule_id;

        // Fetch available seats for the selected schedule from the pivot table
        $this->availableSeats = Schedule::findOrFail($schedule_id)
            ->seats()
            ->wherePivot('is_available', true) // Check availability in the pivot table
            ->get();
    }

    public function toggleSeatSelection($seat_id)
    {
        // Add or remove seat from selectedSeats array
        if (in_array($seat_id, $this->selectedSeats)) {
            $this->selectedSeats = array_diff($this->selectedSeats, [$seat_id]);
        } else {
            $this->selectedSeats[] = $seat_id;
        }
    }

    public function validateSeats()
    {
        if (empty($this->selectedSeats)) {
            session()->flash('error', 'Please select at least one seat.');
            return false;
        }

        $schedule = Schedule::findOrFail($this->selectedSchedule);

        // Validate seat availability for the selected schedule
        $availableSeats = $schedule->seats()
            ->wherePivot('is_available', true)
            ->whereIn('seats.id', $this->selectedSeats)
            ->pluck('seats.id')
            ->toArray();

        if (count($this->selectedSeats) !== count($availableSeats)) {
            session()->flash('error', 'Some selected seats are no longer available. Please try again.');
            $this->loadSeats($this->selectedSchedule); // Refresh available seats
            return false;
        }

        return true;
    }

    public function bookSeats()
    {
        if (!$this->validateSeats()) {
            return;
        }

        $schedule = Schedule::findOrFail($this->selectedSchedule);

        // Mark seats as booked in the pivot table
        // foreach ($this->selectedSeats as $seat_id) {
        //     $schedule->seats()->updateExistingPivot($seat_id, ['is_available' => false]);
        // }

        // Create a booking record

        Booking::create([
            'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
            'schedule_id' => $this->selectedSchedule,
            'booking_code' => 'BK-' . strtoupper(Str::random(6)),
            'total_seats' => count($this->selectedSeats),
            'total_price' => Seat::findOrFail($this->selectedSeats[0])->price * count($this->selectedSeats),
            'payment_type' => null,
            'payment_date' => null,
            'status' => 'booked',
            'seat_numbers' => implode(',', Seat::whereIn('id', $this->selectedSeats)->pluck('seat_number')->toArray()),
        ]);

        // Reset state and show success message
        session()->flash('success', 'Seats booked successfully!');
        $this->reset(['selectedSchedule', 'availableSeats', 'selectedSeats']);
    }

    public function purchaseSeats()
    {
        if (!$this->validateSeats()) {
            return;
        }

        if (!$this->paymentType) {
            session()->flash('error', 'Please select a payment method.');
            return;
        }

        $schedule = Schedule::findOrFail($this->selectedSchedule);

        // Mark seats as purchased in the pivot table
        // foreach ($this->selectedSeats as $seat_id) {
        //     $schedule->seats()->updateExistingPivot($seat_id, ['is_available' => false]);
        // }

        // Create a purchase record

        Booking::create([
            'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
            'schedule_id' => $this->selectedSchedule,
            'booking_code' => 'PUR-' . strtoupper(Str::random(6)),
            'total_seats' => count($this->selectedSeats),
            'total_price' => Seat::findOrFail($this->selectedSeats[0])->price * count($this->selectedSeats),
            'payment_type' => $this->paymentType,
            'payment_date' => now(),
            'status' => 'purchased',
           'seat_numbers' => implode(',', Seat::whereIn('id', $this->selectedSeats)->pluck('seat_number')->toArray()),
        ]);

        // Reset state and show success message
        session()->flash('success', 'Seats purchased successfully!');
        $this->reset(['selectedSchedule', 'availableSeats', 'selectedSeats', 'paymentType']);
    }

    public function render()
    {
        return view('livewire.customer.schedules.schedule-booking');
    }
}
