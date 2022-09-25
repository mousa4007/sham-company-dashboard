<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function messages()
    {
        $messages = Message::latest()->get();

        return $messages;
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

        return response()->json('success');
    }
}
