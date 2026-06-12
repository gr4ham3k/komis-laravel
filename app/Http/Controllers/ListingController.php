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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::with(['brand', 'carModel', 'fuel', 'transmission', 'bodyType', 'images', 'user'])
            ->latest()
            ->paginate(12);

        return view('listings.index', compact('listings'));
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
}
