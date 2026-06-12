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

         return redirect()
            ->back()
            ->with('activeTab', 'models')
            ->with('success', 'Dodano model');
    }

    public function update(Request $request, $id)
    {
        $model = CarModel::findOrFail($id);

        $request -> validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255'
        ]);

        $model -> update([
            'brand_id' => $request->brand_id,
            'name' => $request -> name
        ]);

         return redirect()
            ->back()
            ->with('activeTab', 'models')
            ->with('success', 'Zmodyfikowano model');
    }

    public function destroy($id)
    {
        $model = CarModel::findOrFail($id);

        $model -> delete();

         return redirect()
            ->back()
            ->with('activeTab', 'models')
            ->with('success', 'Usunięto model');
    }
}
