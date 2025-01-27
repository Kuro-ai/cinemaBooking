<?php

namespace App\Http\Livewire\Admin\Cinemas;

use Livewire\Component;
use App\Models\Cinema;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageCinemas extends Component
{
    use WithFileUploads, WithPagination;

    public $name, $location, $city, $contact_number, $email, $is_active, $cinemaId, $image;
    public $isEditing = false, $showModal = false, $confirmDeleteInput = ''; 
    public $existingImagePath;

    protected $rules = [
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'city' => 'nullable|string|max:255',
        'contact_number' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'is_active' => 'boolean',
        'image' => 'nullable|image|max:1024',
        'confirmDeleteInput' => 'required_if:showModal,true|in:Delete Confirm',
    ];

    protected $listeners = [
        'resetPage' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.admin.cinemas.manage-cinemas', [
            'cinemas' => $this->loadCinemas()
        ]);
    }

    public function loadCinemas()
    {
        return Cinema::paginate(10);
    }

    public function resetForm()
    {
        $this->name = '';
        $this->location = '';
        $this->city = '';
        $this->contact_number = '';
        $this->email = '';
        $this->is_active = true;
        $this->image = null;
        $this->cinemaId = null;
        $this->existingImagePath = null;
        $this->isEditing = false;

        $this->resetPage(); 
    }

    public function mount()
    {
        $this->isEditing = false;
        $this->resetForm();
    }

    public function store()
    {
       
        $this->validate();
        $data = [
            'name' => $this->name,
            'location' => $this->location,
            'city' => $this->city,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'is_active' => $this->is_active ?? false,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('cinemas', 'public');
        }

        Cinema::create($data);

        $this->resetForm();
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
        $this->image = null;
        $this->existingImagePath = $cinema->image_path; 
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $cinema = Cinema::findOrFail($this->cinemaId);

        $data = $this->validate();
        if ($this->image) {
            $data['image_path'] = $this->image->store('cinemas', 'public'); 
        } else {
            $data['image_path'] = $cinema->image_path; 
        }

        $cinema->update($data);

        $this->resetForm();
        session()->flash('success', 'Cinema updated successfully!');
    }

    public function delete()
    {
        $this->validate([
            'confirmDeleteInput' => 'required|in:Delete Confirm', 
        ]);

        Cinema::findOrFail($this->cinemaId)->delete(); 
        session()->flash('success', 'Cinema deleted successfully!');
        $this->closeModal(); 
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->cinemaId = null; 
        $this->confirmDeleteInput = ''; 
    }

    public function confirmDelete($id)
    {
        $this->cinemaId = $id; 
        $this->showModal = true; 
    }
}
