<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyWeight
{
    use SerializesModels;


    public $weight;

    public function __construct($weight)
    {
        $this->weight = $weight;
    }
}
