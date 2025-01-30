<div>
    @if ($schedules->isEmpty())
        <p>No schedules available for this movie in this theatre.</p>
    @else
        <h3>Select a schedule:</h3>
        <ul>
            @foreach ($schedules as $schedule)
                <li>
                    {{ $schedule->date }} ({{ $schedule->start_time }})
                    <button wire:click="loadSeats({{ $schedule->id }})" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                        View Seats
                    </button>
                </li>
            @endforeach
        </ul>
    @endif

    @if ($selectedSchedule)
        <h3>Available Seats:</h3>
        @if ($availableSeats->isEmpty())
            <p>No seats available.</p>
        @else
            <div>
                @foreach ($availableSeats as $seat)
                    <label>
                        <input type="checkbox" wire:model="selectedSeats" value="{{ $seat->id }}">
                        Seat {{ $seat->seat_number }}
                    </label>
                @endforeach
            </div>

            <h3>Select Action:</h3>
            <button wire:click="bookSeats" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                Book
            </button>

            <div>
                <label for="paymentType">Payment Type:</label>
                <select wire:model="paymentType" id="paymentType" class="border-gray-300 rounded-md">
                    <option value="">Select Payment Method</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="cash">Cash</option>
                </select>
            </div>

            <button wire:click="purchaseSeats" class="bg-green-500 text-white px-4 py-2 rounded-md">
                Purchase
            </button>
        @endif
    @endif

    @if (session()->has('success'))
        <p class="text-green-500">{{ session('success') }}</p>
    @endif

    @if (session()->has('error'))
        <p class="text-red-500">{{ session('error') }}</p>
    @endif
</div>
