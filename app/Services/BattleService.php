<?php

namespace App\Services;

use App\Events\BattleFinished;
use App\Events\BattleQueueMoved;
use App\Events\HeroDead;
use App\Events\UnitMoved;
use App\Events\UpdateCell;
use App\Jobs\EnemyMove;
use App\Models\Battle;
use App\Models\BattleQueueUnit;
use App\Models\Cell;
use App\Models\Enemy;
use App\Models\Hero;
use App\Models\HeroStat;
use App\Models\Item;
use App\Models\Map;
use App\Models\Unit;
use App\Repositories\MapRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BattleService
{
    public static function setHeroDefence($hero, $area, $queue_unit)
    {
        $queue_unit->protected_area = $area;
        $queue_unit->save();
        self::HeroStepFinish($hero);
    }

    public static function startBattle($hero)
    {
        $hero->state_name = 'battle';
        $cell = Cell::find($hero->cell_id);
        $battle = Battle::create(['map_id'=>$cell->map_id]);
        $hero->state_object_id = $battle->id;
        $hero->save();
        $order = 1;
        $hero_stats = HeroStat::where('hero_id', $hero->id)->get()->keyBy('name');
        BattleQueueUnit::create(
            [
                'battle_id'=>$battle->id,
                'object_name'=>'hero',
                'type'=>'hero',
                'object_id'=>$hero->id,
                'order'=>$order,
                'emoji_name'=>$hero->emoji,
                'health'=>$hero_stats['health']->current,
                'action_points'=>$hero_stats['action_points']->current,
                'name'=>$hero->name,
            ]
        );

        $enemies = self::searchNearEnemy($cell);
        foreach ($enemies as $unit) {
            $order++;
            $enemy = Enemy::find($unit->object_id);
            BattleQueueUnit::create(
                [
                    'battle_id'=>$battle->id,
                    'object_name'=>'unit',
                    'type'=>'enemy',
                    'object_id'=>$unit->id,
                    'order'=>$order,
                    'emoji_name'=>$unit->emoji,
                    'health'=>$enemy->health,
                    'action_points'=>$enemy->action_points,
                    'name'=>$unit->name,
                ]
            );
        }
    }

    public static function searchNearEnemy($cell)
    {
        $map = Map::find($cell->map_id);
        $radius_x = 10;
        if ($map->size_width <= 21) {
            $radius_x = 21;
        }
        $radius_y = 10;
        if ($map->size_height <= 21) {
            $radius_y = 21;
        }
        $enemies = DB::table('cells')
            ->join('units', 'units.cell_id', '=', 'cells.id')
            ->whereBetween('cells.y', [$cell->y - $radius_y, $cell->y + $radius_y])
            ->whereBetween('cells.x', [$cell->x - $radius_x, $cell->x + $radius_x])
            ->where('units.type', 'enemy')
            ->where('cells.map_id', $cell->map_id)
            ->selectRaw('units.*')
            ->get();

        return $enemies;
    }

    public static function HeroStepFinish(Hero $hero)
    {
        if (!$hero->state_name == 'battle') {
            return;
        }
        $battle_id = $hero->state_object_id;
        $queues = BattleQueueUnit::where('battle_id', $battle_id)->orderBy('order')->get();
        if (blank($queues)) {
            return;
        }
        $fighter = $queues->first();
        if ($fighter->object_name == 'hero' and $fighter->object_id == $hero->id) {
            $final_ap = $hero->getFinalStat('action_points');
            $hero->updateCurrentStat('action_points', $final_ap);
            $fighter->actiion_points = $final_ap;
            //$fighter->protected_area = null;
        }
        $new_queue = self::moveQueue($battle_id);
        $next_fighter = $new_queue->first();
        if ($next_fighter->type == 'enemy') {
            EnemyMove::dispatch($battle_id)->delay(now()->addSeconds(5));
        }

    }

    public static function sendMoveEnemyEventForHeroes($queue_fighters, $path, $cell_with_object): void
    {

        foreach ($queue_fighters as $fighter) {
            if ($fighter->object_name == 'hero') {
                $hero = Hero::find($fighter->object_id);
                UnitMoved::dispatch($hero->id, $path, $cell_with_object);
            }
        }
    }

    public static function sendBattleQueueMovedEventForHeroes($queue_fighters)
    {

        foreach ($queue_fighters as $fighter) {
            if ($fighter->object_name == 'hero') {
                $hero = Hero::find($fighter->object_id);
                BattleQueueMoved::dispatch($hero->id);
            }
        }
    }

    public static function enemyMove($queue_fighters)
    {
        $first_fighter = $queue_fighters->first();
        $unit = Unit::find($first_fighter->object_id);
        $targets = collect();
        $action_points = $first_fighter->action_points;
        if ($unit->type == 'enemy') {
            foreach ($queue_fighters as $qf) {
                if ($qf->object_name == 'hero') {
                    $hero = Hero::find($qf->object_id);
                    $PS = new PathSearchService();
                    $PS->setStartCellById($unit->cell_id)
                        ->setFinishCellById($hero->cell_id)
                        ->loadCells()
                        ->setCellRadius(1);
                    $path = $PS->getShortestPath();
                    $targets->push([
                        'queue_unit'=>$qf,
                        'hero'=>$hero,
                        'path'=>$path,
                    ]);
                }
            }
            if (blank($targets)) {
                self::finishBattle($first_fighter->battle_id);

                return;
            }
            $filtered_targets = $targets->filter(function ($value, int $key) {
                return !blank($value['path']);
            });
            $sorted_targets = $filtered_targets->sortBy(function ($value, int $key) {
                return $value['path']->count();
            });
            $target = $sorted_targets->first();
            if (!$target) {
                return;
            }
            $hero = $target['hero'];
            if ($target['path']->count() > 1) {
                if ($action_points >= ($target['path']->count() - 1)) {
                    $path = $target['path'];
                    $finish_cell = $path->last();
                    UnitService::updateCell($unit, $finish_cell->id);
                    $action_points -= ($target['path']->count() - 1);
                } else {
                    $path = $target['path']->take($action_points + 1);
                    $finish_cell = $path->last();
                    UnitService::updateCell($unit, $finish_cell->id);
                    $action_points = 0;
                }
                $cell_with_object = MapRepository::getCell($unit->cell_id);
                self::sendMoveEnemyEventForHeroes($queue_fighters, $path->pluck('id'), $cell_with_object);
            }

            if ($action_points > 1) {
                $enemy = Enemy::find($unit->object_id);
                while ($action_points > 1) {
                    //sleep(1);
                    $action_points -= 2;
                    $dead = AttackService::attackHeroByEnemy($enemy, $unit, $hero, $queue_fighters);
                    if ($dead) {
                        break;
                    }
                }
            }

        }

    }

    public static function enemyDead($unit, $battle_queue_unit)
    {
        $battle_id = $battle_queue_unit->battle_id;
        if ($unit->drop_item_id) {
            $item = Item::find($unit->drop_item_id);
            CellService::addObject($unit->cell_id, $item->name, $item->emoji, 'Item', $item->id, $item->size, false, 50);
        }
        CellService::removeObject($unit->cell_id, 'Unit', $unit->id);
        $hero_ids = $battle_queue_unit->where('object_name', 'hero')->pluck('object_id');
        $cell_with_object = MapRepository::getCell($unit->cell_id);
        UpdateCell::dispatch($hero_ids, $cell_with_object);
        $unit->delete();
        $battle_queue_unit->delete();
        $queues = BattleQueueUnit::where('battle_id', $battle_id)->orderBy('order')->get();
        $other_hero = $queues->firstWhere('object_name', 'unit');
        if (!$other_hero) {
            self::finishBattle($battle_id);
        }
    }

    public static function heroDead($hero)
    {
        $battle_id = $hero->state_object_id;
        $queue_unit = BattleQueueUnit::where('object_name', 'hero')->where('object_id', $hero->id)->where('battle_id', $battle_id)->first();
        $queue_unit->delete();
        $new_cell_id = Arr::random([498005, 498503, 498504]);
        $hero->state_name = 'traveler';
        $hero->state_object_id = null;
        HeroService::setCell($hero, $new_cell_id);
        $hero->save();
        $ap = $hero->getFinalStat('action_points');
        $hero->updateCurrentStat('action_points', $ap);
        $hero->updateCurrentStat('health', 1);
        HeroDead::dispatch($hero->id);
        $queues = BattleQueueUnit::where('battle_id', $battle_id)->orderBy('order')->get();
        $other_hero = $queues->firstWhere('object_name', 'hero');
        if (!$other_hero) {
            self::finishBattle($battle_id);
        }
    }

    public static function finishBattle($battle_id)
    {
        $queues = BattleQueueUnit::where('battle_id', $battle_id)->orderBy('order')->get();
        foreach ($queues as $queue) {
            if ($queue->object_name == 'unit') {
                $unit = Unit::find($queue->object_id);
                $enemy = Enemy::find($unit->object_id);
                $unit->current_health = $enemy->health;
                $unit->save();
            } elseif ($queue->object_name == 'hero') {
                $hero = Hero::find($queue->object_id);
                $hero->state_name = 'traveler';
                $hero->state_object_id = null;
                $hero->save();
                $ap = $hero->getFinalStat('action_points');
                $hero->updateCurrentStat('action_points', $ap);
                BattleFinished::dispatch($hero->id);
            }
            $queue->delete();
        }
        $battle = Battle::find($battle_id);
        $battle->delete();
    }

    public static function moveQueue($battle_id)
    {
        $queues = BattleQueueUnit::where('battle_id', $battle_id)->orderBy('order')->get();
        if (blank($queues)) {
            return $queues;
        }
        $chunk = $queues->splice(0, 1);
        $fighter = $chunk->first();
        $next_fighter = $queues->first();
        $next_fighter->protected_area = null;
        if ($fighter->object_name == 'hero') {
            $HS = HeroStat::where('name', 'action_points')->where('hero_id', $fighter->object_id)->first();
            $HS->update(['current'=>$HS->final]);
            $fighter->action_points = $HS->final;
        //$fighter->protected_area = null;
        } elseif ($fighter->object_name == 'unit' and $fighter->type == 'enemy') {
            $unit = Unit::find($fighter->object_id);
            $enemy = Enemy::find($unit->object_id);
            $fighter->action_points = $enemy->action_points;
        }
        $queues->push($fighter);
        $i = 0;
        foreach ($queues as $queue) {
            $i++;
            $queue->order = $i;
            $queue->save();
        }
        self::sendBattleQueueMovedEventForHeroes($queues);

        return $queues;
    }

    public static function checkPathToCell($start_cell, $finish_cell)
    {

    }
}
