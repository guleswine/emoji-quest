<?php

namespace App\Repositories;

use App\Models\Cell;
use App\Models\Hero;
use App\Models\Map;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MapRepository
{

    public static function getMap($id){
        return Map::find($id);
    }

    public static function getMapByKey($key){
        return Map::where('key',$key)->first();
    }

    public static function getRelativeCell($map,$side='center',$offset_x=0,$offset_y=0){
        switch ($side) {
            case 'center':
                $cell_x = round($map->size_width / 2)-1;
                $cell_y = round($map->size_height / 2)-1;
                break;
            case 'left':
                $cell_x = 0;
                $cell_y = round($map->size_height / 2)-1;
                break;
            case 'right':
                $cell_x = $map->size_width-1;
                $cell_y = round($map->size_height / 2)-1;
                break;
            case 'top':
                $cell_x = round($map->size_width / 2)-1;
                $cell_y = $map->size_height-1;
                break;
            case 'bottom':
                $cell_x = round($map->size_width / 2)-1;
                $cell_y = 0;
                break;
            default:
                $cell_x = round($map->size_width / 2)-1;
                $cell_y = round($map->size_height / 2)-1;
        }
        $cell_x +=$offset_x;
        $cell_y +=$offset_y;
        $cell = Cell::where('map_id', $map->id)
            ->where('x',$cell_x )
            ->where('y', $cell_y)
            ->first();
        return $cell;
    }

    public static function getFormatedCells($map_id, $x, $y, $radius_x, $radius_y,$hero_id=null)
    {
        $cells = DB::select(
            "
        SELECT * FROM (SELECT c.id,c.name, case when co.use_as_background  then co.emoji else  c.emoji end as emoji,c.size, c.x, c.y,
                              c.surface_type,c.transfer_to_cell_id,
                              co.id as cell_object_id,
                      co.name as object_name,
                      co.object_class as object_class,
                        co.size as object_size,
                        co.type as object_type,
                        case when co.use_as_background then null else co.emoji end as object_emoji,
                        co.object_id as object_id,
                        co.creator_hero_id as object_creator_hero_id,
                        case when co.object_class = 'Hero' and co.object_id = :hero_id then 'i'
                            when co.creator_hero_id = :hero_id then 'owner'
                            else '' end as attitude,
                      greatest(trunc(EXTRACT(EPOCH FROM (b.completed_at-current_timestamp))),0) as building_animation,
                      ROW_NUMBER() OVER (PARTITION BY (c.id) ORDER BY co.priority DESC) row_numb
               FROM cells c
                        left join cell_objects co on c.id = co.cell_id
                        left join buildings b on c.id = b.cell_id
               where c.map_id = :map_id and c.x between :left_x and :right_x and c.y between :bottom_y and :top_y) t
        where row_numb = 1
        order by y desc, x asc",
            [
                'map_id'=>$map_id,
                'left_x'=>$x - $radius_x,
                'right_x'=>$x + $radius_x,
                'bottom_y'=>$y - $radius_y,
                'top_y'=>$y + $radius_y,
                'hero_id'=>$hero_id,
        ]
        );

        return $cells;

    }

    public static function getFormatedCell($cell_id, $hero_id=null)
    {
        $cell = DB::selectOne(
            "
        SELECT * FROM (SELECT c.id,c.name, case when co.use_as_background then co.emoji else  c.emoji end as emoji,c.size, c.x, c.y,
                              c.surface_type,c.transfer_to_cell_id,
                              co.id as cell_object_id,
                      co.name as object_name,
                      co.object_class as object_class,
                        co.size as object_size,
                        co.type as object_type,
                        case when co.use_as_background then null else co.emoji end as object_emoji,
                        co.object_id as object_id,
                        co.creator_hero_id as object_creator_hero_id,
                        case when co.object_class = 'Hero' and co.object_id = :hero_id then 'i'
                            when co.creator_hero_id = :hero_id then 'owner'
                            else '' end as attitude,
                      greatest(trunc(EXTRACT(EPOCH FROM (b.completed_at-current_timestamp))),0) as building_animation,

                      ROW_NUMBER() OVER (PARTITION BY (c.id) ORDER BY co.priority DESC) row_numb
               FROM cells c
                        left join cell_objects co on c.id = co.cell_id
                        left join buildings b on c.id = b.cell_id
               where c.id = :cell_id) t
        where row_numb = 1",
            [
                'cell_id'=>$cell_id,
                'hero_id'=>$hero_id,
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
