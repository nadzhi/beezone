<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use Illuminate\Http\Request;
use Auth;
use App\Repositories\Message\MessageRepositoryEloquent;

class PhoneController extends Controller
{

    protected $message;
    public function __construct()
    {
        $this->message = new MessageRepositoryEloquent;
    }

    public function confirm() {
        return view('frontend.message');
    }

    public function save(Request $request) {

        $phone = $request->input('phone');
        if ($phone) {

            if (is_null(Auth::user()->phone)) {
                $phoneCheck = User::where('phone','=',$phone)->get()->toArray();
                if (sizeof($phoneCheck) === 0) {
                    User::where('id','=',Auth::user()->id)->update(['phone' => $phone]);
                    $this->message->newSend(Auth::user()->id, $phone);
                }
            }
            return redirect()->route('phone.confirm');
        }

        return view('frontend.new_phone');

    }

    public function check(Request $request) {

        $code = $request->input('code');
        $message = User::find(Auth::user()->id)->message;

        if ((int) $message[0]->code === (int) $code) {
            Message::where('user_id','=',Auth::user()->id)->update(['status' => '1']);
            return redirect()->route('dashboard');
        }

        return view('frontend.message');

    }

    public function store() {
        return view('frontend.new_phone');
    }

}
