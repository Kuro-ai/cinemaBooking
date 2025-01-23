<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 mb-4">Manage Movies</h2>
    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="mb-4">
        <div class="grid grid-cols-2 gap-4">
            <!-- Movie Fields -->
            <input type="text" 
                   wire:model.defer="title" 
                   placeholder="Title" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" 
                   required>
            <input type="text" 
                   wire:model.defer="genre" 
                   placeholder="Genre" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" 
                   required>
            <input type="date" 
                   wire:model.defer="release_date" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" 
                   required>
            <input type="text" 
                   wire:model.defer="director" 
                   placeholder="Director" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
            <input type="text" 
                   wire:model.defer="duration" 
                   placeholder="HH:MM" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" 
                   pattern="([0-9]{1,2}):([0-9]{2})" 
                   title="Enter time in HH:MM format" 
                   required 
                   oninput="this.value = this.value.replace(/[^0-9:]/g, '')">            
            <input type="text" 
                   wire:model.defer="language" 
                   placeholder="Language" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
            <input type="url" 
                   wire:model.defer="trailer_url" 
                   placeholder="Trailer URL" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
            <textarea wire:model.defer="description" 
                      placeholder="Description" 
                      class="form-textarea bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"></textarea>
            <label class="flex items-center">
                <input type="checkbox" 
                       wire:model.defer="is_active" 
                       class="form-checkbox bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                <span class="ml-2 text-gray-800 dark:text-gray-200">Active</span>
            </label>
            
            <!-- Multi-Select Dropdown for Theatres -->
            <div class="col-span-2">
                <label for="theatres" class="block text-gray-800 dark:text-gray-200">Select Theatres</label>
                <select wire:model.defer="theatres" id="theatres" multiple 
                        class="form-select bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 w-full"
                        size="5">
                    @foreach($allTheatres as $theatre)
                        <option value="{{ $theatre->id }}">
                            {{ $theatre->name }} ({{ $theatre->cinema->name }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            
        </div>
        <button type="submit" 
                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800">
            {{ $isEditing ? 'Update' : 'Create' }}
        </button>
    </form>

    <!-- Movie Table -->
    <table class="table-auto w-full bg-gray-100 dark:bg-gray-700 border dark:border-gray-600 rounded-lg">
        <thead class="bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
            <tr>
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Genre</th>
                <th class="px-4 py-2">Release Date</th>
                <th class="px-4 py-2">Director</th>
                <th class="px-4 py-2">Duration</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-800 dark:text-gray-200">
            @foreach ($movies as $movie)
                <tr class="border-t dark:border-gray-600">
                    <td class="px-4 py-2">{{ $movie->title }}</td>
                    <td class="px-4 py-2">{{ $movie->genre }}</td>
                    <td class="px-4 py-2">{{ $movie->release_date }}</td>
                    <td class="px-4 py-2">{{ $movie->director }}</td>
                    <td class="px-4 py-2">{{ $movie->duration }}</td>
                    <td class="px-4 py-2">
                        <button wire:click="edit({{ $movie->id }})" class="btn btn-secondary">Edit</button>
                        <button wire:click="delete({{ $movie->id }})" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
