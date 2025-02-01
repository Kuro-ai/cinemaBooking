<?php

namespace App\Http\Livewire\Admin\Theatres;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Cinema;
use App\Models\Theatre;

class ManageTheatres extends Component
{
    use WithFileUploads, WithPagination;

    public $cinemas;
    public $name, $cinema_id, $type = '2D', $is_active = true, $screen_size, $sound_system, $image, $theatreId;
    public $showModal = false;
    public $confirmDeleteInput = '';
    public $deleteId;
    public $isEditing = false;
    public $existingImagePath;
    public $search = '';
    public $filterCinema = '';
    public $filterActive = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'cinema_id' => 'required|exists:cinemas,id',
        'type' => 'in:2D,3D,IMAX',
        'is_active' => 'boolean',
        'screen_size' => 'nullable|string|max:255',
        'sound_system' => 'nullable|string|max:255',
        'image' => 'nullable|image|max:1024', 
    ];

    public function mount()
    {
        $this->cinemas = Cinema::all();
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->cinema_id = '';
        $this->type = '2D';
        $this->is_active = true;
        $this->screen_size = '';
        $this->sound_system = '';
        $this->image = null;
        $this->theatreId = null;
        $this->isEditing = false;

        $this->resetPage(); 
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'cinema_id' => $this->cinema_id,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'screen_size' => $this->screen_size,
            'sound_system' => $this->sound_system,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('theatres', 'public');
        }

        Theatre::create($data);

        $this->resetForm();
        session()->flash('success', 'Theatre created successfully!');
    }

    public function edit($id)
    {
        $theatre = Theatre::findOrFail($id);
        $this->theatreId = $id;
        $this->name = $theatre->name;
        $this->cinema_id = $theatre->cinema_id;
        $this->type = $theatre->type;
        $this->is_active = $theatre->is_active;
        $this->screen_size = $theatre->screen_size;
        $this->sound_system = $theatre->sound_system;
        $this->image = null; 
        $this->existingImagePath = $theatre->image_path; 
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'cinema_id' => $this->cinema_id,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'screen_size' => $this->screen_size,
            'sound_system' => $this->sound_system,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('theatres', 'public');
        }

        Theatre::findOrFail($this->theatreId)->update($data);

        $this->resetForm();
        session()->flash('success', 'Theatre updated successfully!');
    }

    public function delete()
    {
        if ($this->confirmDeleteInput !== 'Delete Confirm') {
            session()->flash('error', 'Please type "Delete Confirm" to confirm deletion.');
            return;
        }

        Theatre::findOrFail($this->deleteId)->delete();
        $this->reset(['confirmDeleteInput', 'deleteId', 'showModal']);
        session()->flash('success', 'Theatre deleted successfully!');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCinema()
    {
        $this->resetPage();
    }

    public function updatingFilterActive()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . strtolower($this->search) . '%';

        $theatres = Theatre::with('cinema')
            ->whereRaw('LOWER(name) LIKE ?', [$searchTerm]);

        if (!empty($this->filterCinema)) {
            $theatres->where('cinema_id', $this->filterCinema);
        }

        if ($this->filterActive !== '') {
            $theatres->where('is_active', $this->filterActive);
        }

        return view('livewire.admin.theatres.manage-theatres', [
            'theatres' => $theatres->paginate(10),
        ]);
    }
}
