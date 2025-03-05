<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Workdo\DairyCattleManagement\DataTables\MilkProductDataTable;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\DailyMilkSheet;
use Workdo\DairyCattleManagement\Entities\MilkInventory;
use Workdo\DairyCattleManagement\Entities\MilkProduct;
use Workdo\DairyCattleManagement\Events\CreateMilkProduct;
use Workdo\DairyCattleManagement\Events\DestroyMilkProduct;
use Workdo\DairyCattleManagement\Events\UpdateMilkProduct;

class MilkProductController extends Controller
{

    public function index(MilkProductDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('milkproduct manage')) {
            return $dataTable->render('dairy-cattle-management::milkproduct.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (Auth::user()->isAbleTo('milkproduct create')) {
            $milkinventorys = MilkInventory::where('workspace', getActiveWorkSpace())
                ->where('created_by', creatorId())
                ->get();

            $animalsGrouped = [];
            foreach ($milkinventorys as $milkinventory) {
                $animals = \Workdo\DairyCattleManagement\Entities\Animal::whereIn('id', explode(',', $milkinventory->daily_milksheet_id))
                    ->get()
                    ->pluck('name')
                    ->toArray();

                $animalNames = implode(', ', $animals);

                if (isset($animalsGrouped[$milkinventory->id])) {
                    $animalsGrouped[$milkinventory->id][] = $animalNames;
                } else {
                    $animalsGrouped[$milkinventory->id] = [$animalNames];
                }
            }

            return view('dairy-cattle-management::milkproduct.create', compact('milkinventorys', 'animalsGrouped'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {

        if (Auth::user()->isAbleTo('milkproduct create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'responsible' => 'required|max:120',
                    'sale_price' => 'required',
                    'cost' => 'required',
                    'quantity_on_hand' => 'required',
                    'forcasted_quantity' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            // Store the validated data into the database
            $milkproduct = new MilkProduct();
            $milkproduct->milk_inventory_id          =  $request->milk_inventory_id;
            $milkproduct->name          =  $request->name;
            $milkproduct->responsible       =  $request->responsible;
            $milkproduct->sale_price         =  $request->sale_price;
            $milkproduct->cost             =  $request->cost;
            $milkproduct->quantity_on_hand        =  $request->quantity_on_hand;
            $milkproduct->forcasted_quantity =  $request->forcasted_quantity;
            $milkproduct->workspace      = getActiveWorkSpace();
            $milkproduct->created_by     = creatorId();
            $milkproduct->save();

            event(new CreateMilkProduct($request, $milkproduct));

            return redirect()->back()->with('success',  __('The milk product has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($id)
    {
        if (Auth::user()->isAbleTo('milkproduct show')) {
            $milkproduct = MilkProduct::find($id);
            $milkproducts = MilkProduct::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($milkproduct->id);


            return view('dairy-cattle-management::milkproduct.show', compact('milkproducts'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($id)
    {
        if (Auth::user()->isAbleTo('milkproduct edit')) {

            $milkproduct = MilkProduct::find($id);

            $milkinventorys = MilkInventory::where('workspace', getActiveWorkSpace())
                ->where('created_by', creatorId())
                ->get();

            $animalsGrouped = [];
            foreach ($milkinventorys as $milkinventory) {
                $animals = \Workdo\DairyCattleManagement\Entities\Animal::whereIn('id', explode(',', $milkinventory->daily_milksheet_id))
                    ->get()
                    ->pluck('name')
                    ->toArray();

                // Convert the array of animal names to a comma-separated string
                $animalNames = implode(', ', $animals);

                if (isset($animalsGrouped[$milkinventory->id])) {
                    $animalsGrouped[$milkinventory->id][] = $animalNames;
                } else {
                    $animalsGrouped[$milkinventory->id] = [$animalNames];
                }
            }

            return view('dairy-cattle-management::milkproduct.edit', compact('milkproduct', 'animalsGrouped'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('milkproduct edit')) {
            $milkproduct = MilkProduct::find($id);

            $rules = [
                'name' => 'required|max:120',
                'responsible' => 'required|max:120',
                'sale_price' => 'required',
                'cost' => 'required',
                'quantity_on_hand' => 'required',
                'forcasted_quantity' => 'required',

            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('milkproduct.index')->with('error', $messages->first());
            }

            $milkproduct->milk_inventory_id          =  $request->milk_inventory_id;
            $milkproduct->name          =  $request->name;
            $milkproduct->responsible       =  $request->responsible;
            $milkproduct->sale_price         =  $request->sale_price;
            $milkproduct->cost             =  $request->cost;
            $milkproduct->quantity_on_hand        =  $request->quantity_on_hand;
            $milkproduct->forcasted_quantity =  $request->forcasted_quantity;
            $milkproduct->workspace      = getActiveWorkSpace();
            $milkproduct->created_by     = creatorId();

            $milkproduct->save();

            event(new UpdateMilkProduct($request, $milkproduct));

            return redirect()->back()->with('success',  __('The milk product has been updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('milkproduct delete')) {

            $milkproduct = MilkProduct::find($id);
            event(new DestroyMilkProduct($milkproduct));
            $milkproduct->delete();
            return redirect()->route('milkproduct.index')->with('success',  __('The milk product has been deleted successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getAnimalData(Request $request)
    {
        $inventoryItems = MilkInventory::where('id', $request->selectedAnimalId)->get();

        $totalMilk = 0;
        foreach ($inventoryItems as $k_milkinv => $milkinv) {
            $totalMilk += $milkinv->grand_total; // Assuming grand_total is the field representing total milk
        }

        // Calculate the total quantity_on_hand and forecasted_quantity
        $totalQuantityOnHand = $inventoryItems->sum('quantity_on_hand');
        $totalForecastedQuantity = $inventoryItems->sum('forecasted_quantity');

        $data = [
            'quantity_on_hand' => $totalQuantityOnHand,
            'forecasted_quantity' => $totalForecastedQuantity,
            'total_milk' => $totalMilk,
        ];

        return response()->json($data);
    }
}
