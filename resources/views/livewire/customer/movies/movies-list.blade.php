<div>
    <h1 class="text-xl font-bold mb-4">Movies and Theatres</h1>

    @foreach ($movies as $movie)
        <div class="mb-6">
            <h2 class="text-lg font-semibold">{{ $movie->title }}</h2>
            <p class="text-gray-600">{{ $movie->genre }} | {{ $movie->duration }} mins</p>

            <!-- Theatres associated with this movie -->
            <ul class="mt-2">
                @foreach ($movie->theatres as $theatre)
                    <li class="mb-2">
                        <strong>Theatre:</strong> {{ $theatre->name }} ({{ $theatre->cinema->name }})
                        <a href="{{ route('schedule.book', ['theatre_id' => $theatre->id, 'movie_id' => $movie->id]) }}" class="text-blue-500 hover:underline">
                            View Schedules & Book Seats
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
