@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow';">
        Accounts
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> accounts</a></li>
        <li class="active">add new account</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>ADD NEW ACCOUNT</b></h5>
            <a href="{{route('accounts.index')}}" id="add_new" style="float: right" class="btn btn-sm btn-grad">Account
                List
            </a>
        </div>
        <div class="box-body">
            <form id="form" action="{{route('accounts.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class=" col-md-4">
                        <label for="type" class="col-form-label text-md-end">Type</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <select required name="type" id="type" class="select2 form-control @error('type') is-invalid @enderror">
                            <option value="">Select Type</option>
                            <option value="1">Petty cash</option>
                            <option value="2">Bank</option>
                        </select>
                        @error('type')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class=" col-md-4">
                        <label for="name" class="col-form-label text-md-end">Account Name</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>


                    <div class=" col-md-4">
                        <label for="opening_balance" class="col-form-label text-md-end">Opening Balance</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <input id="opening_balance" type="number"
                               class="form-control @error('opening_balance') is-invalid @enderror"
                               name="opening_balance" value="{{ old('opening_balance') ?? 0 }}" required>
                        @error('opening_balance')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>
                <div class="row" id="bank-area" style="margin-top: 4px">
                    <div class=" col-md-4">
                        <label for="bank" class="col-form-label text-md-end">Bank Name</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <input id="bank" type="number" class="form-control @error('bank') is-invalid @enderror"
                               name="bank" value="{{ old('bank') }}" required>

                        @error('bank')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>


                    <div class=" col-md-4">
                        <label for="branch" class="col-form-label text-md-end">Branch</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <input id="branch" type="text" class="form-control @error('branch') is-invalid @enderror"
                               name="branch" value="{{ old('branch')}}" required>

                        @error('branch')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class=" col-md-4">
                        <label for="account_no" class="col-form-label text-md-end">Account No</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <input id="account_no" type="text"
                               class="form-control @error('account_no') is-invalid @enderror" name="account_no"
                               value="{{ old('account_no')}}" required>

                        @error('account_no')
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
                        <button onclick="return confirm(`Are you sure ?`)" class="btn btn-sm btn-grad"
                                style="float: right">
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
        $('#bank-area').hide();
        $().ready(function () {
            $("#form").validate({
                rules: {
                    type: {
                        required: true
                    },
                    name: {
                        required: true,
                    },
                },
            });
        })

        $('#type').on('change', function (){
            var type = $("#type option:selected").val();

            if(type == 2){
                $('#bank-area').fadeIn(500)
            }else{
                $('#bank-area').fadeOut(500)
            }
        })
    </script>
@endsection