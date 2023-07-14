<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::table('purchases as p')
                    ->leftJoin('people as c', function ($join) {
                        $join->on('p.supplier_id', '=', 'c.id');
                    })
                    ->orderBy('p.id', 'DESC')
                    ->get(['p.id', 'p.date', 'c.name as supplier']);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('amount', function ($data) {
                        $purchase = DB::table('purchases')
                            ->where('id', $data->id)->first();

                        $purchase_details = DB::table('purchase_details')
                            ->where('purchase_id', $data->id)
                            ->get(['rate', 'quantity']);

                        $paid_amount = DB::table('transactions')
                            ->where('type', 1)
                            ->where('relation_id', $data->id)
                            ->sum('amount');

                        $purchase_amount_det = 0;
                        foreach ($purchase_details as $detail){
                            $amount = $detail->rate * $detail->quantity;
                            $purchase_amount_det += $amount;
                        }

                        $purchase_amount = ($purchase_amount_det + $purchase->shipping_charge) - $purchase->discount;


                        return number_format(($purchase_amount), 2, '.', ',');
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group ">
                                  <button type="button" class="btn btn-success">Action</button>
                                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <span class="caret"></span>
                                    
                                  </button>
                                  <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="' . route('purchase.show', $data->id) . '">
                                            <i class="fa fa-info-circle"></i>  Details
                                        </a>
                                    </li>
                                    <li>
                                        <a href="' . route('purchase.edit', $data->id) . '">
                                            <i class="fa fa-edit"></i>  Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a style="cursor: pointerA" onclick="payment('.$data->id.')">
                                            <i class="fa fa-dollar"></i> 
                                            Payment
                                        </a>
                                    </li>
                                   
                                  </ul>
                                </div>';
                    })
                    ->rawColumns(['amount', 'action'])
                    ->make(true);
            }
            return view('dashboard.pages.purchase.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $suppliers = DB::table('people')
                ->where('type', '3')
                ->orderBy('id', 'DESC')
                ->get();

            $products = DB::table('products')
                ->orderBy('id', 'DESC')
                ->get();


            return view('dashboard.pages.purchase.create',
                compact('suppliers', 'products'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'status' => 'required',
            'date' => 'required',
        ], []);

        DB::beginTransaction();
        try {
            $id = DB::table('purchases')->insertGetId([
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'status' => $request->status,
                'discount' => $request->discount ?? 0,
                'shipping_charge' => $request->shipping_charge ?? 0,
                'payment_status' => $request->payment_status,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            $count = count($request->product);

            for ($i = 0; $i < $count; $i++) {
                DB::table('purchase_details')->insert([
                    'purchase_id' => $id,
                    'product_id' => $request->product[$i],
                    'rate' => $request->price[$i],
                    'quantity' => $request->quantity[$i],
                ]);
            }

            if ($request->payment_status) {
                if ($request->payment_status == 1) {
                    $amount = $request->grand_total;
                }
                if ($request->payment_status == 2) {
                    $amount = $request->payment_amount;
                }
                DB::table('transactions')->insert([
                    'date' => $request->date,
                    'type' => 1,
                    'status' => 1,
                    'relation_id' => $id,
                    'tran_type' => 2,
                    'narration' => 'Purchase Payment',
                    'amount' => $amount,
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now(),
                ]);
            }


            DB::commit();

            return redirect()->route('purchase.index')
                ->with('success', 'Created Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $purchase = DB::table('purchases as d')
                ->where('d.id', $id)
                ->leftJoin('people as p', function ($join) {
                    $join->on('p.id', '=', 'd.supplier_id');
                })
                ->first(['d.*','p.name as supplier_name']);

            $purchaseDetails = DB::table('purchase_details as d')
                ->where('d.purchase_id', $id)
                ->leftJoin('products as p', function ($join) {
                    $join->on('p.id', '=', 'd.product_id');
                })
                ->get(['d.*', 'p.name as product_name']);


            return view('dashboard.pages.purchase.show',
                compact('purchase', 'purchaseDetails'));

        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $categories = DB::table('purchase_categories')
                ->orderBy('id', 'DESC')
                ->get();

            $warehouses = DB::table('warehouses')
                ->orderBy('id', 'DESC')
                ->get();

            $purchase = DB::table('purchases')
                ->where('id', $id)->first();


            return view('dashboard.pages.purchases.edit', compact('purchase',
                'categories', 'warehouses'));

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
        ], []);

        DB::beginTransaction();
        try {
            DB::table('purchases')
                ->where('id', $id)
                ->update([
                    'category_id' => $request->category_id,
                    'warehouse_id' => $request->warehouse_id,
                    'name' => $request->name,
                    'stock' => $request->stock,
                    'purchase_price' => $request->purchase_price,
                    'sale_price' => $request->sale_price,
                    'description' => $request->description,
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                ]);

            DB::commit();

            return redirect()->route('purchase.index')
                ->with('success', 'Updated Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            DB::table('purchases')
                ->where('id', $id)
                ->delete();

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Deleted Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function purchase_payment_render(Request $request){
        $id = $request->id;

        $purchase = DB::table('purchases')
            ->where('id', $id)->first();

        $purchase_details = DB::table('purchase_details')
            ->where('purchase_id', $request->id)
            ->get(['rate', 'quantity']);

        $paid_amount = DB::table('transactions')
            ->where('type', 1)
            ->where('relation_id', $request->id)
            ->sum('amount');

        $purchase_amount_det = 0;
        foreach ($purchase_details as $detail){
            $amount = $detail->rate * $detail->quantity;
            $purchase_amount_det += $amount;
        }

        $purchase_amount = ($purchase_amount_det + $purchase->shipping_charge) - $purchase->discount;


        $due_amount = $purchase_amount - $paid_amount;

        if($due_amount <= 0){
            return 1;
        }

        $html = view('dashboard.pages.purchase.payment-render',
            compact('id','paid_amount','purchase_amount','due_amount'
            ))->render();
        return response()->json([
            $html,
        ]);
    }

    public function purchase_payment(Request $request){

        DB::beginTransaction();
        try {
            DB::table('purchases')
                ->where('id', $request->purchase_id)
                ->update([
                    'payment_status' =>  $request->payment_status
                ]);

            DB::table('transactions')->insert([
                'date' => $request->date,
                'type' => 1,
                'status' => 1,
                'relation_id' => $request->purchase_id,
                'tran_type' => 2,
                'narration' => 'Purchase Payment',
                'amount' => $request->payment_amount,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            DB::commit();
            return redirect()->route('purchase.show', $request->purchase_id)
                ->with('success', 'Paid Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }
}
