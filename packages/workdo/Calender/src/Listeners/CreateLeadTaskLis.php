<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Lead\Events\CreateLeadTask;
use Workdo\Calender\Entities\CalenderUtility;

class CreateLeadTaskLis
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
    public function handle(CreateLeadTask $event)
    {
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $leadTask = $event->leadTask;
            $type = 'lead_task';
            $leadTask->title = $event->request->name;
            $leadTask->start_date = $event->request->date . ' ' . $event->request->time;
            $leadTask->end_date = $event->request->date . ' ' . $event->request->time;
            CalenderUtility::addCalendarData($leadTask, $type);
        }
    }
}
