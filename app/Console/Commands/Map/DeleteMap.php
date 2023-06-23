<?php

namespace App\Console\Commands\Map;

use App\Models\Map;
use Illuminate\Console\Command;

class DeleteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map:delete
                            {map : Map id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove map with cells';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $map_id = $this->argument('map');
        Map::find($map_id)->delete();
        $this->info("Map ($map_id) was deleted");

        return Command::SUCCESS;
    }
}
