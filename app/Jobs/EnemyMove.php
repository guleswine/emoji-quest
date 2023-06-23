<?php

namespace App\Jobs;

use App\Models\Battle;
use App\Models\BattleQueueUnit;
use App\Services\BattleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnemyMove implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public $battle_id
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $battle = Battle::find($this->battle_id);
        if ($battle) {
            $queues = BattleQueueUnit::where('battle_id', $battle->id)->orderBy('order')->get();
            BattleService::enemyMove($queues);
            $new_queue = BattleService::moveQueue($battle->id);
            if (blank($new_queue)) {
                return;
            }
            $fighter = $new_queue->first();
            if ($fighter->type == 'enemy') {
                self::dispatch($battle->id)->delay(now()->addSeconds(5));
            }
        }
    }
}
