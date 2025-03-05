<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\VCard\Events\CreateAppointment;
use Workdo\Calender\Entities\CalenderUtility;

class CreateAppointmentLis
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
    public function handle(CreateAppointment $event)
    {
        $appointment = $event->appointment;
        $type = 'vcard_appointment';
        $appointment->title = $event->request->name;
        $appointment->start_date = $event->request->date;
        $appointment->end_date = $event->request->date;
        CalenderUtility::addCalendarData($appointment, $type);
    }
}
