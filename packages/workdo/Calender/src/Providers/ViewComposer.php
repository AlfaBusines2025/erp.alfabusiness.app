<?php

namespace Workdo\Calender\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot()
    {
        $viewsToCompose = [
            'appointment::schedule.action',
            'appointment::schedule.callback_action',
            'cmms::workorder.create',
            'google-meet::create',
            'hrm::event.create',
            'hrm::leave.create',
            'hrm::holiday.create',
            'lead::deals.tasks',
            'lead::leads.tasks',
            'recruitment::interviewSchedule.create',
            'rotas::rota.create',
            'sales::call.create',
            'sales::meeting.create',
            'taskly::projects.taskCreate',
            'team-workload::holiday.create',
            'to-do::todo.create',
            'zoom-meeting::create',
            'hospital-management::appointment.action',
            'legal-case-management::hearing.create',
        ];

        view()->composer($viewsToCompose, function ($view) {
            $setting = getCompanyAllSetting();
            if (module_is_active('Calender') && Auth::user()->isAbleTo('calander manage')
                && isset($setting['google_calendar_enable']) && $setting['google_calendar_enable'] == 'on'
                && isset($setting['google_calender_id'])
            ) {
                $view->getFactory()->startPush('calendar', view('calender::setting.synchronize'));
            }
        });
    }
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
