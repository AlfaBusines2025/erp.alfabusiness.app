<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyMilkInventory
{
    use SerializesModels;

    public $milkinventory;

    public function __construct($milkinventory)
    {
        $this->milkinventory = $milkinventory;
    }
}
