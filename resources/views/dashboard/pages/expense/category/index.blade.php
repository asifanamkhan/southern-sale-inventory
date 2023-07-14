@extends('dashboard.layouts.master')
@section('content-header')
    <h1 style="font-family: 'Arial Narrow';">
        Expense Category
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-pie-chart"></i> Expense</a></li>
        <li class="active">Categories</li>
    </ol>
@endsection
@section('content')

    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title"><b>CATEGORY LIST</b></h5>
            <button id="add_new" style="float: right" class="btn btn-sm btn-grad">Add New Category</button>
        </div>
        <div class="box-body">
            <table style="width: 100%" class="table table-responsive table-striped data-table" id="table">
                <thead class=""
                       style="color: white;background-image: radial-gradient( circle farthest-corner at 22.4% 21.7%, rgba(4,189,228,1) 0%, rgba(2,83,185,1) 100.2% );">
                <tr class="" style="text-align:center; ">
                    <th style="width: 10%">SL</th>
                    <th style="width: 30%">Name</th>
                    <th style="width: 50%">Description</th>
                    <th style="width: 10%">Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="addModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="create_form" action="{{route('expense-categories.store')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="">Name</label><span style="font-weight: bold; color: red"> *</span>
                                <input name="name" type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Description</label>
                                <textarea class="form-control" name="description" id="" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <button onclick="confirm(`Are You Sure ?`)" style="float: right"
                                        class="btn btn-sm btn-grad">SAVE
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="editModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="edit_form" action="{{route('expense-categories.update')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="">Name</label><span style="font-weight: bold; color: red"> *</span>
                                <input id="edit_name" name="name" type="text" class="form-control">
                            </div>
                            <input type="hidden" id="edit_id" name="product_cat_id">
                            <div class="form-group col-md-12">
                                <label for="">Description</label>
                                <textarea id="edit_description" class="form-control" name="description" id=""
                                          cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <button onclick="confirm(`Are You Sure ?`)" style="float: right"
                                        class="btn btn-sm btn-grad">SAVE
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')

    <script>

        $().ready(function () {
            $("#create_form").validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
            });

            $("#edit_form").validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
            });

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
                url: "{{route('expense-categories.index')}}",
                type: "get",
            },

            columns: [
                {data: "DT_RowIndex", name: "DT_RowIndex", orderable: false,},
                {data: 'name', name: 'name', orderable: true,},
                {data: 'description', name: 'description', orderable: true},
                // {data: 'status', name: 'vat_or_tax_type', orderable: true},
                {data: 'action', searchable: false, orderable: false}

                //only those have manage_user permission will get access

            ],
        });

        $('#add_new').on('click', function () {
            $('#addModal').modal('show');
        })

        function catEdit(id) {
            let name = $('#expense-cat-' + id).attr('cat_name');
            let description = $('#expense-cat-' + id).attr('cat_desc');

            $('#edit_name').val(name);
            $('#edit_description').val(description);
            $('#edit_id').val(id);

            $('#editModal').modal('show');
        }
    </script>
@endsection