<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Theatre;

class MovieController extends Controller
{
    /**
     * Display a listing of movies.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new movie.
     */
    public function create()
    {   
        $theatres = Theatre::all(); 
        return view('movies.create');
    }

    /**
     * Store a newly created movie in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:1',
            'language' => 'nullable|string|max:255',
            'trailer_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);


        return redirect()->route('movies.index')->with('success', 'Movie created successfully.');
    }

    /**
     * Display the specified movie.
     */
    public function show(Movie $movie)
    {
        return view('movies.show', compact('movie'));
    }

    /**
     * Show the form for editing the specified movie.
     */
    public function edit(Movie $movie)
    {
        $theatres = Theatre::all();
        return view('movies.edit', compact('movie'));
    }

    /**
     * Update the specified movie in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:1',
            'language' => 'nullable|string|max:255',
            'trailer_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $movie->update($validatedData);

        return redirect()->route('movies.index')->with('success', 'Movie updated successfully.');
    }

    /**
     * Remove the specified movie from storage.
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Movie deleted successfully.');
    }
}
