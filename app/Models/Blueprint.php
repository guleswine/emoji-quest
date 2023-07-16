<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blueprint extends Model
{
    use HasFactory;

    protected $fillable = ['name','category','type','emoji','description','subcategory','creation_duration','cost_coins','strength'];
}
