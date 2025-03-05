<?php

namespace Workdo\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Workdo\Rotas\Events\CreateRota;
use Workdo\Calender\Entities\CalenderUtility;

class CreateRotaLis
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
    public function handle(CreateRota $event)
    {
        // Google Calender
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $rotas = $event->rotas;
            $type = 'rotas';
            $rotas->title = $event->request->get('note');
            $rotas->start_date = \Carbon\Carbon::parse($event->request->get('rotas_date'))->addHours($event->request->get('start_time'));
            $rotas->end_date = \Carbon\Carbon::parse($event->request->get('rotas_date'))->addHours($event->request->get('end_time'));
            CalenderUtility::addCalendarData($rotas, $type);
        }
    }
}
