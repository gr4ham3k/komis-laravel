<?php

namespace App\Http\Controllers\Admin\Dictionary;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function store(Request $request)
    {
        $request -> validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255'
        ]);

        CarModel::create([
            'brand_id' => $request->brand_id,
            'name' => $request->name
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $brand = CarModel::findOrFail($id);

        $request -> validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255'
        ]);

        $brand -> update([
            'brand_id' => $request->brand_id,
            'name' => $request -> name
        ]);

        return back();
    }

    public function destroy($id)
    {
        $brand = CarModel::findOrFail($id);

        $brand -> delete();

        return back();
    }
}
