<?php

namespace App\Services;

use App\Models\Cell;
use App\Models\Hero;
use App\Models\HeroEquipment;
use App\Models\HeroInventory;
use App\Models\InventorySlot;
use App\Models\User;

class PlayerInitializationService
{
    public static function initialization(User $user)
    {
        $StartCell = Cell::where('x', 0)->where('y', 0)->first();
        $PlayerHero = Hero::where('user_id', $user->id)->first();
        if (!$PlayerHero) {
            $PlayerHero = Hero::create([
                'user_id'=>$user->id,
                'name'=>$user->name,
                'emoji'=>'man_standing',
                'cell_id'=>$StartCell->id,
            ]);
        }
        $InventoryTypes = ['items', 'equipment', 'resources', 'appearance'];

        foreach ($InventoryTypes as $type) {
            $Inventory = HeroInventory::firstOrCreate([
                'hero_id'=>$PlayerHero->id,
                'type'=>$type,
            ]);
            $InventorySlotsCount = InventorySlot::where('inventory_id', $Inventory->id)->count();
            for ($i = $InventorySlotsCount; $i < $Inventory->slots_count; $i++) {
                InventorySlot::create([
                    'inventory_id'=>$Inventory->id,
                ]);
            }
        }

        $equipment = [
            ['name'=>'Головной убор', 'type'=>'equipment', 'category'=>'head', 'side'=>'left', 'sort_order'=>1],
            ['name'=>'Скин', 'type'=>'appearance', 'category'=>'appearance', 'side'=>'right', 'sort_order'=>1],
            ['name'=>'Кисти', 'type'=>'equipment', 'category'=>'hands', 'side'=>'left', 'sort_order'=>2],
            ['name'=>'Торс', 'type'=>'equipment', 'category'=>'torso', 'side'=>'right', 'sort_order'=>2],
            ['name'=>'Левая рука', 'type'=>'equipment', 'category'=>'left_hand', 'side'=>'left', 'sort_order'=>3],
            ['name'=>'Правая рука', 'type'=>'equipment', 'category'=>'right_hand', 'side'=>'right', 'sort_order'=>3],
            ['name'=>'Ступни', 'type'=>'equipment', 'category'=>'feet', 'side'=>'left', 'sort_order'=>4],
            ['name'=>'Ноги', 'type'=>'equipment', 'category'=>'legs', 'side'=>'right', 'sort_order'=>4],
        ];
        foreach ($equipment as $equip) {
            $equip_attr = $equip;
            $equip_attr['hero_id'] = $PlayerHero->id;
            HeroEquipment::firstOrCreate($equip_attr);
        }

    }
}
