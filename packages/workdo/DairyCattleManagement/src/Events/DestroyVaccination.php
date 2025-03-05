<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyVaccination
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $vaccination;

    public function __construct($vaccination)
    {
        $this->vaccination = $vaccination;
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
