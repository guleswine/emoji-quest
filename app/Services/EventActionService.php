<?php

namespace App\Services;

use App\Models\EventAction;
use App\Models\HeroQuestState;

class EventActionService
{
    public $hero;

    public function __construct($hero)
    {
        $this->hero = $hero;
    }

    public function execute($event_id)
    {
        $actions = EventAction::where('event_id', $event_id)->get();
        foreach ($actions as $action) {
            // $class = new $action->class;
            $function = $action->function;
            $resp = $this->$function($action->params);
        }
    }

    public function actionGiveQuest($params)
    {

        $quest_id = $params['quest_id'];
        $hero_id = $this->hero->id;
        if (!$quest_id or !$hero_id) {
            return false;
        }
        $QS = new QuestService();
        $QS->giveQuestForHero($quest_id, $hero_id);
    }

    public function actionFinishQuestState($params)
    {
        $quest_state_id = $params['quest_state_id'];
        $HQS = HeroQuestState::where('quest_state_id', $quest_state_id)->where('hero_id', $this->hero->id)->first();
        if ($HQS) {
            QuestService::completeState($HQS->id);
        }

    }

    public function actionGiveCoins($params)
    {
        HeroService::addCoins($this->hero, $params['count']);
    }

    public function actionAddExperience($params)
    {
        HeroService::addExperience($this->hero, $params['count']);
    }

    public function actionRemoveItem($params)
    {
        $item_id = $params['item_id'];
        HeroService::removeItem($this->hero, $item_id);
    }
}
