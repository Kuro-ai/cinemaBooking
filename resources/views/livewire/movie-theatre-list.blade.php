<div>
    <h3 class="text-lg font-bold">Movies</h3>

    <!-- Movie List -->
    <div class="mt-4">
        @foreach ($movies as $movie)
            <button 
                class="text-blue-500 hover:underline mt-2"
                wire:click="selectMovie({{ $movie->id }})">
                {{ $movie->title }}
            </button>
        @endforeach
    </div>

    <!-- Selected Movie Details -->
    @if ($selectedMovie)
        <div class="mt-6">
            <h4 class="text-xl font-bold">{{ $selectedMovie->title }}</h4>
            <p>{{ $selectedMovie->description }}</p>

            <h5 class="mt-4 text-lg font-semibold">Available Theatres:</h5>
            @foreach ($schedules as $schedule)
                <div class="mt-2 border p-4">
                    <p><strong>Theatre:</strong> {{ $schedule->theatre->name }}</p>
                    <p><strong>Cinema:</strong> {{ $schedule->theatre->cinema->name }}</p>
                    <p><strong>Schedule:</strong> {{ $schedule->start_time }} - {{ $schedule->end_time }}</p>

                    <!-- Book Button -->
                    <button 
                        wire:click="bookSeat({{ $schedule->id }})"
                        class="mt-2 px-4 py-2 bg-green-500 text-white rounded">
                        Book Now
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
