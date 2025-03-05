<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\DataTables\SalesDistributionDataTable;
use Workdo\DairyCattleManagement\Entities\MilkProduct;
use Workdo\DairyCattleManagement\Entities\SalesDistribution;
use Workdo\DairyCattleManagement\Events\CreateSalesDistribution;
use Workdo\DairyCattleManagement\Events\DestroySalesDistribution;
use Workdo\DairyCattleManagement\Events\UpdateSalesDistribution;

class SalesDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(SalesDistributionDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('sales-distribution manage')) {
            return $dataTable->render('dairy-cattle-management::sales_distributions.index');
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
        if (Auth::user()->isAbleTo('sales-distribution create')) {
            $customers = User::where('type','client')->where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('name','id');
            $products  = MilkProduct::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('name','id');
            return view('dairy-cattle-management::sales_distributions.create',compact('customers','products'));
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
        if (Auth::user()->isAbleTo('sales-distribution create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required',
                    'milk_product_id' => 'required',
                    'quantity' => 'required',
                    'total_price' => 'required',
                    'sale_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $sales_distribution                  = new SalesDistribution();
            $sales_distribution->customer_id     = $request->customer_id;
            $sales_distribution->milk_product_id = $request->milk_product_id;
            $sales_distribution->quantity        = $request->quantity;
            $sales_distribution->total_price     = $request->total_price;
            $sales_distribution->sale_date       = $request->sale_date;
            $sales_distribution->workspace       = getActiveWorkSpace();
            $sales_distribution->created_by      = creatorId();
            $sales_distribution->save();

            event(new CreateSalesDistribution($request, $sales_distribution));

            return redirect()->back()->with('success', __('The sales-distribution has been created successfully.'));
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
        if (Auth::user()->isAbleTo('sales-distribution edit')) {

            $sales_distribution = SalesDistribution::find($id);
            $customers = User::where('type','client')->where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->pluck('name','id');
            $products  = MilkProduct::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->pluck('name','id');
            return view('dairy-cattle-management::sales_distributions.edit',compact('sales_distribution','customers','products'));

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
        if (Auth::user()->isAbleTo('sales-distribution edit')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required',
                    'milk_product_id' => 'required',
                    'quantity' => 'required',
                    'total_price' => 'required',
                    'sale_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $sales_distribution                  = SalesDistribution::find($id);
            $sales_distribution->customer_id     = $request->customer_id;
            $sales_distribution->milk_product_id = $request->milk_product_id;
            $sales_distribution->quantity        = $request->quantity;
            $sales_distribution->total_price     = $request->total_price;
            $sales_distribution->sale_date       = $request->sale_date;
            $sales_distribution->save();

            event(new UpdateSalesDistribution($request, $sales_distribution));

            return redirect()->back()->with('success', __('The sales-distribution details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('sales-distribution delete')) {

            $sales_distribution = SalesDistribution::find($id);
            event(new DestroySalesDistribution($sales_distribution));
            $sales_distribution->delete();

            return redirect()->route('sales_distribution.index')->with('success', __('The sales-distribution has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
