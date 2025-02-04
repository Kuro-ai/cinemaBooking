<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfBanned
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->banned_until && now()->lessThan(Auth::user()->banned_until)) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been banned until ' . Auth::user()->banned_until->format('M d, Y h:i A') . '.'
            ]);
        }

        return $next($request);
    }
}
