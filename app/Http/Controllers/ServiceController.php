<?php
// app/Http/Controllers/ServiceController.php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceReview;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        // Pobranie unikalnych miast dla selecta filtrów
        $cities = Service::where('status', 'active')
            ->select('city')
            ->distinct()
            ->pluck('city');

        $query = Service::with('user', 'reviews', 'images')
            ->where('status', 'active');

        // Filtrowanie po wyszukiwaniu tekstowym
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhere('city', 'ilike', "%{$search}%");
            });
        }

        // ✅ FILTR: Miasto
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // ✅ FILTR: Cena minimalna
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->price_min);
        }

        // ✅ FILTR: Cena maksymalna
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->price_max);
        }

        // ✅ SORTOWANIE
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'best_rated':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            default: // newest
                $query->latest();
                break;
        }

        $services = $query->paginate(12)->withQueryString();

        return view('services.index', compact('services', 'search', 'cities'));
    }

    public function show($id)
    {
        $service = Service::with('user', 'reviews.user', 'images')
            ->findOrFail($id);

        $service->increment('views_count');

        $userReview = null;
        if (Auth::check()) {
            $userReview = ServiceReview::where('service_id', $id)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('services.show', compact('service', 'userReview'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048'
        ]);

        $service = Service::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'city' => $validated['city'],
            'status' => 'active'
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                if ($imageFile && $imageFile->isValid()) {
                    $uuid = (string) Str::uuid();
                    $extension = $imageFile->getClientOriginalExtension();
                    $fileName = $uuid . '.' . $extension;

                    $imageFile->storeAs('services', $fileName, 'public');

                    $image = Image::create([
                        'file_name' => $fileName,
                        'original_name' => $imageFile->getClientOriginalName(),
                        'file_type' => $imageFile->getClientMimeType(),
                    ]);

                    $service->images()->attach($image->id);
                }
            }
        }

        return redirect()->route('services.show', $service->id)
            ->with('success', 'Usługa została dodana!');
    }

    public function edit($id)
    {
        $service = Service::where('user_id', Auth::id())->findOrFail($id);
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:255',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:images,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|max:2048'
        ]);

        // Aktualizacja danych podstawowych
        $service->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'city' => $validated['city'],
        ]);

        // Usuwanie zaznaczonych zdjęć
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = Image::find($imageId);
                if ($image) {
                    $filePath = storage_path('app/public/services/' . $image->file_name);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $service->images()->detach($imageId);
                    $image->delete();
                }
            }
        }

        // Dodawanie nowych zdjęć
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $imageFile) {
                if ($imageFile && $imageFile->isValid()) {
                    $uuid = (string) Str::uuid();
                    $extension = $imageFile->getClientOriginalExtension();
                    $fileName = $uuid . '.' . $extension;

                    $imageFile->storeAs('services', $fileName, 'public');

                    $image = Image::create([
                        'file_name' => $fileName,
                        'original_name' => $imageFile->getClientOriginalName(),
                        'file_type' => $imageFile->getClientMimeType(),
                    ]);

                    $service->images()->attach($image->id);
                }
            }
        }

        return redirect()->route('services.show', $service->id)
            ->with('success', 'Usługa została zaktualizowana!');
    }

    public function destroy($id)
    {
        $service = Service::where('user_id', Auth::id())->findOrFail($id);

        foreach ($service->images as $image) {
            $filePath = storage_path('app/public/services/' . $image->file_name);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image->delete();
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Usługa została usunięta!');
    }

    public function addReview(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $service = Service::findOrFail($id);

        if ($service->user_id === Auth::id()) {
            return back()->with('error', 'Nie możesz ocenić własnej usługi!');
        }

        $existingReview = ServiceReview::where('service_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Już dodałeś opinię dla tej usługi!');
        }

        ServiceReview::create([
            'service_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        return back()->with('success', 'Dziękujemy za opinię!');
    }

    public function updateReview(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review = ServiceReview::where('service_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        return back()->with('success', 'Opinia została zaktualizowana!');
    }

    public function myServices()
    {
        $services = Service::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('services.my-services', compact('services'));
    }
}
