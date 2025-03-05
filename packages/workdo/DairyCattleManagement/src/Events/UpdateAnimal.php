<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class UpdateAnimal
{
    use SerializesModels;

    public $request;
    public $animal;

    public function __construct($request ,$animal)
    {
        $this->request = $request;
        $this->animal = $animal;
    }
}
