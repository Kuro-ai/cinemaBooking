<div wire:poll.10s="pollExpiredTickets" x-data="{ now: @js(now()->timestamp) }" x-init="setInterval(() => now++, 1000)">
    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 mt-3 text-center">🎟️ Your Tickets</h3>

    @forelse($tickets as $ticket)
        @php
            $expiryTime = $ticket->status === 'booked' 
                ? \Carbon\Carbon::parse($ticket->schedule->start_time)->subHour() 
                : \Carbon\Carbon::parse($ticket->schedule->start_time)->addMinutes($ticket->schedule->duration);
        @endphp

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg transition hover:shadow-2xl mb-6 relative border-l-8 border-opacity-70 
            {{ $ticket->status === 'booked' ? 'border-red-500' : 'border-blue-500' }}">
            
            <!-- Status Label -->
            <div class="absolute top-2 right-2 text-sm px-4 py-1 rounded-full font-semibold 
                {{ $ticket->status === 'booked' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white' }}">
                <template x-if="now < {{ $expiryTime->timestamp }}">
                    <span x-data="{ timeLeft: {{ $expiryTime->timestamp }} - now }"
                          x-init="setInterval(() => timeLeft--, 1000)">
                        Expires in: <span x-text="Math.floor(timeLeft / 60)"></span>m 
                        <span x-text="(timeLeft % 60)"></span>s
                    </span>
                </template>
                <template x-if="now >= {{ $expiryTime->timestamp }}">
                    <span class="text-gray-300">Expired</span>
                </template>
            </div>

            <!-- Movie Title -->
            <h4 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                🎬 {{ $ticket->movie->title }} ({{ $ticket->movie->language }})
            </h4>

            <!-- Cinema & Theatre -->
            <p class="text-gray-700 dark:text-gray-300">
                📍 <span class="font-medium">Cinema:</span> {{ $ticket->theatre->name }} ({{ $ticket->theatre->cinema->location }})
            </p>

            <!-- Showtime -->
            <p class="text-gray-700 dark:text-gray-300 mt-1">
                ⏰ <span class="font-medium">Showtime:</span> 
                {{ \Carbon\Carbon::parse($ticket->schedule->start_time)->format('M d, Y h:i A') }}
            </p>

            <!-- Seats -->
            <p class="text-gray-700 dark:text-gray-300 mt-1">
                🎟️ <span class="font-medium">Seats:</span> {{ $ticket->seats->pluck('seat_number')->join(', ') }} ({{ ucfirst($ticket->status) }})
            </p>

            <!-- Expandable Details -->
            <button class="mt-4 text-blue-500 underline transition hover:text-blue-400 focus:outline-none"
                    onclick="this.nextElementSibling.classList.toggle('hidden')">
                View More Details ⬇️
            </button>

            <div class="hidden mt-4 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                <!-- Ticket Code -->
                <p class="text-gray-900 dark:text-gray-100 font-medium">
                    🎫 Ticket Code: <span class="font-bold">{{ $ticket->booking_code }}</span>
                </p>

                <!-- Ticket Expiry -->
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    🕒 Created At: {{ \Carbon\Carbon::parse($ticket->created_at)->format('M d, Y h:i A') }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    ⏳ Ticket Expires: {{ $expiryTime->format('M d, Y h:i A') }}
                </p>

                <!-- Total Price -->
                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-4">
                    💰 Total Price: ${{ number_format($ticket->total_price, 2) }}
                </p>

                <!-- Location -->
                <p class="text-gray-700 dark:text-gray-300 mt-1">
                    📌 Location: {{ $ticket->theatre->cinema->location }}
                </p>
            </div>
        </div>
    @empty
        <p class="text-gray-600 dark:text-gray-400 text-center mt-6">😢 No tickets found.</p>
    @endforelse
</div>
