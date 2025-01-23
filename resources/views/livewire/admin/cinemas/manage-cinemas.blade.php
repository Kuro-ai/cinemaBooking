<div class="p-6 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-md">
    <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-100">Manage Cinemas</h2>
    <div class="mb-4">
        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
            <div class="grid grid-cols-2 gap-4">
                <input 
                    type="text" 
                    wire:model.defer="name" 
                    placeholder="Name" 
                    class="form-input bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md" 
                    required
                >
                <input 
                    type="text" 
                    wire:model.defer="location" 
                    placeholder="Location" 
                    class="form-input bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md" 
                    required
                >
                <input 
                    type="text" 
                    wire:model.defer="city" 
                    placeholder="City" 
                    class="form-input bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md"
                >
                <input 
                    type="text" 
                    wire:model.defer="contact_number" 
                    placeholder="Contact Number" 
                    class="form-input bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md"
                >
                <input 
                    type="email" 
                    wire:model.defer="email" 
                    placeholder="Email" 
                    class="form-input bg-gray-200 dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md"
                >
                <label class="flex items-center space-x-2">
                    <input 
                        type="checkbox" 
                        wire:model.defer="is_active" 
                        class="form-checkbox text-blue-600 dark:text-blue-400 border-gray-300 dark:border-gray-600"
                    > 
                    <span>Active</span>
                </label>
            </div>
            <button 
                type="submit" 
                class="mt-4 px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-400"
            >
                {{ $isEditing ? 'Update' : 'Create' }}
            </button>
        </form>
    </div>

    <table class="table-auto w-full text-gray-800 dark:text-gray-200 border-collapse">
        <thead class="bg-gray-300 dark:bg-gray-700">
            <tr>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Name</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Location</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">City</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Contact</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Email</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Status</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cinemas as $cinema)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ $cinema->name }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ $cinema->location }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ $cinema->city }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ $cinema->contact_number }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ $cinema->email }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">
                        {{ $cinema->is_active ? 'Active' : 'Inactive' }}
                    </td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">
                        <button 
                            wire:click="edit({{ $cinema->id }})" 
                            class="btn btn-secondary bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-black dark:text-white rounded-md px-2 py-1"
                        >
                            Edit
                        </button>
                        <button 
                            wire:click="delete({{ $cinema->id }})" 
                            class="btn btn-danger bg-red-500 dark:bg-red-600 hover:bg-red-600 dark:hover:bg-red-500 text-white rounded-md px-2 py-1"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
