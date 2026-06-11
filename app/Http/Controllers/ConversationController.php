<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Service;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function createOrOpenConversation($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        $conversation = Conversation::firstOrCreate([
            'listing_id' => $listingId,
            'user2_id' => Auth::id(),
        ]);

        return redirect()->route('conversations.show', $conversation->id);
    }

    public function createOrOpenServiceConversation($serviceId)
    {
        $service = Service::findOrFail($serviceId);

        $conversation = Conversation::firstOrCreate([
            'service_id' => $serviceId,
            'user2_id' => Auth::id(),
        ]);

        return redirect()->route('conversations.show', $conversation->id);
    }

    public function index()
    {
        $userId = Auth::id();

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

    public function show($id)
    {
        $userId = Auth::id();

        $conversations = Conversation::where('user2_id', $userId)
            ->orWhereHas('listing', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orWhereHas('service', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['messages', 'listing', 'service'])
            ->get();

        $conversationActive = Conversation::with(['messages.sender', 'listing', 'service'])
            ->findOrFail($id);

        return view('conversations.index', compact('conversations', 'conversationActive'));
    }
}
