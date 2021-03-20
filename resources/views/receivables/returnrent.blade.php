@extends('layouts.app')

@section('content')

<style>

#book-list > li.list-group-item:hover {
	background-color: #000 !important;
	color: #fff;
	cursor:pointer;
}

#book-list > li.list-group-item > ul.list-group > li.selectbook_var:hover {
	background-color: #303641 !important;
	color: #fff;
	cursor:pointer;
}
#container .table-bordered, #container .table-bordered td, #container .table-bordered th {
    border-color: rgb(0, 0, 0);
}

</style>

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Return Rent')}}</h3>
			@if(session()->get('msg') != null)
			<div class="alert alert-danger">{{ session()->get('msg') }}</div>
			@endif
        </div>
        <div class="panel-body">
        

				<table>
					<tbody><tr>
					<td>
					<table style="width:800px;" border="0">
						<tbody><tr><td> AR Invoice Number:</td><td>{{$order->ordersource.$order->invoice_number}}</td>
						<td>Customer ID:</td><td><a href="" target="_blank">{{$order->user_id}}</a></td></tr>
						<tr><td> AR Invoice Type:</td><td>{{$order->invoicelookuptype}}</td>
						<td> AR Invoice Date:</td><td>{{$order->invoicedate}}</td></tr>
						<tr><td>AR Invoice Status:</td><td> @if($order->status == 'unpaid'){{_('Open')}} @endif
                        @if($order->status == 'paid'){{_('Paid')}} @endif
                        @if($order->status == 'cancel'){{_('cancel')}} @endif</td>
						<td>Description:</td><td><{{$order->description}}/td></tr>
						<!-- <tr><td>Last Updated By:</td><td>braj@gmail.com</td><td>Last Updated Date:</td><td>{{$order->updated_at}}</td></tr> -->
						
						</tbody>
					</table><br>

					</td>
					
					</tr>
					</tbody>
				</table>
				<form  action="{{route('order.saverent')}}"  method="POST">
				@csrf
				<table id="invtbl" style= "border-color: rgb(17, 2, 1);" class="table table-bordered" >
			  
						<tr>
                        <th></th>
                        <th>S.N.</th><th>ISBN ID</th><th>Book</th>
						<th>MRP/ Security</th>
						<th>Quantity</th>
						<!-- <th>CGST/U</th>  -->
						
						<th>Sale/Rent</th>
						<th>Rent</th>
                        <th>Refund</th>
						<th>Return Qty</th>
						<th>Old ISBN</th></tr>
							<?php
							$refund_payment=0;
                            $save_action_counter=0; // check ISBN present in master copy or not
                            $flagOld = 1;
                            $isbnnotavail = array();
                            $isbnoldavail = array();
                            $ii=0;
                            $tot_return = 0;
                            $ttype = 'R';$inc =0;
							$invoice_line_da = \App\OrderDetail::where('order_id',$order->id)->get();
						
							$url_invoice="{{url('admin/ARinvoice_header_workbench/ARInvoice/')}}.$order->id"; 
							$action_url="<script>window.open('".$url_invoice."');</script>";
							if(sizeof($invoice_line_da)>0) {
							//die("bcddd");
							foreach($invoice_line_da as $invoice_line_data ){
                        $invoice_old_isbn = \App\Product::where('id',$invoice_line_data->product_id)->first(); 
				
				if(isset($invoice_old_isbn->id) and $invoice_line_data->transactiontype=="R") { $save_action_counter+=1; 
					 } // check master copy created
				//if(empty($invoice_old_isbn['BookId']) or $invoice_line_data[$i]['TransactionType']=="R") { $save_action=false; } // check master copy created
				//if(empty($invoice_line_data[$i]['TransactionType']=="R")) { $save_action=false; } // check master copy created
				if($invoice_old_isbn->oldisbn == '' && $invoice_line_data->transactiontype=="R"){$flagOld = 0;
					$isbnnotavail[] = $invoice_old_isbn->isbn;
					
					}else{
						$isbnoldavail[$ii]['iold'] =  $invoice_old_isbn->oldisbn;
						$isbnoldavail[$ii]['type'] = $invoice_line_data->transactiontype;
						$ii++;
						
						}
					
						$rent_quantity = $invoice_line_data->quantity;
						$rent_line=$invoice_line_data->price;
							$pay_line=($invoice_line_data->amount-$rent_line)*$rent_quantity;  
							$refund_payment+=$pay_line;
							//echo $APQuantity[0]['Quantity'];
						if($invoice_line_data->transactiontype=="R") { 
						
                        $AQuantity = \App\ApInvoiceLines::select('quantity')->where('arinvoice', $invoice_line_data->id)->first();
                       $APQuantity = $AQuantity;
                        // $apinvoicedal->getRentReturnQuantityByARLine($invoice_line_data[$i]['LineID']);
						//echo count($APQuantity);
						//echo $APQuantity[0]['Quantity'];
							if($APQuantity){
							  // echo $rent_quantity.' - '.$APQuantity[0]['Quantity'];
							    $rent_quantity = $rent_quantity - $APQuantity->quantity;
								$pay_line=($invoice_line_data->amount-$rent_line)*$rent_quantity;  
							$refund_payment+=$pay_line;
							}
						}
						$tot_return = $tot_return + $rent_quantity;
			?>
			<tr <?php if($rent_quantity == 0){echo "style='background-color:#ccc'";} ?>>
			
			<td style="border:1px solid;"><?php if($invoice_line_data->transactiontype=="R") {?><input type="checkbox" class="bookchk" data-id='<?php echo $invoice_old_isbn->id; ?>'  name="bookid[<?php echo $invoice_old_isbn->id; ?>][]"   <?php if($rent_quantity == 0){ echo "readonly checked='checked'"; }else{echo "checked='checked'";} ?> /><?php } ?></td>
			<td style="border:1px solid;"><?php echo ++$inc; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_old_isbn->isbn; ?></td><td style="border:1px solid;"><?php echo $invoice_old_isbn->name; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data->amount; ?><input type='hidden' value='<?php echo $invoice_line_data->amount; ?>' id='mrp[<?php echo $invoice_line_data->id; ?>]' /></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data->quantity; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data->transactiontype; ?></td><td style="border:1px solid;" >
			<?php if($invoice_line_data->transactiontype=="R") {echo $rent_line=$invoice_line_data->price;?> <input type='hidden' value='<?php echo $rent_line; ?>' id='rent_one[<?php echo $invoice_line_data->id; ?>]' /> <input type="hidden" name="aptype[<?php echo $invoice_old_isbn->id; ?>][]" value="R" />  <?php } elseif($invoice_line_data->transactiontype=="S"){ $ttype = 'S';} ?></td>
			
			<td style="border:1px solid;"><?php if($invoice_line_data->transactiontype=="R") {  ?><input type='text' class="form-control" readonly name='refund[<?php echo $invoice_old_isbn->id; ?>][]' id='refund[<?php echo $invoice_line_data->id; ?>]' value="<?php echo $pay_line;?>" style="width:55px;"  /><?php } ?></td>
			<td style="border:1px solid;"><?php if($invoice_line_data->transactiontype=="R") {?><input style="padding:0px;" type='number' class="form-control" name='ret_qty[<?php echo $invoice_old_isbn->id; ?>][]' id='ret_qty[<?php echo $invoice_line_data->id; ?>]' max="<?php echo $rent_quantity; ?>" min="0" value="<?php echo $rent_quantity; ?>" style="width:39px;" onChange='calc_refund(<?php echo $invoice_old_isbn->id; ?>,<?php echo $invoice_line_data->id; ?>);' /><?php } ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data->oldisbn; ?></td>
			</tr>
			<?php if($invoice_line_data->transactiontype=="R") { ?><input type="hidden" name = "invoice[<?php echo $invoice_old_isbn->id; ?>]" value="<?php echo $invoice_line_data->id; ?>" /><?php } ?>
			<input type="hidden" value="{{$order->user_id}}" name='userid'>
			<input type="hidden" value="{{$order->invoice_number}}" name='invoicenumber'>
			<?php
            }
        }
			?>
			</table><br />
            <?php //echo "Total Refund : <label id='total_refund'>".$refund_payment."</label>";  ?><br />
			<!-- Send SMS OTP* : <input type='radio' name='sms' value='Y' checked='checked' /> Yes <input type='radio' name='sms' value='N' /> No<br /> -->
			<?php 
			$nisbn = '';
			
			if($flagOld==1) {
				$flg = 1;
				//$isbnoldavail = array_unique($isbnoldavail);
				echo "<br/>";
				foreach($isbnoldavail as $isold){
					//echo $isold['iold'];
                    $invoice_old_isbn = \App\Product::where('id',$invoice_line_data->product_id)->first(); 
				
				if($invoice_old_isbn->isbn == '0' and $isold['type']=="R")
				{
				$flg = 0;
				echo "<p style='color:red;'> Books not available with ISBN = <b>".$isold['iold']."</b></p>";
				}
			}
			if($flg == 1){
				 ?>
				 <?php
                 $id = -1;
         if($id==-1) {
        $openinvoices = \App\Order::select('invoice_number','user_id','payment_status','invoicedate','grand_total')->where('payment_status','unpaid')->get();
         }
		else {
        $openinvoices= \App\Order::select('invoice_number','user_id','payment_status','invoicedate','grand_total')->where('payment_status','unpaid')->where('user_id',$id)->get();
        }
        
		?>
			<div style="float:right"><input type='submit' name='save' value='Save and Next' <?php if($tot_return == 0 || count($openinvoices)>0){echo "disabled"; } ?> /></div>
			<div class="clearfix"></div>
			<?php 
		}
			} else{
			
		/*	if($ttype == 'S') {
				?>
				<div style="float:right"><input type='submit' name='save' value='Save and Next' <?php if($tot_return == 0){echo "disabled"; } ?> /></div>
				<?php
				}
		*/	
			echo "<b>Please create master copy for old ISBN.</b> Save button will be activated after the old ISBN creation ; Master copy Not created: ".$save_action_counter; 
			echo "<br/><p style='font-weight:bold'>OLD ISBN has not available for following isbn.</p><br/><br/>";
			$isbnnotavail = array_unique($isbnnotavail);
			$ix=1;
			foreach($isbnnotavail as $uniqIsbn1)
			{
			echo "<p style='color:red;font-weight:bold'> ".$ix .") &nbsp;&nbsp;ISBN1 = ".$uniqIsbn1."</p>";
			$ix++;
			}
			}
			?>
			</form><br />
			Potential Duplicate Invoice:
			<?php 
				for($i=0;$i<count($redundant_invoice);$i++)
			{
echo "<p>AP Invoice Number : ".$redundant_invoice[$i]['InvoiceNumber']." , Book Name : ".$redundant_invoice[$i]['Name']." , Quantity: ".$redundant_invoice[$i]['Quantity']."</p>";
			}			
			?>
			<?php if(count($openinvoices)>0){ ?>
				<div style="color:red;font-weight:bold;float:right;"><p>Some invoice has opened !!! Please close invoice before proceed.</p></div>
		  
		 
			   <div style="clear:both;"></div>
		   <div style="width:80%">
			   <h2 style="color:red;">List of open Invoices</h2>
			   <table width="80%" border="1">
			   <tr><th>Sr. No.</th><th>Invoice ID</th><th>Date</th><th>Total</th></tr>
               <?php $i=0;
               $open = json_decode($openinvoices);
			    foreach($open as $op): ?>
			   <tr><td><?php echo $i++; ?></td><td><?php echo $op->invoice_number ?></td><td><?php echo $op->invoicedate ?></td><td><?php echo $op->grand_total ?></td></tr>
			   <?php endforeach;  ?>
			   </table>
			   
			</div>
			<?php } ?>
			
    </div>
</div>
@endsection

@section('script')
<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/black-tie/jquery-ui.css" />
<script
			  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
			  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
			  crossorigin="anonymous"></script>
		
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
<style>
input,select,select2 select2-container{border: 1px solid #999;
border-radius: 0 !important; height:30px !important}
.form-group {
	line-height: 5px !important;
	padding-bottom: 0px;
	margin-bottom: 5px;
}
textarea{border: 1px solid #999 !important;
border-radius: 0 !important; height:50px !important}
.select2-container--default .select2-selection--single{border: 1px solid #999 !important;
border-radius: 0 !important; height:30px !important}
td{padding: 0 !important;}
ul.a {
  background-color:red;
}
</style>

<script type="text/javascript">
 function calc_refund(bookid,invoiceline)
{
	
	// pass bookid in elements to fetch value from form elements
	var qty=document.getElementById("ret_qty["+invoiceline+"]").value;
	var rent=document.getElementById("rent_one["+invoiceline+"]").value;
	var mrp=document.getElementById("mrp["+invoiceline+"]").value;
	var total=qty*(mrp-rent);
	document.getElementById("refund["+invoiceline+"]").value=total;
}

// $(".bookchk").on('change',function(){
// 	var id =$(this).attr("data-id");
//         $(this).prop("checked", false);
//     });

</script>
@endsection