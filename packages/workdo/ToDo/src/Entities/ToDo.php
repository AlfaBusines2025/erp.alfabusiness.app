<?php

namespace Workdo\ToDo\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToDo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'assigned_to',
        'description',
        'status',
        'priority',
        'workspace_id',
        'created_by',
        'start_date',
        'assign_by',
        'due_date',
        'module',
        'sub_module',
    ];

    protected $table = 'todos';

    public function taskCompleteSubTaskCount()
    {
        return $this->sub_tasks->where('status', '=', '1')->count();
    }
    public function sub_tasks()
    {
        return $this->hasMany('Workdo\ToDo\Entities\TodoSubTask', 'task_id', 'id')->orderBy('id', 'DESC');
    }
    public function taskTotalSubTaskCount()
    {
        return $this->sub_tasks->count();
    }
    public static function getUsersData()
    {
        $zoommeetings = \DB::table('todos')->get();

        $employeeIds = [];
        foreach ($zoommeetings as $item) {
            $employees = explode(',', $item->assigned_to);
            foreach ($employees as $employee) {
                $employeeIds[] = $employee;
            }
        }
        $data = [];
        $users =  User::whereIn('id', array_unique($employeeIds))->get();
        foreach($users as $user)
        {

            $data[$user->id]['name']        = $user->name;
            $data[$user->id]['avatar']      = $user->avatar;
        }
        return $data;

    }
    public function users()
    {
        return User::whereIn('id',explode(',',$this->assigned_to))->get();
    }
    public function comments()
    {
        return $this->hasMany('Workdo\ToDo\Entities\TodoComment', 'task_id', 'id')->orderBy('id', 'DESC');
    }
    public function taskFiles()
    {
        return $this->hasMany('Workdo\ToDo\Entities\TodoTaskFile', 'task_id', 'id')->orderBy('id', 'DESC');
    }
    public function assignedByUser()
    {
        return $this->belongsTo(User::class, 'assign_by');
    }
    public static function getTeams($id)
    {
        $advName = User::whereIn('id', explode(',', $id))->pluck('name')->toArray();
        return implode(', ', $advName);
    }
}
