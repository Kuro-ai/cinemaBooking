<!-- resources/views/livewire/customer/seat-selection.blade.php -->
<div>
    <h3>Seats for Schedule: {{ $schedule->date }} at {{ $schedule->start_time }}</h3>

    @foreach($seats as $seat)
        <div 
            wire:click="toggleSeat({{ $seat->id }})" 
            class="seat {{ $seat->status == 'booked' ? 'bg-red' : 'bg-green' }}">
            {{ $seat->seat_number }}
        </div>
    @endforeach

    <button wire:click="bookSeats">Book Seats</button>
</div>
