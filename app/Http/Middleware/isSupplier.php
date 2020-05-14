<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsSupplier
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
        if (Auth::check() && Auth::user()->user_type == 'supplier') {
            return $next($request);
        }
        else{
            var_dump("Error");
        }
    }
}
