<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\DataTables\BirthRecordsDataTable;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\BirthRecord;
use Workdo\DairyCattleManagement\Events\CreateBirthRecord;
use Workdo\DairyCattleManagement\Events\DestroyBirthRecord;
use Workdo\DairyCattleManagement\Events\UpdateBirthRecord;

class BirthRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(BirthRecordsDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('birth record manage')) {
            return $dataTable->render('dairy-cattle-management::birth_records.index');
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
        if (Auth::user()->isAbleTo('birth record create')) {
            $healthStatusOptions = BirthRecord::$healthstatus;
            $animals = Animal::where('gender','Female')->where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('name','id');

            return view('dairy-cattle-management::birth_records.create',compact('animals','healthStatusOptions'));
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
        if (Auth::user()->isAbleTo('birth record create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required',
                    'birth_date' => 'required',
                    'gender' => 'required',
                    'weight_at_birth' => 'required',
                    'health_status' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $birth_record                  = new BirthRecord();
            $birth_record->animal_id       = $request->animal_id;
            $birth_record->birth_date      = $request->birth_date;
            $birth_record->gender          = $request->gender;
            $birth_record->weight_at_birth = $request->weight_at_birth;
            $birth_record->health_status   = $request->health_status;
            $birth_record->workspace       = getActiveWorkSpace();
            $birth_record->created_by      = creatorId();
            $birth_record->save();

            event(new CreateBirthRecord($request, $birth_record));

            return redirect()->back()->with('success', __('The birth record has been created successfully.'));
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
        if (Auth::user()->isAbleTo('birth record edit')) {

            $healthStatusOptions = BirthRecord::$healthstatus;
            $birth_record = BirthRecord::find($id);
            $animals = Animal::where('gender','Female')->where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->pluck('name','id');
            return view('dairy-cattle-management::birth_records.edit',compact('birth_record','animals','healthStatusOptions'));

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
        if (Auth::user()->isAbleTo('birth record edit')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required',
                    'birth_date' => 'required',
                    'gender' => 'required',
                    'weight_at_birth' => 'required',
                    'health_status' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $birth_record                  = BirthRecord::find($id);
            $birth_record->animal_id       = $request->animal_id;
            $birth_record->birth_date      = $request->birth_date;
            $birth_record->gender          = $request->gender;
            $birth_record->weight_at_birth = $request->weight_at_birth;
            $birth_record->health_status   = $request->health_status;
            $birth_record->save();

            event(new UpdateBirthRecord($request, $birth_record));

            return redirect()->back()->with('success', __('The birth record details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('birth record delete')) {

            $sales_distribution = BirthRecord::find($id);
            event(new DestroyBirthRecord($sales_distribution));
            $sales_distribution->delete();

            return redirect()->route('birth_records.index')->with('success', __('The birth record has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
