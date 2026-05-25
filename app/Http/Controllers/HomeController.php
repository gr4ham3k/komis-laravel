<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Listing;

class HomeController extends Controller
{
    public function index()
    {
        $latestListings = Listing::with(['brand', 'carModel', 'images'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();

        $brands = Brand::orderBy('name')->get();

        return view('home', compact('latestListings', 'brands'));
    }
}
