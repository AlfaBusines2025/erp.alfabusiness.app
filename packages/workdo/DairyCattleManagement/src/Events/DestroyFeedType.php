<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyFeedType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $feed_type;

    public function __construct($feed_type)
    {
        $this->feed_type = $feed_type;
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
