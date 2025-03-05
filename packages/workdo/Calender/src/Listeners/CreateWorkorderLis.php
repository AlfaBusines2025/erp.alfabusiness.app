<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\CMMS\Events\CreateWorkorder;
use Workdo\Calender\Entities\CalenderUtility;

class CreateWorkorderLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateWorkorder $event)
    {
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $workorder = $event->workorder;
            $type = 'work_order';
            $workorder->title = $event->request->wo_name;
            $workorder->start_date = date("Y-m-d");
            $workorder->end_date = $event->request->date;
            CalenderUtility::addCalendarData($workorder, $type);
        }
    }
}
