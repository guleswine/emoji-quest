<?php

namespace App\Services;

use App\Models\HeroSkill;
use App\Models\HeroTravelStat;
use App\Models\Skill;

class SkillService
{
    public static function unlockNextSkill($skill, $hero)
    {
        $next_left_skill = Skill::where('row', $skill->row + 1)->where('col', $skill->col)->first();
        if ($next_left_skill) {
            $before_other_skill = Skill::where('row', $skill->row)->where('col', $skill->col - 1)->first();
            $hero_before_other_skill = HeroSkill::where('hero_id', $hero->id)->where('skill_id', $before_other_skill->id ?? 0)->where('learned', true)->first();
            if ($hero_before_other_skill or !$before_other_skill) {
                HeroSkill::create([
                    'hero_id'=>$hero->id,
                    'skill_id'=>$next_left_skill->id,
                    'unlocked'=>true,
                    'learned'=>false,
                ]);
            }
        }

        $next_right_skill = Skill::where('row', $skill->row + 1)->where('col', $skill->col + 1)->first();
        if ($next_right_skill) {
            $before_other_skill = Skill::where('row', $skill->row)->where('col', $skill->col + 1)->first();
            $hero_before_other_skill = HeroSkill::where('hero_id', $hero->id)->where('skill_id', $before_other_skill->id ?? 0)->where('learned', true)->first();
            if ($hero_before_other_skill or !$before_other_skill) {
                HeroSkill::create([
                    'hero_id'=>$hero->id,
                    'skill_id'=>$next_right_skill->id,
                    'unlocked'=>true,
                    'learned'=>false,
                ]);
            }
        }
    }

    public static function learnSkillWoodConstruction($hero)
    {
        HeroService::learnBlueprint($hero, 1);
        HeroService::learnBlueprint($hero, 2);
    }

    public static function learnSkillLowHealthRecovery($hero)
    {
        $HTS = HeroTravelStat::where('hero_id', $hero->id)->where('name', 'life_recovery_percent')->first();
        $HTS->value = 50;
        $HTS->save();
        $HTS = HeroTravelStat::where('hero_id', $hero->id)->where('name', 'life_recovery_speed')->first();
        $HTS->value = 10;
        $HTS->save();
    }
}
