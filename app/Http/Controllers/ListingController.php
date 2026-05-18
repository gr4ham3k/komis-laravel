<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function show($id)
    {
        $listing = Listing::with('images')->findOrFail($id);

        return view('listings.show',compact('listing'));
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request)
    {
        //todo zapis do bazy
    }
}
