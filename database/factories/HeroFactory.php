<?php

namespace Database\Factories;

use App\Models\Hero;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hero>
 */
class HeroFactory extends Factory
{
    protected $model = Hero::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'emoji'=>'man_standing',
            'size'=>10,
            'experience'=>0,
            'experience_total'=>50,
            'lvl'=>1,
            'coins'=>0,
        ];
    }
}
