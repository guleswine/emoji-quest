<?php

namespace App\Services;

use App\Models\Map;
use App\Models\SurfaceTemplate;
use App\Repositories\CellRepository;
use App\Repositories\MapRepository;
use Illuminate\Support\Facades\Log;

class MapGeneratorService
{


    public static function fillCircleOnMapRegion(int|Map $map, $start_cell,$radius,$surface_template_keys){
        if(is_int($map)){
            $map = MapRepository::getMap($map);
        }
        $surface_templates = SurfaceTemplate::whereIn('key',$surface_template_keys)->get();
        for($i = $start_cell->x-$radius; $i <= $start_cell->x+$radius; $i++){
            for($j = $start_cell->y-$radius; $j <= $start_cell->y+$radius; $j++){
                if (MapService::distanceBetweenCoords($i,$j,$start_cell->x,$start_cell->y)<=$radius){
                    $cell = CellRepository::getCell($map->id,$i,$j);
                    $surface_template = $surface_templates->random();
                    $cell->update([
                        'name'=> $surface_template->name,
                        'emoji'=> $surface_template->emoji,
                        'surface_type'=> $surface_template->type,
                        'size'=> $surface_template->size,
                    ]);
                }

            }
        }
    }

    public static function fillRectangleOnMapRegion(int|Map $map, $left_bottom_cell,$right_top_cell,$surface_template_keys,$hollow = false){
        if(is_int($map)){
            $map = MapRepository::getMap($map);
        }

        $surface_templates = SurfaceTemplate::whereIn('key',$surface_template_keys)->get();
        for($i = $left_bottom_cell->x; $i <= $right_top_cell->x; $i++){
            for($j = $left_bottom_cell->y; $j <= $right_top_cell->y; $j++){
                    if($hollow){
                        if (($i <>  $left_bottom_cell->x and $i <> $right_top_cell->x)
                            and ($j <>  $left_bottom_cell->y and $j <> $right_top_cell->y))
                            continue;
                    }
                    $cell = CellRepository::getCell($map->id,$i,$j);
                    $surface_template = $surface_templates->random();
                    $cell->update([
                        'name'=> $surface_template->name,
                        'emoji'=> $surface_template->emoji,
                        'surface_type'=> $surface_template->type,
                        'size'=> $surface_template->size,
                    ]);

            }
        }
    }

    public static function fillMapRegion($map, $start_cell, $figure,$height,$width,$surface_template_keys){



    }

}
