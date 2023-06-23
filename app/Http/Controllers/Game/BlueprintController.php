<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlueprintController extends Controller
{
    public function getBlueprints($category, $subcategory)
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $data = DB::table('hero_blueprints')
            ->join('blueprints', 'blueprints.id', '=', 'hero_blueprints.blueprint_id')
            ->where('hero_blueprints.category', $category)
            ->where('hero_blueprints.subcategory', $subcategory)
            ->where('hero_blueprints.hero_id', $hero->id)
            ->selectRaw('blueprints.id, blueprints.emoji, blueprints.name, blueprints.description, blueprints.creation_duration, blueprints.cost_coins')
            ->orderBy('hero_blueprints.created_at')->orderBy('hero_blueprints.id')
            ->get();

        return response()->json($data);
    }
}
