<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class CreateWeight
{
    use SerializesModels;

    public $request;
    public $weight;

    public function __construct($request ,$weight)
    {
        $this->request = $request;
        $this->weight = $weight;
    }
}
