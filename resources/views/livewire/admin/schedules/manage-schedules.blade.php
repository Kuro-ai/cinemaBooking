<div class="p-6 bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-md">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 mb-6">Manage Schedules</h2>
    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Schedule Fields -->
            <select wire:model.defer="movie_id" 
                    class="form-select bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md" required>
                <option value="">Select a movie</option>
                @foreach ($movies as $movie)
                    <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                @endforeach
            </select>

            <select wire:model.defer="theatre_id" 
                    class="form-select bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md" required>
                <option value="">Select a theatre</option>
                @foreach ($theatres as $theatre)
                    <option value="{{ $theatre->id }}">{{ $theatre->name }}</option>
                @endforeach
            </select>

            <input type="date" 
                   wire:model.defer="date" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md" required>

            <input type="time" 
                   wire:model.defer="start_time" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md" required>

           
         </div>

        <div>
            <label class="flex items-center space-x-2">
                <input type="checkbox" 
                       wire:model.defer="is_active" 
                       class="form-checkbox text-indigo-600 dark:text-indigo-400 rounded">
                <span>Active</span>
            </label>
        </div>

        <button type="submit" 
                class="bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-lg">
            {{ $isEditing ? 'Update' : 'Create' }}
        </button>
    </form>

    <!-- Schedules Table -->
    <table class="table-auto w-full mt-8 bg-gray-100 dark:bg-gray-700 border dark:border-gray-600 rounded-lg">
        <thead class="bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
            <tr>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Movie</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Theatre</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Date</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Start Time</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Status</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="text-center text-gray-800 dark:text-gray-200">
            @foreach ($schedules as $schedule)
                <tr class="border-t dark:border-gray-600 dark:hover:bg-gray-800">
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $schedule->movie->title }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $schedule->theatre->name }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $schedule->date }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $schedule->start_time }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">
                        @if ($schedule->is_active)
                            <span class="text-green-600 dark:text-green-400 font-semibold">Active</span>
                        @else
                            <span class="text-red-600 dark:text-red-400 font-semibold">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border dark:border-gray-600">
                        <button wire:click="edit({{ $schedule->id }})" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded-lg">
                            Edit
                        </button>
                        <button wire:click="confirmDelete({{ $schedule->id }})" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Confirm Delete Modal -->
    @if ($showModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Confirm Delete</h3>
            <p class="text-sm mb-4 text-gray-600 dark:text-gray-400">
                Type <strong class="text-gray-900 dark:text-gray-100">"Delete Confirm"</strong> to confirm the deletion.
            </p>
            <input 
                type="text" 
                wire:model.defer="confirmDeleteInput" 
                placeholder="Delete Confirm" 
                class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2 w-full"
            >
            @error('confirmDeleteInput')
                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
            @enderror
            <div class="flex justify-end space-x-4 mt-4">
                <button 
                    wire:click="closeModal" 
                    class="px-4 py-2 dark:hover:bg-gray-700 text-white rounded-lg"
                >
                    Cancel
                </button>
                <button 
                    wire:click="delete" 
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-lg"
                >
                    Confirm
                </button>
            </div>
        </div>
    </div>
@endif

</div>
