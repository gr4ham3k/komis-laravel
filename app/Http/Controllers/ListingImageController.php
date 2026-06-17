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
            'images.*' => 'required|image|max:2048',
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
        Storage::disk('public')->delete($image->file_name);

        $image->delete();

        return back()->with('success', 'Zdjęcie usunięte');
    }
}
