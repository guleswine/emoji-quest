<?php

namespace App\Services;

use App\Events\GameEvent;
use App\Models\Cell;
use App\Models\Hero;
use App\Models\Map;
use App\Models\User;
use App\Models\UserGameEvent;

class EventService
{
    public static function getEvents(User $user)
    {
        return UserGameEvent::where('user_id', $user->id)->limit(100)->orderByDesc('id')->get();
    }

    public static function eventsetHeroDefence($hero, $attack_area)
    {
        $emoji = 'brown_shield';
        $message = __('game.notify.defence_' . $attack_area, [], 'ru');
        GameEvent::dispatch($hero->id, $emoji, $message);
        UserGameEvent::create(
            [
                'user_id'=>$hero->user_id,
                'message'=>$message,
                'emoji'=>$emoji,
            ]
        );
    }

    public static function eventAttackHeroByEnemy($unit, $hero, $damage, $attack_area)
    {
        $emoji = 'crossed_swords';
        $message = __(
            'game.events.attack_' . $attack_area,
            ['name_attacker'=>$unit->name, 'name_target'=>$hero->name, 'damage'=>$damage],
            'ru'
        );
        GameEvent::dispatch($hero->id, $emoji, $message);
        UserGameEvent::create(
            [
                'user_id'=>$hero->user_id,
                'message'=>$message,
                'emoji'=>$emoji,
            ]
        );
    }

    public static function eventAttackEnemyByHero($unit, $hero, $damage, $attack_area)
    {
        $emoji = 'crossed_swords';
        $message = __(
            'game.events.attack_' . $attack_area,
            ['name_attacker'=>$hero->name, 'name_target'=>$unit->name, 'damage'=>$damage],
            'ru'
        );

        GameEvent::dispatch($hero->id, $emoji, $message);
        UserGameEvent::create(
            [
                'user_id'=>$hero->user_id,
                'message'=>$message,
                'emoji'=>$emoji,
            ]
        );
    }

    public static function moveToCell(Hero $hero, Cell $cell)
    {
        $message = "Ваш герой $hero->name перемещается на клетку (x=$cell->x,y=$cell->y)";
        $emoji = 'footprints';
        UserGameEvent::create(
            [
            'user_id'=>$hero->user_id,
            'message'=>$message,
            'emoji'=>$emoji,
            ]
        );
        $response = [
            'message'=>$message,
            'emoji'=>$emoji,
        ];

        return $response;
    }

    public static function transferToCell(Hero $hero, Map $map)
    {
        $message = "Ваш герой $hero->name перемещается на карту $map->name";
        $emoji = 'globe_with_meridians';
        UserGameEvent::create(
            [
                'user_id'=>$hero->user_id,
                'message'=>$message,
                'emoji'=>$emoji,
            ]
        );
        $response = [
            'message'=>$message,
            'emoji'=>$emoji,
        ];

        return $response;
    }

    public static function cellNotFreeForMove()
    {

    }
}
