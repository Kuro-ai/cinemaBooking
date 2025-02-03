<?php

namespace App\Livewire\Admin\Seats;

use Livewire\Component;
use App\Models\Seat;
use App\Models\Theatre;
use Livewire\WithPagination;

class ManageSeats extends Component
{
    use WithPagination;

    public $theatres;
    public $seat_number, $theatre_id, $type = 'regular', $price, $seatId;
    public $isEditing = false;
    public $deleteId;
    public $showModal = false;
    public $confirmDeleteInput = '';
    public $search = '';
    public $filterType = '';
    public $filterTheatre = '';

    protected $rules = [
        'seat_number' => 'required|string|max:10',
        'theatre_id' => 'required|exists:theatres,id',
        'type' => 'required|in:regular,vip,recliner',
        'price' => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        $this->theatres = Theatre::all();
    }

    public function resetForm()
    {
        $this->seat_number = '';
        $this->theatre_id = '';
        $this->type = 'regular';
        $this->price = null;
        $this->seatId = null;
        $this->isEditing = false;

        $this->resetPage(); // Reset pagination when resetting the form
    }

    public function store()
    {
        $this->validate();
        Seat::create([
            'seat_number' => $this->seat_number,
            'theatre_id' => $this->theatre_id,
            'type' => $this->type,
            'price' => $this->price,
        ]);
        $this->resetForm();
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
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $seat = Seat::findOrFail($this->seatId);

        $seat->update([
            'seat_number' => $this->seat_number,
            'theatre_id' => $this->theatre_id,
            'type' => $this->type,
            'price' => $this->price,
        ]);

        $this->resetForm();
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
            $this->showModal = false;
            session()->flash('success', 'Seat deleted successfully!');
        } else {
            session()->flash('error', 'You must type "Delete Confirm" to delete the seat.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterTheatre()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Seat::with('theatre');

        if ($this->search) {
            $query->whereRaw('LOWER(seat_number) LIKE ?', ['%' . strtolower($this->search) . '%']);
        }        

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterTheatre) {
            $query->where('theatre_id', $this->filterTheatre);
        }

        return view('livewire.admin.seats.manage-seats', [
            'seats' => $query->paginate(10),
        ]);
    }
}
