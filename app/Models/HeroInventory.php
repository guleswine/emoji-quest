<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroInventory extends Model
{
    use HasFactory;

    protected $fillable = ['hero_id', 'type', 'slots_count'];
}
