<?php

namespace App\Services;

class UnitService
{
    public static function updateCell(&$unit, int $cell_id)
    {
        CellService::removeObject($unit->cell_id, 'Unit', $unit->id);
        $unit->cell_id = $cell_id;
        CellService::addObjectUnit($unit);

        $unit->save();
    }
}
