<?php

namespace Workdo\ToDo\Events;

use Illuminate\Queue\SerializesModels;

class DestroyToDo
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $toDo;

    public function __construct($toDo)
    {
        $this->toDo = $toDo;
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
