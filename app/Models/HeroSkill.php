<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSkill extends Model
{
    use HasFactory;

    protected $fillable = ['hero_id', 'skill_id', 'unlocked', 'learned'];
}
