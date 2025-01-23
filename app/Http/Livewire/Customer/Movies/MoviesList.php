<?php

namespace App\Http\Livewire\Customer\Movies;

use App\Models\Movie;
use Livewire\Component;

class MoviesList extends Component
{
    public $movies;

    public function mount()
    {
        $this->movies = Movie::with(['theatres.cinema'])->get(); 
    }

    public function render()
    {
        return view('livewire.customer.movies.movies-list');
    }
}
