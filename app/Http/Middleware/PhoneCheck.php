<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PhoneCheck
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

        if (Auth::check()) {
            if (Auth::user()->phone) {
                return redirect()->route('phone.confirm');
            }
        } else {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
