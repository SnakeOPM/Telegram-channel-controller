<?php

namespace app\Database\Entities;

use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class Channel
{
    public static function getChannelOwnerId($chat_id)
    {
        $owner = null;
        $chat_admins = Request::getChatAdministrators(['chat_id' => $chat_id])->getResult();
        foreach ($chat_admins as $admin) {
            if ($admin->status != 'creator') {
                continue;
            } else {
                $owner = $admin->user['id'];
            }
        }
        return $owner;
    }

    public static function checkPostingRights($chat_id): bool
    {
        if ($chat_id === null) {
            return false;
        }
        $bot_id = getenv('BOT_USER_ID');
        $chat_permissions = Request::getChatMember(['chat_id' => intval($chat_id), 'user_id' => intval($bot_id)]);
        if (!$chat_permissions->getOk()) {
            return false;
        }
        $permissions = $chat_permissions->getResult();
        if ($permissions->status == "administrator") {
            $can_post_messages = $permissions->can_post_messages;
            $can_delete_messages = $permissions->can_delete_messages;
            if ($can_delete_messages && $can_post_messages) {
                return true;
            }
        }
        return false;
    }
}
