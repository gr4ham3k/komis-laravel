<?php

namespace App\Http\Controllers\Admin\Dictionary;

use App\Http\Controllers\Controller;
use App\Models\Transmission;
use Illuminate\Http\Request;

class TransmissionController extends Controller
{
    public function store(Request $request)
    {
        $request -> validate([
            'name' => 'required|string|max:255'
        ]);

        Transmission::create([
            'name' => $request->name
        ]);

         return redirect()
            ->back()
            ->with('activeTab', 'transmissions')
            ->with('success', 'Dodano skrzynię biegów');
    }

    public function update(Request $request, $id)
    {
        $transmission = Transmission::findOrFail($id);

        $request -> validate([
            'name' => 'required|string|max:255'
        ]);

        $transmission -> update([
            'name' => $request -> name
        ]);

        return redirect()
            ->back()
            ->with('activeTab', 'transmissions')
            ->with('success', 'Zmodyfikowano skrzynię biegów');
    }

    public function destroy($id)
    {
        $transmission = Transmission::findOrFail($id);

        $transmission -> delete();

        return redirect()
            ->back()
            ->with('activeTab', 'transmissions')
            ->with('success', 'Usunięto skrzynię biegów');
    }
}
