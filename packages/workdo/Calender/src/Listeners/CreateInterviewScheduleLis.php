<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Recruitment\Events\CreateInterviewSchedule;
use Workdo\Calender\Entities\CalenderUtility;

class CreateInterviewScheduleLis
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
    public function handle(CreateInterviewSchedule $event)
    {
        // Google Calender
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $schedule = $event->schedule;
            $type = 'interview_schedule';
            $schedule->title = !empty($schedule->applications) ? (!empty($schedule->applications->jobs) ? $schedule->applications->jobs->title : '') : 'title';
            $schedule->start_date = $event->request->date;
            $schedule->end_date = $event->request->date;
            CalenderUtility::addCalendarData($schedule, $type);
        }
    }
}
