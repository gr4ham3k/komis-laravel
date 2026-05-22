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
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function show($id)
    {
        $listing = Listing::with('images', 'tags')->findOrFail($id);
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

            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'tags' => 'array'
        ]);

        $validated['user_id'] = Auth::id() ?? 1; //DO ZMIANY JAK BEDA UZYTKOWNICY!!
        $validated['status'] = 'active';
        $validated['views_count'] = 0;

        $listing = Listing::create($validated);

        $listing->tags()->sync($request->tags ?? []);

        if($request->hasFile('images'))
        {
            foreach($request->file('images') as $imageFile)
            {
                $uuid = (string) Str::uuid();
                $extension = $imageFile->getClientOriginalExtension();

                $fileName = $uuid . '.' . $extension;

                $path = $imageFile->storeAs('listings',$fileName,'public');

                $image = Image::create([
                    'file_name' => $fileName,
                    'original_name' => $imageFile->getClientOriginalName(),
                    'file_type' => $imageFile->getClientMimeType(),
                ]);

                $listing->images()->attach($image->id);

            }
        }

        return redirect()->route('listings.show',$listing->id)->with('success','Ogłoszenie zostało dodane!');
    }
}
