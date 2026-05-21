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

        return back();
    }

    public function update(Request $request, $id)
    {
        $brand = Transmission::findOrFail($id);

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
        $brand = Transmission::findOrFail($id);

        $brand -> delete();

        return back();
    }
}
