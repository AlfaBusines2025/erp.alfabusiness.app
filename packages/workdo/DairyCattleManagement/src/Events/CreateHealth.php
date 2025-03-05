<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class CreateHealth
{
    use SerializesModels;

    public $request;
    public $health;

    public function __construct($request ,$health)
    {
        $this->request = $request;
        $this->health = $health;
    }
}
