<?php

namespace App\Http\Livewire\Admin\Schedules;

use Livewire\Component;
use App\Models\Schedule;
use App\Models\Movie;
use App\Models\Theatre;
use App\Models\Seat;

class ManageSchedules extends Component
{
    public $schedules;
    public $movies;
    public $theatres;
    public $movie_id, $theatre_id, $date, $start_time, $end_time, $is_active = true, $scheduleId;
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
            ->whereHas('movie', function ($query) {
                $query->where('is_active', true);
            })
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

        // Create the schedule
        $schedule = Schedule::create([
            'movie_id' => $this->movie_id,
            'theatre_id' => $this->theatre_id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_active' => $this->is_active,
        ]);

        // Attach seats to the schedule and set them as available in the pivot table
        $theatreSeats = Seat::where('theatre_id', $this->theatre_id)->get();
        foreach ($theatreSeats as $seat) {
            $schedule->seats()->attach($seat->id, ['is_available' => true]);
        }

        // Reset the form and reload schedules
        $this->resetForm();
        $this->loadSchedules();
        session()->flash('success', 'Schedule created successfully, and seats have been associated!');
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
        $this->start_time = substr($this->start_time, 0, 5);  
        $this->end_time = substr($this->end_time, 0, 5);      
    
        $validatedData = $this->validate();
    
        $schedule = Schedule::findOrFail($this->scheduleId);
        $schedule->update($validatedData);
    
        $this->resetForm();
        $this->loadSchedules();
        session()->flash('success', 'Schedule updated successfully!');
    }
    


    public function delete($id)
    {
        $schedule = Schedule::findOrFail($id);

        // Detach seats before deleting the schedule
        $schedule->seats()->detach();
        $schedule->delete();

        $this->loadSchedules();
        session()->flash('success', 'Schedule and associated seats deleted successfully!');
    }

    public function render()
    {
        return view('livewire.admin.schedules.manage-schedules');
    }
}
