<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroVisitedMap extends Model
{
    use HasFactory;

    protected $fillable = ['hero_id', 'map_id'];
}
