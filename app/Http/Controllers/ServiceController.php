<?php
// app/Http/Controllers/ServiceController.php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceReview;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $services = Service::with('user', 'reviews', 'images')
            ->where('status', 'active')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();
        
        return view('services.index', compact('services', 'search'));
    }

    public function show($id)
    {
        $service = Service::with('user', 'reviews.user', 'images')
            ->findOrFail($id);
        
        // Increment views count
        $service->increment('views_count');
        
        return view('services.show', compact('service'));
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
            'images.*' => 'nullable|image|max:2048'
        ]);

        $service = Service::create([
            'user_id' => Auth::id() ?? 1,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'city' => $validated['city'],
            'status' => 'active'
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
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
            'images.*' => 'nullable|image|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:images,id'
        ]);

        $service->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'city' => $validated['city']
        ]);

        // Handle deleting selected images
        if (!empty($validated['delete_images'])) {
            foreach ($validated['delete_images'] as $imageId) {
                $image = Image::find($imageId);
                if ($image) {
                    // Detach from this service
                    $service->images()->detach($imageId);
                    
                    // Check if it's still linked anywhere
                    if (!$image->listings()->exists() && !$image->services()->exists()) {
                        Storage::disk('public')->delete('services/' . $image->file_name);
                        $image->delete();
                    }
                }
            }
        }

        // Handle adding new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
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

        return redirect()->route('services.show', $service->id)
            ->with('success', 'Usługa została zaktualizowana!');
    }

    public function destroy($id)
    {
        $service = Service::where('user_id', Auth::id())->findOrFail($id);
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

        // Check if user already reviewed this service
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

    public function myServices()
    {
        $services = Service::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('services.my-services', compact('services'));
    }
}
