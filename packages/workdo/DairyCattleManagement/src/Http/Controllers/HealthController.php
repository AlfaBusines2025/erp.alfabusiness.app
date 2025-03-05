<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Workdo\DairyCattleManagement\DataTables\HealthDataTable;
use Workdo\DairyCattleManagement\Entities\Health;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Events\CreateHealth;
use Workdo\DairyCattleManagement\Events\DestroyHealth;
use Workdo\DairyCattleManagement\Events\UpdateHealth;

class HealthController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(HealthDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('health manage')) {
            return $dataTable->render('dairy-cattle-management::health.index');
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
        if (Auth::user()->isAbleTo('health create')) {
            $animal = Animal::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $durations = Health::$duration;
            return view('dairy-cattle-management::health.create', compact('animal', 'durations'));
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
        if (Auth::user()->isAbleTo('health create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required|exists:animals,id',
                    'veterinarian' => 'required|max:120',
                    'duration' => 'required',
                    'date' => 'required|date',
                    'checkup_date' => 'required|date',
                    'diagnosis' => 'required|max:120',
                    'treatment' => 'required|max:120',
                    'price' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database
            $health = new Health();
            $health->animal_id          =  $request->animal_id;
            $health->veterinarian       =  $request->veterinarian;
            $health->duration         =  $request->duration;
            $health->date             =  $request->date;
            $health->checkup_date        =  $request->checkup_date;
            $health->diagnosis           =  $request->diagnosis;
            $health->treatment        =  $request->treatment;
            $health->price          =  $request->price;
            $health->workspace      = getActiveWorkSpace();
            $health->created_by     = creatorId();
            $health->save();
            event(new CreateHealth($request, $health));

            return redirect()->back()->with('success', __('The health has been created successfully.'));
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
        if (Auth::user()->isAbleTo('health show')) {
            $health = DB::table('healths')
                ->join('animals', 'healths.animal_id', '=', 'animals.id')
                ->select('healths.*', 'animals.name as animal_name')
                ->where('healths.id', $id)
                ->where('healths.workspace', getActiveWorkSpace())
                ->where('healths.created_by', creatorId())
                ->first();
            return view('dairy-cattle-management::health.show', compact('health'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('health edit')) {

            $health = Health::find($id);
            $animal = Animal::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $durations = Health::$duration;
            return view('dairy-cattle-management::health.edit', compact('animal', 'durations', 'health'));
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
        if (Auth::user()->isAbleTo('health edit')) {

            $health = Health::find($id);

            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required|exists:animals,id',
                    'veterinarian' => 'required|max:120',
                    'duration' => 'required',
                    'date' => 'required|date',
                    'checkup_date' => 'required|date',
                    'diagnosis' => 'required|max:120',
                    'treatment' => 'required|max:120',
                    'price' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database

            $health->animal_id          =  $request->animal_id;
            $health->veterinarian       =  $request->veterinarian;
            $health->duration         =  $request->duration;
            $health->date             =  $request->date;
            $health->checkup_date        =  $request->checkup_date;
            $health->diagnosis           =  $request->diagnosis;
            $health->treatment        =  $request->treatment;
            $health->price          =  $request->price;

            $health->save();
            event(new UpdateHealth($request, $health));
            return redirect()->back()->with('success',  __('The health has been updated successfully.'));
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
        if (Auth::user()->isAbleTo('health delete')) {

            $health = Health::find($id);
            event(new DestroyHealth($health));
            $health->delete();
            return redirect()->route('health.index')->with('success',  __('The health has been deleted successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
