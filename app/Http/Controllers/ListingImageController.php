<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

            $fileName = $uuid . '.' . $extension;

            $imageFile->storeAs('listings', $fileName, 'public');

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
}
