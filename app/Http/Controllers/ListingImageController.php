<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ListingImageController extends Controller
{
    public function create(Listing $listing)
    {
        return view("listings.images.create", compact('listing'));
    }

    public function store(Request $request, Listing $listing)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'images.*.mimes' => 'Dozwolone są tylko pliki JPG, JPEG, PNG i WEBP.',
            'images.*.image' => 'Przesłany plik musi być obrazem.',
        ]);

        foreach ($request->file('images') as $imageFile) {

            $uuid = (string) Str::uuid();
            $extension = $imageFile->getClientOriginalExtension();

            $fileName = 'listings/' . $uuid . '.' . $extension;

            Storage::disk('public')->put(
                $fileName,
                file_get_contents($imageFile)
            );

            $image = Image::create([
                'file_name' => $fileName,
                'original_name' => $imageFile->getClientOriginalName(),
                'file_type' => $imageFile->getClientMimeType(),
            ]);

            $listing->images()->attach($image->id);
        }

        $listing->update([
            'status' => 'active'
        ]);

        return redirect()->route('listings.show', $listing->id)
            ->with('success', 'Zdjęcia dodane!');
    }

    public function edit(Listing $listing)
    {
        $listing->load('images');

        return view('listings.images.edit', compact('listing'));
    }

    public function destroy(Image $image)
    {
        $listing = $image->listings()->first();

        Storage::disk('public')->delete($image->file_name);

        $image->delete();

        if ($listing && $listing->images()->count() === 0) {
            $listing->update([
                'status' => 'inactive'
            ]);
        }

        return back()->with('success', 'Zdjęcie usunięte');
    }
}
