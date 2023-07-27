<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestState extends Model
{
    use HasFactory;

    protected $fillable = ['quest_id','name','sort','event_class','total_progress','params','object_id','final'];

    protected $casts = [
        'params'=>'array',
    ];
}
