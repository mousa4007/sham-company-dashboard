<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function messages(Request $request)
    {
        return $request->user()->messages;
    }

    public function updateWatched(Request $request)
    {
        $request->validate(
            ['message_id' => 'required']
        );

        $message = Message::find($request->message_id);

        $message->update([
            'watched' => true
        ]);

        return 'success';
    }

    public function getMessagesCount(Request $request){

        return count($request->user()->messages->where('watched',false));

    }
}
