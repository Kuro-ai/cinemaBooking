<?php
namespace App\Http\Livewire\Admin\Movies;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Theatre;

class ManageMovies extends Component
{
    public $title, $genre, $release_date, $director, $duration, $language, $trailer_url, $description, $is_active, $theatres = [];
    public $movies, $allTheatres;
    public $isEditing = false;
    public $movieId;

    // Mount method to load existing theatres
    public function mount()
    {
        $this->allTheatres = Theatre::all(); // Get all theatres
        $this->movies = Movie::all(); // Get all movies
    }

    // Store new movie
    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'release_date' => 'required|date',
            'duration' => 'required|string',
            'is_active' => 'boolean',
            'theatres' => 'nullable|array',
            'theatres.*' => 'exists:theatres,id',
        ]);

        $movie = Movie::create([
            'title' => $this->title,
            'genre' => $this->genre,
            'release_date' => $this->release_date,
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

        $this->movies->push($movie); 
        session()->flash('success', 'Movie created successfully!');
        $this->resetForm();
    }

    // Edit existing movie
    public function edit($movieId)
    {
        $this->isEditing = true;
        $movie = Movie::find($movieId);
        $this->movieId = $movie->id;
        $this->title = $movie->title;
        $this->genre = $movie->genre;
        $this->release_date = $movie->release_date;
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
            'release_date' => 'required|date',
            'duration' => 'required|string',
            'is_active' => 'boolean',
            'theatres' => 'nullable|array',
            'theatres.*' => 'exists:theatres,id',
        ]);

        $movie = Movie::find($this->movieId);
        $movie->update([
            'title' => $this->title,
            'genre' => $this->genre,
            'release_date' => $this->release_date,
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

    // Reset the form
    public function resetForm()
    {
        $this->title = '';
        $this->genre = '';
        $this->release_date = '';
        $this->director = '';
        $this->duration = '';
        $this->language = '';
        $this->trailer_url = '';
        $this->description = '';
        $this->is_active = false;
        $this->theatres = [];
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.admin.movies.manage-movies');
    }
}


