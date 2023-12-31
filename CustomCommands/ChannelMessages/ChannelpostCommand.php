<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Channel post command
 *
 * Gets executed when a new post is created in a channel.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use app\Database\Entities\Channel;
use app\Database\Entities\Post;
use app\Database\Entities\User;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class ChannelpostCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'channelpost';

    /**
     * @var string
     */
    protected $description = 'Handle channel post';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
   //TODO: DELETE MESSAGES AFTER 1500 BOT BY ID MAKE BY TIMESTAMP AND IS FORWARDED MESSAGE CHECK
        // Get the channel post
        $channel_post = $this->getChannelPost();
        if ($channel_post->getType() != 'photo') {
            return parent::execute();
        }
        $channel_id = $channel_post->getChat()->getId();
        $message_id = $channel_post->getMessageId();
        $can_post = Channel::checkPostingRights($channel_id);
        if (!$can_post) {
            return parent::execute();
        }
        $channel_owner_id = Channel::getChannelOwnerId($channel_id);
        Post::downloadImage($channel_post);
        $photo_hash = Post::hashImage();
        $distance = Post::distancePostValidation($photo_hash);
        if ($distance['distance'] < intval(getenv('PREFER_DISTANCE'))) {
            $forwarding = Request::forwardMessage([
                'chat_id' => $channel_id,
                'message_id' => $distance['message_id'],
                'from_chat_id' => $distance['chat_id']
            ]);
            $is_forwarded = $forwarding->getOk();
            if (!$is_forwarded) {
                Post::deleteNonExitstingPost($distance['chat_id'], $distance['message_id']);
                Post::createNewChannelPost($channel_post, $photo_hash);
                return parent::execute();
            }
            Request::deleteMessage([
                'chat_id' => $channel_id,
                'message_id' => $message_id
            ]);
            Post::deleteImages();
            return parent::execute();
        }
        Post::createNewChannelPost($channel_post, $photo_hash);
        Post::deleteImages();
        return parent::execute();
    }
}
