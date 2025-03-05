<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\DataTables\EquipmentManageDatatable;
use Workdo\DairyCattleManagement\Entities\EquipmentManage;
use Workdo\DairyCattleManagement\Events\CreateEquipmentManage;
use Workdo\DairyCattleManagement\Events\DestroyEquipmentManage;
use Workdo\DairyCattleManagement\Events\UpdateEquipmentManage;

class EquipmentManageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(EquipmentManageDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('dairy-equipment manage')) {
            return $dataTable->render('dairy-cattle-management::manage_equipments.index');
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
        if (Auth::user()->isAbleTo('dairy-equipment create')) {

            $schedules = EquipmentManage::$schedules;
            return view('dairy-cattle-management::manage_equipments.create',compact('schedules'));
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
        if (Auth::user()->isAbleTo('dairy-equipment create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'description' => 'required',
                    'purchase_date' => 'required',
                    'maintenance_schedule' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $equipment                       = new EquipmentManage();
            $equipment->name                 = $request->name;
            $equipment->description          = $request->description;
            $equipment->purchase_date        = $request->purchase_date;
            $equipment->maintenance_schedule = $request->maintenance_schedule;
            $equipment->workspace            = getActiveWorkSpace();
            $equipment->created_by           = creatorId();
            $equipment->save();

            event(new CreateEquipmentManage($request, $equipment));

            return redirect()->back()->with('success', __('The equipment has been created successfully.'));
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
        $equipment = EquipmentManage::find($id);
        return view('dairy-cattle-management::manage_equipments.description', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('dairy-equipment edit')) {

            $equipment = EquipmentManage::find($id);
            $schedules = EquipmentManage::$schedules;

            return view('dairy-cattle-management::manage_equipments.edit', compact('equipment','schedules'));
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
        if (Auth::user()->isAbleTo('dairy-equipment edit')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'description' => 'required',
                    'purchase_date' => 'required',
                    'maintenance_schedule' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $equipment                       = EquipmentManage::find($id);
            $equipment->name                 = $request->name;
            $equipment->description          = $request->description;
            $equipment->purchase_date        = $request->purchase_date;
            $equipment->maintenance_schedule = $request->maintenance_schedule;
            $equipment->save();

            event(new UpdateEquipmentManage($request, $equipment));

            return redirect()->back()->with('success', __('The equipment details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('dairy-equipment delete')) {

            $equipment = EquipmentManage::find($id);
            event(new DestroyEquipmentManage($equipment));
            $equipment->delete();

            return redirect()->route('dairy-equipments.index')->with('success', __('The equipment has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
