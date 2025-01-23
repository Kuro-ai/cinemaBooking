<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Movie;
use App\Models\Theatre;
use Illuminate\Http\Request;
use App\Models\Seat;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedules.
     */
    public function index()
    {
        $schedules = Schedule::with(['movie', 'theatre'])->orderBy('date')->orderBy('start_time')->get();
        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        $movies = Movie::all();
        $theatres = Theatre::all();
        return view('schedules.create', compact('movies', 'theatres'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'theatre_id' => 'required|exists:theatres,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'boolean',
        ]);

        // Create the schedule
        $schedule = Schedule::create($validatedData);

        // Retrieve all seats for the theatre
        $seats = Seat::where('theatre_id', $schedule->theatre_id)->get();

        // Attach seats to the schedule
        foreach ($seats as $seat) {
            $schedule->seats()->attach($seat->id, ['is_available' => true]);
            Log::info("Seat {$seat->id} attached to Schedule {$schedule->id}");
        }
        

        return redirect()->route('schedules.index')->with('success', 'Schedule created and seats initialized successfully.');
    }

    /**
     * Display the specified schedule.
     */
    public function show(Schedule $schedule)
    {
        return view('schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule)
    {
        $movies = Movie::all();
        $theatres = Theatre::all();
        return view('schedules.edit', compact('schedule', 'movies', 'theatres'));
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validatedData = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'theatre_id' => 'required|exists:theatres,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'boolean',
        ]);

        $schedule->update($validatedData);

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
