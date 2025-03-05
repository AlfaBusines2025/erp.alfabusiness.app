<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyFeedSchedule
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $schedule;

    public function __construct($schedule)
    {
        $this->schedule = $schedule;
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
