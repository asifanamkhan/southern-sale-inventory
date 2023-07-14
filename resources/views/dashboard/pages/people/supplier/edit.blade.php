@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow';">
        Suppliers
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> Suppliers</a></li>
        <li class="active">edit supplier</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>EDIT SUPPLIER</b></h5>
            <a href="{{route('supplier.index')}}" id="add_new" style="float: right" class="btn btn-sm btn-grad">supplier List
            </a>
        </div>
        <div class="box-body">
            <form id="form" action="{{route('supplier.update', $data->id)}}" method="post">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class=" col-md-4">
                        <label for="name" class="col-form-label text-md-end">Supplier Name</label><span style="font-weight: bold; color: red"> *</span>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $data->name }}" required>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class=" col-md-4">
                        <label for="phone" class="col-form-label text-md-end">Phone</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $data->phone }}" >

                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class=" col-md-4">
                        <label for="email" class="col-form-label text-md-end">Email</label>
                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $data->email }}" >

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row" style="margin-top: 4px">
                    <div class=" col-md-6">
                        <label for="address" class="col-form-label text-md-end">Address</label>
                        <textarea class="form-control" name="address" id="" cols="30" rows="3">{{$data->address}}</textarea>

                    </div>

                    <div class=" col-md-6">
                        <label for="description" class="col-form-label text-md-end">Description</label>
                        <textarea class="form-control" name="description" id="" cols="30" rows="3">{{$data->description}}</textarea>

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
                    name: {
                        required: true,
                    },
                },
            });

        })
    </script>
@endsection