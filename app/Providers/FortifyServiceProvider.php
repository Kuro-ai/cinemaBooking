<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Validation\ValidationException;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     Fortify::createUsersUsing(CreateNewUser::class);
    //     Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    //     Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
    //     Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

    //     RateLimiter::for('login', function (Request $request) {
    //         $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

    //         return Limit::perMinute(5)->by($throttleKey);
    //     });

    //     RateLimiter::for('two-factor', function (Request $request) {
    //         return Limit::perMinute(5)->by($request->session()->get('login.id'));
    //     });
    // }

    public function boot()
    {
        // Define the login rate limiter
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email . '|' . $request->ip());
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Auth::validate(['email' => $request->email, 'password' => $request->password])) {
                throw ValidationException::withMessages([
                    'email' => __('These credentials do not match our records.'),
                ]);
            }

            // Check if the user is banned
            if ($user->banned_until && now()->lessThan($user->banned_until)) {
                throw ValidationException::withMessages([
                    'email' => __('Your account has been banned until ' . $user->banned_until->format('M d, Y h:i A') . '.'),
                ]);
            }

            return $user;
        });
    }
}
