<?php

namespace Workdo\Calender\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Permission;
use Spatie\GoogleCalendar\Event as GoogleEvent;
use Illuminate\Support\Carbon;

class CalenderUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        // return \Workdo\Calender\Database\factories\CalenderUtilityFactory::new();
    }

    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $client_permissions = [
            'calander manage',
            'calander show'
        ];

        $staff_permissions = [
            'calander manage',
        ];

        if ($role_id == Null) {
            // client
            $roles_c = Role::where('name', 'client')->get();
            foreach ($roles_c as $role) {
                foreach ($client_permissions as $permission_c) {
                    $permission = Permission::where('name', $permission_c)->first();
                    if(!$role->hasPermission($permission_c))
                    {
                        $role->givePermission($permission);
                    }
                }
            }

            // staff
            $roles_s = Role::where('name', 'staff')->get();
            foreach ($roles_s as $role) {
                foreach ($staff_permissions as $permission_s) {
                    $permission = Permission::where('name', $permission_s)->first();
                    if(!$role->hasPermission($permission_s))
                    {
                        $role->givePermission($permission);
                    }
                }
            }
        } else {

            if ($rolename == 'client') {
                $roles_c = Role::where('name', 'client')->where('id', $role_id)->first();
                foreach ($client_permissions as $permission_c) {
                    $permission = Permission::where('name', $permission_c)->first();
                    if(!$roles_c->hasPermission($permission_c))
                    {
                        $roles_c->givePermission($permission);
                    }
                }

            } elseif ($rolename == 'staff') {
                $roles_s = Role::where('name', 'staff')->where('id', $role_id)->first();
                foreach ($staff_permissions as $permission_s) {
                    $permission = Permission::where('name', $permission_s)->first();
                    if(!$roles_s->hasPermission($permission_s))
                    {
                        $roles_s->givePermission($permission);
                    }
                }
            }
        }
    }

    public static function colorCodeData($type)
    {
        if($type == 'holiday' || $type == 'leave'){
            return 1;
        }elseif($type == 'deal_task' || $type == 'lead_task' || $type == 'rotas' || $type == 'projecttask' || $type == 'work_order' || $type == 'todo' || $type == 'hearing_date' || $type == 'hospital_appointment' ){
            return 2;
        }elseif($type == 'appointment' || $type == 'event' || $type == 'google_meet' || $type == 'interview_schedule' || $type == 'meeting' || $type == 'zoom_meeting' || $type == 'vcard_appointment' ){
            return 3;
        }elseif($type == 'due_invoice' || $type == 'due_bill' ){
            return 4;
        }elseif($type == 'call'){
            return 5;
        }else{
            return 6;
        }
    }

    public static $colorCode=[
        1=>'event-danger border-danger',
        2=>'event-primary border-primary',
        3=>'event-info border-info',
        4=>'event-warning border-warning',
        5=>'event-success border-success',
        6=>'event-secondary border-secondary',
        7=>'event-black',
        8=>'event-info',
        9=>'event-dark',
        10=>'event-success',
        11=>'event-warning',
    ];

    public static function googleCalendarConfig($created_by = null,$workspace = null)
    {
        if(check_file(company_setting('google_calender_json_file',$created_by,$workspace)) == false){
            return 'false';
        }else{
            $path = realpath(company_setting('google_calender_json_file',$created_by,$workspace));
            if($path)
            {
                config([
                'google-calendar.default_auth_profile' => 'service_account',
                'google-calendar.auth_profiles.service_account.credentials_json' => $path,
                'google-calendar.auth_profiles.oauth.credentials_json' => $path,
                'google-calendar.auth_profiles.oauth.token_json' => $path,
                'google-calendar.calendar_id' => company_setting('google_calender_id',$created_by,$workspace) ? company_setting('google_calender_id',$created_by,$workspace) :'',
                'google-calendar.user_to_impersonate' => '',
                ]);
            }
        }

    }

    public static function addCalendarData($request, $type)
    {
        try
        {
            if ((!empty(company_setting('google_calendar_enable', $request->created_by, $request->workspace)) ? company_setting('google_calendar_enable', $request->created_by, $request->workspace) : 'off') == "on") {
                if (self::googleCalendarConfig($request->created_by, $request->workspace) == 'false') {
                    return ['error' => 'Configuration not set properly'];
                } else {
                        $event = new GoogleEvent();
                        $event->name = $request->title;
                        $event->startDateTime = Carbon::parse($request->start_date);
                        $event->endDateTime = Carbon::parse($request->end_date);
                        $event->colorId = self::colorCodeData($type);
                        $event->description = $type;
                }
            }
        }catch(\Exception $e)
        {
            return ['error' => $e->getMessage()];
        }
       
    }

    public static function getCalendarData($type)
    {
        try {
            Self::googleCalendarConfig();
            $data= GoogleEvent::get();
            $type = (!empty($type)) ? $type : 'all';
            $colorCodeData = (!empty($type)) ? Self::colorCodeData($type) : 'all';
            $arrayJson = [];
            foreach($data as $val)
            {
                $end_date=date_create($val->endDateTime);
                date_add($end_date,date_interval_create_from_date_string("1 days"));
                if($val->description == "$type" || $type == 'all'){

                    $arrayJson[] = [
                        "id"=> $val->id,
                        "title" => $val->summary,
                        "start" => $val->startDateTime,
                        "end" => date_format($end_date,"Y-m-d H:i:s"),
                        "className" => Self::$colorCode[$val->colorId],
                        "allDay" => true,

                    ];
                }
            }
        return $arrayJson;
        } catch (\Throwable $th) {
            return ['error'=> $th->getMessage()];
        }

    }
}
