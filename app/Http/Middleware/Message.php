<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;
use App\Repositories\Message\MessageRepositoryEloquent;

class Message
{
    protected $message;
    public function __construct()
    {
        $this->message = new MessageRepositoryEloquent;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::user()->phone) {

            $message = User::find(Auth::user()->id)->message;
            if (sizeof($message)>0) {
                if ($message[0]->status == 0) {
                    return redirect()->route('phone.confirm');
                }
            } else {
                $this->message->send();
                return redirect()->route('phone.confirm');
            }

        } else {
            return redirect()->route('phone.new');
        }

        return $next($request);
    }
}
