<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Service;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{

    public function createOrOpenConversation($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        $conversation = Conversation::where('listing_id', $listingId)
            ->where('user2_id', Auth::id())
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'listing_id' => $listingId,
                'user2_id' => Auth::id(),
            ]);
        }

        return redirect()->route('conversations.show', $conversation->id);
    }

    public function createOrOpenServiceConversation($serviceId)
    {
        $service = Service::findOrFail($serviceId);

        $conversation = Conversation::where('service_id', $serviceId)
            ->where('user2_id', Auth::id())
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'service_id' => $serviceId,
                'user2_id' => Auth::id(),
            ]);
        }

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
                'listing.user',
                'service.user',
                'latestMessage',
            ])
            ->withCount(['messages as unread_count' => function ($q) use ($userId) {
                $q->where('sender_id', '!=', $userId)
                    ->where('is_read', false);
            }])
            ->get()
            ->sortByDesc(function ($conversation) {
                return optional($conversation->latestMessage)->created_at;
            })
            ->values();

        return view('conversations.index', compact('conversations'));
    }

    public function show($id)
    {
        $userId = Auth::id();

        $conversation = Conversation::findOrFail($id);

        if (
            $conversation->user2_id !== $userId &&
            optional($conversation->listing)->user_id !== $userId &&
            optional($conversation->service)->user_id !== $userId
        ) {
            abort(403);
        }

        $conversations = Conversation::where('user2_id', $userId)
            ->orWhereHas('listing', fn($q) => $q->where('user_id', $userId))
            ->orWhereHas('service', fn($q) => $q->where('user_id', $userId))
            ->with([
                'listing.user',
                'service.user',
                'latestMessage',
            ])
            ->withCount(['messages as unread_count' => function ($q) use ($userId) {
                $q->where('sender_id', '!=', $userId)
                    ->where('is_read', false);
            }])
            ->get()
            ->sortByDesc(fn($c) => $c->latestMessage?->created_at)
            ->values();

        $messages = Message::where('conversation_id', $id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get();

        $conversation->setRelation('messages', $messages);

        Message::where('conversation_id', $id)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'visit_time' => now()
            ]);

        return view('conversations.index', [
            'conversations' => $conversations,
            'conversationActive' => $conversation
        ]);
    }
}
