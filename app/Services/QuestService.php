<?php

namespace App\Services;

use App\Events\GameNotification;
use App\Models\HeroQuest;
use App\Models\HeroQuestState;
use App\Models\QuestState;

class QuestService
{
    public function giveQuestForHero($quest_id, $hero_id): bool
    {
        HeroQuest::firstOrCreate([
            'hero_id'=>$hero_id,
            'quest_id'=>$quest_id,
            'completed'=>false
        ]);
        $quest_states = QuestState::where('quest_id', $quest_id)->get();
        foreach ($quest_states as $state) {
            HeroQuestState::firstOrCreate([
                'hero_id'=>$hero_id,
                'quest_id'=>$quest_id,
                'quest_state_id'=>$state->id,
            ]);
        }

        return true;
    }

    public static function completeState($hero_quest_state_id)
    {
        $HQS = HeroQuestState::find($hero_quest_state_id);
        $HQS->update(['completed'=>true]);
        $QS = QuestState::find($HQS->quest_state_id);
        if ($QS->final) {
            $HQ = HeroQuest::where('hero_id', $HQS->hero_id)->where('quest_id', $QS->quest_id)->first();
            $HQ->update(['completed'=>true]);
            GameNotification::dispatch($HQS->hero_id, 'info', 'Квест выполнен!');
        } else {
            GameNotification::dispatch($HQS->hero_id, 'info', 'Квестовое задание выполнено!');
        }
    }

    public function hasQuest($hero_id, $quest_id): bool
    {
        return HeroQuest::where('quest_id', $quest_id)->where('hero_id', $hero_id)->exists();
    }

    public function completedQuest($hero_id, $quest_id): bool
    {
        return HeroQuest::where('quest_id', $quest_id)->where('hero_id', $hero_id)->where('completed', true)->exists();
    }

    public function activeQuest($hero_id, $quest_id): bool
    {
        return HeroQuest::where('quest_id', $quest_id)->where('hero_id', $hero_id)->where('completed', false)->exists();
    }
}
