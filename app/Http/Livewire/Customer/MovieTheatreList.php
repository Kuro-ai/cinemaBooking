<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Theatre;
use App\Models\Schedule;

class MovieTheatreList extends Component
{
    public $movies;
    public $selectedMovie;
    public $schedules;

    public function mount()
    {
        $this->movies = Movie::where('is_active', true)->with('schedules.theatre.cinema')->get();
        $this->selectedMovie = null;
        $this->schedules = [];
    }

    public function selectMovie($movieId)
    {
        $this->selectedMovie = Movie::with('schedules.theatre.cinema')->find($movieId);
        $this->schedules = $this->selectedMovie->schedules;
    }

    public function bookSeat($scheduleId)
    {
        // Check if the schedule exists
        $schedule = Schedule::with(['movie', 'theatre.cinema', 'theatre.seats'])
            ->find($scheduleId);

        if (!$schedule) {
            session()->flash('error', 'Invalid schedule selected.');
            return;
        }

        // Redirect to the booking page with the schedule ID
        return redirect()->route('customer.booking', ['schedule' => $scheduleId]);
    }

    public function render()
    {
        return view('livewire.movie-theatre-list');
    }
}

