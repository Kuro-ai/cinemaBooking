<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\TheatreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SeatController;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/cinemas', \App\Http\Livewire\Admin\Cinemas\ManageCinemas::class)->name('admin.cinemas');
    Route::get('/admin/theatres', \App\Http\Livewire\Admin\Theatres\ManageTheatres::class)->name('admin.theatres');
    Route::get('/admin/movies', \App\Http\Livewire\Admin\Movies\ManageMovies::class)->name('admin.movies');
    Route::get('/admin/seats', \App\Http\Livewire\Admin\Seats\ManageSeats::class)->name('admin.seats');
    Route::get('/admin/manage-schedules', \App\Http\Livewire\Admin\Schedules\ManageSchedules::class)->name('admin.manage-schedules');
    Route::get('/admin/manage-foods', \App\Http\Livewire\Admin\Foods\ManageFoods::class)->name('admin.manage-foods');
    Route::get('/admin/manage-users', \App\Http\Livewire\Admin\ManageUsers::class)->name('admin.users');
    Route::get('/admin/manage-bookings', \App\Http\Livewire\Admin\ManageBookings::class)->name('admin.bookings');

});

Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    Route::get('/cinemas', \App\Http\Livewire\Customer\Cinemas\CinemasList::class)->name('cinemas.list');
    Route::get('/cinema/{cinema}', \App\Http\Livewire\Customer\Theatres\CinemaDetails::class)->name('cinema.details');
    Route::get('/customer/booking/{schedule}', [App\Http\Controllers\BookingController::class, 'create'])
        ->name('customer.booking');
    Route::post('/customer/booking/store', [App\Http\Controllers\BookingController::class, 'store'])
        ->name('bookings.store');
    Route::get('/customer/movie-theatre-list', \App\Http\Livewire\Customer\MovieTheatreList::class)->name('customer.movie-theatre-list');
    Route::get('/movies', \App\Http\Livewire\Customer\Movies\MoviesList::class)->name('movies.list');
    Route::get('/schedule/book/{theatre_id}/{movie_id}', \App\Http\Livewire\Customer\Schedules\ScheduleBooking::class)->name('schedule.book');
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

