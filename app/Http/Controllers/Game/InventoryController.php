<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Cell;
use App\Models\Hero;
use App\Models\HeroEquipment;
use App\Models\HeroInventory;
use App\Models\InventorySlot;
use App\Models\Item;
use App\Services\CellService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function getLeftSideEquipment(Request $request)
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $data = DB::table('hero_equipment')
            ->leftJoin('items', 'hero_equipment.item_id', '=', 'items.id')
            ->selectRaw('hero_equipment.id,items.emoji,items.name,items.description')
            ->where('hero_equipment.side', 'left')
            ->where('hero_equipment.hero_id', $hero->id)
            ->orderBy('hero_equipment.sort_order')
            ->get();

        return response()->json($data);
    }

    public function getRightSideEquipment(Request $request)
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $data = DB::table('hero_equipment')
            ->leftJoin('items', 'hero_equipment.item_id', '=', 'items.id')
            ->selectRaw('hero_equipment.id,items.emoji,items.name,items.description')
            ->where('hero_equipment.side', 'right')
            ->where('hero_equipment.hero_id', $hero->id)
            ->orderBy('hero_equipment.sort_order')
            ->get();

        return response()->json($data);
    }

    public function getInventory($type)
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $data = DB::table('hero_inventories')
            ->join('inventory_slots', 'hero_inventories.id', '=', 'inventory_slots.inventory_id')
            ->leftJoin('items', 'inventory_slots.item_id', '=', 'items.id')
            ->where('hero_inventories.type', $type)
            ->where('hero_inventories.hero_id', $hero->id)
            ->selectRaw('inventory_slots.id,items.emoji,items.name,items.description')
            ->orderBy('inventory_slots.created_at')->orderBy('inventory_slots.id')
            ->get();

        return response()->json($data);
    }

    public function onDropEquipment(Request $request)
    {
        $id = $request->input('id');
        $entity_id = $request->input('entity_id');
        $entity_type = $request->input('entity_type');
        $hero_equipment = HeroEquipment::find($id);
        if ($entity_type == 'inventory_slot') {
            $inventory_slot = InventorySlot::find($entity_id);
            $hero_inventory = HeroInventory::find($inventory_slot->inventory_id);
            $inventory_item_id = $inventory_slot->item_id;
            $inventory_slot->update(['item_id'=>$hero_equipment->item_id]);
            $hero_equipment->update(['item_id'=>$inventory_item_id]);
            if ($hero_equipment->type == 'appearance') {
                $hero = Hero::find($hero_equipment->hero_id);
                $item = Item::find($inventory_item_id);
                $hero->update(['emoji'=>$item->emoji]);
                $cell_object = CellService::findCellObject($hero->cell_id,'Hero',$hero->id);
                $cell_object->update(['emoji'=>$item->emoji]);
            }
        }

        return response()->json([
            'id'=>$id,
            'entity_id'=>$entity_id,
            'entity_type'=>$entity_type,
        ]);
    }

    public function onDropInventory(Request $request)
    {
        $id = $request->input('id');
        $entity_id = $request->input('entity_id');
        $entity_type = $request->input('entity_type');
        $inventory_slot_finite = InventorySlot::find($id);
        if ($entity_type == 'inventory_slot') {
            $inventory_slot_initial = InventorySlot::find($entity_id);
            if ($inventory_slot_initial->inventory_id == $inventory_slot_finite->inventory_id) {

                DB::transaction(function () use ($inventory_slot_finite, $inventory_slot_initial) {
                    $tmp_item_id = $inventory_slot_finite->item_id;
                    $tmp_items_count = $inventory_slot_finite->items_count;
                    $inventory_slot_finite->update([
                        'item_id'=>$inventory_slot_initial->item_id,
                        'items_count'=>$inventory_slot_initial->items_count,
                    ]);
                    $inventory_slot_initial->update([
                        'item_id'=>$tmp_item_id,
                        'items_count'=>$tmp_items_count,
                    ]);
                }, 3);

            }
        }

        return response()->json([
            'id'=>$id,
            'entity_id'=>$entity_id,
            'entity_type'=>$entity_type,
        ]);
    }

    public function removeItem(Request $request)
    {
        $entity_id = $request->input('entity_id');
        $entity_type = $request->input('entity_type');
        switch ($entity_type) {
            case 'inventory_slot':
                $inventory_slot = InventorySlot::find($entity_id);
                $inventory_slot->clear();
                break;
            case 'hero_equipment':
                $hero_equipment = HeroEquipment::find($entity_id);
                $hero_equipment->clear();
                break;
        }
    }
}
