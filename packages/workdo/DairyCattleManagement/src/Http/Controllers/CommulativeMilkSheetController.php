<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\AnimalMilk;
use Workdo\DairyCattleManagement\Entities\MilkInventory;

class CommulativeMilkSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('commulativemilksheet manage')) {
            $animals = Animal::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name','id');
            $animalsWithTotalMorningMilk = Animal::select('animals.*', DB::raw('COALESCE(SUM(animal_milks.morning_milk), 0) as total_morning_milk'))
            ->leftJoin('animal_milks', 'animals.id', '=', 'animal_milks.animal_id')
            ->groupBy('animals.id')
            ->get();
            $animalsWithTotalEveningMilk = Animal::select('animals.*', DB::raw('COALESCE(SUM(animal_milks.evening_milk), 0) as total_evening_milk'))
            ->leftJoin('animal_milks', 'animals.id', '=', 'animal_milks.animal_id')
            ->groupBy('animals.id')
            ->get();
            $animalsWithTotalMilk = Animal::select('animals.*', DB::raw('COALESCE(SUM(animal_milks.morning_milk), 0) + COALESCE(SUM(animal_milks.evening_milk), 0) as total_milk'))
            ->leftJoin('animal_milks', 'animals.id', '=', 'animal_milks.animal_id')
            ->groupBy('animals.id')
            ->get();

            return view('dairy-cattle-management::commulativemilksheet.index',compact('animals','animalsWithTotalMorningMilk','animalsWithTotalEveningMilk','animalsWithTotalMilk'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }


    public function create()
    {
        return view('dairy-cattle-management::create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        return view('dairy-cattle-management::show');
    }


    public function edit($id)
    {
        return view('dairy-cattle-management::edit');
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
