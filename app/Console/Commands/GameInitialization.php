<?php

namespace App\Console\Commands;

use App\Models\HeroTravelStat;
use App\Models\Talent;
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
        $this->call('map:create', ['name'=>'Start world','size'=>'100:100','--surface'=>'trees']);

        $guest_user = User::firstOrCreate([
            'name' => 'Guest',
            'email' => 'guest@'.parse_url(env('APP_URL'))['host'],
        ], [
            'password' => bcrypt('guest'),
        ]);
        $talents = [
            ['name'=>'Силач', 'emoji'=>'man_lifting_weights'],
            ['name'=>'Строитель', 'emoji'=>'hammer_and_wrench'],
            ['name'=>'Торговец', 'emoji'=>'money_with_wings'],
            ['name'=>'Исследователь', 'emoji'=>'globe_with_meridians'],
            ['name'=>'Боец', 'emoji'=>'crossed_swords'],
            ['name'=>'Оратор', 'emoji'=>'speaking_head']
        ];
        foreach ($talents as $talent_attr) {
            Talent::firstOrCreate($talent_attr);
        }
        $this->info('Talents created');
        $this->info('Guest user created, id:'.$guest_user->id);
        $HS = new HeroService();
        $HS->create($guest_user);
        $this->info('Guest hero created, id:'.$guest_user->id);


        return Command::SUCCESS;
    }
}
