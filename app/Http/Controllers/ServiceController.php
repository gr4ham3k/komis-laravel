<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::with('user', 'reviews')
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('services.index', compact('services'));
    }

    public function show(int $id): View
    {
        $service = Service::with('user', 'reviews.user')->findOrFail($id);
        $service->increment('views_count');

        return view('services.show', compact('service'));
    }

    public function create(): View
    {
        return view('services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'city' => ['required', 'string', 'max:255'],
        ]);

        $service = Service::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'city' => $validated['city'],
            'status' => 'active',
        ]);

        return redirect()
            ->route('services.show', $service->id)
            ->with('success', 'Usluga zostala dodana.');
    }

    public function edit(int $id): View
    {
        $service = $this->findManageableService($id);

        return view('services.edit', compact('service'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $service = $this->findManageableService($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'city' => ['required', 'string', 'max:255'],
        ]);

        $service->update($validated);

        return redirect()
            ->route('services.show', $service->id)
            ->with('success', 'Usluga zostala zaktualizowana.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $service = $this->findManageableService($id);
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Usluga zostala usunieta.');
    }

    public function addReview(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::findOrFail($id);

        $existingReview = ServiceReview::query()
            ->where('service_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Ta usluga ma juz Twoja opinie.');
        }

        ServiceReview::create([
            'service_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Dziekujemy za opinie.');
    }

    public function myServices(): View
    {
        $services = Service::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('services.my-services', compact('services'));
    }

    private function findManageableService(int $id): Service
    {
        $query = Service::query();

        if (! Auth::user()?->is_admin) {
            $query->where('user_id', Auth::id());
        }

        return $query->findOrFail($id);
    }
}
