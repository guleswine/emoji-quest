<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id', 'name', 'emoji', 'surface_type',
        'x', 'y', 'z', 'size','transfer_to_cell_id'];

    public function emojiObject()
    {
        return $this->belongsTo(Emoji::class, 'emoji', 'key');
    }

    public function surfaceType()
    {
        return $this->belongsTo(SurfaceType::class, 'surface_type', 'name');
    }

    public function map()
    {
        return $this->belongsTo(Map::class);
    }
}
