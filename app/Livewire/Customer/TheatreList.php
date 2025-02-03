<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Theatre;

class TheatreList extends Component
{
    public $movieId;

    public function render()
    {
        $theatres = Theatre::whereHas('schedules', function ($query) {
            $query->where('movie_id', $this->movieId);
        })->get();

        return view('livewire.customer.theatre-list', compact('theatres'));
    }
}

