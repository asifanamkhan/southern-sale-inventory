@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow';">
        Purchase
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> Purchase</a></li>
        <li class="active">add new purchase</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>ADD NEW PURCHASE</b></h5>
            <a href="{{route('purchase.index')}}" id="add_new" style="float: right" class="btn btn-sm btn-grad">Purchase
                List
            </a>
        </div>
        <div class="box-body">
            <form id="form" action="{{route('purchase.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class=" col-md-4">
                        <label for="date" class="col-form-label text-md-end">Purchase Date</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <input id="date" type="date" class="form-control @error('date') is-invalid @enderror"
                               name="date" value="{{ old('date') ??\Carbon\Carbon::now()->toDateString()  }}" required>

                        @error('date')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class=" col-md-4">
                        <label for="supplier" class="col-form-label text-md-end">Supplier</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <select name="supplier_id" id="supplier_id"
                                class="select2 form-control @error('supplier_id') is-invalid @enderror">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class=" col-md-4">
                        <label for="status" class="col-form-label text-md-end">Status</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <select name="status" id="status"
                                class="select2 form-control @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            <option value="1">Complete</option>
                            <option value="2">Pending</option>
                        </select>
                        @error('status')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row" style="margin-top: 4px">
                    <div class=" col-md-11">
                        <label for="product_id" class="col-form-label text-md-end">Product</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <select name="" id="product_id"
                                class="select2 form-control @error('product_id') is-invalid @enderror">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option
                                        product_name="{{$product->name}}"
                                        price="{{$product->purchase_price}}"
                                        value="{{$product->id}}">
                                    {{$product->name}}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-1">
                        <label style="color: white" for="product_id" class="col-form-label text-md-end">-</label>
                        <a id="product_add" class="btn btn-sm btn-grad">
                            Add
                        </a>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px">
                    <div class="col-md-9">
                        <table style="width: 100%;"
                               class="table table-responsive table-striped data-table"
                               id="table">
                            <thead class=""
                                   style="color: white;background-image: radial-gradient( circle farthest-corner at 22.4% 21.7%, rgba(4,189,228,1) 0%, rgba(2,83,185,1) 100.2% );">
                            <tr class="" style="text-align:center; ">
                                <th style="width: 40%">Product</th>
                                <th style="width: 20%">Rate</th>
                                <th style="width: 15%">Quantity</th>
                                <th style="width: 20%">Total</th>
                                <th style="width: 5%">Action</th>
                            </tr>
                            </thead>
                            <tbody id="product-add-area"></tbody>
                        </table>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="date" class="col-form-label text-md-end">Total Quantity</label>
                                <input readonly id="total_qty" type="number" class="form-control" name="">
                            </div>
                            <div class="col-md-12">
                                <label for="date" class="col-form-label text-md-end">Total</label>
                                <input readonly id="total_amount" type="number" class="form-control" name="">
                            </div>
                            <div class="col-md-12">
                                <label for="date" class="col-form-label text-md-end">Discount</label>
                                <input id="discount" onkeyup="totalCalc()" type="number" class="form-control" value=""
                                       name="discount">
                            </div>
                            <div class="col-md-12">
                                <label for="date" class="col-form-label text-md-end">Shipping charge</label>
                                <input style="" onkeyup="totalCalc()" id="shipping_charge" type="number"
                                       class="form-control" name="shipping_charge">
                            </div>

                            <div class="col-md-12">
                                <label for="date" class="col-form-label text-md-end">Grand Total</label>
                                <input style="" id="grand_total" type="number" class="form-control" name="grand_total">
                            </div>

                            <div class="col-md-12">
                                <label for="date" class="col-form-label text-md-end">Payment Status</label><span
                                        style="font-weight: bold; color: red"> *</span>
                                <select onchange="totalCalc()" required name="payment_status" id="payment_status"
                                        class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">Paid</option>
                                    <option value="2">Partially paid</option>
                                    <option value="3">Unpaid</option>
                                </select>
                            </div>

                            <div class=" col-md-12" id="payment_acc">
                                <label for="supplier" class="col-form-label text-md-end">Payment Account</label><span
                                        style="font-weight: bold; color: red"> *</span>
                                <select required name="account_id" id="account_id"
                                        class="select2 form-control @error('account_id') is-invalid @enderror">
                                    <option value="">Select account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->id}}">
                                            @if($account->type == 1)
                                                {{$account->name}} (Petty Cash)
                                            @else
                                                {{$account->account_no}} => {{$account->branch}} => {{$account->bank}}
                                            @endif

                                        </option>
                                    @endforeach
                                </select>
                                @error('account_id')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>

                            <div id="payment_area">
                                <div class="col-md-12">
                                    <label for="payment_amount" class="col-form-label text-md-end">Payment
                                        Amount</label><span
                                            style="font-weight: bold; color: red"> *</span>
                                    <input onkeyup="totalCalc()" id="payment_amount" type="number" class="form-control"
                                           name="payment_amount">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="date" class="col-form-label text-md-end">Due Amount</label><span
                                        style="font-weight: bold; color: red"> *</span>
                                <input readonly id="due_amount" type="number" class="form-control" name="">
                            </div>
                        </div>
                    </div>
                </div>


                <div class=" row" style="margin-top: 4px">
                    <div class=" col-md-12">
                        <label for="description" class="col-form-label text-md-end">Description</label>
                        <textarea class="form-control" name="description" id="" cols="30"
                                  rows="3"></textarea>

                    </div>
                </div>

                <div class="row" style="margin-top: 5px">
                    <div class=" col-md-12">
                        <button id="add_btn" onclick="return confirm(`Are you sure ?`)" class="btn btn-sm btn-grad"
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
        $('#payment_area').hide();
        $('#payment_acc').hide();
        $().ready(function () {
            $("#form").validate({
                rules: {
                    supplier_id: {
                        required: true
                    },
                    status: {
                        required: true
                    },
                    date: {
                        required: true,
                    },
                },
            });

        })

        var x = 0;
        var product_arr = [];
        $('#product_add').on('click', function () {
            let $product = $('#product_id option:selected');
            let product_id = $product.val();
            let product_name = $product.attr('product_name');
            let price = $product.attr('price');

            let isValid = product_arr.includes(product_id);

            if (!isValid) {
                x++;
                $('#product-add-area').prepend(
                    `<tr id="tr-${x}">
                    <td>
                        <input type="hidden" name="product[]" id="product-id-${x}" value="${product_id}">
                        ${product_name}
                    </td>
                    <td>
                        <input required name="price[]" id="price-${x}" onkeyup="calc(${x})" type="number" class="price form-control" value="${price}">
                    </td>
                    <td>
                        <input required name="quantity[]" id="quantity-${x}" onkeyup="calc(${x})" type="number" class="quantity form-control" value="">
                    </td>
                    <td>
                        <input required name="total[]" id="total-${x}" readonly type="number" class="total form-control" value="0">
                    </td>
                    <td>
                        <a style="color: red; font-size: 15px" onclick="remove(${x})" class="btn btn">
                            <i class="fa fa-times"></i>
                        </a>
                    </td>
                </tr>`
                )
                product_arr.push(product_id);
            } else {
                toastr.warning("Product Already Added");
            }
        })

        function remove(x) {
            let product_id = $('#product-id-' + x).val();
            $('#tr-' + x).remove();
            product_arr = product_arr.filter(function (item) {
                return item !== product_id
            })
            totalCalc();
        }

        function calc(x) {
            let price = $('#price-' + x).val();
            let quantity = $('#quantity-' + x).val();
            let total = parseFloat(price) * parseFloat(quantity);
            $('#total-' + x).val(total);
            totalCalc();
        }

        function totalCalc() {
            var qty = 0;
            var total = 0;

            var grand_total = 0;
            $('.total').each(function () {
                total += parseFloat(this.value);
            });
            $('.quantity').each(function () {
                qty += parseFloat(this.value);
            });

            $('#total_qty').val(qty)
            $('#total_amount').val(total)
            let shipping = $('#shipping_charge').val();
            let discount = $('#discount').val();
            let payment_amount = $('#payment_amount').val();

            if (isNaN(shipping) || shipping == "") {
                shipping = 0;
            }
            if (isNaN(discount) || discount == "") {
                discount = 0;
            }

            if (isNaN(payment_amount) || payment_amount == "") {
                payment_amount = 0;
            }

            grand_total = (total + parseFloat(shipping)) - parseFloat(discount);

            $('#grand_total').val(grand_total)

            if ($('#payment_status').val() == 1) {
                $('#due_amount').val(0)
            } else {
                $('#due_amount').val(grand_total - parseFloat(payment_amount))
            }

        }

        $('#payment_status').on('change', function () {
            let payment_status = $(this).val();
            if (payment_status == 2) {
                $('#payment_area').show();
            } else {
                $('#payment_area').hide();
                $('#payment_amount').val(0);
            }

            if (payment_status == 3) {
                $('#payment_acc').hise();
            } else {
                $('#payment_acc').show();
            }
            totalCalc();
        })

    </script>
@endsection