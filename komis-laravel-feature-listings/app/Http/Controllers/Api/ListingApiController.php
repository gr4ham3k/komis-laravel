<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingApiController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'brand_id',
            'model_id',
            'fuel_id',
            'transmission_id',
            'tag_ids',
            'city',
            'price_from',
            'price_to',
            'year_from',
            'year_to',
        ]);

        $filters['tag_ids'] = array_values(array_filter((array) $request->input('tag_ids', [])));

        $sort = $request->string('sort')->toString();
        $perPage = max(1, min((int) $request->input('per_page', 12), 50));

        $listings = Listing::query()
            ->with(['brand', 'carModel', 'fuel', 'transmission', 'images', 'tags'])
            ->where('status', 'active')
            ->filter($filters)
            ->sortOption($sort)
            ->paginate($perPage)
            ->withQueryString();

        return response()->json($listings);
    }

    public function show(Listing $listing)
    {
        $listing->increment('views_count');

        return response()->json(
            $listing->load(['brand', 'carModel', 'fuel', 'transmission', 'bodyType', 'user', 'images', 'tags'])
        );
    }
}
