<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BattleQueueUnit extends Model
{
    use HasFactory;

    protected $fillable = ['battle_id', 'type', 'object_id', 'order', 'object_name', 'health', 'action_points', 'emoji_name', 'name', 'protected_area'];
}
