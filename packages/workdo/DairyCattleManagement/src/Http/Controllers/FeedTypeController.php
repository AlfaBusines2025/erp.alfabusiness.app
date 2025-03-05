<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\DataTables\FeedTypesDatatable;
use Workdo\DairyCattleManagement\Entities\FeedType;
use Workdo\DairyCattleManagement\Events\CreateFeedType;
use Workdo\DairyCattleManagement\Events\DestroyFeedType;
use Workdo\DairyCattleManagement\Events\UpdateFeedType;

class FeedTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(FeedTypesDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('feed type manage')) {
            return $dataTable->render('dairy-cattle-management::feeds_type.index');
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
        if (Auth::user()->isAbleTo('feed type create')) {

            return view('dairy-cattle-management::feeds_type.create');
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
        if (Auth::user()->isAbleTo('feed type create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            // Store the validated data into the database
            $feed_type              = new FeedType();
            $feed_type->name        =  $request->name;
            $feed_type->description =  $request->description;
            $feed_type->workspace   = getActiveWorkSpace();
            $feed_type->created_by  = creatorId();
            $feed_type->save();

            event(new CreateFeedType($request, $feed_type));

            return redirect()->back()->with('success', __('The feed type has been created successfully.'));
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
        $feed_type = FeedType::find($id);
        return view('dairy-cattle-management::feeds_type.description', compact('feed_type'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('feed type edit')) {

            $feed_type = FeedType::find($id);

            return view('dairy-cattle-management::feeds_type.edit', compact('feed_type'));
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
        if (Auth::user()->isAbleTo('feed type edit')) {

            $rules = [

                'name' => 'required|max:120',
                'description' => 'required'
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $feed_type              = FeedType::find($id);
            $feed_type->name        = $request->name;
            $feed_type->description = $request->description;
            $feed_type->save();

            event(new UpdateFeedType($request, $feed_type));

            return redirect()->back()->with('success', __('The feed type details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('feed type delete')) {

            $feed_type = FeedType::find($id);
            event(new DestroyFeedType($feed_type));
            $feed_type->delete();

            return redirect()->route('feeds_type.index')->with('success', __('The feed type has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
