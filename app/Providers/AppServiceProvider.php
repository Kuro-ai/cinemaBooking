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
        Livewire::component('admin.cinemas.manage-cinemas', \App\Livewire\Admin\Cinemas\ManageCinemas::class);
        Livewire::component('admin.theatres.manage-theatres', \App\Livewire\Admin\Theatres\ManageTheatres::class);
        Livewire::component('admin.movies.manage-movies', \App\Livewire\Admin\Movies\ManageMovies::class);
        Livewire::component('admin.movies.manage-seats', \App\Livewire\Admin\Seats\ManageSeats::class);
        Livewire::component('admin.schedules.manage-schedules', \App\Livewire\Admin\Schedules\ManageSchedules::class);
        Livewire::component('admin.foods.manage-foods', \App\Livewire\Admin\Foods\ManageFoods::class);
        Livewire::component('admin.manage-users', \App\Livewire\Admin\ManageUsers::class);
        Livewire::component('admin.manage-bookings', \App\Livewire\Admin\ManageBookings::class);

        Livewire::component('customer.movie-list', \App\Livewire\Customer\MovieList::class);
        Livewire::component('customer.theatre-list', \App\Livewire\Customer\TheatreList::class);
        Livewire::component('customer.seat-selection', \App\Livewire\Customer\SeatSelection::class);
        Livewire::component('customer.booking-page', \App\Livewire\Customer\BookingPage::class);
        Livewire::component('customer.food-showcase', \App\Livewire\Customer\FoodShowcase::class);
    
    } 
}
