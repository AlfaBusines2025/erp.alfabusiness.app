<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAnimal
{
    use SerializesModels;

    public $animal;

    public function __construct($animal)
    {
        $this->animal = $animal;
    }
}
