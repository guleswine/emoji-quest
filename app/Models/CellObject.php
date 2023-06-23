<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CellObject extends Model
{
    use HasFactory;

    protected $fillable = ['cell_id', 'name', 'emoji', 'object_class', 'object_id', 'type', 'size', 'priority', 'use_as_background', 'creator_hero_id'];
}
