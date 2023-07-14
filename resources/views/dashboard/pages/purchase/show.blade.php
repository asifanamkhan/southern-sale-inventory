@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow' , sans-serif;">
        Purchase
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> Purchase</a></li>
        <li class="active">Purchase Show</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>PURCHASE SHOW</b></h5>
            <a href="{{route('purchase.create')}}" id="add_new" style="float: right" class="btn btn-sm btn-grad">Add New
                Purchase</a>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th style="width: 10%">Expense Date</th>
                    <td style="width: 10%">{{\Illuminate\Support\Carbon::parse($purchase->date)->format('d-M-Y')}}</td>
                    <th style="width: 10%">Purchase status</th>
                    <td style="width: 10%">
                        @if($purchase->status == 1)
                            <span class="label pull-left bg-green">COMPLETE</span>
                        @else
                            <span class="label pull-left bg-yellow">PENDING</span>
                        @endif
                    </td>
                    <th style="width: 10%">Payment status</th>
                    <td style="width: 10%">
                        @if($purchase->payment_status == 1)
                            <span class="label pull-left bg-green">PAID</span>
                        @elseif($purchase->payment_status == 2)
                            <span class="label pull-left bg-yellow">PARTIALLY PAID</span>
                        @else
                            <span class="label pull-left bg-red">UNPAID</span>
                        @endif
                    </td>
                    <th style="width: 10%">Supplier</th>
                    <td style="width: 30%">{{$purchase->supplier_name}}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td colspan="7">{{$purchase->description}}</td>
                </tr>
            </table>
            <div class="row" style="margin-top: 20px">
                <div class="col-md-7">
                    <p style="font-weight: bold; font-size: 20px;">Purchase Details</p>
                    <table style="width: 100%; margin-top: 5px" class="table table-responsive table-striped data-table"
                           id="table">
                        <thead class=""
                               style="color: white;background-image: radial-gradient( circle farthest-corner at 22.4% 21.7%, rgba(4,189,228,1) 0%, rgba(2,83,185,1) 100.2% );">
                        <tr class="" style="text-align:center; ">
                            <th style="width: 10%">SL</th>
                            <th style="width: 40%">Product</th>
                            <th style="width: 15%; text-align: center">Quantity</th>
                            <th style="width: 15%; text-align: right">Rate</th>
                            <th style="width: 20%; text-align: right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $total_amount = 0;
                            $total_qty = 0;
                            $total_tran_amount = 0;
                        @endphp
                        @foreach($purchaseDetails as $purchaseDetail)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$purchaseDetail->product_name}}</td>
                                <td style="text-align: center">
                                    @php $total_qty += $purchaseDetail->quantity @endphp
                                    {{$purchaseDetail->quantity}}
                                </td>
                                <td style="text-align: right">{{number_format(($purchaseDetail->rate), 2, '.', ',')}}</td>
                                <td style="text-align: right">
                                    @php
                                        $amount = $purchaseDetail->rate * $purchaseDetail->quantity;
                                        $total_amount += $amount;

                                    @endphp
                                    {{number_format(($amount), 2, '.', ',')}}
                                </td>
                            </tr>
                        @endforeach
                        <tfoot>
                        <tr>
                            <th colspan="2" style="text-align: center">Total</th>
                            <th style="text-align: center">{{$total_qty}}</th>
                            <th></th>
                            <th style="text-align: right">{{number_format(($total_amount), 2, '.', ',')}}</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="text-align: center">Discount</th>
                            <th style="text-align: center" colspan="2"></th>
                            <th style="text-align: right">{{number_format(($purchase->discount), 2, '.', ',')}}</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="text-align: center">Shipping charge</th>
                            <th style="text-align: center" colspan="2"></th>
                            <th style="text-align: right">{{number_format(($purchase->shipping_charge), 2, '.', ',')}}</th>
                        </tr>
                        <tr style="color: darkred">
                            <th colspan="2" style="text-align: center;">Grand Total</th>
                            <th style="text-align: center" colspan="2"></th>
                            @php $grant_total =  ($total_amount + $purchase->shipping_charge) - $purchase->discount @endphp
                            <th style="text-align: right">{{number_format(($grant_total), 2, '.', ',')}}</th>
                        </tr>
                        </tfoot>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-5">
                    <p style="font-weight: bold; font-size: 20px;">Payment Details</p>
                    <table style="width: 100%; margin-top: 5px" class="table table-responsive table-striped data-table"
                           id="table">
                        <thead class="" style="color: white;background-image: radial-gradient( circle farthest-corner at 22.4% 21.7%, rgba(4,189,228,1) 0%, rgba(2,83,185,1) 100.2% );">
                        <tr class="" style="text-align:center; ">
                            <th style="width: 20%">SL</th>
                            <th style="width: 40%">Date</th>
                            <th style="width: 40%; text-align: right">Amount</th>
                        </tr>
                        </thead>

                        @if($purchase->payment_status != 3)
                            @php
                                $transactions = \Illuminate\Support\Facades\DB::table('transactions')
                                                ->where('type', 1)
                                                ->where('relation_id', $purchase->id)
                                                ->get();
                            @endphp
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{\Illuminate\Support\Carbon::parse($transaction->date)->format('d-M-Y')}}</td>
                                    <td style="text-align: right">
                                        @php
                                            $total_tran_amount += $transaction->amount;
                                        @endphp
                                        {{number_format(($transaction->amount), 2, '.', ',')}}
                                    </td>
                                </tr>
                            @endforeach
                            <tfoot>
                            <tr>
                                <th colspan="2" style="text-align: center">Total</th>
                                <th style="text-align: right">{{number_format(($total_tran_amount), 2, '.', ',')}}</th>
                            </tr>
                            </tfoot>
                            </tbody>
                        @endif
                    </table>
                </div>
                <div class="col-md-12" style="margin-top: 10px">
                    <p style="text-align: center; font-size: 20px; font-weight: bolder; color: darkred">DUE AMOUNT: {{number_format(($grant_total - $total_tran_amount), 2, '.', ',')}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
