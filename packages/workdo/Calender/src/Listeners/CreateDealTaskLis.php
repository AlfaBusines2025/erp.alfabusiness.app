<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Lead\Events\CreateDealTask;
use Workdo\Calender\Entities\CalenderUtility;

class CreateDealTaskLis
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
    public function handle(CreateDealTask $event)
    {
        // Google Calender
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $dealTask = $event->dealTask;
            $type = 'deal_task';
            $dealTask->title = $event->request->name;
            $dealTask->start_date = $event->request->date;
            $dealTask->end_date = $event->request->date;
            CalenderUtility::addCalendarData($dealTask, $type);
        }
    }
}
