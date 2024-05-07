<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Events\MessageSent;


class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $content = $request->input('message');

        $message = new Message([
            'user_id' => $user->id,
            'content' => $content,
        ]);
        $message->save();

        event(new MessageSent($user, $message));

        return response()->json(['message' => 'Message sent successfully!', 'data' => $message]);
    }
}
