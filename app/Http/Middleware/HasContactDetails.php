<?php

namespace App\Http\Middleware;

use Closure;

class HasContactDetails
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
        if (! $request->session()->has('contact_number')) {
            return redirect(route('contact-number.create'));
        }

        return $next($request);
    }
}
