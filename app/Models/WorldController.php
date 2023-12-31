<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorldController extends Model
{
    use HasFactory;

    protected $fillable = ['name','condition_group_id'];
}
