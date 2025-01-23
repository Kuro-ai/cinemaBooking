<?php

namespace App\Http\Livewire\Admin\Cinemas;

use Livewire\Component;
use App\Models\Cinema;

class ManageCinemas extends Component
{
    public $cinemas, $name, $location, $city, $contact_number, $email, $is_active, $cinemaId;

    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'city' => 'nullable|string|max:255',
        'contact_number' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        return view('livewire.admin.cinemas.manage-cinemas');
    }

    public function mount()
    {
        $this->loadCinemas();
    }

    public function loadCinemas()
    {
        $this->cinemas = Cinema::all();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->location = '';
        $this->city = '';
        $this->contact_number = '';
        $this->email = '';
        $this->is_active = true;
        $this->cinemaId = null;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate();
        Cinema::create($this->validate());
        $this->resetForm();
        $this->loadCinemas();
        session()->flash('success', 'Cinema created successfully!');
    }

    public function edit($id)
    {
        $cinema = Cinema::findOrFail($id);
        $this->cinemaId = $id;
        $this->name = $cinema->name;
        $this->location = $cinema->location;
        $this->city = $cinema->city;
        $this->contact_number = $cinema->contact_number;
        $this->email = $cinema->email;
        $this->is_active = $cinema->is_active;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();
        Cinema::findOrFail($this->cinemaId)->update($this->validate());
        $this->resetForm();
        $this->loadCinemas();
        session()->flash('success', 'Cinema updated successfully!');
    }

    public function delete($id)
    {
        Cinema::findOrFail($id)->delete();
        $this->loadCinemas();
        session()->flash('success', 'Cinema deleted successfully!');
    }

    
}

