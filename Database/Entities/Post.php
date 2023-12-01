<?php

namespace app\Database\Entities;

use Illuminate\Database\Capsule\Manager;
use Jenssegers\ImageHash\Hash;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class Post
{
    private static string $image_folder = __DIR__ . '/../../Download/photos/';
    public static function createNewChannelPost(Message $channel_post, string $photo_hash): void
    {
        try {
            $channel_id = $channel_post->getChat()->getId();
            $post_id = $channel_post->getMessageId();
            Manager::table('posts')->insert([
                'post_id' => $post_id,
                'dhash' => $photo_hash,
                'channel_id' => $channel_id
            ]);
        } catch (\Exception $exception) {
            Request::sendMessage([
                'chat_id' => 361018101,
                'text' => 'a' . $exception
            ]);
        }
    }

    public static function hashImage(): string
    {
        $hasher = new ImageHash(new DifferenceHash());
        return $hasher
            ->hash(self::$image_folder . scandir(self::$image_folder, SCANDIR_SORT_DESCENDING)[0])
            ->toHex();
    }
    public static function downloadImage(Message $channel_post): void
    {
            $message_photo_array = $channel_post->getPhoto();
            $message_photo = end($message_photo_array)->getFileId();
            $photo = Request::getFile(['file_id' => $message_photo]);
            Request::downloadFile($photo->getResult());
    }
    public static function deleteImages(): void
    {
        $files = glob(self::$image_folder . '*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }
    }

    public static function distancePostValidation(string $hash): array
    {
        $hash = Hash::fromHex($hash);
        $hasher = new ImageHash(new DifferenceHash());
        $distances = [
            'chat_id' => '',
            'message_id' => '',
            'distance' => 100
        ];
        $similar_posts = Manager::table('posts')
            ->select('*')
            ->orderByRaw("SIMILARITY(dhash, '" . $hash . "') DESC")
            ->limit(5)
            ->get();
        if ($similar_posts->isEmpty()) {
            return $distances;
        }
        foreach ($similar_posts as $post) {
            $post_hash = Hash::fromHex($post->dhash);
            $current_distance = $hasher->distance($hash, $post_hash);
            if ($current_distance < $distances['distance']) {
                $distances = [
                    'chat_id' => $post->channel_id,
                    'message_id' => $post->post_id,
                    'distance' => $current_distance
                ];
            }
        }
        return $distances;
    }
}
