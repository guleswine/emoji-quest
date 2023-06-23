<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\HeroStat;
use App\Repositories\HeroRepository;
use App\Services\HeroService;
use Illuminate\Support\Facades\Auth;

class HeroController extends Controller
{
    protected $service;
    protected $repository;

    public function __construct(HeroService $service, HeroRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function getHero()
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();

        return response()->json($hero);
    }

    public function getHeroStats()
    {
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $hero_stats = HeroStat::where('hero_id', $hero->id)->get()->keyBy('name');

        return response()->json($hero_stats);
    }
}
