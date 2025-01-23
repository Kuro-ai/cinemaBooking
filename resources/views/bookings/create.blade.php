<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book Seats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold">Movie: {{ $schedule->movie->title }}</h3>
                <p><strong>Theatre:</strong> {{ $schedule->theatre->name }}</p>
                <p><strong>Cinema:</strong> {{ $schedule->theatre->cinema->name }}</p>
                <p><strong>Schedule:</strong> {{ $schedule->start_time }} - {{ $schedule->end_time }}</p>

                <h4 class="mt-6 font-semibold">Available Seats</h4>
                <form method="POST" action="{{ route('bookings.store') }}">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                    <div class="grid grid-cols-4 gap-4 mt-4">
                        @foreach ($schedule->seats as $seat)
                            @if ($seat->pivot->is_available)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="seats[]" value="{{ $seat->id }}" />
                                    <span>{{ $seat->seat_number }} ({{ ucfirst($seat->type) }})</span>
                                </label>
                            @else
                                <span class="text-gray-500">
                                    {{ $seat->seat_number }} (Booked)
                                </span>
                            @endif
                        @endforeach
                    </div>
                
                    <button type="submit" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded">
                        Confirm Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
