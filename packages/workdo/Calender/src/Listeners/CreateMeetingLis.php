<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Sales\Events\CreateMeeting;
use Workdo\Calender\Entities\CalenderUtility;

class CreateMeetingLis
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
    public function handle(CreateMeeting $event)
    {
        // Google Calender
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $meeting = $event->meeting;
            $type = 'meeting';
            $meeting->title = $event->request->name;
            CalenderUtility::addCalendarData($meeting, $type);
        }
    }
}
