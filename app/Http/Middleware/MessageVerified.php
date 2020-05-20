<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;

class MessageVerified
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
                $message = User::find(Auth::user()->id)->message;
                if (sizeof($message)>0) {
                    if ($message[0]->status == 1) {
                        return redirect()->route('dashboard');
                    }
                }
            } else {
                return redirect()->route('phone.new');
            }

        } else {
            return redirect()->route('home');
        }

        return $next($request);

    }
}
