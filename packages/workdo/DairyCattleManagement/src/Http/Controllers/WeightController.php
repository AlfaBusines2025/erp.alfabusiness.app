<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Workdo\DairyCattleManagement\DataTables\WeightDataTable;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\Weight;
use Workdo\DairyCattleManagement\Events\CreateWeight;
use Workdo\DairyCattleManagement\Events\DestroyWeight;
use Workdo\DairyCattleManagement\Events\UpdateWeight;

class WeightController extends Controller
{

    public function index(WeightDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('weight manage')) {
            return $dataTable->render('dairy-cattle-management::weight.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('weight create')) {

            $animal = Animal::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            return view('dairy-cattle-management::weight.create', compact('animal'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('weight create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required|exists:animals,id',
                    'date' => 'required|date',
                    'age' => 'required',
                    'weight' => 'required'

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database
            $weight = new Weight();
            $weight->animal_id          =  $request->animal_id;
            $weight->date               =  $request->date;
            $weight->age                =  $request->age;
            $weight->weight             =  $request->weight;
            $weight->workspace          = getActiveWorkSpace();
            $weight->created_by         = creatorId();
            $weight->save();
            event(new CreateWeight($request, $weight));

            return redirect()->back()->with('success',  __('The weight has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($id)
    {
        if (Auth::user()->isAbleTo('health show')) {
            $weight = DB::table('weights')
                ->join('animals', 'weights.animal_id', '=', 'animals.id')
                ->select('weights.*', 'animals.name as animal_name')
                ->where('weights.id', $id)
                ->where('weights.workspace', getActiveWorkSpace())
                ->where('weights.created_by', creatorId())
                ->first();
            return view('dairy-cattle-management::weight.show', compact('weight'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('weight edit')) {
            $weight = Weight::find($id);
            $animal = Animal::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            return view('dairy-cattle-management::weight.edit', compact('animal', 'weight'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        return view('dairy-cattle-management::edit');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('weight edit')) {
            $weight = Weight::find($id);
            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required|exists:animals,id',
                    'date' => 'required|date',
                    'age' => 'required',
                    'weight' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database
            $weight->animal_id          =  $request->animal_id;
            $weight->date               =  $request->date;
            $weight->age                =  $request->age;
            $weight->weight             =  $request->weight;
            $weight->workspace          = getActiveWorkSpace();
            $weight->created_by         = creatorId();
            $weight->save();
            event(new UpdateWeight($request, $weight));

            return redirect()->back()->with('success',  __('The weight has been updated successfully.'));
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
        if (Auth::user()->isAbleTo('weight delete')) {

            $weight = Weight::find($id);
            event(new DestroyWeight($weight));
            $weight->delete();
            return redirect()->route('weight.index')->with('success',  __('The weight has been deleted successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
