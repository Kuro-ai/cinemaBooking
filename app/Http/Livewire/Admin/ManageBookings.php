<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
use App\Models\Movie;

class ManageBookings extends Component
{
    use WithPagination;

    public $search = ''; 
    public $movieFilter = '';
    public $dateFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

    protected $queryString = ['search', 'movieFilter', 'dateFilter', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMovieFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function refund($bookingId)
    {
        $booking = Booking::find($bookingId);
        if ($booking) {
            $booking->update(['status' => 'refunded']);
        }
    }

    public function render()
    {
        $bookings = Booking::with(['user', 'schedule.movie'])
            ->whereHas('user', function ($query) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($this->search) . '%']);
            })
            ->orWhere('booking_code', 'LIKE', '%' . $this->search . '%') 
            ->orWhere('status', 'LIKE', '%' . $this->search . '%'); 

        if ($this->movieFilter) {
            $bookings->whereHas('schedule.movie', function ($query) {
                $query->where('id', $this->movieFilter);
            });
        }

        if ($this->dateFilter) {
            $bookings->whereHas('schedule', function ($query) {
                $query->whereDate('date', '=', $this->dateFilter);
            });
        }

        if ($this->statusFilter) {
            $bookings->where('status', $this->statusFilter);
        }

        $bookings = $bookings->paginate($this->perPage);

        $movies = Movie::orderBy('title')->get();

        return view('livewire.admin.manage-bookings', compact('bookings', 'movies'));
    }

}
