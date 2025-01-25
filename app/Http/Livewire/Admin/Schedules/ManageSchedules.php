<?php

namespace App\Http\Livewire\Admin\Schedules;

use Livewire\Component;
use App\Models\Schedule;
use App\Models\Movie;
use App\Models\Theatre;
use App\Models\Seat;

class ManageSchedules extends Component
{
    public $schedules, $movies, $theatres;
    public $movie_id, $theatre_id, $date, $start_time, $end_time, $is_active = true, $scheduleId;
    public $confirmDeleteInput = '';
    public $deleteId;
    public $showModal = false;
    public $isEditing = false;

    protected $rules = [
        'movie_id' => 'required|exists:movies,id',
        'theatre_id' => 'required|exists:theatres,id',
        'date' => 'required|date|after_or_equal:today',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->movies = Movie::where('is_active', true)->get();
        $this->theatres = Theatre::all();
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $this->schedules = Schedule::with(['movie', 'theatre'])
            ->whereHas('movie', fn($q) => $q->where('is_active', true))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    public function resetForm()
    {
        $this->movie_id = '';
        $this->theatre_id = '';
        $this->date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->is_active = true;
        $this->scheduleId = null;
        $this->isEditing = false;
    }

    public function store()
    {
        $validatedData = $this->validate();

        $schedule = Schedule::create($validatedData);

        $theatreSeats = Seat::where('theatre_id', $this->theatre_id)->get();
        foreach ($theatreSeats as $seat) {
            $schedule->seats()->attach($seat->id, ['is_available' => true]);
        }

        $this->resetForm();
        $this->loadSchedules();
        session()->flash('success', 'Schedule created successfully and seats associated!');
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $this->scheduleId = $id;
        $this->movie_id = $schedule->movie_id;
        $this->theatre_id = $schedule->theatre_id;
        $this->date = $schedule->date;
        $this->start_time = $schedule->start_time;
        $this->end_time = $schedule->end_time;
        $this->is_active = $schedule->is_active;
        $this->isEditing = true;
    }

    public function update()
    {
        $validatedData = $this->validate();

        $schedule = Schedule::findOrFail($this->scheduleId);
        $schedule->update($validatedData);

        $this->resetForm();
        $this->loadSchedules();
        session()->flash('success', 'Schedule updated successfully!');
    }

    public function confirmDelete($id)
    {
        $this->showModal = true;
        $this->deleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteInput !== 'Delete Confirm') {
            session()->flash('error', 'Please type "Delete Confirm" to confirm deletion.');
            return;
        }

        $schedule = Schedule::findOrFail($this->deleteId);
        $schedule->seats()->detach();
        $schedule->delete();

        $this->reset(['confirmDeleteInput', 'deleteId', 'showModal']);
        $this->loadSchedules();
        session()->flash('success', 'Schedule and associated seats deleted successfully!');
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'confirmDeleteInput', 'deleteId']);
    }

    public function render()
    {
        return view('livewire.admin.schedules.manage-schedules');
    }
}

