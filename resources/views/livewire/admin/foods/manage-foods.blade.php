<div class="p-6 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-md">
    <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-100">Manage Foods</h2>
    <div class="mb-4">
        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <input 
                    type="text" 
                    wire:model.defer="name" 
                    placeholder="Name" 
                    class="form-input bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md" 
                >
                <select 
                    wire:model.defer="type" 
                    class="form-select bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md"
                >
                    <option value="">Select Type</option>
                    <option value="Food">Food</option>
                    <option value="Drink">Drink</option>
                </select>
                <div>
                    <input 
                        type="file" 
                        wire:model="image" 
                        class="form-input bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md"
                    >
                    @error('image') 
                        <span class="text-red-500">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <div class="mt-4 mb-4">
                @if ($image)
                    <p class="text-gray-600 dark:text-gray-300 mb-2">Image Preview:</p>
                    <img src="{{ $image->temporaryUrl() }}" class="w-52 h-46 rounded-md border border-gray-300 dark:border-gray-600">
                @elseif ($isEditing && $existingImagePath)
                    <p class="text-gray-600 dark:text-gray-300 mb-2">Existing Image:</p>
                    <img src="{{ asset('storage/' . $existingImagePath) }}" class="w-52 h-46 rounded-md border border-gray-300 dark:border-gray-600">
                @else
                    <p class="text-gray-600 dark:text-gray-300">No Image Available</p>
                @endif
            </div>

            <button 
                type="submit" 
                class="bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-lg"
            >
                {{ $isEditing ? 'Update' : 'Create' }}
            </button>
        </form>
    </div>
    <div class="flex justify-between mb-4">
        <input 
            type="text" 
            wire:model.debounce.300ms="search" 
            placeholder="Search foods..." 
            class="form-input bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md"
        >
    </div>
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
    <table class="table-auto w-full text-center text-gray-800 dark:text-gray-200 border-collapse">
        <thead class="bg-gray-300 dark:bg-gray-700">
            <tr>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Image</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Name</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Type</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($foods as $food)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2 h-20">
                        <div class="flex justify-center items-center">
                            @if ($food->image_path)
                                <img src="{{ asset('storage/' . $food->image_path) }}" class="w-22 h-16 rounded-md">
                            @else
                                <span>No Image</span>
                            @endif
                        </div>
                    </td>
                    
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ $food->name }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ $food->type }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">
                        <button 
                            wire:click="edit({{ $food->id }})" 
                            class="px-2 py-1 bg-yellow-500 text-white rounded"
                        >
                            Edit
                        </button>
                        <button 
                            wire:click="confirmDelete({{ $food->id }})" 
                            class="bg-red-500 dark:bg-red-400 hover:bg-red-600 dark:hover:bg-red-500 text-white px-4 py-1 rounded-lg"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $foods->links() }}
    </div>
    
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
            @error('confirmDeleteInput')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
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
