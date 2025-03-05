<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyBreeding
{
    use SerializesModels;


    public $breeding;

    public function __construct($breeding)
    {
        $this->breeding = $breeding;
    }
}
