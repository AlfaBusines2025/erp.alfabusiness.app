<?php

namespace Workdo\ToDo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\ToDo\Entities\ToDo;
use Workdo\ToDo\Entities\TodoStage;
use Workdo\ToDo\Events\ToDoStageSystemSetup;

class TaskStageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::check() && Auth::user()->isAbleTo('task stage manage')) {
            $stages = TodoStage::where('workspace_id', '=', getActiveWorkSpace())->where('created_by', '=', Auth::user()->id)->orderBy('order')->get();

            return view('to-do::stage.index',['stages'=>$stages]);
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
        return view('to-do::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->isAbleTo('task stage create')) {
            $rules = [
                'stages' => 'required|present|array',
            ];
            $attributes = [];
            if ($request->stages) {

                foreach ($request->stages as $key => $val) {

                    $rules['stages.' . $key . '.name']      = 'required|max:255';
                    $attributes['stages.' . $key . '.name'] = __('Stage Name');
                }
            }

            $validator = \Validator::make($request->all(), $rules, [], $attributes);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $arrStages = TodoStage::where('workspace_id', '=', getActiveWorkSpace())->orderBy('order')->pluck('name', 'id')->all();
            $order     = 0;
            foreach($request->stages as $key => $stage)
            {

                $obj = null;
                if($stage['id'])
                {
                    $obj = TodoStage::find($stage['id']);
                    unset($arrStages[$obj->id]);
                }
                else
                {
                    $obj               = new TodoStage();
                    $obj->workspace_id = getActiveWorkSpace();
                    $obj->created_by = creatorId();
                }
                $obj->name     = $stage['name'];
                $obj->color    = $stage['color'];
                $obj->order    = $order++;
                $obj->complete = 0;
                $obj->save();
            }

            $taskExist = [];
            if($arrStages)
            {
                foreach($arrStages as $id => $name)
                {
                    $count = ToDo::where('status', '=', $id)->count();
                    if($count != 0)
                    {
                        $taskExist[] = $name;
                    }
                    else
                    {
                        TodoStage::find($id)->delete();
                    }
                }
            }

            $lastStage = TodoStage::where('workspace_id', '=', getActiveWorkSpace())->orderBy('order', 'desc')->first();
            if($lastStage)
            {
                $lastStage->complete = 1;
                $lastStage->save();
            }
            event(new ToDoStageSystemSetup($request));

            return redirect()->back()->with('success', __('Stages successfully updated.'));
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
        return view('to-do::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('to-do::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
