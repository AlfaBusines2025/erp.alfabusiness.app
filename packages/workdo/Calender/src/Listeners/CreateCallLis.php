<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Sales\Events\CreateCall;
use Workdo\Calender\Entities\CalenderUtility;

class CreateCallLis
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
    public function handle(CreateCall $event)
    {
        // Google Calender
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $type = 'call';
            $event->call->title = $event->request->name;
            CalenderUtility::addCalendarData($event->call, $type);
        }
    }
}
