<?php

namespace Workdo\ToDo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Workdo\ToDo\Entities\ToDo;
use Workdo\ToDo\Entities\TodoActivityLog;
use Workdo\ToDo\Entities\TodoModuleList;
use Workdo\ToDo\Entities\TodoStage;
use Workdo\ToDo\Events\CompleteToDo;
use Workdo\ToDo\Events\CreateToDo;
use Workdo\ToDo\Events\DestroyToDo;
use Workdo\ToDo\Events\UpdateToDo;
use Workdo\ToDo\Events\UpdateToDoStage;
use Workdo\ToDo\DataTables\TodoListDataTable;
class ToDoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ToDoListDataTable $dataTable,Request $request)
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo manage')) {

            $stages = TodoStage::where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()])->orderBy('order')->get();
            $statusClass = [];
            if ($stages) {
                foreach ($stages as $status) {
                    $statusClass[] = 'task-list-' . str_replace(' ', '_', $status->id);

                    $task = ToDo::query()->orderBy('order')->where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()]);

                    if ($request->filter == 'All' || empty($request->filter)) {
                        $status['tasks'] = $task->where('status', '=', $status->id)->get();
                    } else {
                        $status['tasks'] = $task->where('status', '=', $status->id)->where('priority', $request->filter)->get();
                    }
                }
            }

            $priorities = [
                'All',
                'Low',
                'Medium',
                'High',
            ];
            $todoStage = TodoStage::where(['workspace_id' => getActiveWorkSpace(), 'created_by' => creatorId()])->where('name', 'Completed')->first();

            return $dataTable->render('to-do::todo.index',compact('stages','statusClass','priorities','todoStage'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        return $this->loadToDoView('to-do::todo.index', $request);
    }

    public function board(Request $request)
    {
        return $this->loadToDoView('to-do::todo.board', $request);
    }

    private function loadToDoView($view, $request)
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo manage')) {

            $stages = TodoStage::where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()])->orderBy('order')->get();
            $statusClass = [];
            if ($stages) {
                foreach ($stages as $status) {
                    $statusClass[] = 'task-list-' . str_replace(' ', '_', $status->id);

                    $task = ToDo::query()->orderBy('order')->where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()]);

                    if ($request->filter == 'All' || empty($request->filter)) {
                        $status['tasks'] = $task->where('status', '=', $status->id)->get();
                    } else {
                        $status['tasks'] = $task->where('status', '=', $status->id)->where('priority', $request->filter)->get();
                    }
                }
            }

            $priorities = [
                'All',
                'Low',
                'Medium',
                'High',
            ];
            $todoStage = TodoStage::where(['workspace_id' => getActiveWorkSpace(), 'created_by' => creatorId()])->where('name', 'Completed')->first();

            $pendingStage = TodoStage::where(['workspace_id' => getActiveWorkSpace(), 'created_by' => creatorId()])->where('name', 'Pending')->first();
            $pendingTodo = ToDo::where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()])->where('status',$pendingStage->id)->get();

            $upcomingStage = TodoStage::where(['workspace_id' => getActiveWorkSpace(), 'created_by' => creatorId()])->where('name', 'Upcoming')->first();
            $upcomingTodo = ToDo::where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()])->where('status',$upcomingStage->id)->get();

            $completeStage = TodoStage::where(['workspace_id' => getActiveWorkSpace(), 'created_by' => creatorId()])->where('name', 'Completed')->first();
            $completeTodo = ToDo::where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()])->where('status',$completeStage->id)->get();

            return view($view, ['stages' => $stages, 'statusClass' => $statusClass, 'priorities' => $priorities, 'todoStage' => $todoStage,'pendingTodo'=>$pendingTodo,'upcomingTodo'=>$upcomingTodo,'completeTodo'=>$completeTodo]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function calendar()
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo manage')) {
            $schedule = ToDo::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace());

            $schedules = $schedule->get();
            $events = $schedule->whereYear('start_date', Carbon::now()->year)->whereMonth('start_date', Carbon::now()->month)->get();

            $arrSchedule = [];

            foreach ($schedules as $schedule) {
                $arr['id'] = $schedule['id'];
                $arr['title'] = $schedule['title'];
                $arr['start'] = $schedule['start_date'];
                $arr['end'] = $schedule['due_date'];
                if ($schedule['priority'] == 'High') {
                    $arr['className'] = 'event-danger todo-show';
                } elseif ($schedule['priority'] == 'Medium') {
                    $arr['className'] = 'event-warning todo-show';
                } else {
                    $arr['className'] = 'event-success todo-show';
                }
                $arr['url'] = route('to-do.show', $schedule['id']);
                $arrSchedule[] = $arr;
            }
            $arrSchedule = json_encode($arrSchedule);

            return view('to-do::todo.calendar',['arrSchedule'=>$arrSchedule,'events'=>$events]);

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo create')) {
            $users = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->pluck('name', 'id');

            $modules = TodoModuleList::select('module', 'sub_module')->get();
            $module_custumefield = [];
            foreach ($modules as $module) {
                if(module_is_active($module->module) || $module->module == 'Base')
                {
                    $sub_module_custumefield = TodoModuleList::select('module', 'sub_module')->where('module', $module->module)->get();
                    $temp = [];
                    foreach ($sub_module_custumefield as $sb) {
                        $temp[strtolower($module->module) . '-' . strtolower($sb->sub_module)] = $sb->sub_module;
                    }
                    $module_custumefield[Module_Alias_Name($module->module)] = $temp;
                }
            }

            return view('to-do::todo.create', ['users' => $users,'module_custumefield' => $module_custumefield]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'assigned_to' => 'required',
                    'start_date' => 'required',
                    'due_date' => 'required',
                ]
            );

            $module = explode('-', $request->module);
            if (!empty($module) && isset($module[0])) {
                $m_name = $module[0];
            }
            if (!empty($module) && isset($module[1])) {
                $subm_name = $module[1];
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $stage = TodoStage::where('workspace_id', '=', getActiveWorkSpace())->orderBy('order')->first();
            if ($stage) {
                $toDo = ToDo::create([
                    'title'         => $request->title,
                    'assigned_to'   => implode(',', $request->assigned_to),
                    'description'   => $request->description,
                    'status'        => $stage->id,
                    'priority'      => $request->priority,
                    'start_date'    => $request->start_date,
                    'due_date'      => $request->due_date,
                    'module'        => $m_name,
                    'sub_module'    => $subm_name,
                    'assign_by'     => Auth::user()->id,
                    'created_by'    => creatorId(),
                    'workspace_id'  => getActiveWorkSpace(),
                ]);

                TodoActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'user_type' => get_class(Auth::user()),
                    'log_type' => 'Create Task',
                    'remark' => json_encode(['title' => $toDo->title]),
                ]);

                event(new CreateToDo($request,$toDo));

            } else {
                return redirect()->back()->with('error', __('Please add stages first.'));
            }

            return redirect()->back()->with('success', __('To Do successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo show')) {
            $task             = ToDo::findOrFail($id);
            return view('to-do::todo.show', compact('task'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo edit')) {
            $toDo = ToDo::findOrFail($id);
            $users = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->pluck('name', 'id');

            $modules = TodoModuleList::select('module', 'sub_module')->get();
            $module_custumefield = [];
            foreach ($modules as $module) {
                if(module_is_active($module->module) || $module->module == 'Base')
                {
                    $sub_module_custumefield = TodoModuleList::select('module', 'sub_module')->where('module', $module->module)->get();
                    $temp = [];
                    foreach ($sub_module_custumefield as $sb) {
                        $temp[strtolower($module->module) . '-' . strtolower($sb->sub_module)] = $sb->sub_module;
                    }
                    $module_custumefield[Module_Alias_Name($module->module)] = $temp;
                }
            }
            return view('to-do::todo.edit', ['toDo' => $toDo, 'users' => $users, 'module_custumefield' => $module_custumefield]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'assigned_to' => 'required',
                    'start_date' => 'required',
                    'due_date' => 'required',
                ]
            );

            $module = explode('-', $request->module);
            if (!empty($module) && isset($module[0])) {
                $m_name = $module[0];
            }
            if (!empty($module) && isset($module[1])) {
                $subm_name = $module[1];
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $toDo = ToDo::findOrFail($id);

            $toDo->update([
                'title'         => $request->title,
                'assigned_to'   => implode(',', $request->assigned_to),
                'description'   => $request->description,
                'priority'      => $request->priority,
                'start_date'    => $request->start_date,
                'due_date'      => $request->due_date,
                'module'        => $m_name,
                'sub_module'    => $subm_name,
                'assign_by'     => Auth::user()->id,
            ]);
            event(new UpdateToDo($request,$toDo));

            return redirect()->back()->with('success', __('To Do successfully update.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo delete')) {
            $toDo = ToDo::findOrFail($id);
            event(new DestroyToDo($toDo));
            $toDo->delete();

            return redirect()->back()->with('success', __('To Do successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function taskOrderUpdate(Request $request)
    {
        $currentWorkspace = getActiveWorkSpace();
        if (isset($request->sort)) {
            foreach ($request->sort as $index => $taskID) {
                $task        = ToDo::find($taskID);
                $task->order = $index;
                $task->save();
            }
        }

        if ($request->new_status != $request->old_status) {
            $new_status   = TodoStage::find($request->new_status);
            $old_status   = TodoStage::find($request->old_status);
            $user         = Auth::user();
            $task         = ToDo::find($request->id);
            $task->status = $request->new_status;
            $task->save();

            $id = $user->id;

            TodoActivityLog::create([
                'user_id' => $id,
                'user_type' => get_class($user),
                'log_type' => 'Move',
                'remark' => json_encode(
                    [
                        'title' => $task->title,
                        'old_status' => $old_status->name,
                        'new_status' => $new_status->name,
                    ]
                ),
            ]);
            event(new UpdateToDoStage($request,$task));
            return $task->toJson();
        }
    }

    public function status($id)
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo complete')) {
            $todo = ToDo::find($id);

            return view('to-do::todo.status', compact('todo'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function statusUpdate($id)
    {
        if (Auth::check() && Auth::user()->isAbleTo('todo complete')) {

            $todo = ToDo::find($id);
            $todoStage = TodoStage::where(['workspace_id' => getActiveWorkSpace(), 'created_by' => creatorId()])->where('name', 'Completed')->first();

            if (!$todoStage) {
                return redirect()->route('todo.index')->with('error', __('Stage data mismatched.'));
            }

            $todo->status = $todoStage->id;
            $todo->save();

            event(new CompleteToDo($todo));

            return redirect()->back()->with('success', __('You have successfully completed the to-do.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function description($id)
    {
        $desc = ToDo::find($id);
        return view('to-do::todo.description', compact('desc'));
    }
}
