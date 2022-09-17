<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Notification;

class MessageController extends Controller
{
    public function messages()
    {
        $messages = Message::all();

        return $messages;

    }
}
