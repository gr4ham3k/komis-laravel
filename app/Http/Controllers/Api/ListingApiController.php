<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingApiController extends Controller
{
    public function index()
    {
        return Listing::with(['brand','carModel','fuel','transmission','bodyType','tags','images'])
            ->where('status', 'active')
            ->latest()
            ->get();
    }

    public function show(Listing $listing)
    {
        return $listing->load(['brand','carModel','fuel','transmission','bodyType','tags','images']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required|min:20',
            'price' => 'required|numeric',
            'city' => 'required',
            'year' => 'required|integer',
            'brand_id' => 'required',
            'model_id' => 'required',
            'fuel_id' => 'required',
            'transmission_id' => 'required',
            'body_type_id' => 'required',
            'color' => 'required',
            'mileage' => 'required|integer',
            'engine_capacity' => 'required|integer',
            'power_hp' => 'required|integer',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'inactive';
        $validated['views_count'] = 0;

        $listing = Listing::create($validated);

        $listing->tags()->sync($request->tags ?? []);

        return response()->json($listing, 201);
    }

    public function update(Request $request, Listing $listing)
    {
        $listing->update($request->all());

        return response()->json($listing);
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();

        return response()->json(['message' => 'deleted']);
    }
}
