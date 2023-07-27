<?php

namespace App\Repositories;

use App\Models\Cell;

class CellRepository
{

    public static function getCell($map_id, $x, $y){
        $cell = Cell::where('map_id', $map_id)->where('x', $x)->where('y', $y)->first();
        return $cell;
    }

    public static function updateCell($cell, $attributes){
        $cell->update($attributes);
        return  $cell;
    }

}
