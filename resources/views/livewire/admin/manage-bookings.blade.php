<div class="p-6 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-md">
    <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-100">Manage Bookings</h2>

    <input type="text" wire:model.debounce.500ms="search" placeholder="Search by customer name, email, movie, or booking code" 
           class="form-input bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-md w-full mb-4">

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
