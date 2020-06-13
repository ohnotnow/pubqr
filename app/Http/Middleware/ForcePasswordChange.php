<?php

namespace App\Http\Middleware;

use Closure;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! auth()->check()) {
            return $next($request);
        }

        if ($request->user()->mustChangeTheirPassword()) {
            return redirect(route('password.reset'));
        }

        return $next($request);
    }
}
