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

        return redirect()
            ->back()
            ->with('activeTab', 'fuels')
            ->with('success', 'Dodano paliwo');
    }

    public function update(Request $request, $id)
    {
        $fuel = Fuel::findOrFail($id);

        $request -> validate([
            'name' => 'required|string|max:255'
        ]);

        $fuel -> update([
            'name' => $request -> name
        ]);

        return redirect()
            ->back()
            ->with('activeTab', 'fuels')
            ->with('success', 'Zmodyfikowano paliwo');
    }

    public function destroy($id)
    {
        $fuel = Fuel::findOrFail($id);

        $fuel -> delete();

        return redirect()
            ->back()
            ->with('activeTab', 'fuels')
            ->with('success', 'Usunięto paliwo');
    }
}
