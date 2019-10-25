<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryRequest;
use App\Http\Requests\Message\MessageRequest;
use App\Services\CategoryService;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $messageService;

    /**
     * UserController constructor.
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * @param MessageRequest $messageRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(MessageRequest $messageRequest)
    {
        return $this->messageService->send($messageRequest);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list_admin()
    {
        return $this->messageService->list_admin();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list_users()
    {
        return $this->messageService->list_users();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list_all_messages(Request $request)
    {
        return $this->messageService->list_all_messages($request['sender_id']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch_last_message()
    {
        return $this->messageService->fetch_last_message();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function read_message(Request $request)
    {
        return $this->messageService->read_message($request['message_id']);
    }
}
