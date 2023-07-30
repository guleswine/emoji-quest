<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CellObjectTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['key','name','emoji','use_as_background','size','priority','object_class','object_id','type'];
}
