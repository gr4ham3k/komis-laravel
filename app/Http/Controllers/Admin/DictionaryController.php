<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Tag;
use App\Models\Transmission;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::when($s = $request->input('brands_search'), fn($q) => $q->where('name', 'ilike', "%{$s}%"))
            ->paginate(20, ['*'], 'brands_page')
            ->withQueryString();

        $models = CarModel::with('brand')
            ->when($s = $request->input('models_search'), fn($q) => $q->where(function($qq) use ($s) {
                $qq->where('name', 'ilike', "%{$s}%")
                   ->orWhereHas('brand', fn($b) => $b->where('name', 'ilike', "%{$s}%"));
            }))
            ->paginate(20, ['*'], 'models_page')
            ->withQueryString();

        $fuels = Fuel::when($s = $request->input('fuels_search'), fn($q) => $q->where('name', 'ilike', "%{$s}%"))
            ->paginate(20, ['*'], 'fuels_page')
            ->withQueryString();

        $transmissions = Transmission::when($s = $request->input('transmissions_search'), fn($q) => $q->where('name', 'ilike', "%{$s}%"))
            ->paginate(20, ['*'], 'transmissions_page')
            ->withQueryString();

        $bodyTypes = BodyType::when($s = $request->input('body_types_search'), fn($q) => $q->where('name', 'ilike', "%{$s}%"))
            ->paginate(20, ['*'], 'body_types_page')
            ->withQueryString();

        $tags = Tag::when($s = $request->input('tags_search'), fn($q) => $q->where('name', 'ilike', "%{$s}%"))
            ->paginate(30, ['*'], 'tags_page')
            ->withQueryString();

        $activeTab = $request->input('active_tab', session('activeTab', 'brands'));

        return view('admin.dictionaries', compact(
            'brands', 'models', 'fuels', 'transmissions', 'bodyTypes', 'tags', 'activeTab'
        ));
    }
}
