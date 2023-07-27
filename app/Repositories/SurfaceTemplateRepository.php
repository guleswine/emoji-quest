<?php

namespace App\Repositories;

use App\Models\SurfaceTemplate;

class SurfaceTemplateRepository
{

    public static function getTemplate($key){
        return SurfaceTemplate::where('key', $key)->first();
    }

}
