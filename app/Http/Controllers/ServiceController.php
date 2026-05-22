<?php
// app/Http/Controllers/ServiceController.php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('user', 'reviews')
            ->where('status', 'active')
            ->latest()
            ->paginate(12);
        
        return view('services.index', compact('services'));
    }

    public function show($id)
    {
        $service = Service::with('user', 'reviews.user')
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
            'city' => 'required|string|max:255'
        ]);

        $service = Service::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'city' => $validated['city'],
            'status' => 'active'
        ]);

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
            'city' => 'required|string|max:255'
        ]);

        $service->update($validated);

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