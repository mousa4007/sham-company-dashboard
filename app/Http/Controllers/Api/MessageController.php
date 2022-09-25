<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;


class MessageController extends Controller
{
    public function messages()
    {
        $messages = Message::latest()->get();

        return $messages;
    }
}
