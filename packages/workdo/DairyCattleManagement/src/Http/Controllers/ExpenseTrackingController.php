<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\DataTables\ExpenseTrackingDataTable;
use Workdo\DairyCattleManagement\Entities\ExpenseTracking;
use Workdo\DairyCattleManagement\Events\CreateExpenseTracking;
use Workdo\DairyCattleManagement\Events\DestroyExpenseTracking;
use Workdo\DairyCattleManagement\Events\UpdateExpenseTracking;

class ExpenseTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ExpenseTrackingDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('expense tracking manage')) {
            return $dataTable->render('dairy-cattle-management::expense_tracking.index');
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
        if (Auth::user()->isAbleTo('expense tracking create')) {

            return view('dairy-cattle-management::expense_tracking.create');
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
        if (Auth::user()->isAbleTo('expense tracking create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'category' => 'required',
                    'amount' => 'required',
                    'description' => 'required',
                    'expense_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $expense_tracking               = new ExpenseTracking();
            $expense_tracking->category     = $request->category;
            $expense_tracking->amount       = $request->amount;
            $expense_tracking->description  = $request->description;
            $expense_tracking->expense_date = $request->expense_date;
            $expense_tracking->workspace    = getActiveWorkSpace();
            $expense_tracking->created_by   = creatorId();
            $expense_tracking->save();

            event(new CreateExpenseTracking($request, $expense_tracking));

            return redirect()->back()->with('success', __('The expense tracking data has been created successfully.'));
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
        $expense_tracking = ExpenseTracking::find($id);
        return view('dairy-cattle-management::expense_tracking.description', compact('expense_tracking'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('expense tracking edit')) {

            $expense_tracking = ExpenseTracking::find($id);

            return view('dairy-cattle-management::expense_tracking.edit', compact('expense_tracking'));
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
        if (Auth::user()->isAbleTo('expense tracking edit')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'category' => 'required',
                    'amount' => 'required',
                    'description' => 'required',
                    'expense_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $expense_tracking               = ExpenseTracking::find($id);
            $expense_tracking->category     = $request->category;
            $expense_tracking->amount       = $request->amount;
            $expense_tracking->description  = $request->description;
            $expense_tracking->expense_date = $request->expense_date;
            $expense_tracking->save();

            event(new UpdateExpenseTracking($request, $expense_tracking));

            return redirect()->back()->with('success', __('The expense tracking details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('expense tracking delete')) {

            $expense_tracking = ExpenseTracking::find($id);
            event(new DestroyExpenseTracking($expense_tracking));
            $expense_tracking->delete();

            return redirect()->route('expense_tracking.index')->with('success', __('The expense tracking has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
