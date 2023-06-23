<?php

namespace App\Services;

use App\Models\Cell;
use App\Models\Enemy;
use App\Models\Unit;
use App\Models\WorldControllerEvent;
use Illuminate\Support\Facades\DB;

class WorldService
{
    public static function execute($world_controller)
    {
        $CS = new ConditionService(null);
        if ($world_controller->condition_group_id) {
            $res = $CS->execute($world_controller->condition_group_id);
            if (!$res) {
                return;
            }
        }
        $events = WorldControllerEvent::where('world_controller_id', $world_controller->id)->get();
        foreach ($events as $event) {
            $function = $event->function;
            self::$function($event->params);
        }
    }

    /**
     * @param array  $params - cell_id, radius_size
     */
    public static function removeItemsOnArea(array $params): void
    {
        $cell_id = $params['cell_id'];
        $radius_size = $params['radius_size'];
        $start_cell = Cell::find($cell_id);
        $objects = DB::table('cells')
            ->join('cell_objects', 'cell_objects.cell_id', '=', 'cells.id')
            ->where('cells.map_id', $start_cell->map_id)
            ->whereBetween('cells.y', [$start_cell->y - $radius_size, $start_cell->y + $radius_size])
            ->whereBetween('cells.x', [$start_cell->x - $radius_size, $start_cell->x + $radius_size])
            ->where('cell_objects.object_class', 'Item')
            ->selectRaw('cell_objects.*')
            ->get();
        foreach ($objects as $object) {
            CellService::removeObject($object->cell_id, $object->object_class, $object->object_id);
        }
    }

    /**
     * @param array  $params - cell_id, radius_size, enemy_id
     */
    public static function spawnEnemyOnArea(array $params): void
    {
        $enemy_id = $params['enemy_id'];
        $cell_id = $params['cell_id'];
        $radius_size = $params['radius_size'];
        $cell = Cell::find($cell_id);
        $free_cell = Cell::where('map_id', $cell->map_id)
            ->leftJoin('cell_objects', 'cell_objects.cell_id', '=', 'cells.id')
            ->whereBetween('y', [$cell->y - $radius_size, $cell->y + $radius_size])
            ->whereBetween('x', [$cell->x - $radius_size, $cell->x + $radius_size])
            ->whereNull('cell_objects.id')
            ->selectRaw('cells.*')
            ->inRandomOrder()
            ->first();
        if ($free_cell) {
            $enemy = Enemy::find($enemy_id);
            $unit = Unit::create([
                'name'=>$enemy->name,
                'emoji'=>$enemy->emoji,
                'cell_id'=>$free_cell->id,
                'size'=>8,
                'type'=>'enemy',
                'object_id'=>$enemy->id,
                'current_health'=>$enemy->health,
                'drop_item_id'=>$enemy->drop_item_id,
            ]);
            CellService::addObjectUnit($unit);
        }

    }
}
