<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\Quest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestController extends Controller
{
    public function getQuests()
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $quests = DB::table('hero_quests')
            ->join('quests', 'hero_quests.quest_id', '=', 'quests.id')
            ->where('hero_quests.hero_id', $hero->id)
            ->selectRaw('quests.*,hero_quests.completed')
            ->get();

        return response()->json($quests);
    }

    public function getQuest($quest_id)
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $quest = Quest::find($quest_id);
        $states = DB::table('quest_states')
            ->leftJoin('hero_quest_states', function ($join) use ($hero) {
                $join->on('quest_states.id', '=', 'hero_quest_states.quest_state_id')
                    ->where('hero_quest_states.hero_id', $hero->id);
            })
            ->where('quest_states.quest_id', $quest_id)
            ->selectRaw('quest_states.*,hero_quest_states.completed,hero_quest_states.current_progress')
            ->orderBy('sort')
            ->get();

        return response()->json([
            'quest'=>$quest,
            'states'=>$states,
        ]);
    }
}
