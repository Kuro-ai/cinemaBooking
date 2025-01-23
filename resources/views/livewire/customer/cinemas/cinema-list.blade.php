<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Cinemas</h2>

    <!-- List Cinemas -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2">Select a Cinema:</h3>
        <ul>
            @foreach($cinemas as $cinema)
                <li class="mb-2">
                    <button wire:click="loadTheatres({{ $cinema->id }})"
                        class="text-blue-500 hover:underline">
                        {{ $cinema->name }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- List Theatres -->
    @if ($selectedCinema)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">
                Theatres in {{ $selectedCinema->name }}:
            </h3>
            <ul>
                @foreach($theatres as $theatre)
                    <li class="mb-2">
                        <button wire:click="loadMovies({{ $theatre->id }})"
                            class="text-blue-500 hover:underline">
                            {{ $theatre->name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- List Movies -->
    @if ($selectedTheatre)
        <div>
            <h3 class="text-lg font-semibold mb-2">
                Movies in {{ $selectedTheatre->name }}:
            </h3>
            <ul>
                @foreach($movies as $movie)
                    <li class="mb-4">
                        <div>
                            <strong>{{ $movie->title }}</strong>
                            <p>{{ $movie->description }}</p>

                            <!-- List Schedules for Each Movie -->
                            @foreach($movie->schedules as $schedule)
                                <div class="mt-2 border p-2 rounded bg-gray-100">
                                    <p><strong>Schedule:</strong> {{ $schedule->start_time }}</p>
                                    
                                    <button 
                                        wire:click="loadSeats({{ $schedule->id }})" 
                                        class="text-blue-500 hover:underline">
                                        View Seats
                                    </button>

                                    <!-- Display Available Seats -->
                                    @if ($selectedSchedule == $schedule->id && !empty($availableSeats))
                                        <div>
                                            <p><strong>Available Seats:</strong></p>
                                            <div>
                                                @foreach ($availableSeats as $seat)
                                                    <label class="inline-block mr-4">
                                                        <input type="checkbox" 
                                                            wire:click="toggleSeatSelection({{ $seat->id }})" 
                                                            @checked(in_array($seat->id, $selectedSeats))>
                                                        Seat {{ $seat->seat_number }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Seat Actions -->
                                        <h3 class="mt-4">Select Action:</h3>
                                        <button 
                                            wire:click="bookSeats({{ $schedule->id }})" 
                                            class="bg-blue-500 dark:text-black px-4 py-2 rounded-md">
                                            Book
                                        </button>

                                        <div class="mt-2">
                                            <label for="paymentType">Payment Type:</label>
                                            <select wire:model="paymentType" id="paymentType" class="border-gray-300 rounded-md">
                                                <option value="">Select Payment Method</option>
                                                <option value="credit_card">Credit Card</option>
                                                <option value="paypal">PayPal</option>
                                                <option value="cash">Cash</option>
                                            </select>
                                        </div>

                                        <button 
                                            wire:click="purchaseSeats({{ $schedule->id }})" 
                                            class="bg-green-500 dark:text-black px-4 py-2 rounded-md mt-2">
                                            Purchase
                                        </button>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
