<?php

namespace App\Services;

use App\Enums\SideEnum;
use App\Events\GameNotification;
use App\Models\Blueprint;
use App\Models\Cell;
use App\Models\Hero;
use App\Models\HeroBlueprint;
use App\Models\HeroEquipment;
use App\Models\HeroInventory;
use App\Models\HeroSkill;
use App\Models\HeroStat;
use App\Models\HeroTalent;
use App\Models\HeroTravelStat;
use App\Models\InventorySlot;
use App\Models\Item;
use App\Models\Map;
use App\Models\Skill;
use App\Models\Talent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HeroService
{
    public function create(User $user)
    {

        $hero = Hero::where('user_id', $user->id)->first();
        if (!$hero) {
            $map = Map::find(1);
            $x = floor($map->size_width/2);
            $y = floor($map->size_height/2);
            $StartCell = MapService::getRelativeCell('start_world',SideEnum::Center,0,-1);
            $hero = Hero::factory()->create([
                'user_id'=>$user->id,
                'name'=>$user->name,
                'map_id'=>1,
                'cell_id'=>$StartCell->id,
            ]);
            self::updateCell($hero, $StartCell->id);
        }
        //Добавление характиристик
        $base_hero_stats = ['attack'=>5, 'dodge'=>0, 'critical_hit'=>0, 'armor'=>1, 'attack_range'=>1, 'action_points'=>4, 'health'=>50];
        foreach ($base_hero_stats as $name=>$value) {
            HeroStat::firstOrCreate([
               'hero_id'=>$hero->id,
               'attribute'=>$name,
            ], [
                'final_value'=>$value,
                'value'=>$value,
                'base_value'=>$value,
            ]);
        }

        //Добавление характеристик путешественника
        $base_hero_travel_stats = ['life_recovery_percent'=>30, 'life_recovery_speed'=>5];
        foreach ($base_hero_travel_stats as $name=>$value) {
            HeroTravelStat::firstOrCreate([
                'hero_id'=>$hero->id,
                'attribute'=>$name,
            ], [
                'value'=>$value,
            ]);
        }

        //Добавление стартовых талантов
        $talents = Talent::where('available_at_start', true)->get();
        foreach ($talents as $talent) {
            $hero_talent = HeroTalent::where('hero_id', $hero->id)->where('talent_id', $talent->id)->exists();
            if (!$hero_talent) {
                HeroTalent::factory()->create([
                    'hero_id'=>$hero->id,
                    'talent_id'=>$talent->id,
                ]);
            }

        }
        //создание слотов инвентаря
        $InventoryTypes = ['items', 'equipment', 'resources', 'appearance'];

        foreach ($InventoryTypes as $type) {
            $Inventory = HeroInventory::firstOrCreate([
                'hero_id'=>$hero->id,
                'type'=>$type,
            ],[
                'slots_count'=>25
            ]);
            $InventorySlotsCount = InventorySlot::where('inventory_id', $Inventory->id)->count();
            for ($i = $InventorySlotsCount; $i < $Inventory->slots_count; $i++) {
                InventorySlot::create([
                    'inventory_id'=>$Inventory->id,
                ]);
            }
        }
        //Создание слотов экпипировки
        $equipment = [
            ['name'=>'Головной убор', 'type'=>'equipment', 'category'=>'head', 'side'=>'left', 'sort_order'=>1],
            ['name'=>'Скин', 'type'=>'appearance', 'category'=>'appearance', 'side'=>'right', 'sort_order'=>1],
            ['name'=>'Кисти', 'type'=>'equipment', 'category'=>'hands', 'side'=>'left', 'sort_order'=>2],
            ['name'=>'Торс', 'type'=>'equipment', 'category'=>'torso', 'side'=>'right', 'sort_order'=>2],
            ['name'=>'Левая рука', 'type'=>'equipment', 'category'=>'left_hand', 'side'=>'left', 'sort_order'=>3],
            ['name'=>'Правая рука', 'type'=>'equipment', 'category'=>'right_hand', 'side'=>'right', 'sort_order'=>3],
            ['name'=>'Ступни', 'type'=>'equipment', 'category'=>'feet', 'side'=>'left', 'sort_order'=>4],
            ['name'=>'Ноги', 'type'=>'equipment', 'category'=>'legs', 'side'=>'right', 'sort_order'=>4],
        ];
        foreach ($equipment as $equip) {
            $equip_attr = $equip;
            $equip_attr['hero_id'] = $hero->id;
            HeroEquipment::firstOrCreate($equip_attr);
        }

        HeroSkill::firstOrCreate([
            'hero_id'=>$hero->id,
            'skill_id'=>1,
            'unlocked'=>true,
            'learned'=>false
        ]);
        $IS = new InventoryService($hero);
        $IS->addItem(1);
        $IS->addItem(2);
        $IS->addItem(3);
        $IS->addItem(4);
        return  $hero;
    }

    public static function setCell(Hero &$hero, int $cell_id)
    {
        CellService::removeObject($hero->cell_id, 'Hero', $hero->id);
        $hero->cell_id = $cell_id;
        CellService::addObjectHero($hero);

    }

    public static function updateCell(Hero &$hero, int $cell_id)
    {
        self::setCell($hero, $cell_id);
        $hero->save();
    }

    public static function addExperience(Hero $hero, int $exp)
    {
        $hero->experience += $exp;
        while ($hero->experience >= $hero->experience_total) {
            $hero->experience -= $hero->experience_total;
            self::increaceLvl($hero);
            $hero->experience_total = round(pow($hero->lvl, 1.2), 1) * 50;
        }
        $hero->save();

    }

    public static function increaceLvl(Hero &$hero)
    {
        $hero->lvl++;
        $hero->skill_points++;
        GameNotification::dispatch($hero->id, 'info', 'Вы получили новый уровень');
    }

    public static function addCoins(Hero &$hero, int $count)
    {
        $hero->coins += $count;
        $hero->save();
        //GameNotification::dispatch($hero->id,'info',"Вы получили $count монет");
    }

    public static function removeCoins(Hero &$hero, int $count)
    {
        $hero->coins -= $count;
        $hero->save();
    }

    public static function learnBlueprint(Hero &$hero, int $blueprint_id)
    {
        $blueprint = Blueprint::find($blueprint_id);
        HeroBlueprint::firstOrCreate(
            ['hero_id' => $hero->id, 'blueprint_id' => $blueprint_id],
            ['category'=>$blueprint->category, 'subcategory'=>$blueprint->subcategory, 'type'=>$blueprint->type]
        );
    }

    public static function learnSkill(Hero &$hero, int $skill_id)
    {
        $skill = Skill::find($skill_id);
        $hero_skill = HeroSkill::where('hero_id', $hero->id)->where('skill_id', $skill_id)->first();
        if (!$hero_skill->learned and $hero->skill_points > 0) {
            $hero_skill->learned = true;
            $hero_skill->save();
            $hero->skill_points--;
            $hero->save();
            SkillService::unlockNextSkill($skill, $hero);
            if ($skill->function) {
                $function = $skill->function;
                SkillService::$function($hero);
            }
        }

    }

    public static function removeItem(Hero &$hero, int $item_id)
    {
        $hero_item_slot = DB::table('hero_inventories')
            ->join('inventory_slots', 'inventory_slots.inventory_id', '=', 'hero_inventories.id')
            ->where('hero_inventories.hero_id', $hero->id)
            ->where('inventory_slots.item_id', $item_id)
            ->select('inventory_slots.id')
            ->first();
        $hero_item_slot = InventorySlot::find($hero_item_slot->id);
        $hero_item_slot->items_count -= 1;
        if ($hero_item_slot->items_count == 0) {
            $hero_item_slot->item_id = null;
        }
        $hero_item_slot->save();
        //$item = Item::find($item_id);
        //GameNotification::dispatch($hero->id,'info',"Вы получили $item->name монет");
    }
}
