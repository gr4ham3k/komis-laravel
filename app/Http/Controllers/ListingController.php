<?php

namespace App\Http\Controllers;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Image;
use App\Models\Listing;
use App\Models\Tag;
use App\Models\Transmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        $listing->load(['images', 'fuel', 'transmission', 'user', 'brand', 'carModel', 'tags', 'bodyType']);

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
        $validated = $this->validateListing($request);
        $validated['user_id'] = $this->resolveCurrentUserId();
        $validated['status'] = 'active';
        $validated['views_count'] = 0;

        $listing = Listing::create($validated);

        $listing->tags()->sync($request->input('tags', []));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $uuid = (string) Str::uuid();
                $extension = $imageFile->getClientOriginalExtension();
                $fileName = $uuid . '.' . $extension;

                $imageFile->storeAs('listings', $fileName, 'public');

                $image = Image::create([
                    'file_name' => $fileName,
                    'original_name' => $imageFile->getClientOriginalName(),
                    'file_type' => $imageFile->getClientMimeType(),
                ]);

                $listing->images()->attach($image->id, ['sort_order' => $index + 1]);
            }
        }

        return redirect()->route('listings.show', $listing)->with('success', 'Ogloszenie zostalo dodane.');
    }

    public function myListings()
    {
        $userId = $this->resolveCurrentUserId();

        $listings = Listing::with(['brand', 'carModel'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('listings.my', compact('listings'));
    }

    public function edit(Listing $listing)
    {
        $this->authorizeListingAccess($listing);
        $listing->load('tags');

        $brands = Brand::all();
        $models = CarModel::with('brand')->get();
        $fuels = Fuel::all();
        $transmissions = Transmission::all();
        $bodyTypes = BodyType::all();
        $tags = Tag::all();

        return view('listings.edit', compact('listing', 'brands', 'models', 'fuels', 'transmissions', 'bodyTypes', 'tags'));
    }

    public function update(Request $request, Listing $listing)
    {
        $this->authorizeListingAccess($listing);

        $validated = $this->validateListing($request);
        $listing->update($validated);

        $listing->tags()->sync($request->input('tags', []));

        if ($request->hasFile('images')) {
            $lastSortOrder = (int) $listing->images()->max('listing_images.sort_order');

            foreach ($request->file('images') as $index => $imageFile) {
                $uuid = (string) Str::uuid();
                $extension = $imageFile->getClientOriginalExtension();
                $fileName = $uuid . '.' . $extension;

                $imageFile->storeAs('listings', $fileName, 'public');

                $image = Image::create([
                    'file_name' => $fileName,
                    'original_name' => $imageFile->getClientOriginalName(),
                    'file_type' => $imageFile->getClientMimeType(),
                ]);

                $listing->images()->attach($image->id, ['sort_order' => $lastSortOrder + $index + 1]);
            }
        }

        return redirect()->route('my.listings')->with('success', 'Ogloszenie zostalo zaktualizowane.');
    }

    public function destroy(Listing $listing)
    {
        $this->authorizeListingAccess($listing);
        $listing->delete();

        return redirect()->route('my.listings')->with('success', 'Ogloszenie zostalo usuniete.');
    }

    public function apiIndex(Request $request)
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
        $perPage = max(1, min((int) $request->integer('per_page', 12), 50));

        $paginator = Listing::query()
            ->with(['brand', 'carModel', 'fuel', 'transmission', 'images', 'tags'])
            ->where('status', 'active')
            ->filter($filters)
            ->sortOption($sort)
            ->paginate($perPage)
            ->withQueryString();

        return response()->json($paginator);
    }

    private function validateListing(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'city' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'between:1950,' . (date('Y') + 1)],
            'brand_id' => ['required', Rule::exists('brands', 'id')],
            'model_id' => ['required', Rule::exists('car_models', 'id')],
            'fuel_id' => ['required', Rule::exists('fuels', 'id')],
            'transmission_id' => ['required', Rule::exists('transmissions', 'id')],
            'body_type_id' => ['required', Rule::exists('body_types', 'id')],
            'color' => ['required', 'string', 'max:255'],
            'mileage' => ['required', 'integer', 'min:0'],
            'engine_capacity' => ['required', 'integer', 'min:0'],
            'power_hp' => ['required', 'integer', 'min:0'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', Rule::exists('tags', 'id')],
        ]);
    }

    private function authorizeListingAccess(Listing $listing): void
    {
        $user = Auth::user();
        $currentUserId = $this->resolveCurrentUserId();

        if ($listing->user_id !== $currentUserId && ! ($user && $user->is_admin)) {
            abort(403, 'Brak uprawnien do zarzadzania tym ogloszeniem.');
        }
    }

    private function resolveCurrentUserId(): int
    {
        $currentUserId = Auth::id();
        if ($currentUserId) {
            return $currentUserId;
        }

        $firstUserId = User::query()->value('id');
        if (! $firstUserId) {
            abort(500, 'Brak uzytkownika w bazie. Dodaj uzytkownika i sprobuj ponownie.');
        }

        return (int) $firstUserId;
    }
}
