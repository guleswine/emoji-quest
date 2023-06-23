<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Services\HeroService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function getSkills()
    {

        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $skills = DB::table('skills')
            ->leftJoin('hero_skills', function ($join) use ($hero) {
                $join->on('skills.id', '=', 'hero_skills.skill_id')
                    ->where('hero_skills.hero_id', $hero->id);
            })
            ->selectRaw('skills.*,hero_skills.unlocked,hero_skills.learned')
            ->get();
        $data = [
            'skills'=>$skills,
            'hero'=>$hero,
        ];

        return response()->json($data);
    }

    public function getSkillNames()
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $skills = DB::table('hero_skills')
            ->join('skills', 'skills.id', '=', 'hero_skills.skill_id')
            ->where('hero_skills.hero_id', $hero->id)
            ->where('hero_skills.learned', true)
            ->selectRaw('skills.name')
            ->get();

        return $skills->keyBy('name');
    }

    public function learnSkill($skill_id)
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        HeroService::learnSkill($hero, $skill_id);
    }
}
