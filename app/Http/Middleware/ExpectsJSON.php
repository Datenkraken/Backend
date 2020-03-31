<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Checks if the request is expecting JSON. If not redirect.
 */
class ExpectsJSON
{
    /**
     * Only handle incomming request if json is expected in the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->expectsJson()) {
            return $next($request);
        }
        return redirect()->route('home');
    }
}
