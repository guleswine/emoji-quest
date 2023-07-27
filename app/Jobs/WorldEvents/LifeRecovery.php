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
SET value = nv.new_value         -- we can reference joined table here
FROM (SELECT hs.id,hs.final_value,hs.value,htsrp.value,htsrs.value,round(hs.final_value::NUMERIC/100*htsrp.value) as need,
             round(hs.final_value::NUMERIC/100*htsrs.value) as add,
             least(round(hs.final_value::NUMERIC/100*htsrp.value),hs.value+round(hs.final_value::NUMERIC/100*htsrs.value)) as new_value
      FROM heroes h
               join hero_stats hs on h.id = hs.hero_id and hs.attribute='health'
               join hero_travel_stats htsrp on h.id = htsrp.hero_id and htsrp.attribute='life_recovery_percent'
               join hero_travel_stats htsrs on h.id = htsrs.hero_id and htsrs.attribute='life_recovery_speed'
      where  h.state_name='traveler'
        and hs.value < round(hs.final_value::NUMERIC/100*htsrp.value)) nv                                       -- joined table
WHERE
        hs.id = nv.id");
    }
}
