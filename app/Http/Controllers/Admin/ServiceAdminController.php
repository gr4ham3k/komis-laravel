<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceAdminController extends Controller
{
    public function index()
    {
        $services = Service::with('user')
            ->latest()
            ->paginate(15);
        $users = User::orderBy('name')->get();

        return view('admin.services', compact('services', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateService($request);

        Service::create($validated + [
            'views_count' => 0,
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Usługa została dodana.');
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update($this->validateService($request));

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Usługa została zaktualizowana.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Usługa została usunięta.');
    }

    private function validateService(Request $request): array
    {
        return $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,deleted',
        ]);
    }
}
