<?php

namespace App\Listeners;

use App\Events\GameNotification;
use App\Events\HeroReceivedItem;
use App\Models\HeroQuestState;
use Illuminate\Support\Facades\DB;

class ReceivingItemQuest
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\HeroReceivedItem  $event
     * @return void
     */
    public function handle($event)
    {
        $hero_quest_states = DB::table('quest_states')
            ->join('hero_quest_states', 'quest_states.id', '=', 'hero_quest_states.quest_state_id')
            ->where('event_class', HeroReceivedItem::class)
            ->where('object_id', $event->item->id)
            ->where('hero_quest_states.completed', false)
            ->where('hero_quest_states.hero_id', $event->hero->id)
            ->selectRaw('hero_quest_states.*')
            ->get();
        foreach ($hero_quest_states as $state) {
            $hero_quest_state = HeroQuestState::find($state->id)->update(['completed'=>true]);
            GameNotification::dispatch($event->hero->id, 'info', 'Квестовое задание выполнено!');
        }

    }
}
