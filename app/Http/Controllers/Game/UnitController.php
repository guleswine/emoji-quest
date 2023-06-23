<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Unit;

class UnitController extends Controller
{
    public function getUnit(int $id)
    {
        $unit = Unit::find($id);

        return response()->json($unit);
    }
}
