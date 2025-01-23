<div class="p-6 bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-lg">
    <h2 class="font-semibold text-xl mb-4">Manage Theatres</h2>
    <div class="mb-4">
        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
            <div class="grid grid-cols-2 gap-4">
                <input 
                    type="text" 
                    wire:model.defer="name" 
                    placeholder="Theatre Name" 
                    class="form-input bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200" 
                    required
                >
                <select 
                    wire:model.defer="cinema_id" 
                    class="form-select bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200"
                >
                    <option value="">Select Cinema</option>
                    @foreach ($cinemas as $cinema)
                        <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                    @endforeach
                </select>
                <input 
                    type="number" 
                    wire:model.defer="capacity" 
                    placeholder="Capacity" 
                    class="form-input bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200" 
                    required
                >
                <select 
                    wire:model.defer="type" 
                    class="form-select bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200"
                >
                    <option value="2D">2D</option>
                    <option value="3D">3D</option>
                    <option value="IMAX">IMAX</option>
                </select>
                <input 
                    type="text" 
                    wire:model.defer="screen_size" 
                    placeholder="Screen Size" 
                    class="form-input bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200"
                >
                <input 
                    type="file" 
                    wire:model="image" 
                    class="form-input" 
                    accept="image/*"
                >
                
                <label class="flex items-center space-x-2">
                    <input 
                        type="checkbox" 
                        wire:model.defer="is_active" 
                        class="form-checkbox bg-gray-100 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span>Active</span>
                </label>
            </div>

            @if ($image)
            <div class="mt-2">
                <img 
                    src="{{ $image->temporaryUrl() }}" 
                    alt="Preview" 
                    style="width: 200px; height: 150px; object-fit: cover;" 
                >
            </div>
            @elseif ($isEditing && $image)
                <div class="mt-2">
                    <img 
                        src="{{ Storage::url($image_path) }}" 
                        alt="Existing Image" 
                        style="width: 200px; height: 150px; object-fit: cover;"
                    >
                </div>
            @endif
        

            <button 
                type="submit" 
                class="mt-4 btn btn-primary bg-blue-600 dark:bg-blue-500 text-white hover:bg-blue-700 dark:hover:bg-blue-600"
            >
                {{ $isEditing ? 'Update' : 'Create' }}
            </button>
        </form>
    </div>

    <table class="table-auto w-full bg-gray-100 dark:bg-gray-700 rounded-lg">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-800">
                <th class="py-2 px-4">Name</th>
                <th class="py-2 px-4">Cinema</th>
                <th class="py-2 px-4">Capacity</th>
                <th class="py-2 px-4">Type</th>
                <th class="py-2 px-4">Image</th>
                <th class="py-2 px-4">Status</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($theatres as $theatre)
                <tr class="border-b border-gray-300 dark:border-gray-600">
                    <td class="py-2 px-4">{{ $theatre->name }}</td>
                    <td class="py-2 px-4">{{ $theatre->cinema->name }}</td>
                    <td class="py-2 px-4">{{ $theatre->capacity }}</td>
                    <td class="py-2 px-4">{{ $theatre->type }}</td>
                    <td>
                        @if ($theatre->image_path)
                            <img src="{{ Storage::url($theatre->image_path) }}" alt="{{ $theatre->name }}" class="h-12">
                        @else
                            No Image
                        @endif
                    </td>
                    <td class="py-2 px-4">{{ $theatre->is_active ? 'Active' : 'Inactive' }}</td>
                    
                    <td class="py-2 px-4 flex space-x-2">
                        <button 
                            wire:click="edit({{ $theatre->id }})" 
                            class="btn btn-secondary bg-yellow-500 dark:bg-yellow-400 text-white hover:bg-yellow-600 dark:hover:bg-yellow-500"
                        >
                            Edit
                        </button>
                        <button 
                            wire:click="delete({{ $theatre->id }})" 
                            class="btn btn-danger bg-red-600 dark:bg-red-500 text-white hover:bg-red-700 dark:hover:bg-red-600"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
