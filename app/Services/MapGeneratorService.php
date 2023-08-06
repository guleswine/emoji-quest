<?php

namespace App\Services;

use App\Models\Emoji;
use App\Models\Map;
use App\Models\SurfaceTemplate;
use App\Repositories\CellRepository;
use App\Repositories\MapRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MapGeneratorService
{

    public static function loadEmojies(){
        $files = Storage::disk('public')->files('/open_emoji/lite_colored');
        $data = [];
        foreach ($files as $file) {
            $data[] = ['key'=>str_replace('.png','',basename($file)),'img'=>$file];
        }
        Emoji::upsert($data,['key']);
    }

    public static function renderDataVariables($data){
        foreach ($data as $key=>$value) {
            if (is_array($value)){
                foreach ($value as $k=>$v) {
                    if (Str::startsWith($v,'%relative_cell_id%')){
                        $params = explode(' ',$v);
                        $data[$key][$k] = MapService::getRelativeCell($params[1],$params[2],$params[3],$params[4])->id;
                    }
                }
            }else{
                if (Str::startsWith($value,'%relative_cell_id%')){
                    $params = explode(' ',$value);
                    $data[$key] = MapService::getRelativeCell($params[1],$params[2],$params[3],$params[4])->id;
                }
            }
        }
        return $data;
    }

    public static function fillingObjectProcessing($fo){
        switch ($fo['type']){
            case 'rectangle':
                MapGeneratorService::fillRectangleOnMapRegion($fo['map'],
                    MapService::getRelativeCell($fo['map'],$fo['lb_cell']['side'],$fo['lb_cell']['offset_x'],$fo['lb_cell']['offset_y']),
                    MapService::getRelativeCell($fo['map'],$fo['rt_cell']['side'],$fo['rt_cell']['offset_x'],$fo['rt_cell']['offset_y']),
                    $fo['templates'],$fo['hollow'] ?? false);
                break;
            case 'single':
                if(isset($fo['extra'])) $fo['extra'] = MapGeneratorService::renderDataVariables($fo['extra']);

                MapGeneratorService::fillSingleCell(MapService::getRelativeCell($fo['map'],$fo['side'],
                    $fo['offset_x'],$fo['offset_y']),$fo['templates'],$fo['extra'] ?? []);
                break;
            case 'circle':
                MapGeneratorService::fillCircleOnMapRegion($fo['map'],
                    MapService::getRelativeCell($fo['map'],$fo['side'],$fo['offset_x'],$fo['offset_y']),
                    $fo['radius'],$fo['templates']);
                break;

        }
    }

    public static function fillSingleCell($cell,$surface_template_keys,$extra_attributes = []){
        $surface_templates = SurfaceTemplate::whereIn('key',$surface_template_keys)->get();
        $surface_template = $surface_templates->random();
        $default = [
            'name'=> $surface_template->name,
            'emoji'=> $surface_template->emoji,
            'surface_type'=> $surface_template->type ?? $cell->surface_type,
            'size'=> $surface_template->size,
        ];
        $data = array_merge($default, $extra_attributes);
        CellRepository::updateCell($cell, $data);
    }
    public static function fillCircleOnMapRegion(string|Map $map, $start_cell,$radius,$surface_template_keys){
        if(is_string($map)){
            $map = MapRepository::getMapByKey($map);
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
                        'surface_type'=> $surface_template->type ?? $cell->surface_type,
                        'size'=> $surface_template->size,
                    ]);
                }

            }
        }
    }

    public static function fillRectangleOnMapRegion(string|Map $map, $left_bottom_cell,$right_top_cell,$surface_template_keys,$hollow = false){
        if(is_string($map)){
            $map = MapRepository::getMapByKey($map);
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
                        'surface_type'=> $surface_template->type ?? $cell->surface_type,
                        'size'=> $surface_template->size,
                    ]);

            }
        }
    }

    public static function fillMapRegion($map, $start_cell, $figure,$height,$width,$surface_template_keys){



    }

}
