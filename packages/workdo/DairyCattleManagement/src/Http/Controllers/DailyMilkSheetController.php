<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Workdo\DairyCattleManagement\DataTables\DailyMilkSheetDataTable;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\AnimalMilk;
use Workdo\DairyCattleManagement\Entities\DailyMilkSheet;
use Workdo\DairyCattleManagement\Events\CreateDailyMilkSheet;
use Workdo\DairyCattleManagement\Events\DestroyDailyMilkSheet;
use Workdo\DairyCattleManagement\Events\UpdateDailyMilkSheet;

class DailyMilkSheetController extends Controller
{

    public function index(DailyMilkSheetDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('dailymilksheet manage')) {
            return  $dataTable->render('dairy-cattle-management::dailymilksheet.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (Auth::user()->isAbleTo('dailymilksheet create')) {
            $animal = Animal::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');

            return view('dairy-cattle-management::dailymilksheet.create', compact('animal'));
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
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database
            $dailymilksheet = new DailyMilkSheet();
            $dailymilksheet->animal_id          =  $request->animal_id;
            $dailymilksheet->start_date       =  $request->start_date;
            $dailymilksheet->end_date         =  $request->end_date;
            $dailymilksheet->workspace      = getActiveWorkSpace();
            $dailymilksheet->created_by     = creatorId();
            $dailymilksheet->save();
            event(new CreateDailyMilkSheet($request, $dailymilksheet));

            return redirect()->back()->with('success', __('The daily milk sheet has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {
        if (Auth::user()->isAbleTo('dailymilksheet show')) {
            $id = Crypt::decrypt($id);

            $dailymilksheet = DailyMilkSheet::find($id);
            $dailymilksheets = DB::table('daily_milk_sheets')
                ->join('animals', 'daily_milk_sheets.animal_id', '=', 'animals.id')
                ->select('daily_milk_sheets.*', 'animals.name as animal_name')
                ->where('daily_milk_sheets.workspace', getActiveWorkSpace())
                ->where('daily_milk_sheets.created_by', creatorId())
                ->where('daily_milk_sheets.id', $id)
                ->first();

            $startDate = $dailymilksheet->start_date;
            $endDate = $dailymilksheet->end_date;
            $animalmilks = DB::table('animal_milks')
                ->join('animals', 'animal_milks.animal_id', '=', 'animals.id')
                ->select('animal_milks.*', 'animals.name as animal_name')
                ->where('animal_milks.workspace', getActiveWorkSpace())
                ->where('animal_milks.created_by', creatorId())
                ->where('animal_milks.animal_id', $dailymilksheet->animal_id)
                ->whereBetween('animal_milks.date', [$startDate, $endDate])
                ->get();

            return view('dairy-cattle-management::dailymilksheet.show', compact('dailymilksheet', 'dailymilksheets', 'animalmilks'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($id)
    {

        if (Auth::user()->isAbleTo('dailymilksheet edit')) {
            $dailymilksheet = DailyMilkSheet::find($id);
            $animal = Animal::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');

            return view('dairy-cattle-management::dailymilksheet.edit', compact('dailymilksheet', 'animal'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('dailymilksheet edit')) {
            $dailymilksheet = DailyMilkSheet::find($id);
            $validator = \Validator::make(
                $request->all(),
                [
                    'animal_id' => 'required|exists:animals,id',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database
            $dailymilksheet->animal_id          =  $request->animal_id;
            $dailymilksheet->start_date       =  $request->start_date;
            $dailymilksheet->end_date         =  $request->end_date;
            $dailymilksheet->workspace      = getActiveWorkSpace();
            $dailymilksheet->created_by     = creatorId();
            $dailymilksheet->save();
            event(new UpdateDailyMilkSheet($request, $dailymilksheet));

            return redirect()->back()->with('success',__('The daily milk sheet has been updated successfully.'));
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
        if (Auth::user()->isAbleTo('dailymilksheet edit')) {

            $dailymilksheet = DailyMilkSheet::find($id);
            event(new DestroyDailyMilkSheet($dailymilksheet));
            $dailymilksheet->delete();
            return redirect()->route('dailymilksheet.index')->with('success', __('The daily milk sheet has been deleted successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
