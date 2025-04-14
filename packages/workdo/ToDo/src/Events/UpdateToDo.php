<?php

namespace Workdo\ToDo\Events;

use Illuminate\Queue\SerializesModels;

class UpdateToDo
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $toDo;

    public function __construct($request,$toDo)
    {
        $this->request = $request;
        $this->toDo    = $toDo;
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
