<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\People;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::table('people as p')
                    ->where('type', 1)
                    ->leftJoin('designations as d', function ($join) {
                        $join->on('p.designation_id', '=', 'd.id');
                    })
                    ->orderBy('p.id', 'DESC')
                    ->get(['p.id', 'p.name', 'p.phone', 'd.name as designation']);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        return '<div class="" role="group">
                                    <a id="employee-' . $data->id . '"
                                        href="' . route('employee.edit', $data->id) . '" class="btn btn-sm btn-success" style="cursor:pointer"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    
                                    <a class="btn btn-sm btn-danger" style="cursor:pointer" 
                                       href="' . route('employee.delete', [$data->id]) . '" 
                                       onclick=" return confirm(`Are You Sure ? You Cant revert it`)" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('dashboard.pages.people.employee.index');
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
            $designations = DB::table('designations')
                ->orderBy('id', 'DESC')
                ->get();

            return view('dashboard.pages.people.employee.create', compact('designations'));
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
            'designation_id' => 'required',
            'phone' => 'required',
        ], []);

        DB::beginTransaction();
        try {
            DB::table('people')->insert([
                'name' => $request->name,
                'type' => 1,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'nid' => $request->nid,
                'designation_id' => $request->designation_id,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('employee.index')
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

            $designations = DB::table('designations')
                ->orderBy('id', 'DESC')
                ->get();

            $data = DB::table('people')
                ->where('type', 1)
                ->where('id', $id)->first();


            return view('dashboard.pages.people.employee.edit', compact('data', 'designations'));

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
            'designation_id' => 'required',
            'phone' => 'required',
        ], []);

        DB::beginTransaction();
        try {
            DB::table('people')
                ->where('type', 1)
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'description' => $request->description,
                    'nid' => $request->nid,
                    'designation_id' => $request->designation_id,
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                ]);

            DB::commit();

            return redirect()->route('employee.index')
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
                ->where('type', 1)
                ->where('id', $id)
                ->delete();

            DB::commit();
            return redirect()->route('employee.index')
                ->with('success', 'Deleted Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
