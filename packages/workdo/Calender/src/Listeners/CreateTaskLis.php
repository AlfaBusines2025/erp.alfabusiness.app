<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Taskly\Events\CreateTask;
use Workdo\Calender\Entities\CalenderUtility;

class CreateTaskLis
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
    public function handle(CreateTask $event)
    {
        // Google Calender
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $task = $event->task;
            $type = 'projecttask';
            $task->end_date = $event->request->due_date;
            CalenderUtility::addCalendarData($task, $type);
        }
    }
}
