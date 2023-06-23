<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroBlueprint extends Model
{
    use HasFactory;

    protected $fillable = ['hero_id', 'blueprint_id', 'category', 'subcategory', 'type'];
}
