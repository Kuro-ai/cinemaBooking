<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Seat;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'schedule'])->get();
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */

    public function create($scheduleId)
    {
        // Fetch the schedule with movie, theatre, and seats details
        $schedule = Schedule::with(['movie', 'theatre.cinema', 'theatre.seats'])
            ->findOrFail($scheduleId);

        // Pass the schedule to the booking form view
        return view('bookings.create', compact('schedule'));
    }

    /**
     * Store a newly created booking in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,id',
        ]);

        $schedule = Schedule::with('seats')->findOrFail($validated['schedule_id']);

        // Check seat availability from the pivot table (schedule_seat)
        $availableSeats = $schedule->seats()
            ->whereIn('seats.id', $validated['seats']) // Make sure to reference the seats table directly
            ->wherePivot('is_available', true) // This checks the availability in the pivot table (schedule_seat)
            ->pluck('seats.id')
            ->toArray();

        if (count($validated['seats']) !== count($availableSeats)) {
            return redirect()->back()->with('error', 'Some seats are no longer available.');
        }

        // Calculate the total price for the booked seats
        $totalPrice = Seat::whereIn('id', $validated['seats'])->sum('price');

        // Create the booking record
        $booking = Booking::create([
            'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
            'schedule_id' => $schedule->id,
            'booking_code' => strtoupper(uniqid('BOOK-')),
            'total_seats' => count($validated['seats']),
            'total_price' => $totalPrice,
            'status' => 'confirmed',
        ]);

        // Attach the seats to the booking and mark them as unavailable for the schedule
        foreach ($validated['seats'] as $seatId) {
            // Attach seat to the booking
            $booking->seats()->attach($seatId);

            // Update seat availability for this schedule in the pivot table
            $schedule->seats()->updateExistingPivot($seatId, ['is_available' => false]);
        }

        return redirect()->route('customer.dashboard')->with('success', 'Booking successful!');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        return view('bookings.edit', compact('booking'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:schedules,id',
            'total_seats' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $booking->update($validatedData);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
