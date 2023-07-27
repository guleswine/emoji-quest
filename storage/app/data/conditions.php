<?php

return [
    ['name' => 'Отсутствие стартового квеста','function'=>'conditionHasQuest','params'=>["quest_id"=> 1],'condition_group_id'=>1,'result'=>false],
    ['name' => 'Отсутствие врагов в пещере','function'=>'conditionEmptyPlace','params'=>["cell_id"=> 3834,"radius_size"=>7],'condition_group_id'=>2,'result'=>true],
    ['name' => 'Наличие крысы у героя','function'=>'conditionHasItem','params'=>["item_id"=> 6],'condition_group_id'=>3,'result'=>true],
    ['name' => 'Наличие активного квеста','function'=>'conditionActiveQuest','params'=>["quest_id"=> 1],'condition_group_id'=>3,'result'=>true],
];

