<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Appointment\Events\AppointmentStatus;
use Workdo\Calender\Entities\CalenderUtility;

class AppointmentStatusLis
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
    public function handle(AppointmentStatus $event)
    {
        // Google Calender
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $schedule = $event->schedule;
            $type = 'appointment';
            $schedule->title = $schedule->appointment->name;
            $schedule->start_date = $schedule->date;
            $schedule->end_date   = $schedule->date;
            if ($schedule->status == 'Approved') {
                CalenderUtility::addCalendarData($schedule , $type);
            }
        }
    }
}
