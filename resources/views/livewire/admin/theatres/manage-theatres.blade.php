<div class="p-6 bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-md">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 mb-6">Manage Theatres</h2>
    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Theatre Fields -->
            <input type="text" 
                   wire:model.defer="name" 
                   placeholder="Theatre Name" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md" 
                   required>
            <select wire:model.defer="cinema_id" 
                    class="form-select bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md"> 
                <option value="">Select Cinema</option>
                @foreach ($cinemas as $cinema)
                    <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                @endforeach
            </select>
            <input type="number" 
                   wire:model.defer="capacity" 
                   placeholder="Capacity" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md" 
                   required>
            <select wire:model.defer="type" 
                    class="form-select bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md">
                <option value="2D">2D</option>
                <option value="3D">3D</option>
                <option value="IMAX">IMAX</option>
            </select>
            <input type="text" 
                   wire:model.defer="screen_size" 
                   placeholder="Screen Size" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-md">
            <input type="file" 
                   wire:model="image" 
                   class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md">
        </div>
        <div>
            <label class="flex items-center space-x-2">
                <input type="checkbox" 
                       wire:model.defer="is_active" 
                       class="form-checkbox text-indigo-600 dark:text-indigo-400 rounded">
                <span>Active</span>
            </label>
        </div>
        @if ($image)
            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-32 h-24 rounded-md border border-gray-300 dark:border-gray-600 mt-4">
        @elseif ($isEditing && $image_path)
            <img src="{{ Storage::url($image_path) }}" alt="Existing Image" class="w-32 h-24 rounded-md border border-gray-300 dark:border-gray-600 mt-4">
        @endif
        <button type="submit" 
                class="bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-lg">
            {{ $isEditing ? 'Update' : 'Create' }}
        </button>
    </form>

    <!-- Theatres Table -->
    <table class="table-auto w-full mt-8 bg-gray-100 dark:bg-gray-700 border dark:border-gray-600 rounded-lg">
        <thead class="bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
            <tr>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Name</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Cinema</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Capacity</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Type</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Image</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Status</th>
                <th class="px-4 py-2 text-center border dark:border-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="text-center text-gray-800 dark:text-gray-200">
            @foreach ($theatres as $theatre)
                <tr class="border-t dark:border-gray-600 dark:hover:bg-gray-800">
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $theatre->name }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $theatre->cinema->name }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $theatre->capacity }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $theatre->type }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">
                        @if ($theatre->image_path)
                            <img src="{{ Storage::url($theatre->image_path) }}" alt="Image" class="w-16 h-16 rounded-md">
                        @else
                            No Image
                        @endif
                    </td>
                    <td class="px-4 py-2 border dark:border-gray-600">{{ $theatre->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600">
                        <button wire:click="edit({{ $theatre->id }})" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded-lg">
                            Edit
                        </button>
                        <button wire:click="confirmDelete({{ $theatre->id }})" 
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
                <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
                <p class="text-sm mb-4">Type <strong>"Delete Confirm"</strong> to confirm.</p>
                <input type="text" 
                       wire:model.defer="confirmDeleteInput" 
                       class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-md w-full">
                <div class="flex justify-end space-x-4 mt-4">
                    <button wire:click="closeModal" 
                            class="px-4 py-2 bg-gray-500 dark:bg-gray-700 text-white rounded-lg">
                        Cancel
                    </button>
                    <button wire:click="delete" 
                            class="px-4 py-2 bg-red-600 dark:bg-red-700 text-white rounded-lg">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
