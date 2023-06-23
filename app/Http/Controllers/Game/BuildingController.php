<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Blueprint;
use App\Models\Building;
use App\Models\Cell;
use App\Models\Hero;
use App\Services\CellService;
use App\Services\HeroService;
use App\Services\TalentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    public function create(Request $request)
    {
        $cell_id = $request->input('cell_id');
        $blueprint_id = $request->input('blueprint_id');
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $blueprint = Blueprint::find($blueprint_id);
        if ($blueprint->cost_coins <= $hero->coins) {
            HeroService::removeCoins($hero, $blueprint->cost_coins);
            TalentService::increaseProgress($hero->id, 2);
            $building = Building::create(
                [
                    'cell_id'=>$cell_id,
                    'blueprint_id'=>$blueprint_id,
                    'name'=>$blueprint->name,
                    'emoji'=>$blueprint->emoji,
                    'category'=>$blueprint->subcategory,
                    'type'=>$blueprint->type,
                    'creator_hero_id'=>$hero->id,
                    'completed_at'=>Carbon::now()->addSeconds($blueprint->creation_duration),
                ]
            );
            CellService::addObject($cell_id, $building->name, $building->emoji, 'Building', $building->id, 10, true, 50, $hero->id);
            $data = [
                'success'=>true,
                'blueprint'=>$blueprint,
            ];
        } else {
            $data = [
                'success'=>false,
            ];
        }

        return $data;
    }

    public function destroy($cell_id)
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $cell = Cell::find($cell_id);
        DB::transaction(function () use ($cell) {
            $building = Building::where('cell_id', $cell->id)->first();
            CellService::removeObject($cell->id, 'Building', $building->id);
            $building->delete();
        });

        $data = [
            'cell'=>$cell,
            ];

        return $data;
    }
}
