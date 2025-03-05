<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\DataTables\VaccinationDatatable;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\Vaccination;
use Workdo\DairyCattleManagement\Events\CreateVaccination;
use Workdo\DairyCattleManagement\Events\DestroyVaccination;
use Workdo\DairyCattleManagement\Events\UpdateVaccination;

class VaccinationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(VaccinationDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('vaccination manage')) {
            return $dataTable->render('dairy-cattle-management::vaccinations.index');
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
        if (Auth::user()->isAbleTo('vaccination create')) {
            $animals = Animal::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name','id');
            return view('dairy-cattle-management::vaccinations.create',compact('animals'));
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
        if (Auth::user()->isAbleTo('vaccination create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required',
                    'vaccination_name' => 'required',
                    'vaccination_date' => 'required',
                    'next_due_date' => 'required',
                    'veterinarian' => 'required',
                    'notes' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $vaccination                   = new Vaccination();
            $vaccination->animal_id        = $request->animal_id;
            $vaccination->vaccination_name = $request->vaccination_name;
            $vaccination->vaccination_date = $request->vaccination_date;
            $vaccination->next_due_date    = $request->next_due_date;
            $vaccination->veterinarian     = $request->veterinarian;
            $vaccination->notes            = $request->notes;
            $vaccination->workspace        = getActiveWorkSpace();
            $vaccination->created_by       = creatorId();
            $vaccination->save();

            event(new CreateVaccination($request, $vaccination));

            return redirect()->back()->with('success', __('The vaccination has been created successfully.'));
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
        $vaccination = Vaccination::find($id);
        return view('dairy-cattle-management::vaccinations.note', compact('vaccination'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('vaccination edit')) {

            $vaccination = Vaccination::find($id);
            $animals     = Animal::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->pluck('name','id');

            return view('dairy-cattle-management::vaccinations.edit', compact('vaccination','animals'));
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
        if (Auth::user()->isAbleTo('vaccination edit')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required',
                    'vaccination_name' => 'required',
                    'vaccination_date' => 'required',
                    'next_due_date' => 'required',
                    'veterinarian' => 'required',
                    'notes' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $vaccination                   = Vaccination::find($id);
            $vaccination->animal_id        = $request->animal_id;
            $vaccination->vaccination_name = $request->vaccination_name;
            $vaccination->vaccination_date = $request->vaccination_date;
            $vaccination->next_due_date    = $request->next_due_date;
            $vaccination->veterinarian     = $request->veterinarian;
            $vaccination->notes            = $request->notes;
            $vaccination->save();

            event(new UpdateVaccination($request, $vaccination));

            return redirect()->back()->with('success', __('The vaccination details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('vaccination delete')) {

            $vaccination = Vaccination::find($id);
            event(new DestroyVaccination($vaccination));
            $vaccination->delete();

            return redirect()->route('vaccinations.index')->with('success', __('The vaccination has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
