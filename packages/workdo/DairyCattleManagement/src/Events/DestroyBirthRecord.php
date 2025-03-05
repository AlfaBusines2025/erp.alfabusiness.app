<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyBirthRecord
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $birth_records;

    public function __construct($birth_records)
    {
        $this->birth_records = $birth_records;
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
