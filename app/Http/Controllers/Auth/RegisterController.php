<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Supplier;
use App\BusinessSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Session;

//Repository
use App\Repositories\Message\MessageRepositoryEloquent as Message;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:17|unique:users',//|regex:/(0)[0-9]{9}/
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $message = new Message;
        $message->newSend($user->id, $user->phone);

        if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {

			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
			$user->hash = $user->id.substr(str_shuffle($permitted_chars), 0, 10);
			$user->referal = Session::get("referal",NULL);
            $user->email_verified_at = date('Y-m-d H:m:s');
            $user->user_type = 'supplier';
            $user->save();
            flash(__('Registration successful.'))->success();

        }
        else {
            flash(__('Registration successful. Please verify your email.'))->success();
        }

        $customer = new Supplier;
        $customer->user_id = $user->id;
        $customer->save();

        return $user;
    }
}
