<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGameEvent extends Model
{
    use HasFactory;

    protected $fillable = ['emoji', 'message', 'user_id'];
}
