<?php

namespace App\Http\Livewire\Customer\Cinemas;

use App\Models\Cinema;
use App\Models\Theatre;
use App\Models\Movie;
use Livewire\Component;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CinemasList extends Component
{
    public $cinemas; 
    public $selectedCinema; 
    public $theatres = [];
    public $selectedTheatre; 
    public $movies = []; 
    public $availableSeats = [];
    public $selectedSeats = [];
    public $selectedSchedule;
    public $paymentType; 

    public function mount()
    {
        $this->cinemas = Cinema::with('theatres')->get();
    }

    public function loadTheatres($cinemaId)
    {
        $this->selectedCinema = Cinema::find($cinemaId);
        $this->theatres = $this->selectedCinema->theatres;
        $this->movies = []; 
        $this->selectedTheatre = null;
    }

    public function loadMovies($theatreId)
    {
        $this->selectedTheatre = Theatre::find($theatreId);

        // Fetch movies with their schedules and available seats
        $this->movies = $this->selectedTheatre->movies()->with(['schedules' => function ($query) {
            $query->with(['seats' => function ($seatQuery) {
                $seatQuery->wherePivot('is_available', true);
            }]);
        }])->get();
    }
    
    public function loadSeats($schedule_id)
    {
        $this->selectedSchedule = $schedule_id;

        // Fetch seats tied to the specific schedule
        $this->availableSeats = Schedule::findOrFail($schedule_id)
            ->seats()
            ->wherePivot('is_available', true) // Ensure only available seats are fetched
            ->get(['seats.id', 'seats.seat_number']); // Fetch necessary columns
    }


    public function toggleSeatSelection($seat_id)
    {
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

        // Validate that the selected seats are available for this schedule
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

        // Mark seats as booked
        foreach ($this->selectedSeats as $seat_id) {
            $schedule->seats()->updateExistingPivot($seat_id, ['is_available' => false]);
        }

        // Create a booking record
        Booking::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'schedule_id' => $this->selectedSchedule,
            'booking_code' => 'BOOK-' . strtoupper(Str::random(6)),
            'total_seats' => count($this->selectedSeats),
            'total_price' => $schedule->price * count($this->selectedSeats),
            'payment_type' => null,
            'payment_date' => null,
            'status' => 'booked',
        ]);

        session()->flash('success', 'Seats booked successfully!');
        $this->reset(['selectedSeats', 'availableSeats', 'selectedSchedule']);
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

        try {
            DB::transaction(function () {
                $schedule = Schedule::findOrFail($this->selectedSchedule);

                // Ensure selected seats are still available
                foreach ($this->selectedSeats as $seat_id) {
                    $isAvailable = $schedule->seats()
                        ->wherePivot('seat_id', $seat_id)
                        ->wherePivot('is_available', true)
                        ->exists();

                    if (!$isAvailable) {
                        throw new \Exception('One or more selected seats are no longer available.');
                    }
                }

                // Mark seats as purchased in the pivot table
                foreach ($this->selectedSeats as $seat_id) {
                    $schedule->seats()->updateExistingPivot($seat_id, ['is_available' => false]);
                }

                // Create a purchase record
                Booking::create([
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'schedule_id' => $this->selectedSchedule,
                    'booking_code' => 'PUR-' . strtoupper(Str::random(6)),
                    'total_seats' => count($this->selectedSeats),
                    'total_price' => $schedule->price * count($this->selectedSeats),
                    'payment_type' => $this->paymentType,
                    'payment_date' => now(),
                    'status' => 'purchased',
                ]);
            });

            session()->flash('success', 'Seats purchased successfully!');
            $this->reset(['selectedSchedule', 'availableSeats', 'selectedSeats', 'paymentType']);
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while purchasing seats: ' . $e->getMessage());
            Log::error('Purchase Seats Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.customer.cinemas.cinema-list');
    }
}


