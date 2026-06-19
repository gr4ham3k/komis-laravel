<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('user', 'images');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhere('city', 'ilike', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    });
            });
        }

        $services = $query->latest()->paginate(15);
        $users = User::orderBy('name')->get();

        return view('admin.services', compact('services', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateService($request);

        $service = Service::create($validated + [
            'views_count' => 0,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                if ($imageFile && $imageFile->isValid()) {
                    $uuid = (string) Str::uuid();
                    $extension = $imageFile->getClientOriginalExtension();
                    $fileName = 'services/' . $uuid . '.' . $extension;

                    $imageFile->storeAs('services', $uuid . '.' . $extension, 'public');

                    $image = Image::create([
                        'file_name' => $fileName,
                        'original_name' => $imageFile->getClientOriginalName(),
                        'file_type' => $imageFile->getClientMimeType(),
                    ]);

                    $service->images()->attach($image->id);
                }
            }
        }

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Usługa została dodana.');
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update($this->validateService($request));

        // Usuwanie zaznaczonych zdjęć
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = Image::find($imageId);
                if ($image) {
                    $filePath = storage_path('app/public/' . $image->file_name);
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
                    $fileName = 'services/' . $uuid . '.' . $extension;

                    $imageFile->storeAs('services', $uuid . '.' . $extension, 'public');

                    $image = Image::create([
                        'file_name' => $fileName,
                        'original_name' => $imageFile->getClientOriginalName(),
                        'file_type' => $imageFile->getClientMimeType(),
                    ]);

                    $service->images()->attach($image->id);
                }
            }
        }

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Usługa została zaktualizowana.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        // Usuwanie wszystkich powiązanych zdjęć z dysku i bazy
        foreach ($service->images as $image) {
            $filePath = storage_path('app/public/' . $image->file_name);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image->delete();
        }

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
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:images,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|max:2048',
        ]);
    }
}
