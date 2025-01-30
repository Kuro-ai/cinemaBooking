<div class="p-6 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Manage Users</h2>

    <div class="mb-4">
        <input
            type="text"
            wire:model="search"
            placeholder="Search by name or email..."
            class="form-input w-full p-2 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-500"
        />
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
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Name</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Email</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Role</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Banned</th>
                <th class="border border-gray-400 dark:border-gray-600 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ $user->name }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ $user->email }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">{{ ucfirst($user->role) }}</td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">
                        @if ($user->is_banned)
                            <span class="text-red-500 font-semibold">
                                Until {{ $user->banned_until->format('Y-m-d H:i') }}
                            </span>
                        @else
                            <span class="text-green-500 font-semibold">No</span>
                        @endif
                    </td>
                    <td class="border border-gray-400 dark:border-gray-600 px-4 py-2">
                        @if ($user->is_banned)
                            <button wire:click="unbanUser({{ $user->id }})"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md">
                                Unban
                            </button>
                        @else
                            <button wire:click="banUser({{ $user->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                Ban
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

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
