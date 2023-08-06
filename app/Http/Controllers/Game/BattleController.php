<?php

namespace App\Http\Controllers\Game;

use App\Events\GameNotification;
use App\Http\Controllers\Controller;
use App\Models\Battle;
use App\Models\BattleQueueUnit;
use App\Models\Cell;
use App\Models\Hero;
use App\Services\AttackService;
use App\Services\BattleService;
use App\Services\EventService;
use Illuminate\Support\Facades\Auth;

class BattleController extends Controller
{
    public function attack($cell_id, $area)
    {
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        $cell = Cell::find($cell_id);
        if ($hero->state_name == 'battle') {
            $fighter = BattleQueueUnit::where('battle_id', $hero->state_object_id)->orderBy('order')->first();
            if ($fighter->object_name == 'hero' and $fighter->object_id == $hero->id) {
                $response = AttackService::handlerHeroAttack($hero, $cell, $area);
            } else {
                GameNotification::dispatch($hero->id, 'info', 'Now it\'s not your turn to go');
            }
        }

        return $response;
    }

    public function setHeroDefence($hero_id, $area)
    {
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        if ($hero->state_name == 'battle') {
            $fighter = BattleQueueUnit::where('battle_id', $hero->state_object_id)->orderBy('order')->first();
            if ($fighter->object_name == 'hero' and $fighter->object_id == $hero->id) {
                BattleService::setHeroDefence($hero, $area, $fighter);
                EventService::eventsetHeroDefence($hero, $area);
            //$response = AttackService::handlerHeroAttack($hero,$cell,$area);
            } else {
                GameNotification::dispatch($hero->id, 'info', 'Now it\'s not your turn to go');
            }
        }
    }

    public function getBattle()
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        if (!$hero->state_name == 'battle') {
            return response()->json(null);
        }

        $battle = Battle::find($hero->state_object_id);
        $queue = BattleQueueUnit::where('battle_id', $hero->state_object_id)->orderBy('order')->get();
        $data = [
            'id'=>$battle->id,
            'queue'=>$queue,
        ];

        return response()->json($data);
    }
}
