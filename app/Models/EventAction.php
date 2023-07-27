<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAction extends Model
{
    use HasFactory;

    protected $casts = [
      'params'=>'array',
    ];

    protected  $fillable = ['event_id','class','function','params'];
}
