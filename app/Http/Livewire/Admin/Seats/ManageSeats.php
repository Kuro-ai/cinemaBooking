<?php

namespace App\Http\Livewire\Admin\Seats;

use Livewire\Component;
use App\Models\Seat;
use App\Models\Theatre;

class ManageSeats extends Component
{
    public $seats;
    public $theatres;
    public $seat_number, $theatre_id, $type = 'regular', $price, $is_available = true, $seatId;
    public $isEditing = false;
    public $deleteId;
    public $showModal = false;
    public $confirmDeleteInput = '';

    protected $rules = [
        'seat_number' => 'required|string|max:10',
        'theatre_id' => 'required|exists:theatres,id',
        'type' => 'required|in:regular,vip,recliner',
        'price' => 'nullable|numeric|min:0',
        'is_available' => 'boolean',
    ];

    public function mount()
    {
        $this->loadSeats();
        $this->theatres = Theatre::all();
    }

    public function loadSeats()
    {
        $this->seats = Seat::with('theatre')->get();
    }

    public function resetForm()
    {
        $this->seat_number = '';
        $this->theatre_id = '';
        $this->type = 'regular';
        $this->price = null;
        $this->is_available = true;
        $this->seatId = null;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate();
        Seat::create($this->validate());
        $this->resetForm();
        $this->loadSeats();
        session()->flash('success', 'Seat created successfully!');
    }

    public function edit($id)
    {
        $seat = Seat::findOrFail($id);
        $this->seatId = $id;
        $this->seat_number = $seat->seat_number;
        $this->theatre_id = $seat->theatre_id;
        $this->type = $seat->type;
        $this->price = $seat->price;
        $this->is_available = $seat->is_available;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();
        Seat::findOrFail($this->seatId)->update($this->validate());
        $this->resetForm();
        $this->loadSeats();
        session()->flash('success', 'Seat updated successfully!');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showModal = true;
        $this->confirmDeleteInput = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function delete()
    {
        if ($this->confirmDeleteInput === 'Delete Confirm') {
            Seat::findOrFail($this->deleteId)->delete();
            $this->loadSeats();
            $this->showModal = false;
            session()->flash('success', 'Seat deleted successfully!');
        } else {
            session()->flash('error', 'You must type "Delete Confirm" to delete the seat.');
        }
    }

    public function render()
    {
        return view('livewire.admin.seats.manage-seats');
    }
}

