<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Transmission;
use App\Models\BodyType;
use App\Models\Tag;
use Illuminate\Http\Request;

class ListingAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with(['brand', 'carModel', 'fuel', 'transmission', 'bodyType', 'tags', 'images', 'user']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                    ->orWhere('city', 'ilike', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    });
            });
        }

        $listings = $query->latest()->paginate(20);

        return view('admin.listings.index', compact('listings'));
    }

    public function edit($id)
    {
        $listing = Listing::with('tags')->findOrFail($id);
        $brands = Brand::all();
        $models = CarModel::with('brand')->get();
        $fuels = Fuel::all();
        $transmissions = Transmission::all();
        $bodyTypes = BodyType::all();
        $tags = Tag::all();

        return view('admin.listings.edit', compact('listing', 'brands', 'models', 'fuels', 'transmissions', 'bodyTypes', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
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
            'status' => 'required|in:active,inactive,sold',
            'tags' => 'array',
        ]);

        $listing->update($validated);
        $listing->tags()->sync($request->tags ?? []);

        return redirect()->route('admin.listings.index')
            ->with('success', 'Ogłoszenie zostało zaktualizowane.');
    }

    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);

        foreach ($listing->images as $image) {
            $filePath = storage_path('app/public/' . $image->file_name);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image->delete();
        }

        $listing->delete();

        return redirect()->route('admin.listings.index')
            ->with('success', 'Ogłoszenie zostało usunięte.');
    }
}
