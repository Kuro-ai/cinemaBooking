<div class="p-6 bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow">
    <h2 class="font-semibold text-xl mb-4">Manage Seats</h2>
    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="mb-4">
        <div class="grid grid-cols-3 gap-4">
            <input 
                type="text" 
                wire:model.defer="seat_number" 
                placeholder="Seat Number" 
                class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2" 
                required
            >
            <select 
                wire:model.defer="theatre_id" 
                class="form-select bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2" 
                required
            >
                <option value="">Select Theatre</option>
                @foreach ($theatres as $theatre)
                    <option value="{{ $theatre->id }}">{{ $theatre->name }}</option>
                @endforeach
            </select>
            <select 
                wire:model.defer="type" 
                class="form-select bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2" 
                required
            >
                <option value="regular">Regular</option>
                <option value="vip">VIP</option>
                <option value="recliner">Recliner</option>
            </select>
            <input 
                type="number" 
                wire:model.defer="price" 
                placeholder="Price" 
                class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2"
            >
            <label class="flex items-center space-x-2">
                <input 
                    type="checkbox" 
                    wire:model.defer="is_available" 
                    class="form-checkbox text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 rounded"
                >
                <span>Available</span>
            </label>
        </div>
        <button 
            type="submit" 
            class="mt-4 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-lg"
        >
            {{ $isEditing ? 'Update' : 'Create' }}
        </button>
    </form>

    <table class="table-auto w-full mt-6 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border-collapse border border-gray-300 dark:border-gray-600">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-800">
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Seat Number</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Theatre</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Type</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Price</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Available</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($seats as $seat)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $seat->seat_number }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $seat->theatre->name }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ ucfirst($seat->type) }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $seat->price ?? 'N/A' }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $seat->is_available ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                        <button 
                            wire:click="edit({{ $seat->id }})" 
                            class="bg-yellow-500 dark:bg-yellow-400 hover:bg-yellow-600 dark:hover:bg-yellow-500 text-white px-4 py-1 rounded-lg"
                        >
                            Edit
                        </button>
                        <button 
                            wire:click="delete({{ $seat->id }})" 
                            class="bg-red-500 dark:bg-red-400 hover:bg-red-600 dark:hover:bg-red-500 text-white px-4 py-1 rounded-lg"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
