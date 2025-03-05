<?php

namespace Workdo\DairyCattleManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDailyMilkSheet
{
    use SerializesModels;

    public $dailymilksheet;

    public function __construct($dailymilksheet)
    {
        $this->dailymilksheet = $dailymilksheet;
    }
}
