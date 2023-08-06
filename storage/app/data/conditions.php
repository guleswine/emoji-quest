<?php

return [
    ['name' => 'No starting quest','function'=>'conditionHasQuest','params'=>["quest_id"=> 1],'condition_group_id'=>1,'result'=>false],
    ['name' => 'No enemies in the cave','function'=>'conditionEmptyPlace',
        'params'=>["cell_id"=> '%relative_cell_id% start_cave center 0 0',"radius_size"=>7],'condition_group_id'=>2,'result'=>true],
    ['name' => 'Hero has a rat','function'=>'conditionHasItem','params'=>["item_id"=> 6],'condition_group_id'=>3,'result'=>true] ,
    ['name' => 'Quest active','function'=>'conditionActiveQuest','params'=>["quest_id"=> 1],'condition_group_id'=>3,'result'=>true],
];

