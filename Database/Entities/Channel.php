<?php

namespace app\Database\Entities;

use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class Channel
{
    public static function getChannelOwnerId($chat_id)
    {
        $owner = null;
        $chat_admins = Request::getChatAdministrators([ 'chat_id' => $chat_id])->getResult();
        foreach ($chat_admins as $admin) {
            if ($admin->status != 'creator') {
                continue;
            } else {
                $owner = $admin->user['id'];
            }
        }
        return $owner;
    }
}
