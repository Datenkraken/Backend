<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Checks if the authenticated user is an admin.
 */
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->is_admin) {
            return $next($request);
        }
        if (Auth::check()) {
            Auth::logout();
        }

        if ($request->expectsJson()) {
            return response()->json('You are not an admin.', 403);
        }

        return redirect()->route('login')->withErrors('You are not an admin.');
    }
}
