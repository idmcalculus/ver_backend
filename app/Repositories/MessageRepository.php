<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MessageRepository extends BaseRepository
{
    public function model()
    {
        return Message::class;
    }

    public function fetch_all_messages($sender_id, $receiver_id)
    {
        return DB::select("SELECT * FROM messages WHERE (sender_id = '" . $sender_id .
            "' AND receiver_id = '" . $receiver_id . "') OR (sender_id = '" . $receiver_id .
            "' AND receiver_id = '" . $sender_id . "')");
    }

    public function fetch_last_message($sender_id, $receiver_id)
    {
        return DB::select("SELECT * FROM messages WHERE (sender_id = '" . $sender_id .
            "' AND receiver_id = '" . $receiver_id . "') OR (sender_id = '" . $receiver_id .
            "' AND receiver_id = '" . $sender_id . "') ORDER BY id DESC LIMIT 1");
    }

    public function fetch_users($user_id)
    {
        $data = $users = DB::table('messages')
            ->where('receiver_id', $user_id)
            ->groupBy('sender_id')
            ->get();
        $users = [];
        for($i = 0; $i < count($data); $i++)
        {
            $d = DB::table('users')
                ->where('email', $data[$i]->sender_id)
                ->first();

            array_push($users, $d);
        }

        return $users;
    }
}
