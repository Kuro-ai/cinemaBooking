<div class="p-6 bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-md">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 mb-6">Manage Movies</h2>
    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Movie Fields -->
            <input type="text" 
                   wire:model.defer="title" 
                   placeholder="Title" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md" 
                   required>
            <input type="text" 
                   wire:model.defer="genre" 
                   placeholder="Genre" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md" 
                   required>
            <input type="text" 
                   wire:model.defer="director" 
                   placeholder="Director" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md">
            <input type="text" 
                   wire:model.defer="duration" 
                   placeholder="HH:MM" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md" 
                   pattern="([0-9]{1,2}):([0-9]{2})" 
                   title="Enter time in HH:MM format" 
                   required>
            <input type="text" 
                   wire:model.defer="language" 
                   placeholder="Language" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md">
            <input type="url" 
                   wire:model.defer="trailer_url" 
                   placeholder="Trailer URL" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md">
        </div>
        <div class="flex space-x-4">
            <!-- Release Date Input -->
            <div class="w-1/2">
                <input type="date" 
                       wire:model.defer="release_date" 
                       placeholder="Release Date" 
                       class="form-input w-full bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md">
            </div>
        
            <!-- Image Upload Input -->
            <div class="w-1/2">
                <input type="file" 
                       wire:model="image" 
                       id="image"
                       class="form-input w-full bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md">
                @error('image') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>
        
        <textarea wire:model.defer="description" 
                  placeholder="Description" 
                  class="form-textarea bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md w-full"></textarea>
        
        
        <div>
            @if ($image)
                <p class="text-gray-600 dark:text-gray-300 mb-2">Image Preview:</p>
                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-52 h-46 rounded-md border border-gray-300 dark:border-gray-600">
            @elseif ($isEditing && $existingImagePath)
                <p class="text-gray-600 dark:text-gray-300 mb-2">Existing Image:</p>
                <img src="{{ asset('storage/' . $existingImagePath) }}" alt="Existing Image" class="w-52 h-46 rounded-md border border-gray-300 dark:border-gray-600">
            @else
                <p class="text-gray-600 dark:text-gray-300">No Image Available</p>
            @endif 
        </div>
        <div class="flex items-center space-x-2">
            <input type="checkbox" 
                   wire:model.defer="is_active" 
                   class="form-checkbox text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 rounded">
            <span class="dark:text-gray-200">Active</span>
        </div>
        <button type="submit" 
                class=" bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-lg">
            {{ $isEditing ? 'Update' : 'Create' }}
        </button>
    </form>
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

    <form class="flex w-full space-x-2 mt-4 mb-4" role="search">
        <input type="text" wire:model.live="search" placeholder="Search by Title or Director" 
        class="form-control w-2/3 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-500 px-3 py-2">
        
        <select wire:model.live="filterStatus" class="form-control w-1/3 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-500 px-3 py-2">
            <option value="">All</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>   
    </form>
    
    <!-- Movie Table -->
    <table class="table-auto w-full mt-8 bg-gray-100 dark:bg-gray-700 border dark:border-gray-600 rounded-lg">
        <thead class="bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
            <tr>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Title</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Genre</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Image</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Director</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Duration</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Release Date</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Status</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-800 dark:text-gray-200">
            @foreach ($movies as $movie)
                <tr class="border-t dark:border-gray-600 dark:hover:bg-gray-800 text-center">
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $movie->title }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $movie->genre }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">
                        @if($movie->image_path)
                            <img src="{{ asset('storage/' . $movie->image_path) }}" alt="Cinema Image" class="w-22 h-16 rounded-md mx-auto">
                        @else
                            <span>No Image</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $movie->director }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $movie->duration }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">
                        {{ $movie->release_date ? \Carbon\Carbon::parse($movie->release_date)->format('M d, Y') : 'N/A' }}
                    </td>                    
                    <td class="px-4 py-2 border dark:border-gray-600">
                        @if ($movie->is_active)
                            <span class="text-green-600 dark:text-green-400 font-semibold">Active</span>
                        @else
                            <span class="text-red-600 dark:text-red-400 font-semibold">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border dark:border-gray-600">
                        <button wire:click="edit({{ $movie->id }})" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                        <button wire:click="confirmDelete({{ $movie->id }})" class="bg-red-500 dark:bg-red-400 hover:bg-red-600 dark:hover:bg-red-500 text-white px-4 py-1 rounded-lg">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>    
    <div class="mt-4">
        {{ $movies->links() }}
    </div>
    <!-- Delete Confirmation Modal -->
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
                <p class="text-sm mb-4">Type <strong>"Delete Confirm"</strong> to confirm the deletion.</p>
                <input 
                    type="text" 
                    wire:model.defer="confirmDeleteInput" 
                    placeholder="Delete Confirm" 
                    class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2 w-full"
                >
                <div class="flex justify-end space-x-4 mt-4">
                    <button 
                        wire:click="closeModal" 
                        class="dark:hover:bg-gray-700 text-white px-4 py-2 rounded-lg"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="delete" 
                        class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-lg"
                    >
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
