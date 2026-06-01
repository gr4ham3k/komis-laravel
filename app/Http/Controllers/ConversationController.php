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

    public function show($id)
    {
        $conversation = Conversation::with([
            'messages.sender',
            'listing'
        ])->findOrFail($id);

        return view('conversations.show', compact('conversation'));
    }

    public function index()
    {
        $userId = 2; // do zmiany

        $conversations = Conversation::where('user2_id', $userId)
            ->orWhereHas('listing', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with([
                'listing',
                'messages' => function ($q) {
                    $q->latest();
                }
            ])
            ->get();

        return view('conversations.index', compact('conversations'));
    }
}
