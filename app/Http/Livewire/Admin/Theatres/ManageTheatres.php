<?php

namespace App\Http\Livewire\Admin\Theatres;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Cinema;
use App\Models\Theatre;

class ManageTheatres extends Component
{
    use WithFileUploads;

    public $theatres = [], $cinemas;
    public $name, $cinema_id, $type = '2D', $is_active = true, $screen_size, $sound_system, $image, $theatreId;
    public $showModal = false;
    public $confirmDeleteInput = '';
    public $deleteId;
    public $isEditing = false;
    public $existingImagePath;

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
        $this->loadTheatres();
    }

    public function loadTheatres()
    {
        $this->theatres = Theatre::with('cinema')->get();
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

        // Handle image upload
        if ($this->image) {
            $data['image_path'] = $this->image->store('theatres', 'public');
        }

        Theatre::create($data);

        $this->resetForm();
        $this->loadTheatres();
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

        // Handle image upload
        if ($this->image) {
            $data['image_path'] = $this->image->store('theatres', 'public');
        }

        Theatre::findOrFail($this->theatreId)->update($data);

        $this->resetForm();
        $this->loadTheatres();
        session()->flash('success', 'Theatre updated successfully!');
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

        Theatre::findOrFail($this->deleteId)->delete();
        $this->reset(['confirmDeleteInput', 'deleteId', 'showModal']);
        $this->loadTheatres();
        session()->flash('success', 'Theatre deleted successfully!');
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'confirmDeleteInput', 'deleteId']);
    }

    public function render()
    {
        return view('livewire.admin.theatres.manage-theatres');
    }
}
