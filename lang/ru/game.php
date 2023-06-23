<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'events'=>[
        'attack_head'=>':name_attacker наносит :name_target :damage ед. урона в голову!',
        'attack_body'=>':name_attacker наносит :name_target :damage ед. урона в туловище!',
        'attack_limbs'=>':name_attacker наносит :name_target :damage ед. урона в конечности!'
    ],
    'notify'=>[
        'defence_head'=>'Поставлена защита на голову!',
        'defence_body'=>'Поставлена защита на туловище!',
        'defence_limbs'=>'Поставлена защита на конечности!',
    ],
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

];
