<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\DataTables\FeedConsumptionDatatable;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\FeedConsumption;
use Workdo\DairyCattleManagement\Entities\FeedType;
use Workdo\DairyCattleManagement\Events\CreateFeedConsumption;
use Workdo\DairyCattleManagement\Events\DestroyFeedConsumption;
use Workdo\DairyCattleManagement\Events\UpdateFeedConsumption;

class FeedConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(FeedConsumptionDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('feed consumption manage')) {
            return $dataTable->render('dairy-cattle-management::feeds_consumption.index');
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
        if (Auth::user()->isAbleTo('feed consumption create')) {
            $animals = Animal::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name','id');
            $feed_types = FeedType::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name','id');
            return view('dairy-cattle-management::feeds_consumption.create',compact('animals','feed_types'));
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
        if (Auth::user()->isAbleTo('feed consumption create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required',
                    'feed_type_id' => 'required',
                    'quantity_consumed' => 'required',
                    'consumption_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $consumption                    = new FeedConsumption();
            $consumption->animal_id         = $request->animal_id;
            $consumption->feed_type_id      = $request->feed_type_id;
            $consumption->quantity_consumed = $request->quantity_consumed;
            $consumption->consumption_date  = $request->consumption_date;
            $consumption->workspace         = getActiveWorkSpace();
            $consumption->created_by        = creatorId();
            $consumption->save();

            event(new CreateFeedConsumption($request, $consumption));

            return redirect()->back()->with('success', __('The feed consumption has been created successfully.'));
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
        if (Auth::user()->isAbleTo('feed consumption edit')) {

            $consumption = FeedConsumption::find($id);
            $animals     = Animal::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->pluck('name','id');
            $feed_types  = FeedType::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->pluck('name','id');

            return view('dairy-cattle-management::feeds_consumption.edit', compact('consumption','animals','feed_types'));
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
        if (Auth::user()->isAbleTo('feed consumption edit')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required',
                    'feed_type_id' => 'required',
                    'quantity_consumed' => 'required',
                    'consumption_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $consumption                    = FeedConsumption::find($id);
            $consumption->animal_id         = $request->animal_id;
            $consumption->feed_type_id      = $request->feed_type_id;
            $consumption->quantity_consumed = $request->quantity_consumed;
            $consumption->consumption_date  = $request->consumption_date;
            $consumption->save();

            event(new UpdateFeedConsumption($request, $consumption));

            return redirect()->back()->with('success', __('The feed consumption details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('feed consumption delete')) {

            $consumption = FeedConsumption::find($id);
            event(new DestroyFeedConsumption($consumption));
            $consumption->delete();

            return redirect()->route('feeds_consumption.index')->with('success', __('The feed consumption has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
