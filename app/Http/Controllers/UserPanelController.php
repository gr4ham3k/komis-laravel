<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Service;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class UserPanelController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'listings_count' => Listing::where('user_id', $user->id)->count(),
            'active_listings' => Listing::where('user_id', $user->id)->where('status', 'active')->count(),
            'services_count' => Service::where('user_id', $user->id)->count(),
            'conversations_count' => Conversation::where('user2_id', $user->id)->count(),
            'total_views' => Listing::where('user_id', $user->id)->sum('views_count'),
        ];

        $recentListings = Listing::with(['brand', 'carModel', 'images'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentListings'));
    }

    public function listings()
    {
        $listings = Listing::with(['brand', 'carModel', 'images', 'tags'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.listings', compact('listings'));
    }
}
