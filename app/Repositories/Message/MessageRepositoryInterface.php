<?php


namespace App\Repositories\Message;


interface MessageRepositoryInterface
{
    public function send():int;
}
