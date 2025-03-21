<?php

namespace Workdo\Hrm\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\Hrm\DataTables\EmpResignationDataTable;
use Workdo\Hrm\Entities\Employee;
use Workdo\Hrm\Entities\Resignation;
use Workdo\Hrm\Events\CreateResignation;
use Workdo\Hrm\Events\DestroyResignation;
use Workdo\Hrm\Events\UpdateResignation;

class ResignationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(EmpResignationDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('resignation manage')) {

            return $dataTable->render('hrm::resignation.index');
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
        if (Auth::user()->isAbleTo('resignation create')) {
            $employees = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

            return view('hrm::resignation.create', compact('employees'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('resignation create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'resignation_date' => 'required|after:yesterday',
                    'last_working_date' => 'required|after_or_equal:resignation_date',
                    'description' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $resignation = new Resignation();
            if (in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $employee = Employee::where('user_id', '=', $request->employee_id)->first();
                $resignation->employee_id = !empty($employee) ? $employee->id : '0';
                $resignation->user_id = $request->employee_id;
            } else {
                $employee = Employee::where('user_id', '=', creatorId())->first();
                $resignation->user_id = Auth::user()->id;
                $resignation->employee_id = !empty($employee) ? $employee->id : '';
            }

            $resignation->resignation_date = $request->resignation_date;
            $resignation->last_working_date = $request->last_working_date;
            $resignation->description      = $request->description;
            $resignation->workspace        = getActiveWorkSpace();
            $resignation->created_by       = creatorId();
            $resignation->save();

            event(new CreateResignation($request, $resignation));
            $company_settings = getCompanyAllSetting();
            if (!empty($company_settings['Employee Resignation']) && $company_settings['Employee Resignation']  == true) {
                $User        = User::where('id', $resignation->user_id)->where('workspace_id', '=',  getActiveWorkSpace())->first();

                $uArr = [
                    'assign_user' => !empty($employee->name) ? $employee->name : '',
                    'resignation_date'  => $request->resignation_date,
                    'notice_date' => $request->last_working_date,
                ];
                try {

                    $resp = EmailTemplate::sendEmailTemplate('Employee Resignation', [$User->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->route('resignation.index')->with('success', __('The resignation has been created successfully.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }

            return redirect()->route('resignation.index')->with('success', __('The resignation has been created successfully.'));
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
        return redirect()->back();
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Resignation $resignation)
    {
        if (Auth::user()->isAbleTo('resignation edit')) {
            if ($resignation->created_by == creatorId() && $resignation->workspace == getActiveWorkSpace()) {
                $employees = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

                return view('hrm::resignation.edit', compact('resignation', 'employees'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Resignation $resignation)
    {
        if (Auth::user()->isAbleTo('resignation edit')) {
            if ($resignation->created_by == creatorId() && $resignation->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'resignation_date' => 'required|date',
                        'last_working_date' => 'required|after_or_equal:resignation_date',
                        'description' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                if (in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                    $employee = Employee::where('user_id', '=', $request->employee_id)->first();
                    $resignation->employee_id = !empty($employee) ? $employee->id : '';
                    $resignation->user_id = $request->employee_id;
                } else {
                    $employee = Employee::where('user_id', '=', creatorId())->first();
                    $resignation->user_id = Auth::user()->id;
                    $resignation->employee_id = !empty($employee) ? $employee->id : '';
                }

                $resignation->resignation_date = $request->resignation_date;
                $resignation->last_working_date = $request->last_working_date;
                $resignation->description      = $request->description;

                $resignation->save();

                event(new UpdateResignation($request, $resignation));

                return redirect()->route('resignation.index')->with('success', __('The resignation details are updated successfully.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Resignation $resignation)
    {
        if (Auth::user()->isAbleTo('resignation delete')) {
            if ($resignation->created_by == creatorId() && $resignation->workspace == getActiveWorkSpace()) {

                event(new DestroyResignation($resignation));

                $resignation->delete();

                return redirect()->route('resignation.index')->with('success', __('The resignation has been deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function description($id)
    {
        $resignations = Resignation::find($id);
        return view('hrm::resignation.description', compact('resignations'));
    }
}
