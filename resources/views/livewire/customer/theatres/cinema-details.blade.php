<div class="p-6">
    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
        Theatres at {{ $cinema->name }}
    </h2>
    <div class="grid grid-cols-1 gap-4">
        @foreach ($cinema->theatres as $theatre)
            <div class="border border-gray-300 dark:border-gray-700 p-4 rounded-lg shadow bg-white dark:bg-gray-800">
                <h3 class="font-bold text-gray-900 dark:text-gray-100">{{ $theatre->name }}</h3>
                <p class="text-gray-700 dark:text-gray-300">Capacity: {{ $theatre->capacity }}</p>
                <h4 class="mt-2 text-gray-800 dark:text-gray-200">Schedules:</h4>
                <ul class="mt-2 space-y-2">
                    @foreach ($theatre->schedules as $schedule)
                        <li class="flex justify-between items-center border-t pt-2 border-gray-200 dark:border-gray-700">
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ $schedule->movie->title }} - {{ $schedule->start_time }} to {{ $schedule->end_time }}
                            </span>
                            <button onclick="location.href='{{ route('schedule.details', $schedule->id) }}'"
                                    class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded transition-all dark:bg-blue-600 dark:hover:bg-blue-700">
                                Check Details
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
