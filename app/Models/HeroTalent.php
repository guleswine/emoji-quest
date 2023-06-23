<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroTalent extends Model
{
    use HasFactory;

    protected $table = 'hero_talents';

    protected $fillable = ['hero_id', 'talent_id', 'level', 'current_progress', 'total_progress'];
}
