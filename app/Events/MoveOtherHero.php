<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MoveOtherHero
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $path;
    public $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($path, $user_id)
    {
        $this->path = $path;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('users.' . $this->user_id);
    }
}
