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
        $brands = Brand::paginate(20, ['*'], 'brands_page');
        $models = CarModel::with('brand')->paginate(20, ['*'], 'models_page');
        $fuels = Fuel::paginate(20, ['*'], 'fuels_page');
        $transmissions = Transmission::paginate(20, ['*'], 'transmissions_page');
        $bodyTypes = BodyType::paginate(20, ['*'], 'body_types_page');
        $tags = Tag::paginate(30, ['*'], 'tags_page');

        $activeTab = $request->input('active_tab', 'brands');

        return view('admin.dictionaries', compact(
            'brands', 'models', 'fuels', 'transmissions', 'bodyTypes', 'tags', 'activeTab'
        ));
    }
}
