<?php

namespace App\Http\Livewire\Admin\Movies;

use Livewire\Component;
use App\Models\Movie;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageMovies extends Component
{
    use WithFileUploads, WithPagination;

    public $title, $genre, $director, $duration, $language, $trailer_url, $description, $is_active;
    public $movieId, $image, $existingImagePath;
    public $isEditing = false, $showModal = false, $deleteId, $confirmDeleteInput = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'genre' => 'required|string|max:255',
        'duration' => 'required|string',
        'is_active' => 'boolean',
        'image' => 'nullable|image|max:1024',
    ];

    public function store()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'genre' => $this->genre,
            'director' => $this->director,
            'duration' => $this->duration,
            'language' => $this->language,
            'trailer_url' => $this->trailer_url,
            'description' => $this->description,
            'is_active' => $this->is_active ?? false,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('movies', 'public');
        }

        Movie::create($data);

        session()->flash('success', 'Movie created successfully!');
        $this->resetForm();
    }

    public function edit($movieId)
    {
        $this->isEditing = true;
        $movie = Movie::findOrFail($movieId);

        $this->movieId = $movie->id;
        $this->title = $movie->title;
        $this->genre = $movie->genre;
        $this->director = $movie->director;
        $this->duration = $movie->duration;
        $this->language = $movie->language;
        $this->trailer_url = $movie->trailer_url;
        $this->description = $movie->description;
        $this->is_active = $movie->is_active;
        $this->existingImagePath = $movie->image_path;
    }

    public function update()
    {
        $this->validate();

        $movie = Movie::findOrFail($this->movieId);

        $data = [
            'title' => $this->title,
            'genre' => $this->genre,
            'director' => $this->director,
            'duration' => $this->duration,
            'language' => $this->language,
            'trailer_url' => $this->trailer_url,
            'description' => $this->description,
            'is_active' => $this->is_active ?? false,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('movies', 'public');
        }

        $movie->update($data);

        session()->flash('success', 'Movie updated successfully!');
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showModal = true;
        $this->confirmDeleteInput = '';
    }

    public function delete()
    {
        if ($this->confirmDeleteInput === 'Delete Confirm') {
            Movie::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'Movie deleted successfully!');
            $this->resetForm();
        } else {
            session()->flash('error', 'You must type "Delete Confirm" to proceed.');
        }
    }

    public function resetForm()
    {
        $this->title = '';
        $this->genre = '';
        $this->director = '';
        $this->duration = '';
        $this->language = '';
        $this->trailer_url = '';
        $this->description = '';
        $this->is_active = false;
        $this->image = null;
        $this->existingImagePath = null;
        $this->isEditing = false;
        $this->showModal = false;
        $this->confirmDeleteInput = '';
        $this->resetPage(); 
    }

    public function render()
    {
        return view('livewire.admin.movies.manage-movies', [
            'movies' => Movie::paginate(10),
        ]);
    }
}
