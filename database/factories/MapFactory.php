<?php

namespace Database\Factories;

use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Map>
 */
class MapFactory extends Factory
{
    protected $model = Map::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->name;
        return [
            'name'=> $name,
            'key'=> Str::snake($name),
            'emoji'=>'map',
            'size_width'=>100,
            'size_height'=>100,
            'ambient_color'=>'bg-white'
        ];
    }
}
