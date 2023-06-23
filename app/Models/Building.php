<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'cell_id',
        'blueprint_id',
        'name',
        'emoji',
        'category',
        'type',
        'creator_hero_id',
        'completed_at',
    ];

    protected $dates = ['completed_at'];
}
