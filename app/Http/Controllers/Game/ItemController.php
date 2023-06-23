<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Cell;
use App\Models\Hero;
use App\Models\Item;
use App\Repositories\MapRepository;
use App\Services\CellService;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function takeItemFromCell($cell_id)
    {
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        //$cell = Cell::find($cell_id);
        $cell_object = CellService::firstCellObjectByClass($cell_id, 'Item');
        if ($cell_object) {
            $item = Item::find($cell_object->object_id);
            $IS = new InventoryService($hero);
            $IS->addItem($item);
            $cell_object->delete();
        }
        $cell = MapRepository::getCell($cell_id);

        return ['cell'=>$cell];

    }
}
