<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/cinemas', \App\Livewire\Admin\Cinemas\ManageCinemas::class)->name('admin.cinemas');
    Route::get('/admin/theatres', \App\Livewire\Admin\Theatres\ManageTheatres::class)->name('admin.theatres');
    Route::get('/admin/movies', \App\Livewire\Admin\Movies\ManageMovies::class)->name('admin.movies');
    Route::get('/admin/seats', \App\Livewire\Admin\Seats\ManageSeats::class)->name('admin.seats');
    Route::get('/admin/manage-schedules', \App\Livewire\Admin\Schedules\ManageSchedules::class)->name('admin.manage-schedules');
    Route::get('/admin/manage-foods', \App\Livewire\Admin\Foods\ManageFoods::class)->name('admin.manage-foods');
    Route::get('/admin/manage-users', \App\Livewire\Admin\ManageUsers::class)->name('admin.users');
    Route::get('/admin/manage-bookings', \App\Livewire\Admin\ManageBookings::class)->name('admin.bookings');

});

Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

   // web.php
    Route::get('/movies', \App\Livewire\Customer\MovieList::class)->name('movies.index');
    Route::get('/booking/{bookingId}', \App\Livewire\Customer\BookingPage::class)->name('booking.index');
    Route::get('/foods', \App\Livewire\Customer\FoodShowcase::class)->name('foods.index');
    Route::get('/movie/{movieId}/booking', \App\Livewire\Customer\BookingPage::class)->name('customer.booking');
    Route::get('/tickets', \App\Livewire\Customer\CustomerTickets::class)->name('customer.tickets');
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        switch (Auth::user()->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'customer':
                return view('customer.dashboard');
            default:
                abort(403, 'Unauthorized access');
        }
    })->name('dashboard');
});

