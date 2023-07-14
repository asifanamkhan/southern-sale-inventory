<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::table('products as p')
                    ->leftJoin('product_categories as c', function($join) {
                        $join->on('p.category_id', '=', 'c.id');
                    })
                    ->orderBy('p.id', 'DESC')
                    ->get(['p.id','p.name','p.stock','c.name as cat_name']);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category', function ($data) {
                        return $data->cat_name;
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="" role="group">
                                    <a id="product-cat-'.$data->id.'"
                                        href="'.route('products.edit', $data->id).'" class="btn btn-sm btn-success" style="cursor:pointer"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    
                                    <a class="btn btn-sm btn-danger" style="cursor:pointer" 
                                       href="'.route('products.delete', [$data->id]).'" 
                                       onclick=" return confirm(`Are You Sure ? You Cant revert it`)" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>';
                    })
                    ->rawColumns(['category','action'])
                    ->make(true);
            }
            return view('dashboard.pages.products.index');
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
            $categories = DB::table('product_categories')
                ->orderBy('id', 'DESC')
                ->get();

            $warehouses = DB::table('warehouses')
                ->orderBy('id', 'DESC')
                ->get();


            return view('dashboard.pages.products.create', compact('categories','warehouses'));
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
            'category_id' => 'required',
            'name' => 'required',
        ], []);

        DB::beginTransaction();
        try {
            DB::table('products')->insert([
                'category_id' => $request->category_id,
                'warehouse_id' => $request->warehouse_id,
                'name' => $request->name,
                'stock' => $request->stock,
                'purchase_price' => $request->purchase_price,
                'sale_price' => $request->sale_price,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('products.index')
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
            $categories = DB::table('product_categories')
                ->orderBy('id', 'DESC')
                ->get();

            $warehouses = DB::table('warehouses')
                ->orderBy('id', 'DESC')
                ->get();

            $product = DB::table('products')
                ->where('id', $id)->first();


            return view('dashboard.pages.products.edit', compact('product',
                'categories','warehouses'));

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
            DB::table('products')
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

            return redirect()->route('products.index')
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
