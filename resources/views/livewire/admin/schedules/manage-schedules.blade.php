<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Manage Schedules</h2>

    @if (session()->has('success'))
        <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="space-y-4">
        <div>
            <label for="movie_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Movie</label>
            <select wire:model="movie_id" id="movie_id" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select a movie</option>
                @foreach ($movies as $movie)
                    <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="theatre_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Theatre</label>
            <select wire:model="theatre_id" id="theatre_id" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select a theatre</option>
                @foreach ($theatres as $theatre)
                    <option value="{{ $theatre->id }}">{{ $theatre->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
            <input type="date" wire:model="date" id="date" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
            <input type="time" wire:model="start_time" id="start_time" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
            <input type="time" wire:model="end_time" id="end_time" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex items-center">
            <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Is Active</label>
            <input type="checkbox" wire:model="is_active" id="is_active" class="rounded text-blue-600 dark:text-blue-400 border-gray-300 dark:border-gray-600 focus:ring-blue-500">
        </div>

        <button type="submit" class="w-full px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-md shadow-sm">
            {{ $isEditing ? 'Update' : 'Create' }} Schedule
        </button>
    </form>

    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-8 mb-4">Schedules</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse border border-gray-300 dark:border-gray-700">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Movie</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Theatre</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Date</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Start Time</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">End Time</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">Is Active</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-900">
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">{{ $schedule->movie->title }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">{{ $schedule->theatre->name }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">{{ $schedule->date }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">{{ $schedule->start_time }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">{{ $schedule->end_time }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">
                            @if ($schedule->is_active)
                                <span class="text-green-600 dark:text-green-400 font-semibold">Active</span>
                            @else
                                <span class="text-red-600 dark:text-red-400 font-semibold">Inactive</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">
                            <button wire:click="edit({{ $schedule->id }})" class="px-2 py-1 text-blue-600 dark:text-blue-400 hover:underline">Edit</button>
                            <button wire:click="delete({{ $schedule->id }})" class="px-2 py-1 text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
