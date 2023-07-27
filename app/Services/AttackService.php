<?php

namespace App\Services;

use App\Events\UnitAttacked;
use App\Models\BattleQueueUnit;
use App\Models\Cell;
use App\Models\Enemy;
use App\Models\Hero;
use App\Models\HeroStat;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class AttackService
{
    public static function handlerHeroAttack($hero, $cell, $area)
    {

        $hero_cell = Cell::find($hero->cell_id);
        $hero_stats = HeroStat::where('hero_id', $hero->id)->get()->keyBy('attribute');
        if (PathSearchService::rangeBetwenCells($hero_cell, $cell) > $hero_stats['attack_range']->value) {
            $data = ['notify'=>['type'=>'info', 'message'=>'Цель слишком далеко']];

            return $data;
        }
        $cell_object = CellService::firstCellObjectByClass($cell->id, 'Unit');
        if ($cell_object) {
            $unit = Unit::find($cell_object->object_id);
            if ($cell_object->type == 'enemy') {
                $action_points = $hero->getCurrentStat('action_points');
                if ($action_points < 2) {
                    return ['notify'=>['type'=>'info', 'message'=>'Не достаточно очков действий для атаки.']];
                }
                $enemy = Enemy::find($unit->object_id);
                $damage = self::attackEnemyByHero($enemy, $unit, $hero, $hero_stats, $area);
                $data = ['damage'=>$damage];

                return $data;
            }
        }

    }

    public static function calcDamage($attack, $armor, $area, $protected_area)
    {
        if ($protected_area == $area) {
            $damage = round($attack * 0.5) - $armor;
        } else {
            switch ($area) {
                case 'head':
                    $attack = round($attack * 1.25);
                    break;
                case 'body':
                    break;
                case 'limbs':
                    $attack = round($attack * 0.75);
                    break;
            }
            $damage = $attack - $armor;
        }

        $result = ($damage > 0 ? $damage : 0);

        return $result;
    }

    public static function attackEnemyByHero(Enemy $enemy, Unit $unit, Hero $hero, Collection $hero_stats, string $attack_area)
    {
        $damage = self::calcDamage($hero_stats['attack']->value, $enemy->armor, $attack_area, null);
        $hero_ids = BattleQueueUnit::where('battle_id', $hero->state_object_id)->where('object_name', 'hero')->where('object_id', '<>', $hero->id)->pluck('object_id');
        UnitAttacked::dispatch($hero_ids, $unit->cell_id, $damage);
        $unit->current_health -= $damage;
        $BQU = BattleQueueUnit::where('object_name', 'unit')->where('object_id', $unit->id)->first();
        $BQU->health -= $damage;

        if ($attack_area == 'limbs') {
            $BQU->action_points = $enemy->action_points - 1;
        }
        $action_points = $hero->getCurrentStat('action_points');
        $new_ap = $action_points - 2;

        $hero->updateCurrentStat('action_points', $new_ap);
        if ($unit->current_health > 0) {
            $unit->save();
            $BQU->save();

        } else {
            BattleService::enemyDead($unit, $BQU);
            HeroService::addExperience($hero, $enemy->experience);
            TalentService::increaseProgress($hero->id, 5);
        }
        if ($new_ap == 0) {
            BattleService::HeroStepFinish($hero);
        }
        EventService::eventAttackEnemyByHero($unit, $hero, $damage, $attack_area);

        return $damage;
    }

    public static function attackHeroByEnemy(Enemy $enemy, Unit $unit, Hero &$hero, $queue_fighters)
    {
        $hero_queue = $queue_fighters->where('object_name', 'hero')->firstWhere('object_id', $hero->id);
        $armor = $hero->getCurrentStat('armor');
        $health = $hero->getCurrentStat('health');
        $action_points = $hero->getFinalStat('action_points');
        $attack_area = Arr::random($enemy->attack_areas);
        $damage = self::calcDamage($enemy->attack, $armor, $attack_area, $hero_queue->protected_area);
        $hero_ids = $queue_fighters->where('object_name', 'hero')->pluck('object_id');
        UnitAttacked::dispatch($hero_ids, $hero->cell_id, $damage);
        if ($attack_area == 'limbs' and $hero_queue->protected_area != 'limbs') {
            $hero->updateCurrentStat('action_points', $action_points - 1);
        }
        //$damage = $enemy->attack - $armor;
        EventService::eventAttackHeroByEnemy($unit, $hero, $damage, $attack_area);
        if (($health - $damage) > 0) {
            $hero->updateCurrentStat('health', $health - $damage);

            return false;
        } else {
            BattleService::heroDead($hero);

            return true;
        }

    }
}
