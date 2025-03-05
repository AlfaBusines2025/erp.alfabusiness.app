<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\Breeding;
use Workdo\DairyCattleManagement\Events\CreateBreeding;
use Workdo\DairyCattleManagement\Events\DestroyBreeding;
use Workdo\DairyCattleManagement\Events\UpdateBreeding;
use Workdo\DairyCattleManagement\DataTables\BreedingDataTable;

class BreedingController extends Controller
{

    public function index(BreedingDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('breeding manage')) {
            return $dataTable->render('dairy-cattle-management::breeding.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('breeding create')) {
            $breedingstatus = Breeding::$breedingstatus;
            $animal = Animal::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name','id');
            return view('dairy-cattle-management::breeding.create',compact('breedingstatus','animal'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('breeding create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required|exists:animals,id',
                    'breeding_date' => 'required|date',
                    'gestation' => 'required',
                    'due_date' => 'required|date',
                    'breeding_status' => 'required',
                    'note' => 'required|max:255'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database
            $breeding = new Breeding();
            $breeding->animal_id          =  $request->animal_id;
            $breeding->breeding_date       =  $request->breeding_date;
            $breeding->gestation         =  $request->gestation;
            $breeding->due_date             =  $request->due_date;
            $breeding->breeding_status        =  $request->breeding_status;
            $breeding->note           =  $request->note;
            $breeding->workspace      = getActiveWorkSpace();
            $breeding->created_by     = creatorId();
            $breeding->save();
            event(new CreateBreeding($request, $breeding));

            return redirect()->back()->with('success', __('The breeding has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {
        if (Auth::user()->isAbleTo('breeding show')) {
            $breedings = DB::table('breedings')
            ->join('animals', 'breedings.animal_id', '=', 'animals.id')
            ->select('breedings.*', 'animals.name as animal_name')
            ->where('breedings.id',$id)
            ->where('breedings.workspace', getActiveWorkSpace())
            ->where('breedings.created_by', creatorId())
            ->first();


            return view('dairy-cattle-management::breeding.show',compact('breedings'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }



    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('breeding edit')) {

            $breeding = Breeding::find($id);
            $breedingstatus = Breeding::$breedingstatus;
            $animal = Animal::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name','id');
            return view('dairy-cattle-management::breeding.edit',compact('breedingstatus','breeding','animal'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('breeding edit')) {
            $breeding = Breeding::find($id);
            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required|exists:animals,id',
                    'breeding_date' => 'required|date',
                    'gestation' => 'required',
                    'due_date' => 'required|date',
                    'breeding_status' => 'required',
                    'note' => 'required|max:255'

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database

            $breeding->animal_id          =  $request->animal_id;
            $breeding->breeding_date       =  $request->breeding_date;
            $breeding->gestation         =  $request->gestation;
            $breeding->due_date             =  $request->due_date;
            $breeding->breeding_status        =  $request->breeding_status;
            $breeding->note           =  $request->note;
            $breeding->workspace      = getActiveWorkSpace();
            $breeding->created_by     = creatorId();
            $breeding->save();
            event(new UpdateBreeding($request, $breeding));

            return redirect()->back()->with('success', __('The breeding has been updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('breeding delete')) {

            $breeding = Breeding::find($id);
            event(new DestroyBreeding($breeding));
            $breeding->delete();
            return redirect()->route('breeding.index')->with('success',__('The breeding has been deleted successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
