<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroStat extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'final_value', 'hero_id', 'static_buff',
        'percent_buff', 'static_improvement', 'percent_improvement', 'base_value', 'attribute'];
}
