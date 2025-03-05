<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyFeedConsumption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $consumption;

    public function __construct($consumption)
    {
        $this->consumption = $consumption;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
