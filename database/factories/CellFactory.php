<?php

namespace Database\Factories;

use App\Models\Cell;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cell>
 */
class CellFactory extends Factory
{
    protected $model = Cell::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=>'Дерево',
            'emoji_name'=>'deciduous_tree',
            'surface_type_name'=>'ground',
            'size'=>8,
        ];
    }
}
