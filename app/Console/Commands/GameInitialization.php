<?php

namespace App\Console\Commands;

use App\Enums\SideEnum;
use App\Models\Answer;
use App\Models\Blueprint;
use App\Models\Cell;
use App\Models\CellObjectTemplate;
use App\Models\Condition;
use App\Models\ConditionGroup;
use App\Models\Enemy;
use App\Models\EventAction;
use App\Models\HeroTravelStat;
use App\Models\Item;
use App\Models\Map;
use App\Models\Quest;
use App\Models\Question;
use App\Models\QuestState;
use App\Models\Skill;
use App\Models\SurfaceTemplate;
use App\Models\SurfaceType;
use App\Models\Talent;
use App\Models\TalentLevel;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorldController;
use App\Models\WorldControllerEvent;
use App\Repositories\CellRepository;
use App\Repositories\MapRepository;
use App\Services\CellService;
use App\Services\HeroService;
use App\Services\MapGeneratorService;
use App\Services\MapService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

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

        $this->call('data:load',['name'=>'Surface templates','path'=>'app/data/surface_templates.php','model'=>SurfaceTemplate::class,'search-attributes'=>['key']]);
        MapGeneratorService::loadEmojies();
        $objects = include storage_path('app/data/maps.php');
        foreach ($objects as $object_attr) {
            $this->call('map:create',
                ['name'=>$object_attr['name'],'size'=>$object_attr['size'],'--surface'=>$object_attr['surface'],
                    '--ambient-color'=>$object_attr['ambient-color']]);
        }



        $admin_user = User::firstOrCreate([
            'name' => 'admin',
            'email' => env('EQ_ADMIN_USER_EMAIL'),
        ], [
            'password' => bcrypt(env('EQ_ADMIN_USER_PASSWORD')),
        ]);
        $guest_user = User::firstOrCreate([
            'name' => 'Guest',
            'email' => 'guest@'.parse_url(env('APP_URL'))['host'],
        ], [
            'password' => bcrypt('guest'),
        ]);
        $this->info('Guest user created, id:'.$guest_user->id);
        $imported_data = [
            ['name'=>'Blueprints','path'=>'app/data/blueprints.php','model'=>Blueprint::class,'search_attributes'=>['name','category','subcategory','type']],
            ['name'=>'Talents','path'=>'app/data/talents.php','model'=>Talent::class,'search_attributes'=>['name']],
            ['name'=>'Talent levels','path'=>'app/data/talent_levels.php','model'=>TalentLevel::class,'search_attributes'=>['level']],
            ['name'=>'Skills','path'=>'app/data/skills.php','model'=>Skill::class,'search_attributes'=>['name']],
            ['name'=>'Enemies','path'=>'app/data/enemies.php','model'=>Enemy::class,'search_attributes'=>['name','emoji']],
            ['name'=>'Items','path'=>'app/data/items.php','model'=>Item::class,'search_attributes'=>['name','type']],
            ['name'=>'NPCs','path'=>'app/data/npcs.php','model'=>Unit::class,'search_attributes'=>['name','type']],
            ['name'=>'Answers','path'=>'app/data/answers.php','model'=>Answer::class,'search_attributes'=>['question_id','text']],
            ['name'=>'Condition groups','path'=>'app/data/condition_groups.php','model'=>ConditionGroup::class,'search_attributes'=>['name','type']],
            ['name'=>'Conditions','path'=>'app/data/conditions.php','model'=>Condition::class,'search_attributes'=>['name','condition_group_id','function']],
            ['name'=>'Event actions','path'=>'app/data/event_actions.php','model'=>EventAction::class,'search_attributes'=>['event_id','class','function']],
            ['name'=>'Quests','path'=>'app/data/quests.php','model'=>Quest::class,'search_attributes'=>['name',]],
            ['name'=>'Quest states','path'=>'app/data/quest_states.php','model'=>QuestState::class,'search_attributes'=>['quest_id','name']],
            ['name'=>'Surface types','path'=>'app/data/surface_types.php','model'=>SurfaceType::class,'search_attributes'=>['name']],
            ['name'=>'Questions','path'=>'app/data/questions.php','model'=>Question::class,'search_attributes'=>['text']],
            ['name'=>'World controllers','path'=>'app/data/world_controllers.php','model'=>WorldController::class,'search_attributes'=>['name']],
            ['name'=>'World controller events','path'=>'app/data/world_controller_events.php','model'=>WorldControllerEvent::class,'search_attributes'=>['world_controller_id','function','object_id']],
            ['name'=>'Cell object templates','path'=>'app/data/cell_object_templates.php','model'=>CellObjectTemplate::class,'search_attributes'=>['key']],
        ];
        foreach ($imported_data as $data_array) {
            $this->call('data:load',['name'=>$data_array['name'],'path'=>$data_array['path'],'model'=>$data_array['model'],'search-attributes'=>$data_array['search_attributes']]);

        }


        $HS = new HeroService();
        $HS->create($admin_user);
        $hero = $HS->create($guest_user);

        $HS::addExperience($hero,500);
        $HS::addCoins($hero,50);
        $this->info('Guest hero created, id:'.$guest_user->id);




        $map_objects = include storage_path('app/data/map_filling.php');
        $bar = $this->output->createProgressBar(count($map_objects));
        $this->info('Start fill map objects...');
        $bar->start();
        foreach ($map_objects as $mo) {
            MapGeneratorService::fillingObjectProcessing($mo);
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();


        $npc = Unit::where('type','NPC')->first();
        CellService::addObjectUnit($npc);



        return Command::SUCCESS;
    }
}
