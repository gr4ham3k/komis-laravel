<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Listing;
use App\Models\Tag;
use App\Models\Transmission;
use App\Models\User;
use Illuminate\Http\Request;

class ListingController extends Controller
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
        $layout = $request->string('layout')->toString() === 'list' ? 'list' : 'grid';

        $listings = Listing::query()
            ->with(['brand', 'carModel', 'fuel', 'transmission', 'images', 'tags'])
            ->where('status', 'active')
            ->filter($filters)
            ->sortOption($sort)
            ->paginate(9)
            ->withQueryString();

        return view('listings.index', [
            'listings' => $listings,
            'brands' => Brand::query()->orderBy('name')->get(['id', 'name']),
            'models' => CarModel::query()->orderBy('name')->get(['id', 'brand_id', 'name']),
            'fuels' => Fuel::query()->orderBy('name')->get(['id', 'name']),
            'transmissions' => Transmission::query()->orderBy('name')->get(['id', 'name']),
            'tags' => Tag::query()->orderBy('name')->get(['id', 'name']),
            'filters' => $filters,
            'sort' => $sort,
            'layout' => $layout,
            'isHome' => $request->routeIs('home'),
        ]);
    }

    public function show(Listing $listing)
    {
        $listing->increment('views_count');
        $listing->load(['images', 'fuel', 'transmission', 'user', 'brand', 'carModel', 'tags']);

        return view('listings.show', compact('listing'));
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'city' => ['required', 'string', 'max:255'],
            'brand_id' => ['required', 'exists:brands,id'],
            'model_id' => ['required', 'exists:car_models,id'],
            'fuel_id' => ['required', 'exists:fuels,id'],
            'transmission_id' => ['required', 'exists:transmissions,id'],
            'body_type_id' => ['required', 'exists:body_types,id'],
            'color' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'mileage' => ['required', 'integer', 'min:0'],
            'engine_capacity' => ['required', 'integer', 'min:0'],
            'power_hp' => ['required', 'integer', 'min:0'],
        ]);

        $userId = auth()->id() ?? User::query()->value('id');

        if (! $userId) {
            return back()->withErrors([
                'user' => 'Brak uzytkownika do przypisania ogloszenia.',
            ])->withInput();
        }

        $validated['user_id'] = $userId;

        $listing = Listing::query()->create($validated);

        return redirect()
            ->route('listings.show', $listing)
            ->with('success', 'Ogloszenie zostalo dodane.');
    }
}
