<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Workdo\DairyCattleManagement\DataTables\MilkInventoryDataTable;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\DailyMilkSheet;
use Workdo\DairyCattleManagement\Entities\MilkInventory;
use Workdo\DairyCattleManagement\Entities\MilkProduct;
use Workdo\DairyCattleManagement\Events\CreateMilkInventory;
use Workdo\DairyCattleManagement\Events\DestroyMilkInventory;
use Workdo\DairyCattleManagement\Events\UpdateMilkInventory;
use Workdo\Taskly\Events\UpdateMilestone;

class MilkInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(MilkInventoryDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('milkinventory manage')) {
            return $dataTable->render('dairy-cattle-management::milkinventory.index');
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
        if (Auth::user()->isAbleTo('milkinventory create')) {
            $dailymilksheets = DailyMilkSheet::where('workspace', getActiveWorkSpace())->get()->pluck('animal_id');
            $animals = Animal::whereIn('id', $dailymilksheets)->get()->pluck('name', 'id');

            $milksheetrecords = [];

            $product = MilkProduct::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('dairy-cattle-management::milkinventory.create', compact('dailymilksheets', 'milksheetrecords', 'animals', 'product'));
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
        if (Auth::user()->isAbleTo('milkinventory create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'daily_milksheet_id' => 'required|exists:animals,id',
                    'date' => 'required|date',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database
            $milkinventory = new MilkInventory();
            $milkinventory->daily_milksheet_id          =  implode(',', $request->daily_milksheet_id);
            $milkinventory->date       =  $request->date;
            $milkinventory->product_id       =  $request->product_id;
            $milkinventory->grand_total       =  $request->grand_total;
            $milkinventory->workspace      = getActiveWorkSpace();
            $milkinventory->created_by     = creatorId();
            $milkinventory->save();
            event(new CreateMilkInventory($request, $milkinventory));

            return redirect()->route('milkinventory.index')->with('success', __('The milk inventory has been created successfully.'));
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
        if (Auth::user()->isAbleTo('milkinventory show')) {
            $id = Crypt::decrypt($id);
            $milkinventory = MilkInventory::find($id);

            $animal = Animal::whereIn('id', explode(',', $milkinventory->daily_milksheet_id))->get()->pluck('name', 'id')->toArray();

            $keys = array_keys($animal);


            $dailymilksheets = DailyMilkSheet::whereIn('animal_id', $keys)
                ->join('animals', 'daily_milk_sheets.animal_id', '=', 'animals.id')
                ->select('daily_milk_sheets.*', 'animals.name as animal_name')
                ->get();

            // $dailymilksheets = DailyMilkSheet::whereIn('animal_id',$keys)->get();

            $morning_milk = DailyMilkSheet::whereIn('animal_id', $keys)->get()->pluck('morning_milk')->sum();
            $evening_milk = DailyMilkSheet::whereIn('animal_id', $keys)->get()->pluck('evening_milk')->sum();
            $total_unit   = $morning_milk + $evening_milk;

            return view('dairy-cattle-management::milkinventory.show', compact('milkinventory', 'dailymilksheets', 'animal', 'total_unit', 'morning_milk', 'evening_milk'));
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
        if (Auth::user()->isAbleTo('milkinventory edit')) {
            $milkinventory = MilkInventory::find($id);
            $dailymilksheets = DailyMilkSheet::where('workspace', getActiveWorkSpace())->get()->pluck('animal_id');
            $animals = Animal::whereIn('id', $dailymilksheets)->get()->pluck('name', 'id');
            $product = MilkProduct::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('dairy-cattle-management::milkinventory.edit', compact('milkinventory', 'animals', 'dailymilksheets', 'product'));
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
        if (Auth::user()->isAbleTo('milkinventory edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'daily_milksheet_id' => 'required|exists:animals,id',
                    'date' => 'required|date',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database
            $milkinventory = MilkInventory::find($id);

            $milkinventory->daily_milksheet_id          =  implode(',', $request->daily_milksheet_id);
            $milkinventory->date       =  $request->date;
            $milkinventory->product_id       =  $request->product_id;
            $milkinventory->grand_total       =  $request->grand_total;
            $milkinventory->workspace      = getActiveWorkSpace();
            $milkinventory->created_by     = creatorId();
            $milkinventory->save();
            event(new UpdateMilkInventory($request, $milkinventory));
            return redirect()->route('milkinventory.index')->with('success',  __('The milk inventory has been updated successfully.'));
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
        if (Auth::user()->isAbleTo('milkinventory delete')) {

            $milkinventory = MilkInventory::find($id);
            event(new DestroyMilkInventory($milkinventory));
            $milkinventory->delete();
            return redirect()->route('milkinventory.index')->with('success',  __('The milk inventory has been deleted successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function milkSectionGet(Request $request)
    {
        $selectedIds = $request->input('animal_id');
        $response = [];
        if ($request->input('animal_id')) {
            foreach ($selectedIds as $animal_id) {
                $milksheetrecords = DB::table('daily_milk_sheets')
                    ->join('animals', 'daily_milk_sheets.animal_id', '=', 'animals.id')
                    ->select('daily_milk_sheets.*', 'animals.name as animal_name')
                    ->where('daily_milk_sheets.workspace', getActiveWorkSpace())
                    ->where('daily_milk_sheets.created_by', creatorId())
                    ->where('daily_milk_sheets.animal_id', $animal_id)
                    ->get();

                foreach ($milksheetrecords as $milksheetrecord) {
                    $dailyMilkSheet = DailyMilkSheet::find($milksheetrecord->id);
                    $amTotal = $dailyMilkSheet->getAmTotal();
                    $pmTotal = $dailyMilkSheet->getPmTotal();
                    $total = $dailyMilkSheet->getTotal();

                    $response[] = [
                        'milksheetrecord' => $milksheetrecord,
                        'amTotal' => $amTotal,
                        'pmTotal' => $pmTotal,
                        'total' => $total,
                    ];
                }
            }
        }
        return response()->json($response);
    }


    public function MilkinventoryProductList(Request $request)
    {
        $milkinventorys = MilkInventory::where('workspace', getActiveWorkSpace())
            ->where('created_by', creatorId())
            ->groupBy('product_id')
            ->get();

        $productTotals = []; // Array to store product totals
        $sameProductMilksheets = [];
        foreach ($milkinventorys as $milkinventory) {
            $totalMilk = 0;


            // Get milk inventory records with the same product_id
            $sameProductMilksheets = MilkInventory::where('product_id', $milkinventory->product_id)
                ->where('workspace', getActiveWorkSpace())
                ->where('created_by', creatorId())
                ->get();

            $milksheetData = [];
            foreach ($sameProductMilksheets as $milksheet) {

                // Assuming $milksheet->grand_total is the field representing total milk quantity
                $totalMilk += $milksheet->grand_total;
                $milksheetData[] = [
                    'id' => $milksheet->id,
                    'date' => $milksheet->date,
                    'grand_total' => $milksheet->grand_total,
                ];
            }

            // Store the total milk for this product_id
            $productTotals[$milkinventory->product_id] = [

                'total_milk' => $totalMilk,
                'milksheet_data' => $milksheetData,
            ];
        }

        return view('dairy-cattle-management::milkinventory.productlist', compact('productTotals', 'milkinventorys', 'sameProductMilksheets'));
    }
}
