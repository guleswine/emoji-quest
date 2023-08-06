<?php

return [
    //Забор
    ['type'=>'rectangle','map'=>'start_world',
        'lb_cell'=>['side'=>'center','offset_x'=>-2,'offset_y'=>1],
        'rt_cell'=>['side'=>'center','offset_x'=>2,'offset_y'=>5],
        'templates'=>['fence'],'hollow'=>true],
    //Клумба
    ['type'=>'rectangle','map'=>'start_world',
        'lb_cell'=>['side'=>'center','offset_x'=>-1,'offset_y'=>2],
        'rt_cell'=>['side'=>'center','offset_x'=>1,'offset_y'=>4],
        'templates'=>['flower_hib','flower_ros','flower_blo','flower_tul'],'hollow'=>true],
    //Тропинка
    ['type'=>'single','map'=>'start_world','side'=>'center','offset_x'=>0,'offset_y'=>2,'templates'=>['trail']],
    ['type'=>'single','map'=>'start_world','side'=>'center','offset_x'=>0,'offset_y'=>1,'templates'=>['trail']],
    ['type'=>'rectangle','map'=>'start_world',
        'lb_cell'=>['side'=>'center','offset_x'=>-20,'offset_y'=>0],
        'rt_cell'=>['side'=>'center','offset_x'=>0,'offset_y'=>0],
        'templates'=>['trail'],'hollow'=>true],
    //Вход в дом
    ['type'=>'single','map'=>'start_world','side'=>'center','offset_x'=>0,'offset_y'=>3,'templates'=>['house_with_garden'],
        'extra'=>['transfer_to_cell_id'=>'%relative_cell_id% start_house top 0 0']],
    ['type'=>'single','map'=>'start_house','side'=>'top','offset_x'=>0,'offset_y'=>0,'templates'=>['door'],
        'extra'=>['transfer_to_cell_id'=>'%relative_cell_id% start_world center 0 3']],
    //Горы
    ['type'=>'circle','map'=>'start_world','side'=>'center','offset_x'=>6,'offset_y'=>-5,'radius'=>4,'templates'=>['mountain','snow_mountain']],
    //Море
    ['type'=>'circle','map'=>'start_world','side'=>'center','offset_x'=>16,'offset_y'=>0,'radius'=>10,'templates'=>['sea']],
    //Камни в пещере
    ['type'=>'rectangle','map'=>'start_cave',
        'lb_cell'=>['side'=>'left-bottom','offset_x'=>0,'offset_y'=>0],
        'rt_cell'=>['side'=>'right-top','offset_x'=>0,'offset_y'=>0],
        'templates'=>['rock'],'hollow'=>true],
    //Вход в пещеру
    ['type'=>'single','map'=>'start_world','side'=>'center','offset_x'=>5,'offset_y'=>-8,'templates'=>['cave_entrance'],
        'extra'=>['transfer_to_cell_id'=>'%relative_cell_id% start_cave left 0 0']],
    ['type'=>'single','map'=>'start_cave','side'=>'left','offset_x'=>0,'offset_y'=>0,'templates'=>['ladder'],
        'extra'=>['transfer_to_cell_id'=>'%relative_cell_id% start_world center 5 -8']],
];
