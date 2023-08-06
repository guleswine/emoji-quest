<?php

namespace App\Http\Controllers;

use App\Events\UserActionEvent;
use App\Jobs\EnemyMove;
use App\Models\Cell;
use App\Models\Enemy;
use App\Models\Hero;
use App\Models\HeroInventory;
use App\Models\InventorySlot;
use App\Models\User;
use App\Services\AttackService;
use App\Services\BattleService;
use App\Services\EventActionService;
use App\Services\HeroService;
use App\Services\MapGeneratorService;
use App\Services\MapService;
use App\Services\PathSearchService;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        MapGeneratorService::loadEmojies();


        dd('ok');
        $free_cell = Cell::where('map_id', 3)
            ->leftJoin('cell_objects', 'cell_objects.cell_id', '=', 'cells.id')
            ->whereBetween('y', [-15, 15])
            ->whereBetween('x', [-15, 15])
            ->whereNull('cell_objects.id')
            ->selectRaw('cells.*')
            ->inRandomOrder()
            ->first();
        dd($free_cell);
        $data1 = [
            ['id'=>1, 'lang_en'=>'Yes'],
            ['id'=>2, 'lang_en'=>'No'],
        ];
        $data2 = [
            ['id'=>1, 'lang_ru'=>'Yes'],
            ['id'=>2, 'lang_ru'=>'No'],
        ];
        $data1 = collect($data1)->keyBy('id');
        $data2 = collect($data2)->keyBy('id');
        foreach ($data1 as $id=>$row) {
            if (isset($data1[$id])) {
                $data1[$id] = array_merge($data1[$id], $data2[$id]);
            }
        }
        dd($data1);
        $user = Auth::user();
        //$hero = Hero::where('user_id',$user->id)->first();
        $HS = new HeroService();
        $HS->create($user);
        dd('ok');
        $start_cell = Cell::find($hero->cell_id);
        $finish_cell = Cell::find(4005);
        $sql = DB::table('cells')
            ->join('heroes', 'heroes.cell_id', '=', 'cells.id')
            ->where('heroes.id', '<>', $hero->id)
            ->where('cells.map_id', $start_cell->map_id)
            ->selectRaw('heroes.*')
            ->where(function (Builder $query) use ($start_cell, $finish_cell) {
                $query->where(function (Builder $sub_query) use ($start_cell) {
                    $sub_query->whereBetween('cells.y', [$start_cell->y - 10, $start_cell->y + 10])
                        ->whereBetween('cells.x', [$start_cell->x - 10, $start_cell->x + 10]);
                })->orWhere(function (Builder $sub_query) use ($finish_cell) {
                    $sub_query->whereBetween('cells.y', [$finish_cell->y - 10, $finish_cell->y + 10])
                        ->whereBetween('cells.x', [$finish_cell->x - 10, $finish_cell->x + 10]);
                });

            })
            ->toSql();
        dd($sql);
        $MS = new MapService();

        $hero_cell = Cell::find($hero->cell_id);
        $heroes = $MS->searchHeroesNearHero($hero_cell, $hero);
        dd($heroes);
        $PS = new PathSearchService();
        $PS->setStartCellById(998177)
            ->setFinishCellById(998176)
            ->loadCells()
            ->setCellRadius(1);
        $path = $PS->getShortestPath();
        dd($path);
        //BattleService::moveQueue(7);
        //EnemyMove::dispatch(7)->delay(now()->addSeconds(10));
        dd(Redis::get('test'));
        event(new UserActionEvent('hello', 1));
        dd('ok');
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        $cell = Cell::find(998241);
        AttackService::handlerHeroAttack($hero, $cell);
        dd('ok');

        $HS = new HeroService();
        $HS->create(Auth::user());
        dd('ok');
        EventActionService::execute(1);
        dd('ok');

        $PS = new PathSearchService();
        $MS = new MapService();

        $PS->setStartCellById(498001)
            ->setFinishCellById(498004)
            ->loadCells();
        $startTime = microtime(true);

        $resp = $PS->getShortestPath();
        $endTime = microtime(true);
        $diff = $endTime - $startTime;
        dd($resp);
        $minutes = floor($diff / 60); // Only minutes
        $seconds = $diff % 60; // Remaining seconds, using modulo operator
        echo "script execution time: $diff";
        dd($PS->getVisitedCells());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'ok';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return 'ok';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return 'ok';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
