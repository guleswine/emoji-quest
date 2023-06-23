<?php

namespace App\Services;

use App\Models\HeroTalent;
use App\Models\TalentLevel;

class TalentService
{
    public static function increaseProgress($hero_id, $talent_id)
    {
        $hero_talent = HeroTalent::where('hero_id', $hero_id)->where('talent_id', $talent_id)->first();
        if ($hero_talent) {
            $hero_talent->current_progress++;
            $hero_talent->save();
            if ($hero_talent->total_progress <= $hero_talent->current_progress) {
                self::levelUp($hero_talent);
            }
        }
    }

    public static function levelUp($hero_talent)
    {
        $new_level = $hero_talent->level + 1;
        $TL = TalentLevel::where('level', $new_level)->first();
        $hero_talent->level = $new_level;
        $hero_talent->current_progress = $hero_talent->current_progress - $hero_talent->total_progress;
        $hero_talent->total_progress = $TL->total_progress;
        $hero_talent->save();

    }
}
