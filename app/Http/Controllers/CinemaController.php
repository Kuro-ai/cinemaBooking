<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    /**
     * Display a listing of cinemas.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        return view('cinemas.index', compact('cinemas'));
    }

    /**
     * Show the form for creating a new cinema.
     */
    public function create()
    {
        return view('cinemas.create');
    }

    /**
     * Store a newly created cinema in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'image_path' => 'nullable|string',
        ]);

        Cinema::create($validatedData);

        return redirect()->route('cinemas.index')->with('success', 'Cinema added successfully.');
    }

    /**
     * Display the specified cinema.
     */
    public function show(Cinema $cinema)
    {
        return view('cinemas.show', compact('cinema'));
    }

    /**
     * Show the form for editing the specified cinema.
     */
    public function edit(Cinema $cinema)
    {
        return view('cinemas.edit', compact('cinema'));
    }

    /**
     * Update the specified cinema in storage.
     */
    public function update(Request $request, Cinema $cinema)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'image_path' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $cinema->update($validatedData);

        return redirect()->route('cinemas.index')->with('success', 'Cinema updated successfully.');
    }

    /**
     * Remove the specified cinema from storage.
     */
    public function destroy(Cinema $cinema)
    {
        $cinema->delete();

        return redirect()->route('cinemas.index')->with('success', 'Cinema deleted successfully.');
    }
}
