<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $casts = [
      'params'=>'array',
    ];

    protected $fillable = ['name','function','condition_group_id','result','params'];
}
