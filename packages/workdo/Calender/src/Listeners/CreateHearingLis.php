<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\LegalCaseManagement\Events\CreateHearing;
use Workdo\Calender\Entities\CalenderUtility;

class CreateHearingLis
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
    public function handle(CreateHearing $event)
    {
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $hearing = $event->hearing;
            $type = 'hearing_date';
            $hearing->title = $hearing->case_name()->title;
            $hearing->start_date = $hearing->date;
            $hearing->end_date = $hearing->date;
            CalenderUtility::addCalendarData($hearing, $type);
        }
    }
}
