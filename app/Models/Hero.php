<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'emoji', 'cell_id', 'experience_total', 'lvl', 'experience', 'coins'];

    public $stats;

    /*
    public function updateCell($cell_id){
        $old_cell = Cell::find($this->cell_id);
        $old_cell->clearObject();
        $this->cell_id = $cell_id;
        $this->save();
        Cell::find($cell_id)->update(['object_class'=>'hero','object_id'=>$this->id]);
    }
    */

    public function loadStats()
    {
        $this->stats = HeroStat::where('hero_id', $this->id)->get()->keyBy('name');
    }

    public function getCurrentStat($stat_name)
    {
        if (!$this->stats) {
            $this->loadStats();
        }

        return $this->stats[$stat_name]->current;
    }

    public function getFinalStat($stat_name)
    {
        if (!$this->stats) {
            $this->loadStats();
        }

        return $this->stats[$stat_name]->final;
    }

    public function updateCurrentStat($stat_name, $value)
    {
        if (!blank($this->stats)) {
            $this->stats[$stat_name]->current = $value;
        }
        HeroStat::where('hero_id', $this->id)->where('name', $stat_name)->update(['current'=>$value]);
        if (in_array($stat_name, ['health', 'action_points']) and $this->state_name == 'battle') {
            BattleQueueUnit::where('object_name', 'hero')->where('object_id', $this->id)->update([$stat_name=>$value]);
        }
    }
}
