<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\HospitalManagement\Events\HospitalAppointmentStatus;
use Workdo\Calender\Entities\CalenderUtility;

class CreateHospitalAppointmentLis
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
    public function handle(HospitalAppointmentStatus $event)
    {
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $appointment = $event->appointment;
            $type = 'hospital_appointment';
            $appointment->title = $appointment->Patient()->name;
            $appointment->start_date = $appointment->date . '' . $appointment->start_time;
            $appointment->end_date = $appointment->date . '' . $appointment->end_time;
            if ($appointment->status == 'Approved') {
                CalenderUtility::addCalendarData($appointment, $type);
            }
        }
    }
}
