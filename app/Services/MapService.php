<?php

namespace App\Services;

use App\Enums\RelativeSide;
use App\Enums\Side;
use App\Enums\SideEnum;
use App\Events\GameEvent;
use App\Events\UnitMoved;
use App\Models\BattleQueueUnit;
use App\Models\Cell;
use App\Models\Hero;
use App\Models\HeroVisitedMap;
use App\Models\Map;
use App\Repositories\CellRepository;
use App\Repositories\MapRepository;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MapService
{

    public static function getRelativeCell(string|Map $map, $side,$offset_x=0,$offset_y=0){
        if(is_string($map)){
            $map = MapRepository::getMapByKey($map);
        }
        switch ($side) {
            case SideEnum::Center:
                $cell_x = round($map->size_width / 2)-1;
                $cell_y = round($map->size_height / 2)-1;
                break;
            case SideEnum::Left:
                $cell_x = 0;
                $cell_y = round($map->size_height / 2)-1;
                break;
            case SideEnum::Right:
                $cell_x = $map->size_width-1;
                $cell_y = round($map->size_height / 2)-1;
                break;
            case SideEnum::Top:
                $cell_x = round($map->size_width / 2)-1;
                $cell_y = $map->size_height-1;
                break;
            case SideEnum::Bottom:
                $cell_x = round($map->size_width / 2)-1;
                $cell_y = 0;
                break;
            case SideEnum::LeftBottom:
                $cell_x  = 0;
                $cell_y = 0;
                break;
            case SideEnum::LeftTop:
                $cell_x  = 0;
                $cell_y = $map->size_height-1;
                break;
            case SideEnum::RightBottom:
                $cell_x  = $map->size_width-1;
                $cell_y = 0;
                break;
            case SideEnum::RightTop:
                $cell_x  = $map->size_width-1;
                $cell_y = $map->size_height-1;
                break;
            default:
                $cell_x = round($map->size_width / 2)-1;
                $cell_y = round($map->size_height / 2)-1;
        }
        $cell_x +=$offset_x;
        $cell_y +=$offset_y;
        $cell = CellRepository::getCell($map->id,$cell_x,$cell_y);
        return $cell;
    }

    public static function rangeBetweenCoords($x1, $y1, $x2, $y2){
        $abs_y = abs($y1 - $y2);
        $abs_x = abs($x1 - $x2);
        $range = $abs_y + $abs_x;

        return $range;
    }

    public static function distanceBetweenCoords($x1, $y1, $x2, $y2){
        $sqr_x = pow($x1 - $x2, 2);
        $sqr_y = pow($y1 - $y2, 2);
        $distance = sqrt($sqr_x + $sqr_y);
        return $distance;
    }

    public function checkCellForMove($cell_id): array
    {
        $cell = Cell::find($cell_id);
        if ($cell->surface_type_name == 'impassable' or $cell->object_name == 'building') {
            $notify = ['type'=>'info', 'message'=>'The field is not available for moving'];

            return ['success'=>false, 'notify'=>$notify];
        }
        $hasHeroes = DB::table('heroes')->where('cell_id', $cell_id)->exists();
        $hasUnits = DB::table('units')->where('cell_id', $cell_id)->exists();
        if (in_array($cell->object_name, ['building', 'hero', 'NPC', 'enemy', 'unit'])) {
            $notify = ['type'=>'info', 'message'=>'The field is occupied by a player/character, it is impossible to go here.'];

            return ['success'=>false, 'notify'=>$notify];
        }

        return ['success'=>true];
    }

    public function searchHeroesNearHero($start_cell, $finish_cell, $hero)
    {
        $heroes = DB::table('cells')
            ->join('heroes', 'heroes.cell_id', '=', 'cells.id')
            ->where('heroes.id', '<>', $hero->id)
            ->where('cells.map_id', $start_cell->map_id)
            ->selectRaw('heroes.*')
            ->where(function (Builder $query) use ($start_cell, $finish_cell) {
                $query->where(function (Builder $sub_query) use ($start_cell) {
                    $sub_query->whereBetween('cells.y', [$start_cell->y - 10, $start_cell->y + 10])
                        ->whereBetween('cells.x', [$start_cell->x - 10, $start_cell->x + 10]);
                })->orWhere(function (Builder $sub_query) use ($finish_cell) {
                    $sub_query->whereBetween('cells.y', [$finish_cell->y - 10, $finish_cell->y + 10])
                        ->whereBetween('cells.x', [$finish_cell->x - 10, $finish_cell->x + 10]);
                });

            })
            ->get();

        return $heroes;
    }

    public function moveToCell($cell_id)
    {
        $user = Auth::user();
        $resp = self::checkCellForMove($cell_id);
        if (!$resp['success']) {
            return ['notify'=>$resp['notify']];
        }
        $hero = Hero::where('user_id', $user->id)->first();
        $hero_cell = Cell::find($hero->cell_id);
        $cell = Cell::find($cell_id);
        $PS = new PathSearchService();
        $PS->setStartCellById($hero->cell_id)->setFinishCellById($cell_id)->loadCells();
        $path = $PS->getShortestPath();
        if (blank($path)) {
            return ['notify'=>['type'=>'info', 'message'=>'Can\'t find a way to this point']];
        }
        if ($hero->state_name == 'battle') {
            $queues = BattleQueueUnit::where('battle_id', $hero->state_object_id)->orderBy('order')->get();
            $fighter = $queues->first();
            $enemies = $queues->firstWhere('object_name', 'unit');
            if (!$enemies) {
                BattleService::finishBattle($hero->state_object_id);
            }
            $isFighterHero = ($fighter->object_name == 'hero' and $fighter->object_id == $hero->id);
            if (!$isFighterHero) {
                return ['notify'=>['type'=>'info', 'message'=>'Now it\'s not your turn to go']];
            }
            $action_points = $hero->getCurrentStat('action_points');
            if ($action_points < ($path->count() - 1)) {
                return ['notify'=>['type'=>'info', 'message'=>'Not enough action points for this path.']];
            }
            $new_ap = $action_points - ($path->count() - 1);
            $hero->updateCurrentStat('action_points', $new_ap);
            if ($new_ap == 0) {
                BattleService::HeroStepFinish($hero);
            }
        }
        $heroes = $this->searchHeroesNearHero($hero_cell, $cell, $hero);
        HeroService::updateCell($hero, $cell->id);
        //$hero_cell->clearObject();
        //$cell->addHero($hero);
        $cell_with_object = MapRepository::getFormatedCell($cell->id,$hero->id);
        if ($heroes) {
            foreach ($heroes as $other_hero) {
                if ($other_hero->updated_at > now()->addHours(-1)) {
                    UnitMoved::dispatch($other_hero->id, $path->pluck('id'), $cell_with_object);
                }
            }
        }
        $map = Map::find($cell->map_id);
        $radius_x = 10;
        if ($map->size_width <= 21) {
            $radius_x = 21;
        }
        $radius_y = 10;
        if ($map->size_height <= 21) {
            $radius_y = 21;
        }
        $repository = new MapRepository();
        $cells = $repository->getFormatedCells($map->id, $cell->x, $cell->y, $radius_x, $radius_y,$hero->id);
        $event = EventService::moveToCell($hero, $cell);
        GameEvent::dispatch($hero->id,$event['emoji'],$event['message']);
        $data = [
            'cells'=>$cells,
            'path'=>$path,
        ];

        return $data;
    }

    public function visitMap($hero, $map)
    {
        $HVM = HeroVisitedMap::where('hero_id', $hero->id)->where('map_id', $map->id)->first();
        if (!$HVM) {
            HeroVisitedMap::create([
                'hero_id'=>$hero->id,
                'map_id'=>$map->id,
            ]);
            TalentService::increaseProgress($hero->id, 4);
        }
    }

    public function transferToCell($cell_id)
    {
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        $cell = Cell::find($cell_id);
        $map = Map::find($cell->map_id);
        $repository = new MapRepository();
        if ($hero->state_name == 'traveler') {
            $state_name = 'traveler';
            HeroService::updateCell($hero, $cell->id);
            $enemy = BattleService::searchNearEnemy($cell);
            \App\Events\HeroVisitedMap::dispatch($hero, $map);
            $radius_x = 10;
            if ($map->size_width <= 21) {
                $radius_x = 21;
            }
            $radius_y = 10;
            if ($map->size_height <= 21) {
                $radius_y = 21;
            }
            if (!blank($enemy)) {

                $state_name = 'battle';
                BattleService::startBattle($hero);
                $data['success'] = true;
                $data['cells'] = $repository->getFormatedCells($map->id, $cell->x, $cell->y, $radius_x, $radius_y, $hero->id);
                $data['state_name'] = $state_name;
                $data['event'] = EventService::transferToCell($hero, $map);
            } else {
                $data['success'] = true;
                $data['cells'] = $repository->getFormatedCells($map->id, $cell->x, $cell->y, $radius_x, $radius_y, $hero->id);
                $data['state_name'] = $state_name;
                $data['event'] = EventService::transferToCell($hero, $map);
            }
        } else {
            $notify = ['type'=>'info', 'message'=>'You can\'t move to another location yet'];
            $data = [
                'success'=>false,
                'notify'=>$notify,
            ];
        }

        return $data;
    }
}
