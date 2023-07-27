<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
