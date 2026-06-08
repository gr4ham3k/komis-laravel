<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingCompareController extends Controller
{
    private const SESSION_KEY = 'compare_listings';

    public function index(Request $request)
    {
        $ids = $request->session()->get(self::SESSION_KEY, []);

        $listings = Listing::with(['brand', 'carModel', 'fuel', 'transmission', 'bodyType', 'images', 'user'])
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn (Listing $listing) => array_search($listing->id, $ids, true))
            ->values();

        return view('listings.compare', compact('listings'));
    }

    public function store(Request $request, Listing $listing)
    {
        $ids = $request->session()->get(self::SESSION_KEY, []);

        if (!in_array($listing->id, $ids, true)) {
            $ids[] = $listing->id;
        }

        $request->session()->put(self::SESSION_KEY, array_values(array_unique($ids)));

        return back()->with('success', 'Ogloszenie dodane do porownania.');
    }

    public function destroy(Request $request, Listing $listing)
    {
        $ids = array_values(array_filter(
            $request->session()->get(self::SESSION_KEY, []),
            fn ($id) => (int) $id !== $listing->id
        ));

        $request->session()->put(self::SESSION_KEY, $ids);

        return back()->with('success', 'Ogloszenie usuniete z porownania.');
    }

    public function clear(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);

        return back()->with('success', 'Lista porownania zostala wyczyszczona.');
    }
}
