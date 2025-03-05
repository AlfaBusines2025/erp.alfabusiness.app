<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\ToDo\Events\CreateToDo;
use Workdo\Calender\Entities\CalenderUtility;

class CreateToDoLis
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
    public function handle(CreateToDo $event)
    {
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $task = $event->toDo;
            $type = 'todo';
            $task->end_date = $event->request->due_date;
            CalenderUtility::addCalendarData($task, $type);
        }
    }
}
