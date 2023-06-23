<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'emoji', 'size', 'cell_id', 'type', 'drop_item_id', 'object_id', 'current_health'];
}
