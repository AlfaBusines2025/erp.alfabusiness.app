<?php

namespace Workdo\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateOvertime
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $overtime;

    public function __construct($request, $overtime)
    {
        $this->request = $request;
        $this->overtime = $overtime;
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
