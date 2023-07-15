<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroTravelStat extends Model
{
    use HasFactory;

    protected $fillable = ['hero_id', 'attribute', 'value'];
}
