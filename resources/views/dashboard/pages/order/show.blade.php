@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow' , sans-serif;">
        Order
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> Order</a></li>
        <li class="active">Order Show</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>ORDER SHOW</b></h5>
            <a href="{{route('order.create')}}" id="add_new" style="float: right" class="btn btn-sm btn-grad">Add New
                Order</a>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th style="width: 10%">Order Date</th>
                    <td style="width: 10%">{{\Illuminate\Support\Carbon::parse($order->date)->format('d-M-Y')}}</td>
                    <th style="width: 10%">Order status</th>
                    <td style="width: 10%">
                        @if($order->status == 1)
                            <span class="label pull-left bg-green">COMPLETE</span>
                        @elseif($order->status == 2)
                            <span class="label pull-left bg-green">PENDING</span>
                        @else
                            <span class="label pull-left bg-yellow">CANCELED</span>
                        @endif
                    </td>
                    <th style="width: 10%">Payment status</th>
                    <td style="width: 10%">
                        @if($order->payment_status == 1)
                            <span class="label pull-left bg-green">PAID</span>
                        @elseif($order->payment_status == 2)
                            <span class="label pull-left bg-yellow">PARTIALLY PAID</span>
                        @else
                            <span class="label pull-left bg-red">UNPAID</span>
                        @endif
                    </td>
                    <th style="width: 10%">Customer</th>
                    <td style="width: 30%">{{$order->customer_name}}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td colspan="7">{{$order->description}}</td>
                </tr>
            </table>
            <div class="row" style="margin-top: 20px">
                <div class="col-md-7">
                    <p style="font-weight: bold; font-size: 20px;">Order Details</p>
                    <table style="width: 100%; margin-top: 5px" class="table table-responsive table-striped data-table"
                           id="table">
                        <thead class=""
                               style="color: white;background-image: radial-gradient( circle farthest-corner at 22.4% 21.7%, rgba(4,189,228,1) 0%, rgba(2,83,185,1) 100.2% );">
                        <tr class="" style="text-align:center; ">
                            <th style="width: 5%">SL</th>
                            <th style="width: 25%">Product</th>
                            <th style="width: 45%; text-align: center">Details</th>
                            <th style="width: 12%; text-align: right">Rate</th>
                            <th style="width: 15%; text-align: right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $total_amount = 0;
                            $total_qty = 0;
                            $total_tran_amount = 0;
                        @endphp
                        @foreach($orderDetails as $orderDetail)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$orderDetail->product_name}}</td>
                                <td>
                                    <table class="table" style="margin-bottom: 0 !important;">
                                        <thead>
                                        <tr>
                                            <th style="width: 20%; text-align: center">Length</th>
                                            <th style="width: 20%; text-align: center">Width</th>
                                            <th style="width: 25%; text-align: center">Qty</th>
                                            <th style="width: 35%; text-align: right">Sft</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $details = DB::table('order_details')
                                                ->where('order_id', $order->id)
                                                ->where('product_id', $orderDetail->product_id)
                                                ->get();
                                            $total_qty = 0;
                                            $total_sqf = 0;
                                            $line_total = 0;
                                        @endphp
                                        @foreach($details as $detail)
                                            @php
                                                $rate = $detail->rate;
                                            @endphp
                                            <tr>
                                                <td style="text-align: center">{{$detail->length}}</td>
                                                <td style="text-align: center">
                                                    {{$detail->width}}
                                                </td>
                                                <td style="text-align: center">
                                                    @php $total_qty += $detail->quantity @endphp
                                                    {{$detail->quantity}}
                                                </td>
                                                <td style="text-align: right">
                                                    @php $total_sqf += $detail->sqf @endphp
                                                    {{$detail->sqf}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th style="text-align: center" colspan="2">Total</th>
                                            <th style="text-align: center">{{$total_qty}}</th>
                                            <th style="text-align: right">{{$total_sqf}}</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </td>
                                <th style="vertical-align: bottom; text-align: right; padding-bottom: 16px">
                                    {{$rate}}
                                </th>
                                <th style="vertical-align: bottom; text-align: right; padding-bottom: 16px">
                                    @php
                                        $line_total = $rate * $total_qty * $total_sqf ;
//                                        $product_total += $line_total
                                    @endphp
                                    {{$line_total}}
                                </th>
                            </tr>
                        @endforeach
                        {{--                        <tfoot>--}}
                        {{--                        <tr>--}}
                        {{--                            <th colspan="2" style="text-align: center">Total</th>--}}
                        {{--                            <th style="text-align: center">{{$total_qty}}</th>--}}
                        {{--                            <th></th>--}}
                        {{--                            <th style="text-align: right">{{number_format(($total_amount), 2, '.', ',')}}</th>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr>--}}
                        {{--                            <th colspan="2" style="text-align: center">Discount</th>--}}
                        {{--                            <th style="text-align: center" colspan="2"></th>--}}
                        {{--                            <th style="text-align: right">{{number_format(($order->discount), 2, '.', ',')}}</th>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr>--}}
                        {{--                            <th colspan="2" style="text-align: center">Shipping charge</th>--}}
                        {{--                            <th style="text-align: center" colspan="2"></th>--}}
                        {{--                            <th style="text-align: right">{{number_format(($order->shipping_charge), 2, '.', ',')}}</th>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr style="color: darkred">--}}
                        {{--                            <th colspan="2" style="text-align: center;">Grand Total</th>--}}
                        {{--                            <th style="text-align: center" colspan="2"></th>--}}
                        {{--                            @php $grant_total =  ($total_amount + $order->shipping_charge) - $order->discount @endphp--}}
                        {{--                            <th style="text-align: right">{{number_format(($grant_total), 2, '.', ',')}}</th>--}}
                        {{--                        </tr>--}}
                        {{--                        </tfoot>--}}
                        </tbody>
                    </table>
                </div>

                <div class="col-md-5">
                    <p style="font-weight: bold; font-size: 20px;">Payment Details</p>
                    <table style="width: 100%; margin-top: 5px" class="table table-responsive table-striped data-table"
                           id="table">
                        <thead class=""
                               style="color: white;background-image: radial-gradient( circle farthest-corner at 22.4% 21.7%, rgba(4,189,228,1) 0%, rgba(2,83,185,1) 100.2% );">
                        <tr class="" style="text-align:center; ">
                            <th style="width: 5%">SL</th>
                            <th style="width: 20%">Date</th>
                            <th style="width: 50%">Payment Via</th>
                            <th style="width: 25%; text-align: right">Amount</th>
                        </tr>
                        </thead>

                        @if($order->payment_status != 3)
                            @php
                                $transactions = \Illuminate\Support\Facades\DB::table('transactions as t')
                                                ->where('t.type', 2)
                                                ->where('t.relation_id', $order->id)
                                                ->leftJoin('accounts as a', function ($join) {
                                                    $join->on('t.account_id', '=', 'a.id');
                                                })
                                                ->get(['t.*','a.type as acc_type','a.name','a.bank','a.branch','a.account_no']);
//                                dd($transactions)
                            @endphp
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{\Illuminate\Support\Carbon::parse($transaction->date)->format('d-M-Y')}}</td>
                                    <td>
                                        @if($transaction->acc_type == 1)
                                            {{$transaction->name}} (Petty Cash)
                                        @else
                                            {{$transaction->account_no}} => {{$transaction->branch}}
                                            => {{$transaction->bank}}
                                        @endif
                                    </td>
                                    <td style="text-align: right">
                                        @php
                                            $total_tran_amount += $transaction->credit_amount;
                                        @endphp
                                        {{number_format(($transaction->credit_amount), 2, '.', ',')}}
                                    </td>
                                </tr>
                            @endforeach
                            <tfoot>
                            <tr>
                                <th colspan="3" style="text-align: center">Total</th>
                                <th style="text-align: right">{{number_format(($total_tran_amount), 2, '.', ',')}}</th>
                            </tr>
                            </tfoot>
                            </tbody>
                        @endif
                    </table>
                </div>
                <div class="col-md-12" style="margin-top: 10px">
                    <p style="text-align: center; font-size: 20px; font-weight: bolder; color: darkred">DUE
                    {{--                        AMOUNT: {{number_format(($grant_total - $total_tran_amount), 2, '.', ',')}}</p>--}}
                </div>
            </div>
        </div>
    </div>
@endsection