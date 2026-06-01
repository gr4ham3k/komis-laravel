<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;


class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string'
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => 2, //do zmiany
            'content' => $request->content,
        ]);

        return redirect()->route('conversations.show', $conversation->id);
    }


}
