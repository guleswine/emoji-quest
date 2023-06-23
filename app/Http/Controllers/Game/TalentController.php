<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TalentController extends Controller
{
    public function getTalents()
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $talents = DB::table('hero_talents')
            ->join('talents', 'hero_talents.talent_id', '=', 'talents.id')
            ->where('hero_talents.hero_id', $hero->id)
            ->selectRaw('talents.name,talents.emoji_name,hero_talents.*')
            ->get();

        return response()->json($talents);
    }
}
