<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroQuestState extends Model
{
    use HasFactory;

    protected $fillable = ['hero_id', 'quest_id', 'quest_state_id', 'current_progress', 'completed'];
}
