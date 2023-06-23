<?php

namespace App\Services;

use App\Models\Cell;
use App\Models\Condition;
use Illuminate\Support\Facades\DB;

class ConditionService
{
    public $hero;

    public function __construct($hero)
    {
        $this->hero = $hero;
    }

    public function execute($condition_group_id)
    {
        $conditions = Condition::where('condition_group_id', $condition_group_id)->get();

        $result = true;
        foreach ($conditions as $condition) {
            $function = $condition->function;
            $condition_result = $this->$function($condition->params);

            if (!$condition->result) {
                $condition_result = !$condition_result;
            }
            $result = $result && $condition_result;
        }

        return $result;
    }

    public function conditionHasQuest($params): bool
    {
        $QS = new QuestService();
        $quest_id = $params['quest_id'];
        $result = $QS->hasQuest($this->hero->id, $quest_id);

        return $result;
    }

    public function conditionCompletedQuest($params): bool
    {
        $QS = new QuestService();
        $quest_id = $params['quest_id'];
        $result = $QS->completedQuest($this->hero->id, $quest_id);

        return $result;
    }

    public function conditionActiveQuest($params): bool
    {
        $QS = new QuestService();
        $quest_id = $params['quest_id'];
        $result = $QS->activeQuest($this->hero->id, $quest_id);

        return $result;
    }

    public function conditionHasItem($params): bool
    {
        $item_id = $params['item_id'];
        $hasItem = DB::table('hero_inventories')
            ->join('inventory_slots', 'inventory_slots.inventory_id', '=', 'hero_inventories.id')
            ->where('hero_inventories.hero_id', $this->hero->id)
            ->where('inventory_slots.item_id', $item_id)
            ->exists();

        return $hasItem;
    }

    public function conditionEmptyPlace($params): bool
    {
        $cell_id = $params['cell_id'];
        $radius_size = $params['radius_size'];
        $cell = Cell::find($cell_id);
        $heroes = DB::table('cells')
            ->join('cell_objects', 'cell_objects.cell_id', '=', 'cells.id')
            ->where('cells.map_id', $cell->map_id)
            ->whereBetween('cells.y', [$cell->y - $radius_size, $cell->y + $radius_size])
            ->whereBetween('cells.x', [$cell->x - $radius_size, $cell->x + $radius_size])
            ->whereIn('cell_objects.object_class', ['Hero', 'Unit'])
            ->exists();

        return !$heroes;

    }
}
