<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentLevel extends Model
{
    use HasFactory;

    protected $fillable = ['level','total_progress'];
}
