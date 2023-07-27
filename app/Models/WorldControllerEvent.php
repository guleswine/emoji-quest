<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorldControllerEvent extends Model
{
    use HasFactory;

    protected $casts = [
        'params'=>'array',
    ];

    protected $fillable = ['world_controller_id','function','object_id','params'];
}
