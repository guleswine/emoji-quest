<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enemy extends Model
{
    use HasFactory;

    protected $casts = [
        'attack_areas'=>'array',
    ];

    protected $fillable  = ['name','emoji','attack','armor','health','attack_range','dodge','critical_hit','action_points','drop_item_id','experience','attack_areas'];
}
