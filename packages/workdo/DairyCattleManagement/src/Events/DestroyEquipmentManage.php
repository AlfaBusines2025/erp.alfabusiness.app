<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyEquipmentManage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $manage_equipment;

    public function __construct($manage_equipment)
    {
        $this->manage_equipment = $manage_equipment;
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
