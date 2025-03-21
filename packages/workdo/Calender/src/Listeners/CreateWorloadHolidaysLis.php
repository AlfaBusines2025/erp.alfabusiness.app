<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\TeamWorkload\Events\CreateWorloadHolidays;
use Workdo\Calender\Entities\CalenderUtility;

class CreateWorloadHolidaysLis
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
    public function handle(CreateWorloadHolidays $event)
    {
        // Google Calender

        if ($event->request->get('synchronize_type') == 'google_calender') {
            $holiday = $event->holiday;
            $type = 'holiday';
            $holiday->title = $event->request->occasion;
            CalenderUtility::addCalendarData($holiday, $type);
        }
    }
}
