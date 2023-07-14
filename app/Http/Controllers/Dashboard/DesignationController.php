<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::table('designations')
                    ->orderBy('id', 'DESC')
                    ->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        return '<div class="" role="group">
                                    <a id="designation-' . $data->id . '"
                                        cat_name="' . $data->name . '"  
                                        cat_desc="' . $data->description . '"  
                                        onclick="catEdit(' . $data->id . ')" class="btn btn-sm btn-success" style="cursor:pointer"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    
                                    <a class="btn btn-sm btn-danger" style="cursor:pointer" 
                                       href="' . route('designation.delete', [$data->id]) . '" 
                                       onclick=" return confirm(`Are You Sure ? You Cant revert it`)" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('dashboard.pages.settings.designation.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('designations')->insert([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('designation.index')
                ->with('success', 'Created Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('designations')
                ->where('id', $request->designation_id)
                ->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                ]);

            DB::commit();

            return redirect()->route('designation.index')
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
            DB::table('designations')
                ->where('id', $id)
                ->delete();

            DB::commit();
            return redirect()->route('designation.index')
                ->with('success', 'Deleted Successfully');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
