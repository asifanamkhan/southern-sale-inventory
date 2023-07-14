@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow';">
        Customer
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> Customer</a></li>
        <li class="active">add new customer</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>ADD NEW CUSTOMER</b></h5>
            <a href="{{route('customer.index')}}" id="add_new" style="float: right" class="btn btn-sm btn-grad">customer List
                </a>
        </div>
        <div class="box-body">
            <form id="form" action="{{route('customer.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class=" col-md-4">
                        <label for="name" class="col-form-label text-md-end">Customer Name</label><span style="font-weight: bold; color: red"> *</span>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class=" col-md-4">
                        <label for="phone" class="col-form-label text-md-end">Phone</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" >

                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class=" col-md-4">
                        <label for="email" class="col-form-label text-md-end">Email</label>
                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" >

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
                        <textarea class="form-control" name="address" id="" cols="30" rows="3"></textarea>

                    </div>

                    <div class=" col-md-6">
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
                    name: {
                        required: true,
                    },
                },
            });

        })
    </script>
@endsection