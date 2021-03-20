@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('AP Invoice Old Payment Varification')}}</h3>
        </div>
        <div class="panel-body">
        <form class="form-inline" method="post" action="{{route('APInvoiceAlls.verifyotp')}}" >
        @csrf
            <div class="form-group">
                <label for="payment">Payment:</label>
                <input type="text" class="form-control" id="payment" name="payment" value="<?php echo $amount; ?>">
                <input type="hidden" class="form-control" id="invoicenumber" name="invoicenumber" value="<?php echo $invoice_number; ?>">
                <input type="hidden" class="form-control" id="supplier_id" name="supplier_id" value="<?php echo $supplier_id; ?>">

            </div>
            <?php 
                $datetime1 = new DateTime();
                $datetime2 = new DateTime($otpdate);
                $interval = $datetime1->diff($datetime2);
                $minute = $interval->format('%i');
                $seconds = $interval->format('%s');
                $totaltime = $minute*60 + $seconds;
            if($otp != '' && $totaltime < 300){ ?>
            <div class="form-group">
                <label for="otp">OTP:</label>
                <input type="text" class="form-control" id="otp" name="otp" >
                <input type="hidden" name="otpnum" id="otpnum" value="<?php echo $otp; ?>" />
                <input type="hidden" name="pamount" id="pamount" value="<?php echo $amount; ?>" />
            </div>
            
            <button type="submit" name="verify"  id ="Verify" class="btn btn-default">Verify</button>
            <button id="Resend" name="resend" value="resend" class="btn btn-default">Resend OTP</button>
            <?php }?>
        </form> 
        </div>
     </div>
</div>

@endsection
@section('script')
<script>//$('#date').datepicker({format: 'yyyy-mm-dd'});
$('#Resend').click(function(e){
    e.preventDefault();
    var otp = $('#otp').val();
    var payment = $('#payment').val();
    var invoicenumber = $('#invoicenumber').val();
    var supplier_id = $('#supplier_id').val();
    $.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/APinvoice_header_workbench/otp_resend') }}',
		data: { invoicenumber:invoicenumber, supplier_id:supplier_id, otp:otp, payment:payment, _token: "{{ csrf_token() }}" },
        //dataType: 'json',
        success: function (data) {
            console.log(data);
                alert('Otp resend');
                    setTimeout(function(){// wait for 5 secs(2)
                location.reload(); // then reload the page.(3)
            }, 2000); 
            }
		});
});

</script>
@endsection