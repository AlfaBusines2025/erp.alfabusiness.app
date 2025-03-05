<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class CreateBreeding
{
    use SerializesModels;

    public $request;
    public $breeding;

    public function __construct($request ,$breeding)
    {
        $this->request = $request;
        $this->breeding = $breeding;
    }
}
