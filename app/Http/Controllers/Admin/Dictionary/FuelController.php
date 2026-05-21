<?php

namespace App\Http\Controllers\Admin\Dictionary;

use App\Http\Controllers\Controller;
use App\Models\Fuel;
use Illuminate\Http\Request;

class FuelController extends Controller
{
    public function store(Request $request)
    {
        $request -> validate([
            'name' => 'required|string|max:255'
        ]);

        Fuel::create([
            'name' => $request->name
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $brand = Fuel::findOrFail($id);

        $request -> validate([
            'name' => 'required|string|max:255'
        ]);

        $brand -> update([
            'name' => $request -> name
        ]);

        return back();
    }

    public function destroy($id)
    {
        $brand = Fuel::findOrFail($id);

        $brand -> delete();

        return back();
    }
}
