<!-- resources/views/livewire/customer/theatre-list.blade.php -->
<div>
    <h3>Theatres for {{ $movie->title }}</h3>
    @foreach($theatres as $theatre)
        <div>
            <h4>{{ $theatre->name }}</h4>
            <button wire:click="showSchedules({{ $theatre->id }})">View Schedules</button>
        </div>
    @endforeach
</div>
