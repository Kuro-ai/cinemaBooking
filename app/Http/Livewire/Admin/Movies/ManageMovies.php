<?php
namespace App\Http\Livewire\Admin\Movies;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Theatre;
use Livewire\WithFileUploads; 

class ManageMovies extends Component
{
    use WithFileUploads;
    
    public $title, $genre, $director, $duration, $language, $trailer_url, $description, $is_active, $theatres = [];
    public $movies, $allTheatres;
    public $isEditing = false;
    public $movieId;
    public $image; 
    public $imagePath; 

    public function mount()
    {
        $this->allTheatres = Theatre::all();
        $this->movies = Movie::all(); 
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'duration' => 'required|string',
            'is_active' => 'boolean',
            'theatres' => 'nullable|array',
            'theatres.*' => 'exists:theatres,id',
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

        // Sync selected theatres
        if (!empty($this->theatres)) {
            $movie->theatres()->sync($this->theatres);
        }

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
        $this->theatres = $movie->theatres->pluck('id')->toArray();
    }

    // Update movie
    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'duration' => 'required|string',
            'is_active' => 'boolean',
            'theatres' => 'nullable|array',
            'theatres.*' => 'exists:theatres,id',
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

        // Sync selected theatres
        if (!empty($this->theatres)) {
            $movie->theatres()->sync($this->theatres);
        }

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
        $this->theatres = [];
        $this->image = null;
        $this->imagePath = null;
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.admin.movies.manage-movies');
    }
}


