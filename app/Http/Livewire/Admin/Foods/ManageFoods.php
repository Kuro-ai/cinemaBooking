<?php

namespace App\Http\Livewire\Admin\Foods;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Food;
use Illuminate\Support\Facades\Storage;

class ManageFoods extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $image, $type, $foodId, $existingImagePath;
    public $isEditing = false, $showModal = false, $confirmDeleteInput = '';
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:Food,Drink',
        'image' => 'nullable|image|max:1024',
        'confirmDeleteInput' => 'required_if:showModal,true|in:Delete Confirm',
    ];

    public function render()
    {
        return view('livewire.admin.foods.manage-foods', [
            'foods' => Food::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('type', 'like', '%' . $this->search . '%')
                ->paginate(10),
        ]);
    }

    public function resetForm()
    {
        $this->name = '';
        $this->image = null;
        $this->type = '';
        $this->foodId = null;
        $this->existingImagePath = null;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('foods', 'public');
        }

        Food::create($data);

        $this->resetForm();
        session()->flash('success', 'Food item added successfully!');
    }

    public function edit($id)
    {
        $food = Food::findOrFail($id);

        $this->foodId = $id;
        $this->name = $food->name;
        $this->type = $food->type;
        $this->existingImagePath = $food->image_path;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $food = Food::findOrFail($this->foodId);

        $data = [
            'name' => $this->name,
            'type' => $this->type,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('foods', 'public');
        } else {
            $data['image_path'] = $food->image_path;
        }

        $food->update($data);

        $this->resetForm();
        session()->flash('success', 'Food item updated successfully!');
    }

    public function confirmDelete($id)
    {
        $this->foodId = $id;
        $this->showModal = true;
    }

    public function delete()
    {
        $this->validate([
            'confirmDeleteInput' => 'required|in:Delete Confirm',
        ]);

        $food = Food::findOrFail($this->foodId);

        if ($food->image_path) {
            Storage::disk('public')->delete($food->image_path);
        }

        $food->delete();

        $this->closeModal();
        session()->flash('success', 'Food item deleted successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->foodId = null;
        $this->confirmDeleteInput = '';
    }
}
