<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyHealth
{
    use SerializesModels;

    public $health;

    public function __construct($health)
    {
        $this->health = $health;
    }
}
