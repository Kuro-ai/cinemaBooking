<div class="p-6 bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow">
    <h2 class="font-semibold text-xl mb-4">Manage Seats</h2>
    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="mb-4">
        <div class="grid grid-cols-2 gap-4">
            <input 
                type="text" 
                wire:model.defer="seat_number" 
                placeholder="Seat Number" 
                class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2 w-full" 
                required
            >
            <select 
                wire:model.defer="theatre_id" 
                class="form-select bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2 w-full" 
                required
            >
                <option value="">Select Theatre</option>
                @foreach ($theatres as $theatre)
                    <option value="{{ $theatre->id }}">{{ $theatre->name }}</option>
                @endforeach
            </select>
            <select 
                wire:model.defer="type" 
                class="form-select bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2 w-full" 
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
                class="form-input bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border dark:border-gray-600 rounded-lg px-4 py-2 w-full"
            >
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
            <tr class="bg-gray-200 dark:bg-gray-800 text-center">
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Seat Number</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Theatre</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Type</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Price</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($seats as $seat)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 text-center">
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $seat->seat_number }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $seat->theatre->name }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ ucfirst($seat->type) }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $seat->price ?? 'N/A' }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                        <button 
                            wire:click="edit({{ $seat->id }})" 
                            class="bg-yellow-500 dark:bg-yellow-400 hover:bg-yellow-600 dark:hover:bg-yellow-500 text-white px-4 py-1 rounded-lg"
                        >
                            Edit
                        </button>
                        <button 
                            wire:click="confirmDelete({{ $seat->id }})" 
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
        {{ $seats->links() }}
    </div>
    {{-- Confirmation Modal --}}
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
                        class=" dark:hover:bg-gray-700 text-white px-4 py-2 rounded-lg"
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
