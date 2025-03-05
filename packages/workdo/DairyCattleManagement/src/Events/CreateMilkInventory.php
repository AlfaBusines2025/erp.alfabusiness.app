<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class CreateMilkInventory
{
    use SerializesModels;

    public $request;
    public $milkinventory;

    public function __construct($request ,$milkinventory)
    {
        $this->request = $request;
        $this->milkinventory = $milkinventory;
    }
}
