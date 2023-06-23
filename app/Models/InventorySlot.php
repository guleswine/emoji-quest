<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySlot extends Model
{
    use HasFactory;

    protected $fillable = ['inventory_id', 'item_id', 'items_count'];

    public function clear()
    {
        $this->item_id = null;
        $this->items_count = 0;
        $this->save();
    }
}
