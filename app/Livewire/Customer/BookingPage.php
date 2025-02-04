<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\ScheduleSeat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookingPage extends Component
{
    public $movie;
    public $selectedSchedule;
    public $schedules = [];
    public $seats = [];
    public $selectedSeats = [];
    public $maxSeats = 10;
    public $bookingType;
    public $totalPrice;
    public $paymentMethod;

    public function mount($movieId)
    {
        $this->movie = Movie::findOrFail($movieId);
        $this->schedules = $this->movie->schedules()->where('is_active', true)->get();
    }

    public function selectSchedule($scheduleId)
    {
        $this->selectedSchedule = Schedule::findOrFail($scheduleId);
        $this->loadSeats();
    }

    public function loadSeats()
    {
        $seats = ScheduleSeat::where('schedule_id', $this->selectedSchedule->id)
            ->with('seat')
            ->orderBy('seat_id')
            ->get();

        $this->seats = [];

        foreach ($seats as $seat) {
            $rowLabel = substr($seat->seat->seat_number, 0, 1);

            // Assign border color based on seat type
            $borderColor = match ($seat->seat->type) {
                'regular' => 'border-blue-500',
                'recliner' => 'border-purple-500',
                'vip' => 'border-yellow-500',
                // default => 'border-gray-300',
            };

            $seat->border_color = $borderColor; // Add new property

            $this->seats[$rowLabel][] = $seat;
        }
    }


    public function toggleSeat($seatId)
    {
        if (in_array($seatId, $this->selectedSeats)) {
            $this->selectedSeats = array_diff($this->selectedSeats, [$seatId]);
        } else {
            if (count($this->selectedSeats) < $this->maxSeats) {
                $this->selectedSeats[] = $seatId;
            }
        }

        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        $this->totalPrice = ScheduleSeat::whereIn('id', $this->selectedSeats)
            ->with('seat') // Ensure seat details are loaded
            ->get()
            ->sum(fn($seat) => $seat->seat->price); // Sum seat prices
    }

    public function processBooking($type)
    {
        if (count($this->selectedSeats) == 0) {
            session()->flash('error', 'Please select at least one seat.');
            return;
        }

        if ($type == 'buy' && !$this->paymentMethod) {
            session()->flash('error', 'Please select a payment method (MPU or Visa).');
            return;
        }

        // Start database transaction
        DB::beginTransaction();
        try {
            // Check seat availability again
            $availableSeats = ScheduleSeat::where('schedule_id', $this->selectedSchedule->id)
                ->whereIn('id', $this->selectedSeats)
                ->where('is_available', true)
                ->get();

            if (count($availableSeats) != count($this->selectedSeats)) {
                session()->flash('error', 'Some selected seats are no longer available.');
                DB::rollBack();
                return;
            }

            // Calculate total price
            $totalPrice = ScheduleSeat::whereIn('id', $this->selectedSeats)
                ->with('seat')
                ->get()
                ->sum(fn($seat) => $seat->seat->price);

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'schedule_id' => $this->selectedSchedule->id,
                'booking_code' => strtoupper(Str::random(10)),
                'total_seats' => count($this->selectedSeats),
                'total_price' => $totalPrice,
                'seat_numbers' => json_encode($this->selectedSeats),
                'status' => $type == 'buy' ? 'purchased' : 'booked',
                'payment_date' => $type == 'buy' ? now() : null,
                'payment_type' => $type == 'buy' ? $this->paymentMethod : null
            ]);

            // Mark seats as unavailable
            ScheduleSeat::where('schedule_id', $this->selectedSchedule->id)
                ->whereIn('id', $this->selectedSeats)
                ->update(['is_available' => false]);

            DB::commit();

            session()->flash('success', $type == 'buy' ? 'Tickets Purchased! Check your ticket code in the tickets for successful transaction!' : 'Seats Booked! Complete payment before the deadline using the ticket code in the tickets.');
            return redirect()->route('customer.dashboard');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'An error occurred. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.customer.booking-page');
    }
}
