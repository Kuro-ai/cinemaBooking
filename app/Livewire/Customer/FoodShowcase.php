<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Food;

class FoodShowcase extends Component
{
    public function render()
    {
        $foods = Food::all()->groupBy('type');
        return view('livewire.customer.food-showcase', compact('foods'));
    }
}
