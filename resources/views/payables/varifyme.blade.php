@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('AP Invoice Old Payment Varification')}}</h3>
        </div>
        <div class="panel-body">
        <form class="form-inline" method="post" action="{{route('APInvoiceAlls.otpSend')}}" >
        @csrf
            <div class="form-group">
                <label for="payment">Payment:</label>
                <input type="text" class="form-control" id="payment" name="payment" value="<?php echo $amount; ?>">
                <input type="hidden" class="form-control" id="invoicenumber" name="invoicenumber" value="<?php echo $invoice_number; ?>">
                <input type="hidden" class="form-control" id="supplier_id" name="supplier_id" value="<?php echo $supplier_id; ?>">

            </div>
            <button class="btn btn-purple" type="submit" name="sendotp" class="btn btn-default">Send OTP</button>
          
        </form> 
        </div>
     </div>
</div>

@endsection
@section('script')
<script>//$('#date').datepicker({format: 'yyyy-mm-dd'});

</script>
@endsection