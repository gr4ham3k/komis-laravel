<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string|max:2000',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);

        if (
            $conversation->user2_id !== Auth::id()
            && optional($conversation->listing)->user_id !== Auth::id()
            && optional($conversation->service)->user_id !== Auth::id()
        ) {
            abort(403);
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'content' => $request->content,
            'is_read' => false,
        ]);

        return redirect()->route('conversations.show', $conversation->id);
    }
}
