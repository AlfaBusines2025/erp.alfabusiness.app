<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyMilkProduct
{
    use SerializesModels;

    public $milkproduct;

    public function __construct($milkproduct)
    {

        $this->milkproduct = $milkproduct;
    }
}
