<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\DataTables\FeedSchedulesDatatable;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\FeedSchedule;
use Workdo\DairyCattleManagement\Entities\FeedType;
use Workdo\DairyCattleManagement\Events\CreateFeedSchedule;
use Workdo\DairyCattleManagement\Events\DestroyFeedSchedule;
use Workdo\DairyCattleManagement\Events\UpdateFeedSchedule;

class FeedScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(FeedSchedulesDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('feed schedule manage')) {
            return $dataTable->render('dairy-cattle-management::feeds_schedule.index');
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
        if (Auth::user()->isAbleTo('feed schedule create')) {
            $animals = Animal::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name','id');
            $feed_types = FeedType::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name','id');
            return view('dairy-cattle-management::feeds_schedule.create',compact('animals','feed_types'));
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
        if (Auth::user()->isAbleTo('feed schedule create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required',
                    'feed_type_id' => 'required',
                    'quantity' => 'required',
                    'scheduled_time' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $schedule                 = new FeedSchedule();
            $schedule->animal_id      =  $request->animal_id;
            $schedule->feed_type_id   =  $request->feed_type_id;
            $schedule->quantity       =  $request->quantity;
            $schedule->scheduled_time =  $request->scheduled_time;
            $schedule->workspace      = getActiveWorkSpace();
            $schedule->created_by     = creatorId();
            $schedule->save();

            event(new CreateFeedSchedule($request, $schedule));

            return redirect()->back()->with('success', __('The feed schedule has been created successfully.'));
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
        return view('dairy-cattle-management::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('feed schedule edit')) {

            $schedule = FeedSchedule::find($id);
            $animals = Animal::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->pluck('name','id');
            $feed_types = FeedType::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->pluck('name','id');

            return view('dairy-cattle-management::feeds_schedule.edit', compact('schedule','animals','feed_types'));
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
        if (Auth::user()->isAbleTo('feed schedule edit')) {

            $rules = [
                'animal_id' => 'required',
                'feed_type_id' => 'required',
                'quantity' => 'required',
                'scheduled_time' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $schedule                 = FeedSchedule::find($id);
            $schedule->animal_id      =  $request->animal_id;
            $schedule->feed_type_id   =  $request->feed_type_id;
            $schedule->quantity       =  $request->quantity;
            $schedule->scheduled_time =  $request->scheduled_time;
            $schedule->save();

            event(new UpdateFeedSchedule($request, $schedule));

            return redirect()->back()->with('success', __('The feed schedule details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('feed schedule delete')) {

            $schedule = FeedSchedule::find($id);
            event(new DestroyFeedSchedule($schedule));
            $schedule->delete();

            return redirect()->route('feeds_schedule.index')->with('success', __('The feed schedule has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
