<?php

namespace App\Http\Controllers\Admin\Dictionary;

use App\Http\Controllers\Controller;
use App\Models\BodyType;
use Illuminate\Http\Request;

class BodyTypeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        BodyType::create([
            'name' => $request->name
        ]);

        return redirect()
            ->back()
            ->with('activeTab', 'bodytypes')
            ->with('success', 'Dodano nadwozie');
    }

    public function update(Request $request, $id)
    {
        $bodytype = BodyType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $bodytype->update([
            'name' => $request->name
        ]);

        return redirect()
            ->back()
            ->with('activeTab', 'bodytypes')
            ->with('success', 'Zmodyfikowano nadwozie');
    }

    public function destroy($id)
    {
        $bodytype = BodyType::findOrFail($id);

        $bodytype->delete();

        return redirect()
            ->back()
            ->with('activeTab', 'bodytypes')
            ->with('success', 'Usunięto nadwozie');
    }
}
