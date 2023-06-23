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
}
