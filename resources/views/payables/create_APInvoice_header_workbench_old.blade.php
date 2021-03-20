@extends('layouts.app')

@section('content')
<style>

.gsts input[type=text]{width:50%}
.gsts input[type=radio]{height:10px !important}
.overlay {
  position: absolute;
  background: rgba(255,0,0,0.7);
  left: 0.7em;
  right: 0.7em;
  height: 3.2em;
  text-align: center;
  margin-top: -44px;
}
</style>

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Create AP Invoice-old')}}</h3>
			@if(session()->get('msg') != null)
			<div class="alert alert-danger">{{ session()->get('msg') }}</div>
			@endif
        </div>
        <div class="panel-body">
            <div class="row col-sm-12">
            <form method="post" action="{{route('APInvoiceAlls.OldInvoiceStore')}}"  >
            @csrf
                <table class="table table-responsive form_table">
			  	     <tr><td>Supplier Mobile1 * : </td><td>
                       <input type='text' name="mobile1" id="mobile11" required="required" onChange="getCustName(this.value);" maxlength="10" />
			        </td>
                    <td>Supplier Name* </td><td><div id="changeValues"></div></td>
                    </tr>
                    <tr><td>Invoice Number* </td><td><input type="text" name="invoice_number" required="required" /></td>
                    <td>Invoice Date* : </td><td><input type="text" name="date" value="<?php echo date("m/d/Y"); ?>" class="tcal" readonly="readonly" /></td></tr>
                    <tr><td>Description</td><td><textarea name="description"></textarea></td><td></td><td><input type="submit" value="Save and Next" name="save" /></td></tr>
                </table>
	         </form>	
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd', maxDate: new Date() });
function getCustName(){
var mobile				= $('#mobile11').val();
					
$.ajax({
				async : false,
				url : '{{ url('admin/APinvoice_header_workbench/get_customer_AP') }}',
				type : "POST",
				data : {'mobile1' : mobile, _token: "{{ csrf_token() }}"},
				dataType : 'text',
				timeout : 1000,
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
			//alert(dataType);
		$('#changeValues').html();
				$('#changeValues').html(dataType);
										}
			});
			    document.getElementById("cust_name").focus();
}

function setFocusToTextBox(){
    document.getElementById("mobile11").focus();
}

</script>
@endsection