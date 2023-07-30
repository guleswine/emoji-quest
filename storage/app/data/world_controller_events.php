<?php

return [
    ['world_controller_id' => 1,'function'=>'spawnEnemyOnArea','object_id'=>1,
        'params'=>["cell_id"=>'%relative_cell_id% start_cave center 3 0', "enemy_id"=>1, "radius_size"=> 4]],

    ['world_controller_id' => 1,'function'=>'spawnEnemyOnArea','object_id'=>2,
        'params'=>["cell_id"=>'%relative_cell_id% start_cave center 3 0', "enemy_id"=>2, "radius_size"=> 4]],

    ['world_controller_id' => 1,'function'=>'removeItemsOnArea','object_id'=>null,
        'params'=>["cell_id"=>'%relative_cell_id% start_cave center 0 0',  "radius_size"=> 7]],
];
