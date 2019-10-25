<?php

namespace App\Services;

use App\Repositories\CareerRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\MessageRepository;
use App\Repositories\OtpRepository;
use App\Repositories\SubcategoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class MessageService extends SmsService
{
    protected $messageRepository, $userRepository;

    /**
     * UserService constructor.
     * @param MessageRepository $messageRepository
     * @param UserRepository $userRepository
     */
    public function __construct(MessageRepository $messageRepository, UserRepository $userRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
    }

    public function send($request)
    {
        $body = [
            'receiver_id' => $request['receiver_id'],
            'message_body' => $request['message_body'],
        ];

        $user = Auth::user();

        $body['sender_id'] = $user['email'];

        $this->messageRepository->create($body);

        $success['StatusCode'] = 200;
        $success['Message'] = 'Message successfully sent';

        return response()->json(['success' => $success], 200);
    }

    public function list_admin()
    {
        $user = Auth::user();

        $data = $this->userRepository->where('user_category', 'Admin')->get();

        $success['StatusCode'] = 200;
        $success['Message'] = 'Admin successfully listed';
        $success['Data'] = $data;

        return response()->json(['success' => $success], 200);
    }

    public function list_users()
    {
        $user = Auth::user();

        $data = $this->messageRepository->fetch_users($user['email']);

        $success['StatusCode'] = 200;
        $success['Message'] = 'Users successfully listed';
        $success['Data'] = $data;

        return response()->json(['success' => $success], 200);
    }

    public function list_all_messages($sender_id)
    {
        $user = Auth::user();

        $data = $this->messageRepository->fetch_all_messages($sender_id, $user['email']);

        $success['StatusCode'] = 200;
        $success['Message'] = 'Messages successfully listed';
        $success['Data'] = $data;

        return response()->json(['success' => $success], 200);
    }

    public function fetch_last_message()
    {
        $user = Auth::user();

        $data = [];
        if($user['user_category'] == "User")
        {
            $data = $this->userRepository->where('user_category', 'Admin')->get();
        }
        else if($user['user_category'] == "Admin")
        {
            $data = $this->messageRepository->fetch_users($user['email']);
        }

        $value = [];
        for($i = 0; $i < count($data); $i++)
        {
            $data = $this->messageRepository->fetch_last_message($data[0]['email'], $user['email']);
            array_push($value, $data);
        }

        $success['StatusCode'] = 200;
        $success['Message'] = 'Message successfully fetched';
        $success['Data'] = $value;

        return response()->json(['success' => $success], 200);
    }

    public function read_message($messageId)
    {
        $data = [
            'is_read' => true
        ];

        $this->messageRepository->updateById($messageId, $data);


        $success['StatusCode'] = 200;
        $success['Message'] = 'Message successfully read';

        return response()->json(['success' => $success], 200);
    }
}
