<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\People;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::table('people as p')
                    ->where('type', 3)
                    ->orderBy('p.id', 'DESC')
                    ->get(['p.id','p.name','p.phone','p.address','p.email']);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        return '<div class="" role="group">
                                    <a id="supplier-'.$data->id.'"
                                        href="'.route('supplier.edit', $data->id).'" class="btn btn-sm btn-success" style="cursor:pointer"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    
                                    <a class="btn btn-sm btn-danger" style="cursor:pointer" 
                                       href="'.route('supplier.delete', [$data->id]).'" 
                                       onclick=" return confirm(`Are You Sure ? You Cant revert it`)" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('dashboard.pages.people.supplier.index');
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
            return view('dashboard.pages.people.supplier.create');
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
            'name' => 'required',
        ], []);

        DB::beginTransaction();
        try {
            DB::table('people')->insert([
                'name' => $request->name,
                'type' => 3,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('supplier.index')
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {

            $data = DB::table('people')
                ->where('type',3)
                ->where('id', $id)->first();


            return view('dashboard.pages.people.supplier.edit', compact('data'));

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
            'name' => 'required',
        ], []);

        DB::beginTransaction();
        try {
            DB::table('people')
                ->where('type', 3)
                ->where('id', $id)
                ->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'description' => $request->description,
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('supplier.index')
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
            DB::table('people')
                ->where('type',3)
                ->where('id', $id)
                ->delete();

            DB::commit();
            return redirect()->route('supplier.index')
                ->with('success', 'Deleted Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
