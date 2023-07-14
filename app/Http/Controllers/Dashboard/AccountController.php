<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::table('accounts')
                    ->orderBy('id', 'DESC')
                    ->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('type', function ($data) {
                        if ($data->type == 1) {
                            return "Petty Cash Account";
                        } else {
                            return "Bank Account";
                        }

                    })
                    ->addColumn('balance', function ($data) {
                        $balance = DB::table('transactions')
                            ->where('account_id', $data->id)
                            ->sum('difference');

                        return $balance;

                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="" role="group">
                                    <a id=""
                                        href="' . route('accounts.edit', $data->id) . '" class="btn btn-sm btn-success" style="cursor:pointer"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    
                                    <a class="btn btn-sm btn-danger" style="cursor:pointer" 
                                       href="' . route('accounts.delete', [$data->id]) . '" 
                                       onclick=" return confirm(`Are You Sure ? You Cant revert it`)" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>';
                    })
                    ->rawColumns(['type', 'balance', 'action'])
                    ->make(true);
            }
            return view('dashboard.pages.accounts.accounts.index');
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

            return view('dashboard.pages.accounts.accounts.create');
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
            'type' => 'required',
            'name' => 'required',
        ], []);

        DB::beginTransaction();
        try {
            $id = DB::table('accounts')->insertGetId([
                'type' => $request->type,
                'name' => $request->name,
                'opening_balance' => $request->opening_balance ?? 0,
                'bank' => $request->bank,
                'branch' => $request->branch,
                'account_no' => $request->account_no,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            DB::table('transactions')->insert([
                'date' => \Illuminate\Support\Carbon::now()->toDateString(),
                'type' => 6,
                'status' => 1,
                'account_id' => $id,
                'relation_id' => $id,
                'narration' => 'Opening Balance',
                'debit_amount' => $request->opening_balance,
                'difference' => $request->opening_balance,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('accounts.index')
                ->with('success', 'Created Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {

            $opening_bal = DB::table('transactions')
                ->where('account_id', $id)
                ->where('type', 6)
                ->sum('debit_amount');

            $data = DB::table('accounts')
                ->where('id', $id)
                ->first();



            return view('dashboard.pages.accounts.accounts.edit', compact('data',
                'opening_bal'));

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
//        dd($request->input());
        $request->validate([
            'type' => 'required',
            'name' => 'required',
        ], []);

        DB::beginTransaction();
        try {

            $id = DB::table('accounts')
                ->where('id', $id)
                ->update([
                    'type' => $request->type,
                    'name' => $request->name,
                    'opening_balance' => $request->opening_balance ?? 0,
                    'bank' => $request->type == 2 ? $request->bank : '',
                    'branch' => $request->type == 2 ? $request->branch: '',
                    'account_no' => $request->type == 2 ? $request->account_no: '',
                    'description' => $request->description,
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now(),
                ]);


            DB::table('transactions')
                ->where('account_id', $id)
                ->where('type', 6) //opening bal
                ->update([
                    'debit_amount' => $request->opening_balance,
                    'difference' => $request->opening_balance,
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                ]);

            DB::commit();

            return redirect()->route('accounts.index')
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
            DB::table('products')
                ->where('id', $id)
                ->delete();

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Deleted Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
