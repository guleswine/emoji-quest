<?php

namespace App\Listeners;

use App\Events\HeroVisitedMap;
use App\Services\TalentService;

class ExplorerTalent
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
     * @param  \App\Events\HeroVisitedMap  $event
     * @return void
     */
    public function handle(HeroVisitedMap $event)
    {
        $HVM = \App\Models\HeroVisitedMap::where('hero_id', $event->hero->id)->where('map_id', $event->map->id)->first();
        if (!$HVM) {
            \App\Models\HeroVisitedMap::create([
                'hero_id'=>$event->hero->id,
                'map_id'=>$event->map->id,
            ]);
            TalentService::increaseProgress($event->hero->id, 4);
        }
    }
}
