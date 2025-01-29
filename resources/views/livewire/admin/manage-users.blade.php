<div class="p-6 bg-gray-100 dark:bg-gray-800 rounded-lg">
    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Manage Users</h2>

    <div class="mb-4">
        <input
            type="text"
            wire:model="search"
            placeholder="Search by name or email..."
            class="form-input w-full p-2 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-500"
        />
    </div>

    <table class="table-auto w-full text-center bg-white dark:bg-gray-700 rounded shadow">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-600">
                <th class="px-4 py-2 text-gray-800 dark:text-gray-200">Name</th>
                <th class="px-4 py-2 text-gray-800 dark:text-gray-200">Email</th>
                <th class="px-4 py-2 text-gray-800 dark:text-gray-200">Role</th>
                <th class="px-4 py-2 text-gray-800 dark:text-gray-200">Banned</th>
                <th class="px-4 py-2 text-gray-800 dark:text-gray-200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="odd:bg-gray-100 even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700">
                    <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                    <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $user->email }}</td>
                    <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ ucfirst($user->role) }}</td>
                    <td class="px-4 py-2">
                        @if ($user->is_banned)
                            <span class="text-red-500 font-semibold">
                                Until {{ $user->banned_until->format('Y-m-d H:i') }}
                            </span>
                        @else
                            <span class="text-green-500 font-semibold">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if ($user->is_banned)
                            <button
                                wire:click="unbanUser({{ $user->id }})"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                            >
                                Unban
                            </button>
                        @else
                            <button
                                wire:click="banUser({{ $user->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                            >
                                Ban
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <div class="mt-4">
        {{ $users->links() }}
    </div> --}}

    @if ($selectedUserId)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-700 p-6 rounded shadow-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Ban User</h3>
                <input
                    type="number"
                    wire:model="banDuration"
                    placeholder="Enter ban duration (days)"
                    class="form-input w-full p-2 mb-4 rounded bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-500"
                />
                @error('banDuration')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror

                <div class="flex justify-end space-x-2">
                    <button
                        wire:click="$set('selectedUserId', null)"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="confirmBan"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                    >
                        Confirm Ban
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
