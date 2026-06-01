<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BodyType;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Transmission;
use App\Models\Brand;
use App\Models\Listing;
use App\Models\Service;
use App\Models\Tag;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function home()
    {
        $featuredListings = Listing::with(['brand', 'carModel', 'fuel', 'transmission', 'bodyType', 'tags', 'images'])
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        $popularTags = Tag::withCount(['listings' => function ($query) {
            $query->where('status', 'active');
        }])
            ->orderByDesc('listings_count')
            ->take(8)
            ->get();

        $stats = [
            'listings' => Listing::where('status', 'active')->count(),
            'brands' => Brand::count(),
            'services' => Service::where('status', 'active')->count(),
        ];

        return view('home', compact('featuredListings', 'popularTags', 'stats'));
    }

    public function index(Request $request)
    {
        $filterOptions = $this->filterOptions();
        $selectedTags = collect($request->input('tags', []))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $query = Listing::with(['brand', 'carModel', 'fuel', 'transmission', 'bodyType', 'tags', 'images', 'user'])
            ->where('status', 'active');

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('color', 'like', "%{$search}%")
                    ->orWhereHas('brand', fn ($brandQuery) => $brandQuery->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('carModel', fn ($modelQuery) => $modelQuery->where('name', 'like', "%{$search}%"));
            });
        }

        foreach (['brand_id', 'model_id', 'fuel_id', 'transmission_id', 'body_type_id'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->integer($field));
            }
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        foreach ([
            'price_min' => ['price', '>='],
            'price_max' => ['price', '<='],
            'year_min' => ['year', '>='],
            'year_max' => ['year', '<='],
            'mileage_max' => ['mileage', '<='],
            'power_min' => ['power_hp', '>='],
            'engine_min' => ['engine_capacity', '>='],
        ] as $input => [$column, $operator]) {
            if ($request->filled($input)) {
                $query->where($column, $operator, $request->input($input));
            }
        }

        foreach ($selectedTags as $tagId) {
            $query->whereHas('tags', fn ($tagQuery) => $tagQuery->where('tags.id', $tagId));
        }

        match ($request->input('sort')) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'year_desc' => $query->orderByDesc('year'),
            'mileage_asc' => $query->orderBy('mileage'),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->latest(),
        };

        $listings = $query->paginate(9)->withQueryString();

        return view('listings.index', array_merge($filterOptions, compact('listings', 'selectedTags')));
    }

    public function show(Listing $listing)
    {
        $listing->load('images', 'tags');
        return view('listings.show', compact('listing'));
    }

    public function create()
    {
        $brands = Brand::all();
        $models = CarModel::with('brand')->get();
        $fuels = Fuel::all();
        $transmissions = Transmission::all();
        $bodyTypes = BodyType::all();
        $tags = Tag::all();

        return view('listings.create', compact('brands', 'models', 'fuels', 'transmissions', 'bodyTypes', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'city' => 'required|string|max:255',
            'year' => 'required|integer',

            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:car_models,id',
            'fuel_id' => 'required|exists:fuels,id',
            'transmission_id' => 'required|exists:transmissions,id',
            'body_type_id' => 'required|exists:body_types,id',

            'color' => 'required|string|max:255',
            'mileage' => 'required|integer',
            'engine_capacity' => 'required|integer',
            'power_hp' => 'required|integer',

            'tags' => 'array'
        ]);

        $validated['user_id'] = Auth::id() ?? 1; //DO ZMIANY JAK BEDA UZYTKOWNICY!!
        $validated['status'] = 'inactive';
        $validated['views_count'] = 0;

        $listing = Listing::create($validated);

        $listing->tags()->sync($request->tags ?? []);

        return redirect()->route('listings.images.create', $listing->id);
    }

    private function filterOptions(): array
    {
        return [
            'brands' => Brand::orderBy('name')->get(),
            'models' => CarModel::with('brand')->orderBy('name')->get(),
            'fuels' => Fuel::orderBy('name')->get(),
            'transmissions' => Transmission::orderBy('name')->get(),
            'bodyTypes' => BodyType::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'cities' => Listing::where('status', 'active')
                ->whereNotNull('city')
                ->select('city')
                ->distinct()
                ->orderBy('city')
                ->pluck('city'),
        ];
    }
}
