<?php

namespace App\Livewire\Admin\Schedules;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Schedule;
use App\Models\Movie;
use App\Models\Theatre;
use App\Models\Seat;

class ManageSchedules extends Component
{
    use WithPagination;

    public $movies, $theatres;
    public $movie_id, $theatre_id, $date, $start_time, $is_active = true, $scheduleId;
    public $confirmDeleteInput = '';
    public $deleteId;
    public $showModal = false;
    public $isEditing = false;
    public $search = ''; 
    public $searchDate = '';
    public $filterTheatre = '';
    public $filterStatus = '';

    protected $rules = [
        'movie_id' => 'required|exists:movies,id',
        'theatre_id' => 'required|exists:theatres,id',
        'date' => 'required|date|after_or_equal:today',
        'start_time' => 'required|date_format:H:i',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->movies = Movie::where('is_active', true)->get();
        $this->theatres = Theatre::all();
    }

    public function resetForm()
    {
        $this->movie_id = '';
        $this->theatre_id = '';
        $this->date = '';
        $this->start_time = '';
        $this->is_active = true;
        $this->scheduleId = null;
        $this->isEditing = false;

        $this->resetPage(); 
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
        $this->is_active = $schedule->is_active;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->start_time = date('H:i', strtotime($this->start_time));
        $validatedData = $this->validate();
        
        $schedule = Schedule::findOrFail($this->scheduleId);
        
        $schedule->update($validatedData);

        $this->resetForm();
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
        session()->flash('success', 'Schedule and associated seats deleted successfully!');
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'confirmDeleteInput', 'deleteId']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTheatre()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $schedules = Schedule::with(['movie', 'theatre'])
            ->whereHas('movie', function ($query) {
                $query->where('is_active', true);
                if ($this->search) {
                    $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($this->search) . '%']);
                }
            })
            ->when($this->filterTheatre, function ($query) {
                $query->where('theatre_id', $this->filterTheatre);
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->when($this->searchDate, function ($query) {
                $query->whereDate('date', $this->searchDate);
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(10);

        return view('livewire.admin.schedules.manage-schedules', [
            'schedules' => $schedules,
        ]);
    }
}
