<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Service;
use App\Models\Conversation;

class ConversationController extends Controller
{
    public function createOrOpenConversation($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        $conversation = Conversation::firstOrCreate([
            'listing_id' => $listingId,
            'service_id' => null,
            'user2_id' => 2, //do zmiany
        ]);

        return redirect()->route('conversations.show', $conversation->id);
    }

    public function createOrOpenServiceConversation($serviceId)
    {
        $service = Service::findOrFail($serviceId);

        $conversation = Conversation::firstOrCreate([
            'listing_id' => null,
            'service_id' => $service->id,
            'user2_id' => 2, //do zmiany
        ]);

        return redirect()->route('conversations.show', $conversation->id);
    }

    public function show($id)
    {
        $conversation = Conversation::with([
            'messages.sender',
            'listing',
            'service'
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
            ->orWhereHas('service', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with([
                'listing',
                'service',
                'messages' => function ($q) {
                    $q->latest();
                }
            ])
            ->get();

        return view('conversations.index', compact('conversations'));
    }
}
