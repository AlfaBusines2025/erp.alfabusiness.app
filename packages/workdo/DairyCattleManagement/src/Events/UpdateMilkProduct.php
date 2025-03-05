<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class UpdateMilkProduct
{
    use SerializesModels;

    public $request;
    public $milkproduct;

    public function __construct($request ,$milkproduct)
    {
        $this->request = $request;
        $this->milkproduct = $milkproduct;
    }
}
