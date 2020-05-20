<?php


namespace App\Repositories\Message;

use App\Message;
use Auth;

class MessageRepositoryEloquent implements MessageRepositoryInterface
{

    const URL = 'https://smsc.kz/sys/send.php?login=';
    const LOGIN = 'emart';
    const PASSWORD = 'qwerty00';
    const COMPANY = 'beezone';

    public function newSend(int $id, string $phone) {

        $code = mt_rand(100000, 999999);
        $this->store($code, $id);
        $this->curl($code, $phone);
        return $code;

    }

    public function send():int {

        $code = mt_rand(100000, 999999);
        $user = Auth::user()->id;
        $phone = Auth::user()->phone;
        $this->store($code, $user);
        $this->curl($code, $phone);
        return $code;

    }

    public function store(int $code, int $user) {

        $message = new Message;
        $message->user_id = $user;
        $message->code = $code;
        $message->save();

    }

    public function curl(int $code, string $phone):void {

        $phone = str_replace(' ', '-', $phone);
        $message = join('',['Ваш SMS код: ',$code,'. Никому не говорите код']);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, join('',[
            self::URL,
            self::LOGIN,
            '&psw=',
            self::PASSWORD,
            '&phones=',
            $phone,
            '&sender=',
            self::COMPANY,
            '&mes=',
            $message
        ]));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

    }
}
