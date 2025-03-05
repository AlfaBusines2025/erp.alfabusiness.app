<?php

namespace Workdo\Calender\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Workdo\Appointment\Events\AppointmentStatus;
use App\Events\CompanyMenuEvent;
use App\Events\CompanySettingEvent;
use App\Events\CompanySettingMenuEvent;
use App\Events\GivePermissionToRole;
use Workdo\ZoomMeeting\Events\CreateZoommeeting;
use Workdo\TeamWorkload\Events\CreateWorloadHolidays;
use Workdo\CMMS\Events\CreateWorkorder;
use Workdo\ToDo\Events\CreateToDo;
use Workdo\Taskly\Events\CreateTask;
use Workdo\Rotas\Events\CreateRota;
use Workdo\Sales\Events\CreateMeeting;
use Workdo\Hrm\Events\CreateLeave;
use Workdo\Lead\Events\CreateLeadTask;
use Workdo\Recruitment\Events\CreateInterviewSchedule;
use Workdo\HospitalManagement\Events\HospitalAppointmentStatus;
use Workdo\Hrm\Events\CreateHolidays;
use Workdo\GoogleMeet\Events\CreateGoogleMeet;
use Workdo\Hrm\Events\CreateEvent;
use Workdo\Lead\Events\CreateDealTask;
use Workdo\Sales\Events\CreateCall;
use Workdo\VCard\Events\CreateAppointment;
use Workdo\LegalCaseManagement\Events\CreateHearing;
use Workdo\Calender\Listeners\AppointmentStatusLis;
use Workdo\Calender\Listeners\CompanyMenuListener;
use Workdo\Calender\Listeners\CompanySettingListener;
use Workdo\Calender\Listeners\CompanySettingMenuListener;
use Workdo\Calender\Listeners\CreateAppointmentLis;
use Workdo\Calender\Listeners\CreateCallLis;
use Workdo\Calender\Listeners\CreateDealTaskLis;
use Workdo\Calender\Listeners\CreateEventLis;
use Workdo\Calender\Listeners\CreateGoogleMeetLis;
use Workdo\Calender\Listeners\CreateHearingLis;
use Workdo\Calender\Listeners\CreateHolidaysLis;
use Workdo\Calender\Listeners\CreateHospitalAppointmentLis;
use Workdo\Calender\Listeners\CreateInterviewScheduleLis;
use Workdo\Calender\Listeners\CreateLeadTaskLis;
use Workdo\Calender\Listeners\CreateLeaveLis;
use Workdo\Calender\Listeners\CreateMeetingLis;
use Workdo\Calender\Listeners\CreateRotaLis;
use Workdo\Calender\Listeners\CreateTaskLis;
use Workdo\Calender\Listeners\CreateToDoLis;
use Workdo\Calender\Listeners\CreateWorkorderLis;
use Workdo\Calender\Listeners\CreateWorloadHolidaysLis;
use Workdo\Calender\Listeners\CreateZoommeetingLis;
use Workdo\Calender\Listeners\GiveRoleToPermission;

class EventServiceProvider extends Provider
{
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    protected $listen = [
        CompanyMenuEvent::class => [
            CompanyMenuListener::class,
        ],
        CompanySettingEvent::class => [
            CompanySettingListener::class,
        ],
        CompanySettingMenuEvent::class => [
            CompanySettingMenuListener::class,
        ],
        AppointmentStatus::class => [
            AppointmentStatusLis::class,
        ],
        CreateAppointment::class => [
            CreateAppointmentLis::class,
        ],
        CreateCall::class => [
            CreateCallLis::class,
        ],
        CreateDealTask::class => [
            CreateDealTaskLis::class,
        ],
        CreateEvent::class => [
            CreateEventLis::class,
        ],
        CreateGoogleMeet::class => [
            CreateGoogleMeetLis::class,
        ],
        CreateHearing::class => [
            CreateHearingLis::class,
        ],
        CreateHolidays::class => [
            CreateHolidaysLis::class,
        ],
        HospitalAppointmentStatus::class => [
            CreateHospitalAppointmentLis::class,
        ],
        CreateInterviewSchedule::class => [
            CreateInterviewScheduleLis::class,
        ],
        CreateLeadTask::class => [
            CreateLeadTaskLis::class,
        ],
        CreateLeave::class => [
            CreateLeaveLis::class,
        ],
        CreateMeeting::class => [
            CreateMeetingLis::class,
        ],
        CreateRota::class => [
            CreateRotaLis::class,
        ],
        CreateTask::class => [
            CreateTaskLis::class,
        ],
        CreateToDo::class => [
            CreateToDoLis::class,
        ],
        CreateWorkorder::class => [
            CreateWorkorderLis::class,
        ],
        CreateWorloadHolidays::class => [
            CreateWorloadHolidaysLis::class,
        ],
        CreateZoommeeting::class => [
            CreateZoommeetingLis::class,
        ],
        GivePermissionToRole::class => [
            GiveRoleToPermission::class,
        ],
    ];

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            __DIR__ . '/../Listeners',
        ];
    }
}
