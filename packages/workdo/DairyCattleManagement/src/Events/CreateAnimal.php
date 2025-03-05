<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class CreateAnimal
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
