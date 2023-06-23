<?php

namespace App\Events;

use App\Models\Hero;
use App\Models\Map;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HeroVisitedMap
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $hero;
    public $map;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Hero $hero, Map $map)
    {
        $this->hero = $hero;
        $this->map = $map;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
