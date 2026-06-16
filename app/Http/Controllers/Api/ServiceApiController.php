<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceApiController extends Controller
{
    public function index()
    {
        return Service::with(['user', 'reviews.user', 'images'])
            ->where('status', 'active')
            ->latest()
            ->get();
    }

    public function show(Service $service)
    {
        return $service->load(['user', 'reviews.user', 'images']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'active';
        $validated['views_count'] = 0;

        $service = Service::create($validated);

        return response()->json($service, 201);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'city' => 'sometimes|string|max:255',
            'status' => 'sometimes|string',
        ]);

        $service->update($validated);

        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json(['message' => 'deleted']);
    }
}
