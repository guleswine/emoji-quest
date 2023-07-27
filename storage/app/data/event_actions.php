<?php

return [
    ['event_id' => 1,'class'=>'App\Services\EventActionService','function'=>'actionGiveQuest','params'=>['quest_id'=>1]],
    ['event_id' => 2,'class'=>'App\Services\EventActionService','function'=>'actionRemoveItem','params'=>['item_id'=>6]],
    ['event_id' => 2,'class'=>'App\Services\EventActionService','function'=>'actionGiveCoins','params'=>['count'=>10]],
    ['event_id' => 2,'class'=>'App\Services\EventActionService','function'=>'actionFinishQuestState','params'=>['quest_state_id'=>3]],
    ['event_id' => 2,'class'=>'App\Services\EventActionService','function'=>'actionAddExperience','params'=>['count'=>30]],
];
