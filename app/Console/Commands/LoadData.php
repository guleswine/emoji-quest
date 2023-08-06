<?php

namespace App\Console\Commands;

use App\Services\MapGeneratorService;
use Illuminate\Console\Command;

class LoadData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:load
                            {name : Data name}
                            {path : Data path}
                            {model :  Model class}
                            {search-attributes* : Search attributes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path  = $this->argument('path');
        $model = $this->argument('model');
        $search_attributes_param = $this->argument('search-attributes');

        $objects = include storage_path($path);
        $bar = $this->output->createProgressBar(count($objects));
        $this->info('Start create '.$name);
        $bar->start();
        foreach ($objects as $object_attr) {
            $search_keys = array_flip($search_attributes_param);
            $fill_attributes = array_diff_key($object_attr,$search_keys);
            $fill_attributes = MapGeneratorService::renderDataVariables($fill_attributes);

            $search_attributes =  array_intersect_key($object_attr,$search_keys);
            $model::firstOrCreate($search_attributes,$fill_attributes);
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
        //$this->info($name.' created');
        return Command::SUCCESS;
    }
}
