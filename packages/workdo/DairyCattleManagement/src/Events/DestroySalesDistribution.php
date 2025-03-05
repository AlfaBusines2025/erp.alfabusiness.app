<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroySalesDistribution
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $sales_distribution;

    public function __construct($sales_distribution)
    {
        $this->sales_distribution = $sales_distribution;
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
