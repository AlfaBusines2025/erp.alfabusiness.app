<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class UpdateDailyMilkSheet
{
    use SerializesModels;

    public $request;
    public $dailymilksheet;

    public function __construct($request ,$dailymilksheet)
    {
        $this->request = $request;
        $this->dailymilksheet = $dailymilksheet;
    }
}
