<?php

namespace App\Console\Commands\Map;

use App\Models\Cell;
use App\Models\Map;
use Illuminate\Console\Command;

class CreateMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map:create
                            {name : Map name}
                            {size? : Map width and height x:y format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new game map, write name and possible size in x:y format';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $attributes = [];
        $name = $this->argument('name');
        $attributes['name'] = $name;
        $size = $this->argument('size');
        if ($size) {
            $data = explode(':', $size);
            $size_width = $data[0];
            $size_height = $data[1];
            if (is_numeric($size_width) and $size_width > 0 and is_numeric($size_height) and $size_height > 0) {
                $attributes['size_width'] = $size_width;
                $attributes['size_height'] = $size_height;
            }
        }
        $map = Map::factory()->create($attributes);

        $cell_name = $this->ask('Cells name?');
        $cell_emoji = $this->ask('Cells emoji?');
        $surface_type = $this->ask('Cells surface type?');

        $bar = $this->output->createProgressBar($map->size_width * $map->size_height);
        $this->info('Start create cells for map...');
        $bar->start();

        for ($x = 0; $x < $map->size_width; $x++) {
            for ($y = 0; $y < $map->size_height; $y++) {
                $cell_attributes = [];
                $cell_attributes['map_id'] = $map->id;
                $cell_attributes['x'] = $x;
                $cell_attributes['y'] = $y;
                if ($cell_name) {
                    $cell_attributes['name'] = $cell_name;
                }
                if ($cell_emoji) {
                    $cell_attributes['emoji_name'] = $cell_emoji;
                }
                if ($surface_type) {
                    $cell_attributes['surface_type_name'] = $surface_type;
                }
                Cell::factory()->create($cell_attributes);
                $bar->advance();
            }
        }
        $cell = Cell::where('map_id', $map->id)
            ->where('x', round($map->size_width / 2))
            ->where('y', round($map->size_height / 2))
            ->first();
        $map->start_cell_id = $cell->id;
        $map->save();
        $bar->finish();

        $this->newLine();
        $this->info('Map created, id:' . $map->id);

        return Command::SUCCESS;
    }
}
