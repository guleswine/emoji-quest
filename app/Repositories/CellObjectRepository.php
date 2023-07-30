<?php

namespace App\Repositories;

use App\Models\CellObjectTemplate;

class CellObjectRepository
{

    public static function getTemplate($key){
        return CellObjectTemplate::where('key', $key)->first();
    }

}
