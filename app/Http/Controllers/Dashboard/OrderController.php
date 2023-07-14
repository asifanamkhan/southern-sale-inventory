<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::table('orders as o')
                    ->leftJoin('people as p', function ($join) {
                        $join->on('o.customer_id', '=', 'p.id');
                    })
                    ->orderBy('o.id', 'DESC')
                    ->get(['o.id', 'o.date', 'p.name as customer']);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('amount', function ($data) {
//                        $purchase = DB::table('orders')
//                            ->where('id', $data->id)->first();
//
//                        $purchase_details = DB::table('order_details')
//                            ->where('order_id', $data->id)
//                            ->get();
//
//                        $paid_amount = DB::table('transactions')
//                            ->where('type', 1)
//                            ->where('relation_id', $data->id)
//                            ->sum('amount');
//
//                        $purchase_amount_det = 0;
//                        foreach ($purchase_details as $detail){
//                            $amount = $detail->rate * $detail->quantity;
//                            $purchase_amount_det += $amount;
//                        }
//
//                        $purchase_amount = ($purchase_amount_det + $purchase->shipping_charge) - $purchase->discount;
//
//
//                        return number_format(($purchase_amount), 2, '.', ',');
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
            return view('dashboard.pages.order.index');
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
            $customers = DB::table('people')
                ->where('type', '2')
                ->orderBy('id', 'DESC')
                ->get();

            $products = DB::table('products')
                ->orderBy('id', 'DESC')
                ->get();

            $outlets = DB::table('outlets')
                ->orderBy('id', 'DESC')
                ->get();


            return view('dashboard.pages.order.create',
                compact('customers', 'products','outlets'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

//        $request->validate([
//            'customer_id' => 'required',
//            'outlet_id' => 'required',
//            'status' => 'required',
//            'date' => 'required',
//        ], []);

//        DB::beginTransaction();
//        try {
            $id = DB::table('orders')->insertGetId([
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'outlet_id' => $request->outlet_id,
                'unit_id' => $request->unit_id,
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
                $length = 'product_'.$request->product[$i].'_length';

                $det_count = count($request->$length);


                for ($j=0; $j < $det_count; $j++){
                    $quantity = 'product_'.$request->product[$i].'_qty';
                    $length = 'product_'.$request->product[$i].'_length';
                    $width = 'product_'.$request->product[$i].'_width';
                    $sqf = 'product_'.$request->product[$i].'_sqf';

                    DB::table('order_details')->insert([
                        'order_id' => $id,
                        'product_id' => $request->product[$i],
                        'rate' => $request->price[$i],
                        'length' => $request->$length[$j],
                        'width' => $request->$width[$j],
                        'sqf' => $request->$sqf[$j],
                        'quantity' => $request->$quantity[$j],
                    ]);
                }

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
                    'type' => 2,
                    'status' => 1,
                    'relation_id' => $id,
                    'tran_type' => 1,
                    'narration' => 'Purchase Payment',
                    'amount' => $amount,
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now(),
                ]);
            }


            DB::commit();

            return redirect()->route('purchase.index')
                ->with('success', 'Created Successfully');
//        } catch (\Exception $exception) {
//            DB::rollback();
//            return redirect()->back()->with('error', $exception->getMessage());
//        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
