<?php

namespace App\Console\Commands\Map;

use App\Models\Cell;
use App\Models\Map;
use App\Models\SurfaceTemplate;
use App\Repositories\MapRepository;
use App\Repositories\SurfaceTemplateRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map:create
                            {name : Map name}
                            {size? : Map width and height x:y format}
                            {--surface : Surface cells}
                            {--start-side : Side for start cell}
                            {--ambient-color : Ambient map}';

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
        $size = $this->argument('size') ?? config('emojiquest.default_map_size');
        if ($size) {
            $data = explode(':', $size);
            $size_width = $data[0];
            $size_height = $data[1];
            if (is_numeric($size_width) and $size_width > 0 and is_numeric($size_height) and $size_height > 0) {
                $attributes['size_width'] = $size_width;
                $attributes['size_height'] = $size_height;
            }
        }
        $attributes['key'] = Str::snake($name);
        if (MapRepository::getMapByKey($attributes['key'])) {
            $this->alert('Map with this key already exists');
            return ;
        }
        $map = Map::factory()->create($attributes);
        $surface_template_key = $this->option('surface');
        if ($surface_template_key) {
            $surface_template = SurfaceTemplateRepository::getTemplate($surface_template_key);
            $cell_name = $surface_template->name;
            $cell_emoji = $surface_template->emoji;
            $surface_type = $surface_template->type;
            $size = $surface_template->size;

        }else{
            $cell_name = $this->ask('Cells name?');
            $cell_emoji = $this->ask('Cells emoji?');
            $surface_type = $this->ask('Cells surface type?');
            $size = $this->ask('Cells size?',8);
        }


        $bar = $this->output->createProgressBar($map->size_width * $map->size_height);
        $this->info('Start create cells for map...');
        $bar->start();

        $map_cells = [];
        $counter = 0;
        for ($x = 0; $x < $map->size_width; $x++) {
            for ($y = 0; $y < $map->size_height; $y++) {
                $cell_attributes = [];
                $cell_attributes['map_id'] = $map->id;
                $cell_attributes['x'] = $x;
                $cell_attributes['y'] = $y;
                $cell_attributes['size'] = $size;
                if ($cell_name) {
                    $cell_attributes['name'] = $cell_name;
                }
                if ($cell_emoji) {
                    $cell_attributes['emoji'] = $cell_emoji;
                }
                if ($surface_type) {
                    $cell_attributes['surface_type'] = $surface_type;
                }
                $map_cells[] = $cell_attributes;
                //Cell::factory()->create($cell_attributes);
                $bar->advance();

                $counter++;
                if ($counter>=5000){
                    Cell::insert($map_cells);
                    $counter=0;
                    $map_cells=[];
                }
            }
        }
        Cell::insert($map_cells);

        $start_side = $this->option('start-side');
        if ($start_side){
            switch ($start_side) {
                case 'center':
                    $cell_x = round($map->size_width / 2);
                    $cell_y = round($map->size_height / 2);
                    break;
                case 'left':
                    $cell_x = 0;
                    $cell_y = round($map->size_height / 2);
                    break;
                case 'right':
                    $cell_x = $map->size_width-1;
                    $cell_y = round($map->size_height / 2);
                    break;
                case 'top':
                    $cell_x = round($map->size_width / 2);
                    $cell_y = $map->size_height-1;
                    break;
                case 'bottom':
                    $cell_x = round($map->size_width / 2);
                    $cell_y = 0;
                    break;
                default:
                    $cell_x = round($map->size_width / 2);
                    $cell_y = round($map->size_height / 2);
            }

            $cell = Cell::where('map_id', $map->id)
                ->where('x',$cell_x )
                ->where('y', $cell_y)
                ->first();
            $map->start_cell_id = $cell->id;

        }

        $ambient_color = $this->option('ambient-color') ?? 'bg-white';
        $map->ambient_color = $ambient_color;
        $map->save();
        $bar->finish();

        $this->newLine();
        $this->info('Map created, key:' . $map->key);

        return Command::SUCCESS;
    }
}
