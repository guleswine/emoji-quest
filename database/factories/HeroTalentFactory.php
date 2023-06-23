<?php

namespace Database\Factories;

use App\Models\HeroTalent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HeroTalent>
 */
class HeroTalentFactory extends Factory
{
    protected $model = HeroTalent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'level'=>1,
            'current_progress'=>0,
            'total_progress'=>100,

        ];
    }
}
