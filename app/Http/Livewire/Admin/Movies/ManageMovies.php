<?php
namespace App\Http\Livewire\Admin\Movies;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Theatre;
use Livewire\WithFileUploads; 

class ManageMovies extends Component
{
    use WithFileUploads;
    
    public $title, $genre, $director, $duration, $language, $trailer_url, $description, $is_active;
    public $movies;
    public $isEditing = false;
    public $movieId;
    public $image; 
    public $imagePath; 
    public $showModal = false;
    public $deleteId;
    public $confirmDeleteInput = '';
    public $existingImagePath;

    public function mount()
    {
        $this->movies = Movie::all(); 
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'duration' => 'required|string',
            'is_active' => 'boolean',
            'image' => 'required|image|max:1024',
        ]);

        // Save the uploaded image
        $imagePath = $this->image->store('movies', 'public');

        $movie = Movie::create([
            'title' => $this->title,
            'genre' => $this->genre,
            'director' => $this->director,
            'duration' => $this->duration,
            'language' => $this->language,
            'trailer_url' => $this->trailer_url,
            'description' => $this->description,
            'is_active' => $this->is_active ?? false,
            'image_path' => $imagePath,
        ]);

        $this->movies->push($movie); 
        session()->flash('success', 'Movie created successfully!');
        $this->resetForm();
    }


    public function edit($movieId)
    {
        $this->isEditing = true;
        $movie = Movie::find($movieId);
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

    // Update movie
    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'duration' => 'required|string',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:1024',
        ]);

        $movie = Movie::find($this->movieId);

        // Update the image if a new one is uploaded
        if ($this->image) {
            $imagePath = $this->image->store('movies', 'public');
            $movie->image_path = $imagePath;
        }

        $movie->update([
            'title' => $this->title,
            'genre' => $this->genre,
            'director' => $this->director,
            'duration' => $this->duration,
            'language' => $this->language,
            'trailer_url' => $this->trailer_url,
            'description' => $this->description,
            'is_active' => $this->is_active ?? false,
        ]);

        $this->movies = $this->movies->map(function ($m) use ($movie) {
            return $m->id === $movie->id ? $movie : $m;
        });

        session()->flash('success', 'Movie updated successfully!');
        $this->resetForm();
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
        $this->imagePath = null;
        $this->isEditing = false;
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
            Movie::findOrFail($this->deleteId)->delete();
            $this->movies = $this->movies->where('id', '!=', $this->deleteId);
            $this->showModal = false;
            session()->flash('success', 'Movie deleted successfully!');
        } else {
            session()->flash('error', 'You must type "Delete Confirm" to proceed.');
        }
    }

    public function render()
    {
        return view('livewire.admin.movies.manage-movies');
    }
}


