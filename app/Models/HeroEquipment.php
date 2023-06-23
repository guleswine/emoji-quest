<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroEquipment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'emoji', 'type', 'category', 'item_id', 'hero_id', 'side', 'sort_order'];

    public function clear()
    {
        $this->item_id = null;
        $this->save();
    }
}
