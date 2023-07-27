<?php

namespace App\Services;

use App\Events\HeroReceivedItem;
use App\Models\Hero;
use App\Models\HeroInventory;
use App\Models\InventorySlot;
use App\Models\Item;

class InventoryService
{
    public $hero;
    public $inventories;

    public function __construct(Hero $hero)
    {
        $this->hero = $hero;
        $this->inventories = HeroInventory::where('hero_id', $hero->id)->get()->keyBy('type');
    }

    public function addItem(Item|int $item, $count = 1)
    {
        if (is_int($item)){
            $item = Item::find($item);
        }
        $inventory = $this->inventories[$item->type];
        $free_slot = $this->getFreeSlot($inventory);
        if (blank($free_slot)) {
            return false;
        }
        $free_slot->item_id = $item->id;
        $free_slot->items_count = $count;
        $free_slot->save();
        HeroReceivedItem::dispatch($this->hero, $item);

        return true;
    }

    public function getFreeSlot($inventory)
    {
        return InventorySlot::where('inventory_id', $inventory->id)->whereNull('item_id')->orderBy('id')->first();
    }
}
