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
                    ->get(['o.*', 'p.name as customer']);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function ($data) {
                        if ($data->status == 1) {
                            return '<span class="label pull-left bg-green">COMPLETE</span>';
                        } else {
                            return '<span class="label pull-left bg-yellow" > PENDING</span >';
                        }

                    })
                    ->addColumn('date', function ($data) {
                        return Carbon::parse($data->date)->format('d-M-Y');
                    })
                    ->addColumn('amount', function ($data) {
                        return number_format(($data->grand_total), 2, '.', ',');
                     })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group ">
                                  <button type="button" class="btn btn-success">Action</button>
                                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <span class="caret"></span>
                                    
                                  </button>
                                  <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="' . route('order.show', $data->id) . '">
                                            <i class="fa fa-info-circle"></i>  Details
                                        </a>
                                    </li>
                                    <li>
                                        <a href="' . route('purchase.edit', $data->id) . '">
                                            <i class="fa fa-edit"></i>  Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a style="cursor: pointer" onclick="payment('.$data->id.')">
                                            <i class="fa fa-dollar"></i> 
                                            Payment
                                        </a>
                                    </li>
                                   
                                  </ul>
                                </div>';
                    })
                    ->rawColumns(['date','status','amount', 'action'])
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
//        dd($request->input());

        $request->validate([
            'customer_id' => 'required',
            'outlet_id' => 'required',
            'status' => 'required',
            'date' => 'required',
        ], []);

        DB::beginTransaction();
        try {
            $id = DB::table('orders')->insertGetId([
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'outlet_id' => $request->outlet_id,
                'unit_id' => $request->unit_id,
                'status' => $request->status,
                'discount' => $request->discount ?? 0,
                'shipping_charge' => $request->shipping_charge ?? 0,
                'grand_total' => $request->grand_total,
                'payment_status' => $request->payment_status,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);



            $count = count($request->product);


            for ($i = 0; $i < $count; $i++) {
                $length = 'product_'.$request->product[$i].'_length';

                $det_count = count($request->$length);

                /*Stock update*/
                $product = DB::table('products')->where('id', $request->product[$i])
                    ->first('stock');

                $quantity = $product->stock - $request->product_quantity[$i];

                DB::table('products')->where('id', $request->product[$i])
                    ->update([
                        'stock' => $quantity
                    ]);
                /*Stock update*/

                for ($j=0; $j < $det_count; $j++){
                    $quantity = 'product_'.$request->product[$i].'_qty';
                    $length = 'product_'.$request->product[$i].'_length';
                    $width = 'product_'.$request->product[$i].'_width';
                    $sqf = 'product_'.$request->product[$i].'_sqf';

                    DB::table('order_details')->insert([
                        'order_id' => $id,
                        'product_id' => $request->product[$i],
                        'thickness' => $request->thickness[$i],
                        'rate' => $request->price[$i],
                        'length' => $request->$length[$j],
                        'width' => $request->$width[$j],
                        'sqf' => $request->$sqf[$j],
                        'quantity' => $request->$quantity[$j],
                    ]);
                }

            }

            if ($request->payment_status != 3) {
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
                    'account_id' => $request->account_id,
                    'narration' => 'Order Payment',
                    'debit_amount' => $amount,
                    'difference' => $amount,
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now(),
                ]);
            }


            DB::commit();

            return redirect()->route('order.index')
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
            $order = DB::table('orders as d')
                ->where('d.id', $id)
                ->leftJoin('people as p', function ($join) {
                    $join->on('p.id', '=', 'd.customer_id');
                })
                ->first(['d.*', 'p.name as customer_name']);

            $orderDetails = DB::table('order_details as d')
                ->where('d.order_id', $id)
                ->distinct('d.product_id')
                ->leftJoin('products as p', function ($join) {
                    $join->on('p.id', '=', 'd.product_id');
                })
                ->get(['product_id', 'p.name as product_name']);

//            dd($orderDetails);

            return view('dashboard.pages.order.show',
                compact('order', 'orderDetails'));

        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
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

    public function order_payment_render(Request $request){

        $id = $request->id;

        $order = DB::table('orders')
            ->where('id', $id)->first();

        $order_details = DB::table('order_details')
            ->where('order_id', $request->id)
            ->distinct('product_id')
            ->get(['product_id']);

        $paid_amount = DB::table('transactions')
            ->where('type', 2)
            ->where('relation_id', $request->id)
            ->sum('debit_amount');

        $due_amount = $order->grand_total - $paid_amount;

        if($due_amount <= 0){
            return 1;
        }

        $html = view('dashboard.pages.order.payment-render',
            compact('id','paid_amount','order','due_amount','order_details'
            ))->render();
        return response()->json([
            $html,
        ]);
    }
}
