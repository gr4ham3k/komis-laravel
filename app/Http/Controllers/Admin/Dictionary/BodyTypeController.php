<?php

namespace App\Http\Controllers\Admin\Dictionary;

use App\Http\Controllers\Controller;
use App\Models\BodyType;
use Illuminate\Http\Request;

class BodyTypeController extends Controller
{
    public function store(Request $request)
    {
        $request -> validate([
            'name' => 'required|string|max:255'
        ]);

        BodyType::create([
            'name' => $request->name
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $brand = BodyType::findOrFail($id);

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
        $brand = BodyType::findOrFail($id);

        $brand -> delete();

        return back();
    }
}
