<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = Payment::with('booking')->get();
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        return view('payments.create', compact('booking'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'status' => 'required|in:pending,completed,failed,cancelled',
            'payment_type' => 'required|in:immediate,post_booking', // added this field to differentiate payment type
        ]);

        // Create payment record
        $payment = Payment::create($validatedData);

        // If payment is immediate, set the booking status to confirmed and update the status
        if ($validatedData['payment_type'] == 'immediate') {
            $payment->status = 'completed';
            $payment->save();

            $booking = Booking::find($validatedData['booking_id']);
            $booking->status = 'confirmed';
            $booking->save();
        }

        return redirect()->route('payments.index')->with('success', 'Payment processed successfully.');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validatedData = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'status' => 'required|in:pending,completed,failed,cancelled',
            'payment_type' => 'required|in:immediate,post_booking', // added this field
        ]);

        $payment->update($validatedData);

        // Update the booking status when payment is confirmed
        if ($validatedData['status'] == 'completed' && $validatedData['payment_type'] == 'immediate') {
            $booking = Booking::find($validatedData['booking_id']);
            $booking->status = 'confirmed';
            $booking->save();
        }

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
