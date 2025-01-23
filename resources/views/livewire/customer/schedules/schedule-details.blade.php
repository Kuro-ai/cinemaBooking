<div class="p-6 bg-white dark:bg-gray-800">
    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Schedule Details</h2>
    <h3 class="font-bold text-gray-900 dark:text-gray-100">{{ $schedule->movie->title }}</h3>
    <p class="text-gray-700 dark:text-gray-300">Theatre: {{ $schedule->theatre->name }}</p>
    <p class="text-gray-700 dark:text-gray-300">Start Time: {{ $schedule->start_time }}</p>
    <p class="text-gray-700 dark:text-gray-300">End Time: {{ $schedule->end_time }}</p>

    <h4 class="mt-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Available Seats:</h4>
    <div class="grid grid-cols-6 gap-2 mt-2">
        @foreach ($schedule->theatre->seats as $seat)
            <button 
                class="py-2 px-4 rounded text-white font-medium transition-all 
                       {{ in_array($seat->id, $selectedSeats) ? 'bg-yellow-500 hover:bg-yellow-600' : 
                          ($seat->is_available ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-400 cursor-not-allowed') }}"
                {{ $seat->is_available ? '' : 'disabled' }}
                wire:click="toggleSeatSelection({{ $seat->id }}, {{ $seat->price }})">
                {{ $seat->seat_number }}
            </button>
        @endforeach
    </div>

    <h4 class="mt-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Total Price: ${{ number_format($totalPrice, 2) }}</h4>

    <div class="mt-4">
        <label class="text-gray-800 dark:text-gray-200" for="payment_method">Payment Method</label>
        <select wire:model="paymentMethod" id="payment_method" class="form-control bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded">
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
        </select>
    </div>

    <div class="mt-4 flex gap-4">
        <button class="py-2 px-6 rounded bg-blue-500 hover:bg-blue-600 text-white font-medium transition-all dark:bg-blue-600 dark:hover:bg-blue-700"
                wire:click="processPayment('purchase')">
            Purchase Selected Seats
        </button>
        <button class="py-2 px-6 rounded bg-yellow-500 hover:bg-yellow-600 text-white font-medium transition-all dark:bg-yellow-600 dark:hover:bg-yellow-700"
                wire:click="processPayment('book')">
            Book Selected Seats
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mt-4 p-4 rounded bg-green-500 text-white">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 p-4 rounded bg-red-500 text-white">
            {{ session('error') }}
        </div>
    @endif
</div>
