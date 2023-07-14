<form action="{{route('purchase.payment')}}" method="post">
    @csrf
    <input type="hidden" name="purchase_id" value="{{$id}}">
    <div class="row">
        <div class="col-md-3">
            <label for="date" class="col-form-label text-md-end">Payment Date</label><span
                    style="font-weight: bold; color: red"> *</span>
            <input name="date" type="date" required class="form-control"
                   value="{{\Illuminate\Support\Carbon::now()->toDateString()}}">
        </div>

        <div class="col-md-3">
            <label for="purchase_amount" class="col-form-label text-md-end">Purchase Amount</label><span
                    style="font-weight: bold; color: red"> *</span>
            <input readonly id="purchase_amount" type="number" class="form-control" value="{{$purchase_amount}}" name="purchase_amount">
        </div>

        <div class="col-md-3">
            <label for="paid_amount" class="col-form-label text-md-end">Paid Amount</label><span
                    style="font-weight: bold; color: red"> *</span>
            <input readonly id="paid_amount" type="number" class="form-control" value="{{$paid_amount}}" name="paid_amount">
        </div>

        <div class="col-md-3">
            <label for="due_amount" class="col-form-label text-md-end">Due Amount</label><span
                    style="font-weight: bold; color: red"> *</span>
            <input readonly id="due_amount" type="number" class="form-control" value="{{$due_amount}}" name="due_amount">
        </div>

    </div>
    <div class="row" style="margin-top: 4px">

        <div class="col-md-4">
            <label for="payment_status" class="col-form-label text-md-end">Payment Status</label><span
                    style="font-weight: bold; color: red"> *</span>
            <select required name="payment_status" id="payment_status" class="form-control">
                <option value="">Select</option>
                <option value="1">Paid</option>
                <option value="2">Partially paid</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="payment_status" class="col-form-label text-md-end">Payment Account</label><span
                    style="font-weight: bold; color: red"> *</span>
            <select required name="account_id" id="account_id" class="select2 form-control">
                <option value="">Select</option>
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
        </div>

        <div class="col-md-4">
            <label for="payment_amount" class="col-form-label text-md-end">Payment Amount</label><span
                    style="font-weight: bold; color: red"> *</span>
            <input required id="payment_amount" type="number" class="form-control" value="" name="payment_amount">
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

<script >
    $('#payment_status').on('change', function (){
        let payment_status = $("#payment_status option:selected").val()

        if(payment_status == 1){
            $('#payment_amount').val("{{$due_amount}}");
        }else{
            $('#payment_amount').val("");
        }
    })
</script>