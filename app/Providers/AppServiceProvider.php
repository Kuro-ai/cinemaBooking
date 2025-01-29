<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('admin.cinemas.manage-cinemas', \App\Http\Livewire\Admin\Cinemas\ManageCinemas::class);
        Livewire::component('admin.theatres.manage-theatres', \App\Http\Livewire\Admin\Theatres\ManageTheatres::class);
        Livewire::component('admin.movies.manage-movies', \App\Http\Livewire\Admin\Movies\ManageMovies::class);
        Livewire::component('admin.movies.manage-seats', \App\Http\Livewire\Admin\Seats\ManageSeats::class);
        Livewire::component('admin.schedules.manage-schedules', \App\Http\Livewire\Admin\Schedules\ManageSchedules::class);
        Livewire::component('admin.foods.manage-foods', \App\Http\Livewire\Admin\Foods\ManageFoods::class);
        Livewire::component('admin.manage-users', \App\Http\Livewire\Admin\ManageUsers::class);

        Livewire::component('customer.movie-theatre-list', \App\Http\Livewire\Customer\MovieTheatreList::class);
        Livewire::component('customer.cinemas.cinemas-list', \App\Http\Livewire\Customer\Cinemas\CinemasList::class);
        Livewire::component('customer.theatres.cinema-details', \App\Http\Livewire\Customer\Theatres\CinemaDetails::class);
        Livewire::component('customer.movies.movies-list', \App\Http\Livewire\Customer\Movies\MoviesList::class);
        Livewire::component('customer.schedules.schedule-booking', \App\Http\Livewire\Customer\Schedules\ScheduleBooking::class);
    }
}
