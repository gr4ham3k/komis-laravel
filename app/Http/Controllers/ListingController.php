<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BodyType;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Transmission;
use App\Models\Brand;
use App\Models\Listing;
use App\Models\Tag;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                    ->whereRaw("similarity(title, ?) > 0.1", [$search])
                    ->orWhereRaw("similarity(description, ?) > 0.1", [$search])
                    ->orWhereRaw("similarity(city, ?) > 0.1", [$search])
                    ->orWhereRaw("similarity(color, ?) > 0.1", [$search])
                    ->orWhereHas('brand', fn ($brandQuery) => $brandQuery->whereRaw("similarity(name, ?) > 0.1", [$search]))
                    ->orWhereHas('carModel', fn ($modelQuery) => $modelQuery->whereRaw("similarity(name, ?) > 0.1", [$search]));
            });
        }

        foreach (['brand_id', 'model_id', 'fuel_id', 'transmission_id', 'body_type_id'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->integer($field));
            }
        }

        if ($request->filled('lat') && $request->filled('lng')) {
            $lat = (float) $request->input('lat');
            $lng = (float) $request->input('lng');
            $radius = (int) $request->input('radius', 50); // Default to 50km if not provided
            
            if ($radius > 0) {
                $query->whereNotNull('latitude')->whereRaw(
                    "(6371 * acos(least(greatest(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)), -1.0), 1.0))) <= ?",
                    [$lat, $lng, $lat, $radius]
                );
            }
        } elseif ($request->filled('city')) {
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

        $listings = $query->paginate(12)->withQueryString();

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
            'description' => 'required|string|min:20',

            'price' => 'required|numeric|min:0',

            'city' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            'year' => 'required|integer|min:1900|max:' . date('Y'),

            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:car_models,id',
            'fuel_id' => 'required|exists:fuels,id',
            'transmission_id' => 'required|exists:transmissions,id',
            'body_type_id' => 'required|exists:body_types,id',

            'color' => 'required|string|max:255',

            'mileage' => 'required|integer|min:0',

            'engine_capacity' => 'required|integer|min:50|max:10000',

            'power_hp' => 'required|integer|min:1|max:3000',

            'tags' => 'array'
        ], [
            'price.min' => 'Cena nie może być ujemna.',
            'year.min' => 'Podaj poprawny rok produkcji.',
            'year.max' => 'Rok produkcji nie może być większy niż bieżący.',
            'mileage.min' => 'Przebieg nie może być ujemny.',
            'engine_capacity.min' => 'Pojemność silnika jest zbyt mała.',
            'engine_capacity.max' => 'Pojemność silnika jest zbyt duża.',
            'power_hp.min' => 'Moc musi być większa od 0 KM.',
            'power_hp.max' => 'Moc jest zbyt duża.',

        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'inactive';
        $validated['views_count'] = 0;

        $listing = Listing::create($validated);

        $listing->tags()->sync($request->tags ?? []);

        return redirect()->route('listings.images.create', $listing->id);
    }



    public function search(Request $request)
    {
        $query = $request->q;

        $brands = Brand::whereRaw("similarity(name, ?) > 0.2", [$query])
            ->orderByRaw("similarity(name, ?) DESC", [$query])
            ->limit(10)
            ->get();

        return response()->json($brands);
    }


    public function searchModels(Request $request)
    {
        $query = $request->q;
        $brandId = $request->brand_id;

        return CarModel::where('brand_id', $brandId)
            ->whereRaw("name % ?", [$query])
            ->orderByRaw("similarity(name, ?) DESC", [$query])
            ->limit(10)
            ->get();
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
