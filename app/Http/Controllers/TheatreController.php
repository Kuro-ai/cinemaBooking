<?php

namespace App\Http\Controllers;

use App\Models\Theatre;
use Illuminate\Http\Request;

class TheatreController extends Controller
{
    /**
     * Display a listing of theatres.
     */
    public function index()
    {
        $theatres = Theatre::with('cinema')->get();
        return view('theatres.index', compact('theatres'));
    }

    /**
     * Show the form for creating a new theatre.
     */
    public function create()
    {
        return view('theatres.create');
    }

    /**
     * Store a newly created theatre in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'cinema_id' => 'required|exists:cinemas,id',
            'type' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'screen_size' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:255',
        ]);

        Theatre::create($validatedData);

        return redirect()->route('theatres.index')->with('success', 'Theatre created successfully.');
    }

    /**
     * Display the specified theatre.
     */
    public function show(Theatre $theatre)
    {
        return view('theatres.show', compact('theatre'));
    }

    /**
     * Show the form for editing the specified theatre.
     */
    public function edit(Theatre $theatre)
    {
        return view('theatres.edit', compact('theatre'));
    }

    /**
     * Update the specified theatre in storage.
     */
    public function update(Request $request, Theatre $theatre)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'cinema_id' => 'required|exists:cinemas,id',
            'type' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'screen_size' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:255',
        ]);

        $theatre->update($validatedData);

        return redirect()->route('theatres.index')->with('success', 'Theatre updated successfully.');
    }

    /**
     * Remove the specified theatre from storage.
     */
    public function destroy(Theatre $theatre)
    {
        $theatre->delete();

        return redirect()->route('theatres.index')->with('success', 'Theatre deleted successfully.');
    }
}
