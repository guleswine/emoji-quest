<?php

namespace App\Console\Commands;

use App\Models\Blueprint;
use App\Models\Enemy;
use App\Models\HeroTravelStat;
use App\Models\Item;
use App\Models\Skill;
use App\Models\Talent;
use App\Models\TalentLevel;
use App\Models\Unit;
use App\Models\User;
use App\Services\HeroService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class GameInitialization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:init';

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
        $this->call('map:create', ['name'=>'Start world','size'=>config('emojiquest.default_map_size'),'--surface'=>'trees']);

        $guest_user = User::firstOrCreate([
            'name' => 'Guest',
            'email' => 'guest@'.parse_url(env('APP_URL'))['host'],
        ], [
            'password' => bcrypt('guest'),
        ]);
        $imported_data = [
            ['name'=>'Blueprints','path'=>'app/data/blueprints.php','model'=>Blueprint::class,'search_attributes'=>['name','category','subcategory','type']],
            ['name'=>'Talents','path'=>'app/data/talents.php','model'=>Talent::class,'search_attributes'=>['name']],
            ['name'=>'Talent levels','path'=>'app/data/talent_levels.php','model'=>TalentLevel::class,'search_attributes'=>['level']],
            ['name'=>'Skills','path'=>'app/data/skills.php','model'=>Skill::class,'search_attributes'=>['name']],
            ['name'=>'Enemies','path'=>'app/data/enemies.php','model'=>Enemy::class,'search_attributes'=>['name','emoji']],
            ['name'=>'Items','path'=>'app/data/items.php','model'=>Item::class,'search_attributes'=>['name','type']],
            ['name'=>'NPCs','path'=>'app/data/npcs.php','model'=>Unit::class,'search_attributes'=>['name','type']],
        ];
        foreach ($imported_data as $data_array) {

            $objects = include storage_path($data_array['path']);
            $bar = $this->output->createProgressBar(count($objects));
            $this->info('Start create '.$data_array['name'],'...');
            $bar->start();
            foreach ($objects as $object_attr) {
                $search_keys = array_flip($data_array['search_attributes']);
                $fill_attributes = array_diff_key($object_attr,$search_keys);
                $search_attributes =  array_intersect_key($object_attr,$search_keys);
                $data_array['model']::firstOrCreate($search_attributes,$fill_attributes);
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
            $this->info($data_array['name'].' created');
        }

        $this->info('Guest user created, id:'.$guest_user->id);
        $HS = new HeroService();
        $HS->create($guest_user);
        $this->info('Guest hero created, id:'.$guest_user->id);


        return Command::SUCCESS;
    }
}
