<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Cell;
use App\Models\Hero;
use App\Models\Map;
use App\Repositories\MapRepository;
use App\Services\MapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    protected $service;
    protected $repository;

    public function __construct(MapService $service, MapRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function getCells(Request $request)
    {
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        $cell = Cell::find($hero->cell_id);
        $map = Map::find($cell->map_id);
        $radius_x = 10;
        if ($map->size_width <= 21) {
            $radius_x = 21;
        }
        $radius_y = 10;
        if ($map->size_height <= 21) {
            $radius_y = 21;
        }

        $data = $this->repository->getCells($map->id, $cell->x, $cell->y, $radius_x, $radius_y);

        return response()->json($data);
    }

    public function getGlobalCells()
    {
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        $cell = Cell::find($hero->cell_id);
        $map = Map::find($cell->map_id);
        $radius_x = 20;
        if ($map->size_width <= 41) {
            $radius_x = 41;
        }
        $radius_y = 20;
        if ($map->size_height <= 41) {
            $radius_y = 41;
        }
        $data = $this->repository->getCells($map->id, $cell->x, $cell->y, $radius_x, $radius_y);

        return response()->json($data);
    }

    public function moveToCell($id)
    {
        $data = $this->service->moveToCell($id);

        return response()->json($data);

    }

    public function transferToCell($cell_id)
    {
        $data = $this->service->transferToCell($cell_id);

        return response()->json($data);
    }
}
