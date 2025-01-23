<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">My Bookings</h2>
    <div class="grid grid-cols-1 gap-4">
        @foreach ($bookings as $booking)
            <div class="border p-4 rounded-lg shadow">
                <h3 class="font-bold">Booking Code: {{ $booking->booking_code }}</h3>
                <p>Movie: {{ $booking->schedule->movie->title }}</p>
                <p>Total Seats: {{ $booking->total_seats }}</p>
                <p>Total Price: ${{ number_format($booking->total_price, 2) }}</p>
                <p>Status: {{ ucfirst($booking->status) }}</p>
                <h4 class="mt-2">Seats:</h4>
                <ul>
                    @foreach ($booking->seats as $seat)
                        <li>{{ $seat->seat_number }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>

