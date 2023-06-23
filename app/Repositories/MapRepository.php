<?php

namespace App\Repositories;

use App\Models\Cell;
use App\Models\Hero;
use App\Models\Map;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MapRepository
{
    public function getCellsOld()
    {
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        $cell = Cell::find($hero->cell_id);
        $map = Map::find($cell->map_id);
        //$items = Cell::whereBetween('y',[$cell->y-10,$cell->y+10])->whereBetween('x',[$cell->x-10,$cell->x+10])->orderByDesc('y')->orderBy('x')->get();
        $size_width_coeff = 10;
        if ($map->size_width <= 21) {
            $size_width_coeff = 21;
        }
        $size_height_coeff = 10;
        if ($map->size_height <= 21) {
            $size_height_coeff = 21;
        }
        $cells = DB::table('cells')
            ->leftJoin('heroes', 'heroes.cell_id', '=', 'cells.id')
            ->leftJoin('units', 'units.cell_id', '=', 'cells.id')
            ->leftJoin('buildings', 'buildings.cell_id', '=', 'cells.id')
            ->leftJoin('cell_objects', 'cells.id', '=', 'cell_objects.cell_id')
            ->selectRaw('cells.id,cells.x,cells.y,cells.transfer_to_cell_id,cells.surface_type,
            units.question_id,
            case
                when  units.type=\'enemy\' then units.current_health
                when  buildings.id is not null then buildings.strength
            end as object_attribute,
            cells.size,
            buildings.creator_hero_id as building_creator_hero_id,
            greatest(trunc(EXTRACT(EPOCH FROM (completed_at-current_timestamp))),0) as building_animation,
            cells.name,
            coalesce(buildings.emoji,cells.emoji) as emoji,
            cell_objects.entity_class as object_class,
            cells.object_class,
            cell_objects.size as object_size,

            cell_objects.name  as object_name,
            cell_objects.entity_id as object_id,
            cell_objects.type  as object_type,
            case when cells.object_class =\'building\' then null else cell_objects.emoji end as object_emoji')
            ->whereBetween('cells.y', [$cell->y - $size_height_coeff, $cell->y + $size_height_coeff])
            ->whereBetween('cells.x', [$cell->x - $size_width_coeff, $cell->x + $size_width_coeff])
            ->where('cells.map_id', $cell->map_id)
            ->orderByDesc('cells.y')->orderBy('cells.x')
            ->get();

        return $cells;
    }

    public static function getCells($map_id, $x, $y, $radius_x, $radius_y)
    {
        $cells = DB::select(
            '
        SELECT * FROM (SELECT c.id,c.name, c.emoji,c.size, c.x, c.y, c.surface_type,c.transfer_to_cell_id,
                              co.id as cell_object_id,
                      co.name as object_name,
                      co.object_class as object_class,
                        co.size as object_size,
                        co.type as object_type,
                        co.emoji as object_emoji,
                        co.object_id as object_id,
                        co.creator_hero_id as object_creator_hero_id,
                      greatest(trunc(EXTRACT(EPOCH FROM (b.completed_at-current_timestamp))),0) as building_animation,
                      ROW_NUMBER() OVER (PARTITION BY (c.id) ORDER BY co.priority DESC) row_numb
               FROM cells c
                        left join cell_objects co on c.id = co.cell_id
                        left join buildings b on c.id = b.cell_id
               where c.map_id = :map_id and c.x between :left_x and :right_x and c.y between :bottom_y and :top_y) t
        where row_numb = 1
        order by y desc, x asc',
            [
                'map_id'=>$map_id,
                'left_x'=>$x - $radius_x,
                'right_x'=>$x + $radius_x,
                'bottom_y'=>$y - $radius_y,
                'top_y'=>$y + $radius_y,
        ]
        );

        return $cells;

    }

    public static function getCell($cell_id)
    {
        $cell = DB::selectOne(
            '
        SELECT * FROM (SELECT c.id,c.name, c.emoji,c.size, c.x, c.y, c.surface_type,c.transfer_to_cell_id,
                              co.id as cell_object_id,
                      co.name as object_name,
                      co.object_class as object_class,
                        co.size as object_size,
                        co.type as object_type,
                        co.emoji as object_emoji,
                        co.object_id as object_id,
                        co.creator_hero_id as object_creator_hero_id,
                      greatest(trunc(EXTRACT(EPOCH FROM (b.completed_at-current_timestamp))),0) as building_animation,
                      ROW_NUMBER() OVER (PARTITION BY (c.id) ORDER BY co.priority DESC) row_numb
               FROM cells c
                        left join cell_objects co on c.id = co.cell_id
                        left join buildings b on c.id = b.cell_id
               where c.id = :cell_id) t
        where row_numb = 1',
            [
                'cell_id'=>$cell_id,
            ]
        );

        return $cell;
    }

    public static function getGlobalCells()
    {
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        $cell = Cell::find($hero->cell_id);
        $map = Map::find($cell->map_id);
        $radius_x = 20;
        if ($map->size_width <= 41) {
            $radius_x = 41;
        }
        $radius_y = 20;
        if ($map->size_height <= 41) {
            $radius_y = 41;
        }
        $cells = DB::table('cells')
            ->whereBetween('cells.y', [$cell->y - $radius_y, $cell->y + $radius_y])
            ->whereBetween('cells.x', [$cell->x - $radius_x, $cell->x + $radius_x])
            ->where('cells.map_id', $cell->map_id)
            ->orderByDesc('cells.y')->orderBy('cells.x')
            ->get();

        return $cells;
    }
}
