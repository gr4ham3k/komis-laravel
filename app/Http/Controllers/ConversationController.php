<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Conversation;

class ConversationController extends Controller
{
    public function createOrOpenConversation($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        $conversation = Conversation::firstOrCreate([
            'listing_id' => $listingId,
            'user2_id' => 2, //do zmiany
        ]);

        return redirect()->route('conversations.show', $conversation->id);
    }

    public function index()
    {
        $conversations = Conversation::with(['messages', 'listing'])
            ->Where('user2_id', 2)
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    public function show($id)
    {
        $conversations = Conversation::with(['messages', 'listing'])->get();

        $conversationActive = Conversation::with(['messages.sender', 'listing'])
            ->findOrFail($id);

        return view('conversations.index', compact('conversations', 'conversationActive'));
    }
}
