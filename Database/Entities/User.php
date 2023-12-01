<?php

namespace app\Database\Entities;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Facades\DB;

class User
{
    public static function createNewUser($user_id, $username, $first_name, $last_name, $chat_id): bool
    {
        try {
            Manager::table('users')->insert([
                'user_id' => $user_id,
                'username' => $username,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'chat_id' => $chat_id,
            ]);
        } catch (\Exception $PDOException) {
            return false;
        }
        return true;
    }
}
