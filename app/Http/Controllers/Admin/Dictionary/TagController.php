<?php

namespace App\Http\Controllers\Admin\Dictionary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Tag::create([
            'name' => $request->name,
        ]);

         return redirect()
            ->back()
            ->with('activeTab', 'tags')
            ->with('success', 'Dodano tag');
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag->update([
            'name' => $request->name,
        ]);

         return redirect()
            ->back()
            ->with('activeTab', 'tags')
            ->with('success', 'Zmodyfikowano tag');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);

        $tag->delete();

         return redirect()
            ->back()
            ->with('activeTab', 'tags')
            ->with('success', 'Usunięto tag');
    }
}
