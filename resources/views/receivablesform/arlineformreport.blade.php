@extends('layouts.app')

@section('content')
<style>
select + .select2-container
{
    width:auto !important;
}
.form-inline .form-group {
	display: inline-block;
	margin-bottom: 15px !important;
	vertical-align: middle;
	margin-right: 15px !important;
}
</style>
<style>
#DataTables_Table_0_wrapper {
	padding-left: 2% !important;
	padding-right: 2% !important;
}
.dt-buttons {
	display: none !important;
}
</style>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
<h1 class="panel-title pull-left pad-no" style="margin-left: 30px;">{{__('Form Report Employee wise')}}</h1>
    <div class="panel-heading bord-btm clearfix pad-all h-100" >
    <div class="col-sm-12">
    
        <fieldset class="scheduler-border">
		<legend class="scheduler-border">Search</legend>
    <div class="preorder">@if($msg != null)
			<div class="alert alert-success">{{ $msg}}</div>
			@endif</div>
		<div class="control-group">
            <form class="form-inline" action="{{route('ArInvoicesAllF.LinearformreportSubmit')}}" id="myForm" method="POST">
            @csrf
                 <div class="form-group ">
                    <label for="invoicenum">Store:</label>
                    <select class="form-control demo-select2-placeholder" name="user" id="user">
                            
								<option value="1">{{__('Amit Book Depot')}}</option>
							
                        </select>
                </div>
                <div class="form-group">
						<label for="startdate">Start PayDate:</label>
						<input type="text" name="startdate" value="<?php echo $startdate; ?>" class="form-control" id="startdate">
					  </div>
					  <div class="form-group">
						<label for="enddate">End PayDate:</label>
						<input type="text" name="enddate" value="<?php echo $enddate; ?>" class="form-control" id="enddate">
					  </div>
					 
					  <div class="form-group">
						<label for="application">Application:</label>
						<input type="text" value="<?php echo $application; ?>" name="application" class="form-control" id="application" />
					  </div>
					  
					  <div class="form-group">
						<label for="startidate">Start InvoiceDate:</label>
						<input type="text" name="startidate" value="<?php echo $startidate; ?>" class="form-control" id="startidate">
					  </div>
					  <div class="form-group">
						<label for="endidate">End InvoiceDate:</label>
						<input type="text" name="endidate" value="<?php echo $endidate; ?>" class="form-control" id="endidate">
					  </div>
					  
					   <div class="form-group">
						<label for="searchkey">Search Key:</label>
						<input type="text" value="<?php echo $searchkey; ?>" name="searchkey" class="form-control" id="searchkey" />
					  </div>
					  
					  <div class="form-group">
						<label for="status">Status:</label>
						<select name="status" class="form-control" id="status">
							<option value=""></option>
							<option value="completed" <?php if($status == 'completed'){echo "selected"; } ?>>Completed</option>
							<option value="incomplete" <?php if($status == 'incomplete'){echo "selected"; } ?>>Incomplete</option>
						</select>
					  </div>
					  
					  <div class="form-group">
						<label for="paybank">PayBank:</label>
						<select name="paybank" class="form-control" id="paybank">
							<option value=""></option>
							<option value="SBOP ABD"  <?php if($paybank == 'SBOP ABD'){echo "selected"; } ?>>SBOP ABD</option>
							<option value="SBT AS" <?php if($paybank == 'SBT AS'){echo "selected"; } ?>>SBT AS</option>
							<option value="HDFC AS" <?php if($paybank == 'HDFC AS'){echo "selected"; } ?>>HDFC AS</option>
							<option value="HDFC GD" <?php if($paybank == 'HDFC GD'){echo "selected"; } ?>>HDFC GD</option>
							<option value="SBOP ABD" <?php if($paybank == 'SBOP ABD'){echo "selected"; } ?>>SBOP ABD</option>
							<option value="HDFC CREDIT CARD" <?php if($paybank == 'HDFC CREDIT CARD'){echo "selected"; } ?>>HDFC CREDIT CARD</option>
							<option value="HDFC AMIT SINGH" <?php if($paybank == 'HDFC AMIT SINGH'){echo "selected"; } ?>>HDFC AMIT SINGH</option>
							<option value="OTHERS" <?php if($paybank == 'OTHERS'){echo "selected"; } ?>>OTHERS</option>
							<option value="BANK CHALAN" <?php if($paybank == 'BANK CHALAN'){echo "selected"; } ?>>BANK CHALAN</option>
							<option value="CUSTOMER CARD/BANKING" <?php if($paybank == 'CUSTOMER CARD/BANKING'){echo "selected"; } ?>>CUSTOMER CARD/BANKING</option>
						  
						</select>
					  </div>
					  
					  
                <div class="form-group ">
                <label for="invoicenum">Users:</label>
                    <select class="form-control demo-select2-placeholder" name="user" id="user">
                            <option value="">{{__('Select User')}}</option>
                            @foreach(\App\User::all() as $user)
								<option value="{{$user->email}}"<?php if(isset($_GET['user'])){echo 'selected'; } ?>>{{__($user->email)}}</option>
							@endforeach
                                
                        </select>
                </div>
                <input type="hidden" value="LineID" name="orderby" />
				<input type="hidden" value="asc" name="order" />
                <div class="form-group ">
                    <button type="submit" name="search" value="search" class="btn btn-primary">Search</button>
                    <button  name="clear" onclick="resetForm();" value="clear" class="btn btn-danger">Clear</button>
                    <button type="button" name="sms" data-toggle="modal" data-target="#myModal" class="btn btn-info sendsms">Send SMS</button>
                </div>
            </form> 
        </div>
        </fieldset> 
    </div>

    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send SMS</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('ArInvoicesAllF.sendsms')}}" method="post">
        @csrf
		<div class="form-group">
			<label>Mobile:</label>
        <textarea name="mobile"  id="mobilenum" class="form-control mobilenum"></textarea>
        </div>
        <div class="form-group">
			<label>SMS Text:</label>
        <textarea name="smstext" class="form-control smstext"></textarea>
        </div>
        <button type="submit" name="sendsms" value="sendsms" class="btn btn-primary"> Send SMS</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
       
        <!-- <div class="pull-right clearfix">
            <form class="" id="sort_orders" action="" method="GET">
               <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" style="margin-top: 40px;"class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type Order code & hit Enter">
                    </div>
                </div>
            </form>
        </div> -->
    </div>
    <div class="panel-body">
        <div class="table-responsive">
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
            <thead>
            <tr><th><input type="checkbox" name="arfrmall1" value="" id="arfrmall1" class="arfrmall" /></th>
            <th>Sr.No.</th>
            <th>AR.No.</th>
            <th>ISBN</th> 
            <th>FormName</th>
            <th>Total amt inc. service charge</th>
            <th>Service Charge</th>
            <th>Paid from Bank</th>
            <th>Paid on date</th>
            <th>Invoice Date</th>
            <th>Status</th>
            <th>Application no</th>
            <th>last attempt</th></tr>
            </thead>
            @if($data != null)
            <tbody>
           
                @foreach ($data as $key => $r)
             
                        <tr>
                        <td><input type="checkbox" name="arfrm" class="arfrm" id="arfrm-<?php echo $key; ?>" value="" data-mob="" /></td>
                        <td><?php   echo $key+1; ?></td>
                        <?php
                        $getall = \App\ArInvoicesAllF::where('invoiceid', $r->invoiceid)->first();
		 
		                ?>
                        <td><a target="_blank" href="ARinvoice-lines-workbench-f.php?invoice_number=<?php echo $getall->invoicenumber; ?>"><?php echo $getall->invoicenumber; ?></a></td>
                        <td><?php 
                        $getallline = \App\ArInvoiceLinesF::where('invoiceid', $getall->invoiceid)->first();
                        $isbn='';
                        $product = \App\Product::where('id',$getallline->itemid)->first(); 
                        
                            if($getallline->variation != NULL && $getallline->variation != 'null')
                            {
                                
                                $productStock = \App\ProductStock::where('variant',$getallline->variation)->where('product_id',$getallline->itemid)->first();
                                if($productStock)
                                {
                                    $isbn = $productStock->isbn;
                                    $product_name =  $product->name.'+'.$getallline->variation;
                                }
                            }
                            else
                            {
                                $isbn = $product->isbn;
                                $product_name = $product->name;
                            }
                        echo $isbn;  ?></td> 
                        <td><?php echo $product_name; ?></td>
                        <td><?php echo $r->recievedamount; //$amt = $amt+$r['recievedamount']; ?></td>
                        <td><?php echo $r->amount; //$sc = $sc+$r['Amount']; ?></td>
                        <td><?php echo $r->paybank; ?></td>
                        <td><?php echo $r->paydate;  ?></td>
                        <td><?php echo $getall->invoicedate;  ?></td>
                        <?php $st = $r->status;
                        if($st == ''){ $st = "Not Attempt"; }
                        ?>
                        <td><?php echo $st;?></td>
                        <td><?php echo $r->application; ?></td>
                        <td><?php echo $r->lastattempt; ?></td>
                        </tr>
                        <?php

                        $amt = $amt + $r->recievedamount;
                $sc = $sc + $r->amount;
                ?>
                @endforeach
            </tbody>
            @endif
        </table>
      </div>
      <div style="clear:both"></div>
      <div style="clear:both"></div>
      <p>Total Searched Data: <b><?php echo $total_records; ?></b></p>
<p>Total Service Charge: <b><?php if($sc != '' ){ echo $sc; } ?></b></p>
<p>Total Amount inc Service Charge: <b><?php echo $amt ?></b></p>
    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">
       $('#startdate').datepicker({ dateFormat: 'yy-mm-dd'});
  $('#enddate').datepicker({ dateFormat: 'yy-mm-dd'});
  $('#startidate').datepicker({ dateFormat: 'yy-mm-dd'});
  $('#endidate').datepicker({ dateFormat: 'yy-mm-dd'});

  $('.arfrmall').change(function(e){
var mob = [];	
if($(this).prop('checked') == true){
$('.arfrmall').prop('checked',true);
$('.arfrm').prop('checked',true);
$(".arfrm:checked").each(function(){
    mob.push($(this).val());
});
$('#mobilenum').val(mob.join());
}else{
	
$('.arfrmall').prop('checked',false);	
$('.arfrm').prop('checked',false);
mob1 = '';
$('#mobilenum').val(mob1);
}
});

$('.arfrm').change(function(e){
	var mob = [];
	$(".arfrm:checked").each(function(){
    mob.push($(this).val());
});
$('#mobilenum').val(mob.join());
});

function resetForm() {
    document.getElementById("myForm").reset();
}

    </script>
@endsection
