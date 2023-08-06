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
        'attack_head'=>':name_attacker deals :name_target :damage head damage!',
        'attack_body'=>':name_attacker deals :name_target :damage torso damage!',
        'attack_limbs'=>':name_attacker deals :name_target :damage limb damage!'
    ],
    'notify'=>[
        'defence_head'=>'Head protected!',
        'defence_body'=>'Body protected!',
        'defence_limbs'=>'Limbs protected!',
    ],
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

];
