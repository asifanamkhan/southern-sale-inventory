@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow';">
        Order
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> Order</a></li>
        <li class="active">add new order</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>ADD NEW ORDER</b></h5>
            <a href="{{route('order.index')}}" id="add_new" style="float: right" class="btn btn-sm btn-grad">Order
                List
            </a>
        </div>
        <div class="box-body">
            <form id="form" action="{{route('order.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class=" col-md-2">
                        <label for="date" class="col-form-label text-md-end">Order Date</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <input id="date" type="date" class="form-control @error('date') is-invalid @enderror"
                               name="date" value="{{ old('date') ??\Carbon\Carbon::now()->toDateString()  }}" required>

                        @error('date')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class=" col-md-3">
                        <label for="outlet" class="col-form-label text-md-end">Outlet</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <select name="outlet_id" id="outlet_id"
                                class="select2 form-control @error('outlet_id') is-invalid @enderror">
                            <option value="">Select Outlet</option>
                            @foreach($outlets as $outlet)
                                <option value="{{$outlet->id}}">{{$outlet->name}}</option>
                            @endforeach
                        </select>
                        @error('outlet_id')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class=" col-md-3">
                        <label for="customer" class="col-form-label text-md-end">Customer</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <select name="customer_id" id="customer_id"
                                class="select2 form-control @error('customer_id') is-invalid @enderror">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class=" col-md-2">
                        <label for="unit" class="col-form-label text-md-end">Unit</label><span
                                style="font-weight: bold; color: red"> *</span>
                        <select name="unit_id" id="unit_id"
                                class="select2 form-control @error('unit_id') is-invalid @enderror">
                            <option value="">Select Unit</option>
                            <option value="1">Inch</option>
                            <option value="2">Millimeter</option>
                        </select>
                        @error('unit_id')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class=" col-md-2">
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
                                        price="{{$product->sale_price}}"
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
                                <th style="width: 20%">Product</th>
                                <th style="width: 15%">Thickness</th>
                                <th style="width: 10%">Rate</th>
                                <th style="width: 50%">Details</th>
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

                            <div id="payment_area">
                                <div class="col-md-12">
                                    <label for="payment_amount" class="col-form-label text-md-end">Payment
                                        Amount</label><span
                                            style="font-weight: bold; color: red"> *</span>
                                    <input onkeyup="totalCalc()" id="payment_amount" required type="number" class="form-control"
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
        $().ready(function () {
            $("#form").validate({
                rules: {
                    customer_id: {
                        required: true
                    },
                    outlet_id: {
                        required: true
                    },
                    unit_id: {
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
                x = product_id;
                $('#product-add-area').prepend(
                    `<tr id="tr-${x}">
                    <td>

                        <input type="hidden" name="product[]" id="product-id-${x}" value="${product_id}">
                        ${product_name}
                    </td>
                    <td>
                        <input required name="thickness[]" id="thickness-${x}" onkeyup="calc(${x})" type="text" class="price form-control">
                    </td>

                    <td>
                        <input required name="price[]" id="price-${x}" onkeyup="calc(${x})" type="number" class="price form-control" value="${price}">
                    </td>
                    <td>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 23%">Length</th>
                                    <th style="width: 23%">Width</th>
                                    <th style="width: 24%">Qty</th>
                                    <th style="width: 27%">Sft</th>
                                    <th style="width: 3%">AC</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-det-${x}">
                                <tr >
                                    <td>
                                        <input name="product_${x}_length[]" type="number" onkeyup="detCal(${x},1)" id="tr-det-length-${x}-1" class="form-control">
                                    </td>
                                    <td>
                                        <input name="product_${x}_width[]" type="number" onkeyup="detCal(${x},1)" id="tr-det-width-${x}-1" class="form-control">
                                    </td>
                                    <td>
                                        <input name="product_${x}_qty[]" type="number" onkeyup="detCal(${x},1)" id="tr-det-qty-${x}-1" class="form-control tr-det-qty-${x}">
                                    </td>
                                    <td>
                                        <input name="product_${x}_sqf[]" type="number" id="tr-det-sqf-${x}-1" readonly class="form-control tr-det-sqf-${x}">
                                    </td>
                                    <td>
                                         <a style="color: green; font-size: 15px" onclick="addDet(${x})" class="btn btn">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>

                            <tbody id="less-area-${x}">

                            </tbody>

                            <tfoot>
                                <tr >
                                    <td colspan="4" style="text-align: right">
                                        <a style="cursor: pointer" onclick="lessAdd(${x})" id="less-btn-${x}">Add Less</a>
                                    </td>
                                </tr>

                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>
                                        <input type="number" name="product_quantity[]" id="tr-det-tot-qty-${x}" class="form-control qty_det" readonly>
                                    </th>
                                    <th>
                                        <input type="number"  id="tr-det-tot-sqf-${x}" class="form-control" readonly>
                                    </th>

                                </tr>
                                <tr>
                                    <th colspan="2">Amount</th>

                                    <th colspan="2">
                                        <input type="number" style="text-align: right" id="tr-det-tot-amount-${x}" class="form-control amount_det" readonly>
                                    </th>

                                </tr>
                            </tfoot>
                        </table>
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

        var y = 1;

        function addDet(id) {
            y++
            $('#tbody-det-' + id).append(`
                <tr id="tr-det-${id}-${y}">
                    <td>
                        <input onkeyup="detCal(${x},${y})"
                            name="product_${x}_length[]" id="tr-det-length-${id}-${y}" type="number" class="form-control">
                    </td>
                    <td>
                        <input name="product_${x}_width[]" onkeyup="detCal(${x},${y})" type="number" id="tr-det-width-${id}-${y}" class="form-control">
                    </td>
                    <td>
                        <input name="product_${x}_qty[]" onkeyup="detCal(${x},${y})" type="number" id="tr-det-qty-${id}-${y}" class="form-control tr-det-qty-${id}">
                    </td>
                    <td>
                        <input name="product_${x}_sqf[]" type="number" id="tr-det-sqf-${id}-${y}" readonly class="form-control tr-det-sqf-${id}">
                    </td>
                    <td>
                        <a style="color: red; font-size: 15px" onclick="removeDet(${id},${y})" class="btn btn">
                            <i class="fa fa-times"></i>
                        </a>
                    </td>
                </tr>
            `)
        }

        function lessAdd(id){
            $('#less-area-'+x).append(`
                <tr>
                    <td>
                        <input onkeyup="detCal(${x},${y})"
                            name="product_${x}_length[]" id="tr-det-length-${id}-${y}" type="number" class="form-control">
                    </td>
                    <td>
                        <input name="product_${x}_width[]" onkeyup="detCal(${x},${y})" type="number" id="tr-det-width-${id}-${y}" class="form-control">
                    </td>
                    <td>
                        <input name="product_${x}_qty[]" onkeyup="detCal(${x},${y})" type="number" id="tr-det-qty-${id}-${y}" class="form-control tr-det-qty-${id}">
                    </td>
                    <td>
                        <input name="product_${x}_sqf[]" type="number" id="tr-det-sqf-${id}-${y}" readonly class="form-control tr-det-sqf-${id}">
                    </td>
                    <td>
                        <a style="color: red; font-size: 15px" onclick="removeDet(${id},${y})" class="btn btn">
                            <i class="fa fa-times"></i>
                        </a>
                    </td>
                </tr>

            `)
        }

        function removeDet(id, y) {
            $('#tr-det-' + id + '-' + y).remove();
            detCal(id, y);
        }

        function detCal(x, y) {
            let length = $('#tr-det-length-' + x + '-' + y).val();
            let width = $('#tr-det-width-' + x + '-' + y).val();
            let qty = $('#tr-det-qty-' + x + '-' + y).val();

            let unit_id = $('#unit_id').val();

            if (isNaN(length) || length == "") {
                length = 0;
            }
            if (isNaN(width) || width == "") {
                width = 0;
            }

            if (isNaN(qty) || qty == "") {
                qty = 0;
            }

            if (unit_id == 2) {
                length = parseFloat(length) / 25.40;
                width = parseFloat(width) / 25.40;
            }

            let sqf = ((parseFloat(length) * parseFloat(width) * parseFloat(qty)) / 144).toFixed(2)

            $('#tr-det-sqf-' + x + '-' + y).val(sqf);
            calc(x);

        }

        function remove(x) {
            let product_id = $('#product-id-' + x).val();
            $('#tr-' + x).remove();
            product_arr = product_arr.filter(function (item) {
                return item !== product_id
            })

            calc(x);
        }

        function calc(x) {
            var total_sqf = 0;
            var total_qty = 0;

            let price = $('#price-'+ x).val();

            if (isNaN(price) || price == "") {
                price = 0;
            }

            $('.tr-det-sqf-' + x).each(function () {
                total_sqf += parseFloat(this.value);
            });

            $('.tr-det-qty-' + x).each(function () {
                total_qty += parseFloat(this.value);
            });

            $('#tr-det-tot-sqf-' + x).val(total_sqf.toFixed(2));
            $('#tr-det-tot-qty-' + x).val(total_qty);
            $('#tr-det-tot-amount-' + x).val((parseFloat(price) *  total_sqf) .toFixed(2));

            totalCalc();
        }

        function totalCalc() {
            var qty_det = 0;
            var amount_det = 0;
            var grand_total = 0;

            $('.amount_det').each(function () {
                amount_det += parseFloat(this.value);
            });

            $('.qty_det').each(function () {
                qty_det += parseFloat(this.value);
            });
            $('#total_qty').val(qty_det);
            $('#total_amount').val(amount_det.toFixed(2));

            let shipping = $('#shipping_charge').val();
            let discount = $('#discount').val();
            let payment_amount = $('#payment_amount').val();

            if(isNaN(shipping) || shipping == ""){
                shipping = 0;
            }
            if(isNaN(discount) || discount == ""){
                discount = 0;
            }

            if(isNaN(payment_amount) || payment_amount == ""){
                payment_amount = 0;
            }

            grand_total = ((amount_det + parseFloat(shipping)) - parseFloat(discount)).toFixed(2);

            $('#grand_total').val(grand_total)

            if($('#payment_status').val() == 1){
                $('#due_amount').val(0)
            }else{
                $('#due_amount').val((grand_total - parseFloat(payment_amount)).toFixed(2))
            }
        }

        $('#payment_status').on('change',function (){
            let payment_status = $(this).val();
            if(payment_status == 2 ){
                $('#payment_area').show();
            }else{
                $('#payment_area').hide();
                $('#payment_amount').val(0);
            }
            totalCalc();
        })

    </script>
@endsection