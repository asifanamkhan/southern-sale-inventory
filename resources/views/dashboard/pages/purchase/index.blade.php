@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow' , sans-serif;">
        Purchase
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> Purchase</a></li>
        <li class="active">Purchase List</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>PURCHASE LIST</b></h5>
            <a href="{{route('purchase.create')}}" id="add_new" style="float: right" class="btn btn-sm btn-grad">Add New
                Purchase</a>
        </div>
        <div class="box-body">
            <table style="width: 100%" class="table table-responsive table-striped data-table" id="table">
                <thead class=""
                       style="color: white;background-image: radial-gradient( circle farthest-corner at 22.4% 21.7%, rgba(4,189,228,1) 0%, rgba(2,83,185,1) 100.2% );">
                <tr class="" style="text-align:center; ">
                    <th style="width: 8%">SL</th>
                    <th style="width: 12%">Date</th>
                    <th style="width: 35%">Supplier</th>
                    <th style="width: 15%">Status</th>
                    <th style="width: 20%">Amount</th>
                    <th style="width: 15%">Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="paymentModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div id="payment-area" class="modal-body">

                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')

    <script>
        var datatable = $('.data-table').DataTable({
            order: [],
            lengthMenu: [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "All"]],
            processing: true,
            responsive: true,
            serverSide: true,
            language: {
                processing: '<i class="ace-icon fa fa-spinner fa-spin bigger-500" style="font-size:60px;"></i>'
            },
            scroller: {
                loadingIndicator: false
            },
            pagingType: "full_numbers",

            ajax: {
                url: "{{route('purchase.index')}}",
                type: "get",
            },

            columns: [
                {data: "DT_RowIndex", name: "DT_RowIndex", orderable: false,},
                {data: 'date', name: 'date', orderable: true,},
                {data: 'supplier', name: 'supplier', orderable: true,},
                {data: 'status', name: 'status', orderable: true,},
                {data: 'amount', name: 'amount', orderable: true,},
                // {data: 'status', name: 'vat_or_tax_type', orderable: true},
                {data: 'action', searchable: false, orderable: false}

                //only those have manage_user permission will get access

            ],
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function payment(id) {

            $.ajax({
                type: 'POST',
                url: "{{ route('purchase.payment.render') }}",
                data: {id: id},
                success: function (data) {
                    if(data == 1){
                        toastr.warning("Already Paid");
                    }else{
                        $('#payment-area').html(data)
                        $('#paymentModal').modal('show');
                    }

                }
            });
        }

    </script>
@endsection