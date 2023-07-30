<?php

namespace App\Services;

use App\Models\CellObject;
use App\Repositories\CellObjectRepository;
use App\Repositories\CellRepository;
use App\Repositories\SurfaceTemplateRepository;

class CellService
{
    public static function moveObject($start_cell_id, $finish_cell_id, $name, $emoji, $object_class, $object_id, $size, $use_as_background, $priority, $creator_hero_id = null)
    {
        $cell_object = CellObject::where('cell_id', $start_cell_id)->where('object_class', $object_class)->where('object_id', $object_id)->first();
        if ($cell_object) {
            $cell_object->cell_id = $finish_cell_id;
            $cell_object->save();
        } else {
            self::addObject($finish_cell_id, $name, $emoji, $object_class, $object_id, $size, $use_as_background, $priority, $creator_hero_id);
        }

    }

    public static function updateCellByTemplateKey($cell, $surface_template_key,$extra_attributes = []){
        $surface_template = SurfaceTemplateRepository::getTemplate($surface_template_key);
        $default = [
            'name'=> $surface_template->name,
            'emoji'=> $surface_template->emoji,
            'surface_type'=> $surface_template->type ?? $cell->surface_type,
            'size'=> $surface_template->size,
        ];
        $data = array_merge($default, $extra_attributes);
        CellRepository::updateCell($cell, $data);
    }

    public static function addObjectByTemplateKey($cell, $cell_object_template_key,$extra_attributes = []){
        $cell_object_template = CellObjectRepository::getTemplate($cell_object_template_key);
        self::addObject($cell->id, $cell_object_template->name, $cell_object_template->emoji, $cell_object_template->object_class,
            $cell_object_template->object_id, $cell_object_template->size, $cell_object_template->use_as_background, $cell_object_template->priority);
    }
    public static function addObject($cell_id, $name, $emoji, $object_class, $object_id, $size, $use_as_background, $priority, $creator_hero_id = null)
    {
        $cell_object = CellObject::create([
            'cell_id'=>$cell_id,
            'name'=>$name,
            'emoji'=>$emoji,
            'object_class'=>$object_class,
            'object_id'=>$object_id,
            'size'=>$size,
            'use_as_background'=>$use_as_background,
            'priority'=>$priority,
            'creator_hero_id'=>$creator_hero_id,
        ]);
    }

    public static function addObjectHero($hero)
    {
        $cell_object = CellObject::create([
            'cell_id'=>$hero->cell_id,
            'name'=>$hero->name,
            'emoji'=>$hero->emoji,
            'object_class'=>'Hero',
            'object_id'=>$hero->id,
            'size'=>$hero->size,
            'use_as_background'=>false,
            'priority'=>100,
            'creator_hero_id'=>$hero->id,
        ]);
    }

    public static function addObjectUnit($unit)
    {
        $cell_object = CellObject::create([
            'cell_id'=>$unit->cell_id,
            'name'=>$unit->name,
            'emoji'=>$unit->emoji,
            'object_class'=>'Unit',
            'object_id'=>$unit->id,
            'type'=>$unit->type,
            'size'=>$unit->size,
            'use_as_background'=>false,
            'priority'=>100,
        ]);
    }

    public static function removeObject($cell_id, $object_class, $object_id)
    {
        $cell_object = CellObject::where('cell_id', $cell_id)->where('object_class', $object_class)->where('object_id', $object_id)->first();
        if ($cell_object) {
            $cell_object->delete();
        }
    }

    public static function findCellObject($cell_id, $object_class, $object_id)
    {
        return CellObject::where('cell_id', $cell_id)->where('object_class', $object_class)->where('object_id', $object_id)->first();
    }

    public static function firstCellObjectByClass($cell_id, $object_class)
    {
        return CellObject::where('cell_id', $cell_id)->where('object_class', $object_class)->first();
    }
}
