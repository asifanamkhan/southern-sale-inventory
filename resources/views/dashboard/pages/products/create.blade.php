@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow';">
        Products
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> Products</a></li>
        <li class="active">add new product</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>ADD NEW PRODUCT</b></h5>
            <a href="{{route('products.index')}}" id="add_new" style="float: right" class="btn btn-sm btn-grad">Product List
                </a>
        </div>
        <div class="box-body">
            <form id="form" action="{{route('products.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class=" col-md-4">
                        <label for="category" class="col-form-label text-md-end">Category</label><span style="font-weight: bold; color: red"> *</span>
                        <select name="category_id" id="category_id" class="select2 form-control @error('category_id') is-invalid @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class=" col-md-4">
                        <label for="warehouse_id" class="col-form-label text-md-end">Warehouse</label><span style="font-weight: bold; color: red"> *</span>
                        <select name="warehouse_id" id="warehouse_id" class="select2 form-control @error('warehouse_id') is-invalid @enderror">
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class=" col-md-4">
                        <label for="name" class="col-form-label text-md-end">Product Name</label><span style="font-weight: bold; color: red"> *</span>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>
                <div class="row" style="margin-top: 4px">
                    <div class=" col-md-4">
                        <label for="stock" class="col-form-label text-md-end">Current Stock</label>
                        <input id="stock" type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock') ?? 0 }}" required>

                        @error('stock')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class=" col-md-4">
                        <label for="purchase_price" class="col-form-label text-md-end">Purchase Price</label>
                        <input id="purchase_price" type="number" class="form-control @error('purchase_price') is-invalid @enderror" name="purchase_price" value="{{ old('purchase_price') ?? 0 }}" required>

                        @error('purchase_price')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class=" col-md-4">
                        <label for="sale_price" class="col-form-label text-md-end">Sale Price</label>
                        <input id="sale_price" type="text" class="form-control @error('sale_price') is-invalid @enderror" name="sale_price" value="{{ old('sale_price') ?? 0 }}" required>

                        @error('sale_price')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>

                <div class="row" style="margin-top: 4px">
                    <div class=" col-md-12">
                        <label for="description" class="col-form-label text-md-end">Description</label>
                        <textarea class="form-control" name="description" id="" cols="30" rows="3"></textarea>

                    </div>
                </div>

                <div class="row" style="margin-top: 5px">
                    <div class=" col-md-12">
                        <button onclick="return confirm(`Are you sure ?`)" class="btn btn-sm btn-grad" style="float: right">
                            SUBMIT
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $().ready(function () {
            $("#form").validate({
                rules: {
                    category_id: {
                        required: true
                    },
                    warehouse_id: {
                        required: true
                    },
                    name: {
                        required: true,
                    },
                },
            });

        })
    </script>
@endsection