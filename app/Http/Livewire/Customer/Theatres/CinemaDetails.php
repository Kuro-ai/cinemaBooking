<?php

namespace App\Http\Livewire\Customer\Theatres;

use Livewire\Component;
use App\Models\Cinema;

class CinemaDetails extends Component
{
    public $cinema;

    public function mount($cinema)
    {
        $this->cinema = Cinema::with(['theatres.schedules.movie'])->findOrFail($cinema);
    }

    public function render()
    {
        return view('livewire.customer.theatres.cinema-details');
    }
}

