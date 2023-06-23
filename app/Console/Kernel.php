<?php

namespace App\Console;

use App\Jobs\WorldEvents\LifeRecovery;
use App\Models\WorldController;
use App\Services\WorldService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $WCs = WorldController::all();
            foreach ($WCs as $wc) {
                WorldService::execute($wc);
            }
        })->everyTenMinutes();
        $schedule->job(new LifeRecovery)->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
