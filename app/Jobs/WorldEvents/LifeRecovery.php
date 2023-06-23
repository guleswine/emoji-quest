<?php

namespace App\Jobs\WorldEvents;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class LifeRecovery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::statement("UPDATE hero_stats hs
SET current = nv.new_current         -- we can reference joined table here
FROM (SELECT hs.id,hs.final,hs.current,htsrp.value,htsrs.value,round(hs.final::NUMERIC/100*htsrp.value) as need,
             round(hs.final::NUMERIC/100*htsrs.value) as add,
             least(round(hs.final::NUMERIC/100*htsrp.value),hs.current+round(hs.final::NUMERIC/100*htsrs.value)) as new_current
      FROM heroes h
               join hero_stats hs on h.id = hs.hero_id and hs.name='health'
               join hero_travel_stats htsrp on h.id = htsrp.hero_id and htsrp.name='life_recovery_percent'
               join hero_travel_stats htsrs on h.id = htsrs.hero_id and htsrs.name='life_recovery_speed'
      where  h.state_name='traveler'
        and hs.current < round(hs.final::NUMERIC/100*htsrp.value)) nv                                       -- joined table
WHERE
        hs.id = nv.id");
    }
}
