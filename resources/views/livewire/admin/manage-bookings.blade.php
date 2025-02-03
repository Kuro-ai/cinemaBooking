<div class="p-6 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-md">
    <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-100">Manage Bookings</h2>

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

    <form class="mt-4 mb-4" role="search">
        <input type="text" wire:model.live="search" placeholder="Search by Customer Name, Email, or booking code"
            class="form-control w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-500 px-3 py-2 mb-4">

        <div class="flex w-full space-x-2">
            <select wire:model.live="movieFilter" class="form-control w-1/3 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-500 px-3 py-2">
                <option value="">All Movies</option>
                @foreach ($movies as $movie)
                    <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                @endforeach
            </select>
    
            <input type="date" wire:model.live="dateFilter" class="form-control w-1/3 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-500 px-3 py-2">

            <select wire:model.live="statusFilter" class="form-control w-1/3 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-500 px-3 py-2">
                <option value="">All Status</option>
                <option value="confirmed">Confirmed</option>
                <option value="pending">Pending</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>
    </form>
    <table class="table-auto w-full text-center text-gray-800 dark:text-gray-200 border-collapse">
        <thead class="bg-gray-300 dark:bg-gray-700">
            <tr>
                <th class="border border-gray-400 px-4 py-2">Booking Code</th>
                <th class="border border-gray-400 px-4 py-2">Customer Name</th>
                <th class="border border-gray-400 px-4 py-2">Movie</th>
                <th class="border border-gray-400 px-4 py-2">Movie Date</th>
                <th class="border border-gray-400 px-4 py-2">Seats</th>
                <th class="border border-gray-400 px-4 py-2">Total Price</th>
                <th class="border border-gray-400 px-4 py-2">Payment</th>
                <th class="border border-gray-400 px-4 py-2">Payment Date</th>
                <th class="border border-gray-400 px-4 py-2">Status</th>
                <th class="border border-gray-400 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td class="border border-gray-400 px-4 py-2">{{ $booking->booking_code }}</td>
                    <td class="border border-gray-400 px-4 py-2">{{ $booking->user->name ?? 'N/A' }} ({{ $booking->user->email ?? 'N/A' }})</td>
                    <td class="border border-gray-400 px-4 py-2">
                        {{ $booking->schedule->movie->title ?? 'N/A' }}
                    </td>
                    <td class="border border-gray-400 px-4 py-2">
                        {{ $booking->schedule->date ?? 'N/A' }}
                    </td>
                    <td class="border border-gray-400 px-4 py-2">
                        {{ $booking->total_seats }}  
                        ({{ is_array($booking->seat_numbers) ? implode(', ', $booking->seat_numbers) : $booking->seat_numbers }})
                    </td>                    
                    <td class="border border-gray-400 px-4 py-2">${{ number_format($booking->total_price, 2) }}</td>
                    <td class="border border-gray-400 px-4 py-2">
                        @if($booking->payment_date)
                            <span class="text-green-500">{{$booking->payment_type}}</span>
                        @else
                            <span class="text-red-500">Pending</span>
                        @endif
                    </td>
                    <td class="border border-gray-400 px-4 py-2">
                        {{ $booking->payment_date ? \Carbon\Carbon::parse($booking->payment_date)->format('d M Y, h:i A') : 'N/A' }}
                    </td>
                    <td class="border border-gray-400 px-4 py-2">{{ ucfirst($booking->status) }}</td>
                    <td class="border border-gray-400 px-4 py-2">
                        @if ($booking->status === 'refunded')
                            <span class="text-gray-500">Refunded</span>
                        @elseif ($booking->status === 'booked')
                            <button wire:click="confirmBooking({{ $booking->id }})" class="bg-green-500 text-white px-2 py-1 rounded">
                                Confirm
                            </button>
                        @else
                            <button wire:click="refund({{ $booking->id }})" class="bg-blue-500 text-white px-2 py-1 rounded">
                                Refund
                            </button>
                        @endif
                    </td>         
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="border border-gray-400 px-4 py-2 text-center">No bookings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $bookings->links() }} 
    </div>
</div>
