<div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg transition duration-300">
    <h3 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">🎬 Booking</h3>

    <!-- Movie and Schedule Section -->
    <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-1/3">
            <img src="{{ asset('storage/' . $movie->image_path) }}" class="w-full rounded-xl shadow-md object-cover h-64 md:h-auto">
        </div>

        <div class="md:w-2/3">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $movie->title }}</h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $movie->genre }} | {{ $movie->language }}</p>

            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">🎟 Select Schedule</h4>
            <div class="flex flex-wrap gap-3">
                @foreach($schedules as $schedule)
                    <button wire:click="selectSchedule({{ $schedule->id }})"
                        class="px-4 py-2 rounded-lg border transition duration-200
                        {{ $selectedSchedule && $selectedSchedule->id == $schedule->id 
                            ? 'bg-blue-600 text-white border-blue-600' 
                            : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-200 text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-600 border-gray-300 dark:border-gray-500' }}">
                        {{ \Carbon\Carbon::parse($schedule->release_date)->format('M d, Y h:i A') }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="dark:bg-green-100 border dark:border-green-400 dark:text-green-700 px-4 py-3 rounded relative mt-3 mb-3 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="dark:bg-red-100 border dark:border-red-400 dark:text-red-700 px-4 py-3 rounded relative mt-3 mb-3 text-center">
            {{ session('error') }}
        </div>
    @endif

    @if($selectedSchedule)
        <div class="mt-10 border-t border-gray-300 dark:border-gray-600 pt-6">
            <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100">🪑 Seat Selection</h4>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Choose your preferred seats below.</p>

            <!-- Seat Legend -->
            <div class="flex gap-6 mb-4 text-sm dark:text-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 border-2 border-blue-500 rounded"></div> Regular ($10)
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 border-2 border-purple-500 rounded"></div> Recliner ($15)
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 border-2 border-yellow-500 rounded"></div> VIP ($20)
                </div>
            </div>

            <div class="mx-auto">
                <p class="text-gray-600 dark:text-gray-400 mb-4 text-center">📽️ Screen</p>
                @foreach($seats as $rowLabel => $rowSeats)
                    <div class="flex items-center justify-center mb-2 gap-1">
                        <span class="w-6 text-lg font-semibold text-gray-800 dark:text-gray-300">{{ $rowLabel }}</span>

                        @foreach($rowSeats as $seat)
                            @php
                                $borderColor = match($seat->seat->seat_type) {
                                    'Regular' => 'border-blue-500',
                                    'Recliner' => 'border-purple-500',
                                    'VIP' => 'border-yellow-500',
                                    default => 'border-gray-300',
                                };
                            @endphp

                            <button 
                            wire:click="toggleSeat({{ $seat->id }})"
                            class="w-12 h-12 border-2 {{ $seat->border_color }} rounded-md flex items-center justify-center transition duration-200
                                {{ in_array($seat->id, $selectedSeats) 
                                    ? 'bg-green-500 text-white' 
                                    : ($seat->is_available 
                                        ? 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-900 dark:text-white' 
                                        : 'bg-red-500 text-white opacity-60 cursor-not-allowed') }}"
                            {{ !$seat->is_available ? 'disabled' : '' }}>
                            {{ $seat->seat->seat_number }}
                            </button>

                        @endforeach
                    </div>
                @endforeach
            </div>
            
            <!-- Price and Actions -->
            @if(count($selectedSeats) > 0)
                <h4 class="mt-4 text-xl font-semibold text-gray-900 dark:text-gray-200">
                    💰 Total Price: ${{ $totalPrice }}
                </h4>
            @endif

            <div class="mt-4">
                <label for="paymentMethod" class="block text-lg font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                <select wire:model="paymentMethod" id="paymentMethod" class="mt-1 block w-full border rounded-md shadow-sm p-2 dark:bg-gray-700 dark:text-white">
                    <option value="">Select Payment Method</option>
                    <option value="MPU">MPU</option>
                    <option value="Visa">Visa</option>
                </select>
            </div>    

            <div class="mt-6 flex gap-4">
                <button wire:click="processBooking('book')" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg shadow transition">
                    Book
                </button>
                <button wire:click="processBooking('buy')" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow transition">
                    Buy
                </button>
            </div>
        </div>
    @endif
</div>
