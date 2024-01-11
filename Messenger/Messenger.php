<?php

namespace app\Messenger;

use Longman\TelegramBot\Request;

trait Messenger
{
    public function sendWarningAboutSamePicInOneChannel($chat_id): void
    {
        Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'oops, it seems like you repeated post in your channel' .
            'ignore this, if bot triggered to slightly different picture :3' .
                'this message could be sent to you several times, it depends' .
                'on times you have triggered me :333'
        ]);
    }
}