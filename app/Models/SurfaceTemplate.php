<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurfaceTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['key','name','emoji','type','size'];
}
