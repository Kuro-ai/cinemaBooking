<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    /**
     * Display a listing of the seats.
     */
    public function index()
    {
        $seats = Seat::with('theatre')->get();
        return view('seats.index', compact('seats'));
    }

    /**
     * Show the form for creating a new seat.
     */
    public function create()
    {
        return view('seats.create');
    }

    /**
     * Store a newly created seat in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'theatre_id' => 'required|exists:theatres,id',
            'seat_number' => 'required|string|max:10',
            'type' => 'required|in:regular,vip,recliner',
            'price' => 'nullable|numeric|min:0',
            'is_available' => 'boolean',
        ]);

        Seat::create($validatedData);

        return redirect()->route('seats.index')->with('success', 'Seat created successfully.');
    }

    /**
     * Display the specified seat.
     */
    public function show(Seat $seat)
    {
        return view('seats.show', compact('seat'));
    }

    /**
     * Show the form for editing the specified seat.
     */
    public function edit(Seat $seat)
    {
        return view('seats.edit', compact('seat'));
    }

    /**
     * Update the specified seat in storage.
     */
    public function update(Request $request, Seat $seat)
    {
        $validatedData = $request->validate([
            'theatre_id' => 'required|exists:theatres,id',
            'seat_number' => 'required|string|max:10',
            'type' => 'required|in:regular,vip,recliner',
            'price' => 'nullable|numeric|min:0',
            'is_available' => 'boolean',
        ]);

        $seat->update($validatedData);

        return redirect()->route('seats.index')->with('success', 'Seat updated successfully.');
    }

    /**
     * Remove the specified seat from storage.
     */
    public function destroy(Seat $seat)
    {
        $seat->delete();

        return redirect()->route('seats.index')->with('success', 'Seat deleted successfully.');
    }
}
