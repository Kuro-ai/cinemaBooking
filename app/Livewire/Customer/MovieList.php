<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MovieList extends Component
{
    public $movieType = 'now_showing'; 
    public $selectedMovie = null;
    public $trailerUrl = null;

    public function selectMovie($movieId)
    {
        $this->selectedMovie = Movie::find($movieId);
        $this->trailerUrl = null;
    }

    public function fetchTrailer()
    {
        if ($this->selectedMovie && $this->selectedMovie->trailer_url) {
            $this->trailerUrl = $this->selectedMovie->trailer_url;
        } else {
            $this->trailerUrl = null;
        }
    }

    public function startBooking($movieId)
    {
        return redirect()->route('customer.booking', ['movieId' => $movieId]);
    }

    public function render()
    {
        // Now Showing Movies
        $nowShowing = Movie::where('is_active', true)
            ->whereHas('schedules', function ($query) {
                $query->where('is_active', true)
                    ->whereDate('release_date', '<=', Carbon::today());
            })
            ->get();

        // Upcoming Movies
        $upcomingMovies = Movie::where('is_active', true)
            ->whereHas('schedules', function ($query) {
                $query->where('is_active', true)
                    ->whereDate('release_date', '>', Carbon::today());
            })
            ->get();

        return view('livewire.customer.movie-list', compact('nowShowing', 'upcomingMovies'));
    }
}
