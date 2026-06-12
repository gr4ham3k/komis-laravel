<?php

namespace App\Http\Controllers\Admin\Dictionary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function store(Request $request)
    {
        $request -> validate([
            'name' => 'required|string|max:255'
        ]);

        Brand::create([
            'name' => $request->name
        ]);

       return redirect()
            ->back()
            ->with('activeTab', 'brands')
            ->with('success', 'Dodano markę');
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request -> validate([
            'name' => 'required|string|max:255'
        ]);

        $brand -> update([
            'name' => $request -> name
        ]);

        return redirect()
            ->back()
            ->with('activeTab', 'brands')
            ->with('success', 'Zmodyfikowano markę');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        $brand -> delete();

        return redirect()
            ->back()
            ->with('activeTab', 'brands')
            ->with('success', 'Usunięto markę');
    }
}
