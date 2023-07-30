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
        $this->call('map:create',
            ['name'=>'Start world','size'=>config('emojiquest.default_map_size'),'--surface'=>'forest',
                '--start-side'=>'center','--ambient-color'=>'rgb(255 255 255)']);

        $this->call('map:create',
            ['name'=>'Start house','size'=>'11:11','--surface'=>'house',
                '--start-side'=>'top','--ambient-color'=>'rgb(255 237 213)']);
        $this->call('map:create',
            ['name'=>'Start cave','size'=>'15:15','--surface'=>'cave',
                '--start-side'=>'left','--ambient-color'=>'rgb(63 63 70)']);



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
            ['name'=>'Answers','path'=>'app/data/answers.php','model'=>Answer::class,'search_attributes'=>['question_id','text']],
            ['name'=>'Condition groups','path'=>'app/data/condition_groups.php','model'=>ConditionGroup::class,'search_attributes'=>['name','type']],
            ['name'=>'Conditions','path'=>'app/data/conditions.php','model'=>Condition::class,'search_attributes'=>['name','condition_group_id','function']],
            ['name'=>'Event actions','path'=>'app/data/event_actions.php','model'=>EventAction::class,'search_attributes'=>['event_id','class','function']],
            ['name'=>'Quests','path'=>'app/data/quests.php','model'=>Quest::class,'search_attributes'=>['name',]],
            ['name'=>'Quest states','path'=>'app/data/quest_states.php','model'=>QuestState::class,'search_attributes'=>['quest_id','name']],
            ['name'=>'Surface types','path'=>'app/data/surface_types.php','model'=>SurfaceType::class,'search_attributes'=>['name']],
            ['name'=>'Surface templates','path'=>'app/data/surface_templates.php','model'=>SurfaceTemplate::class,'search_attributes'=>['key']],
            ['name'=>'Questions','path'=>'app/data/questions.php','model'=>Question::class,'search_attributes'=>['text']],
            ['name'=>'World controllers','path'=>'app/data/world_controllers.php','model'=>WorldController::class,'search_attributes'=>['name']],
            ['name'=>'World controller events','path'=>'app/data/world_controller_events.php','model'=>WorldControllerEvent::class,'search_attributes'=>['world_controller_id','function','object_id']],
            ['name'=>'Cell object templates','path'=>'app/data/cell_object_templates.php','model'=>CellObjectTemplate::class,'search_attributes'=>['key']],
        ];
        foreach ($imported_data as $data_array) {

            $objects = include storage_path($data_array['path']);
            $bar = $this->output->createProgressBar(count($objects));
            $this->info('Start create '.$data_array['name'],'...');
            $bar->start();
            foreach ($objects as $object_attr) {
                $search_keys = array_flip($data_array['search_attributes']);
                $fill_attributes = array_diff_key($object_attr,$search_keys);
                foreach ($fill_attributes as $key=>$value) {
                    if (is_array($value)){
                        foreach ($value as $k=>$v) {
                            if (Str::startsWith($v,'%relative_cell_id%')){
                                $params = explode(' ',$v);
                                $fill_attributes[$key][$k] = MapService::getRelativeCell($params[1],$params[2],$params[3],$params[4])->id;

                            }
                        }
                    }else{
                        if (Str::startsWith($value,'%relative_cell_id%')){
                            $params = explode(' ',$value);
                            $fill_attributes[$key] = MapService::getRelativeCell($params[1],$params[2],$params[3],$params[4])->id;

                        }
                    }
                }
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
        $hero = $HS->create($guest_user);
        $HS::addExperience($hero,500);
        $HS::addCoins($hero,50);
        $this->info('Guest hero created, id:'.$guest_user->id);


        //add objects
        $map = Map::find(1);
        $map_house = Map::find(2);
        $cell = MapService::getRelativeCell($map,SideEnum::Center,0,3);
        $cell_house = MapService::getRelativeCell($map_house,SideEnum::Top,0,0);

        CellService::updateCellByTemplateKey($cell,'house_with_garden',['transfer_to_cell_id'=>$cell_house->id]);
        CellService::updateCellByTemplateKey($cell_house,'door',['transfer_to_cell_id'=>$cell->id]);

        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,-1,1),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,-2,2),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,-2,3),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,-2,4),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,-2,5),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,-1,5),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,0,5),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,1,5),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,2,5),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,2,4),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,2,3),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,2,2),'fence');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,1,1),'fence');
        $cell_left = MapService::getRelativeCell($map,SideEnum::Center,-1,2);
        $cell_right = MapService::getRelativeCell($map,SideEnum::Center,1,4);
        MapGeneratorService::fillRectangleOnMapRegion($map,$cell_left,$cell_right,['flower_hib','flower_ros','flower_blo','flower_tul'],true);

        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,0,2),'trail');
        CellService::updateCellByTemplateKey(MapService::getRelativeCell($map,SideEnum::Center,0,1),'trail');

        $cell_for_npc = MapService::getRelativeCell($map_house,SideEnum::Left,1,0);
        $npc = Unit::where('type','NPC')->first();
        $npc->update(['cell_id'=>$cell_for_npc->id]);
        CellService::addObjectUnit($npc);

        $cell_mounts = MapService::getRelativeCell($map,SideEnum::Center,6,-5);
        MapGeneratorService::fillCircleOnMapRegion($map,$cell_mounts,4,['mountain','snow_mountain']);
        $cell_sea = MapService::getRelativeCell($map,SideEnum::Center,16,0);
        MapGeneratorService::fillCircleOnMapRegion($map,$cell_sea,10,['sea']);
        $cell_cave_entrance = MapService::getRelativeCell($map,SideEnum::Center,5,-8);


        $cell_left = MapService::getRelativeCell('start_cave',SideEnum::LeftBottom,0,0);
        $cell_right = MapService::getRelativeCell('start_cave',SideEnum::RightTop,0,0);
        MapGeneratorService::fillRectangleOnMapRegion('start_cave',$cell_left,$cell_right,['rock'],true);

        $cell_cave = MapService::getRelativeCell('start_cave',SideEnum::Left,0,0);
        CellService::updateCellByTemplateKey($cell_cave_entrance,'cave_entrance',['transfer_to_cell_id'=>$cell_cave->id]);
        CellService::updateCellByTemplateKey($cell_cave,'ladder',['transfer_to_cell_id'=>$cell_cave_entrance->id]);
        $this->info('Update Cave');

        return Command::SUCCESS;
    }
}
