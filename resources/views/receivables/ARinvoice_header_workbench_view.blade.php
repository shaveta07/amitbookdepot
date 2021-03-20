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
            <h3 class="panel-title">{{__('AR Invoice Form Lines Workbench')}}</h3>
		
        </div>
        <div class="panel-body">
		<div>
		@if(session()->get('msg') != null)
			<div class="alert alert-danger">{{ session()->get('msg') }}</div>
			@endif
		</div>
        <div id="divtoprint" class="table-responsive">
				<table>
					<tbody><tr>
					<td>
					<table style="width:800px;" border="0">
						<tbody><tr><td>Invoice Number:</td><td>{{$order->ordersource.$order->invoice_number}}</td>
						<td>Customer ID:</td><td><a href="{{route('customers.edit', encrypt($order->user_id))}}" target="_blank">{{$order->user_id}}</a></td></tr>
						<tr><td>Invoice Type:</td><td>{{$order->invoicelookuptype}}</td>
						<td>Invoice Date:</td><td>{{$order->invoicedate}}</td></tr>
						<tr><td>Invoice Status:</td><td>@if($order->status == 'O'){{_('Open')}} @endif
                        @if($order->status == 'P'){{_('Paid')}} @endif
                        @if($order->status == 'C'){{_('cancel')}} @endif</td>
						<td>Description:</td><td><{{$order->description}}/td></tr>
						<!-- <tr><td>Last Updated By:</td><td>braj@gmail.com</td><td>Last Updated Date:</td><td>{{$order->updated_at}}</td></tr> -->
						<tr><td>GSTIN</td><td>{{$order->gstin}}</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						</tbody>
					</table><br>

					</td>
					<!-- <td>
						<img style="border: 2px solid #ccc;margin-bottom: 5px;" src="qrinv/122529.png" alt="QR Code">
					</td> -->
					</tr>
					</tbody>
				</table>
				<div class="table-responsive">
				<table id="invtbl" style= "border-color: rgb(17, 2, 1);" class="table table-bordered" >
			  
						<tr><th>S.N.</th><th>ISBN ID</th><th>Book</th>
						<th>MRP/ Security</th>
						<th>SGST/U</th>
						<th>CGST/U</th> 
						<th>GST/U</th>
						<th>IGST/U</th>
						<th>BP/U</th>
						<th>Discount/U (%)</th>
						<th>QTY</th>
						<th>S/R</th>
						<th>Rent</th>
						<th>Final Amount</th>
						<th>Last update</th><th></th></tr>
							<?php
							$inc=0;
							$payment=0;$tqty=0;
							$virtuemart_product_ids = '';
							$_igst=$_sgst=$_gst=$_cgst=$_baseprice=0;$_discount=0;
							$invoice_line_da = \App\OrderDetail::where('order_id',$order->id)->get();
						
							$url_invoice="{{url('admin/ARinvoice_header_workbench/ARInvoice/')}}.$order->id"; 
							$action_url="<script>window.open('".$url_invoice."');</script>";
							if(sizeof($invoice_line_da)>0) {
							//die("bcddd");
							foreach($invoice_line_da as $invoice_line_data ){
								$isbn='';
								$product = \App\Product::where('id',$invoice_line_data->product_id)->first(); 
								
									if($invoice_line_data->variation != NULL && $invoice_line_data->variation != 'null')
									{
										
										$productStock = \App\ProductStock::where('variant',$invoice_line_data->variation)->where('product_id',$product->id)->first();
										if($productStock)
										{
											$isbn = $productStock->isbn;
											$product_name =  $product->name.'+'.$invoice_line_data->variation;
										}
									}
									else
									{
										$isbn = $product->isbn;
										$product_name = $product->name;
									}
								
							
							$_igst = $_igst+$invoice_line_data->igst*$invoice_line_data->quantity;
							$_cgst = $_cgst+$invoice_line_data->cgst*$invoice_line_data->quantity;
							$_sgst = $_sgst+$invoice_line_data->sgst*$invoice_line_data->quantity;
							
							$_baseprice = $_baseprice+$invoice_line_data->baseprice*$invoice_line_data->quantity;
							$_discount = $_discount + $invoice_line_data->discount*$invoice_line_data->quantity;
							$tax = $invoice_line_data->igst;
							//$tax = $invoice_line_data[$i]['gst'] + $invoice_line_data[$i]['igst'];
							$tqty = $tqty+$invoice_line_data->quantity;
							//die('tyhgfj');
							if($order->payment_status=="unpaid"){
							
								?>
										<tr class="tr_parent_{{ $invoice_line_data->id }}"><td><?php echo ++$inc; ?></td>
										
										<td><?php echo $isbn; ?></td>
										<td><?php echo $product_name; if($invoice_line_data->transactiontype=="R"){echo ",Return Due Date:".$invoice_line_data->rentDueDate."";} ?></td>
										<td> 
											<input style="width:60px;text-align:center" type="text" class="erpamount order-item-price-{{ $invoice_line_data->id }}"  data-line="<?= $invoice_line_data->preorderlineid ?>" name="amount[]" id = "amount-<?= $invoice_line_data->preorderlineid?>" data-book="<?= $invoice_line_data->product_id ?>" value="<?php echo $invoice_line_data->amount; ?>" />
										</td>

									<td>
										<input style="width:60px;text-align:center" type="text" class="sgst" data-line="<?= $invoice_line_data->preorderlineid ?>" name="sgst[]" id = "sgst-<?= $invoice_line_data->id  ?>" data-book="<?= $invoice_line_data->product_id ?>" value="<?php echo $invoice_line_data->sgst; ?>" />
										
										</td>
									<td>
										<input style="width:60px;text-align:center" type="text" class="cgst" data-line="<?= $invoice_line_data->preorderlineid ?>" name="cgst[]" id = "cgst-<?= $invoice_line_data->id  ?>" data-book="<?= $invoice_line_data->product_id ?>" value="<?php echo $invoice_line_data->cgst; ?>" />
										</td>
										<td>
									<input style="width:60px;text-align:center" type="text" class="gst" data-line="<?= $invoice_line_data->preorderlineid ?>" name="gst[]" id = "gst-<?= $invoice_line_data->id ?>" data-book="<?= $invoice_line_data->product_id ?>" value="<?php echo $invoice_line_data->gstamount; ?>" />
									</td>
									<td>
										<input style="width:60px;text-align:center"  type="text" class="igst" data-line="<?= $invoice_line_data->preorderlineid ?>" name="igst[]" id = "igst-<?= $invoice_line_data->id ?>" data-book="<?= $invoice_line_data->product_id ?>" value="<?php echo $invoice_line_data->igst; ?>" />
										</td>
									<td>
										<input  style="width:60px;text-align:center" type="text" class="baseprice order-item-base-{{ $invoice_line_data->id }}" data-line="<?= $invoice_line_data->preorderlineid ?>" name="baseprice[]" id = "baseprice-<?= $invoice_line_data->preorderlineid ?>" data-book="<?= $invoice_line_data->product_id ?>" value="<?php echo $invoice_line_data->baseprice; ?>" />
										</td>
									
									<td>
										<input style="width:50px;text-align:center" type="text" class="discount order-item-discount-{{ $invoice_line_data->id }}" data-line="<?= $invoice_line_data->preorderlineid ?>" name="discount[]" id = "discount-<?= $invoice_line_data->preorderlineid ?>" data-book="<?= $invoice_line_data->product_id?>" @if($invoice_line_data->amount > 0) value="<?php echo round((($invoice_line_data->discount+$tax)/$invoice_line_data->amount)*100,2) ?>" @else value="0" @endif /> %
										<?php
										
										$avail_qty = $product->maxorderqty;
										$SaleRent_1 = $product->onrent;
										
										?>
									<td>
										
										<?php $max = 1; if($invoice_line_data->transactiontype == 'S'){$max = $avail_qty+$invoice_line_data->quantity; } ?>
										<input style="width:60px;text-align:center" type="number" data-item-id="{{ $invoice_line_data->id }}" class="quantity order-item-qty-{{ $invoice_line_data->id }}" data-line="<?= $invoice_line_data->preorderlineid ?>" name="quantity[]" max="<?= $max ?>" min="1" id = "quantity-<?= $invoice_line_data->preorderlineid  ?>" data-book="<?= $invoice_line_data->product_id ?>" value="<?php echo $invoice_line_data->quantity; ?>" />
										</td>
									<td>
										<select style="width:60px;text-align:center" data-item-id="{{ $invoice_line_data->id }}" class="TransactionType order-item-type-{{ $invoice_line_data->id }}" data-booktype='<?= $SaleRent_1 ?>' data-line="<?= $invoice_line_data->preorderlineid  ?>" name="TransactionType[]" id = "TransactionType-<?= $invoice_line_data->preorderlineid  ?>" data-book="<?= $invoice_line_data->product_id ?>">
											<option value="S" <?php if($invoice_line_data->transactiontype == 'S'){echo "SELECTED";} ?>>Sale</option>
											<option value="R" <?php if($invoice_line_data->transactiontype == 'R'){echo "SELECTED";} ?>>Rent</option>
										</select>
										</td>
										
									<td><?php 
									if($invoice_line_data->transactiontype=="R") {
										?>
										<input style="width:60px;text-align:center" type="text" data-line="<?= $invoice_line_data->preorderlineid  ?>" data-item-id="{{ $invoice_line_data->id }}" class="sr linerent-{{ $invoice_line_data->id }}" name="sr[]" id = "sr-<?= $invoice_line_data->preorderlineid  ?>" data-book="<?= $invoice_line_data->product_id ?>" data-val="<?php echo $invoice_line_data->price*$invoice_line_data->quantity;  ?>" value="<?php echo $invoice_line_data->price*$invoice_line_data->quantity;  ?>" />
										<?php
									}else{
										?>
										<input style="width:60px;text-align:center" type="hidden" data-item-id="{{ $invoice_line_data->id }}" data-line="<?= $invoice_line_data->preorderlineid ?>" class="sr linerent-{{ $invoice_line_data->id }}" name="sr[]" id = "sr-<?= $invoice_line_data->preorderlineid ?>" data-book="<?= $invoice_line_data->product_id ?>" value="<?php echo $invoice_line_data->price*$invoice_line_data->quantity;  ?>" data-val="<?php echo $invoice_line_data->price*$invoice_line_data->quantity;  ?>" />
										<?php
										}
									?>
										</td>
										<td>
											<?php $pay_line=($invoice_line_data->amount-$invoice_line_data->discount)*$invoice_line_data->quantity; $payment+=$pay_line;  ?>
											<input  style="width:60px;text-align:center" type="text" data-line="<?= $invoice_line_data->preorderlineid ?>" class="payline order-item-final-{{ $invoice_line_data->id }}" data-item-id="{{ $invoice_line_data->id }}" name="payline[]" id = "payline-<?= $invoice_line_data->id ?>" data-book="<?= $invoice_line_data->product_id ?>" data-val="<?php echo $pay_line/$invoice_line_data->quantity; ?>" value="<?php echo $pay_line; ?>" />
											</td>
										<td><?php echo date('Y-m-d',strtotime($invoice_line_data->updated_at)); ?></td>
									
										<?php  if($order->payment_status=="unpaid"){ ?>
										<td>
											<button  type='button'  style="border:none; padding:0px;" title='delete' class='delete_order_item' data-order-id='<?php echo $invoice_line_data->id ; ?>'><i class="fa fa-trash-o" style="font-size:24px;color:red"></i></button>
											<button type='button' 	style="border:none; padding:0px;" title='edit' class='save_order_item' data-order-id='<?php echo $invoice_line_data->id ; ?>'><i class="fa fa-edit" style="font-size:24px;color:blue"></i></button>
											
										</td>
										<?php } ?>
										</tr>
										<?php
										
						}else{ ///// Paid Invoice
						
							?>
							<tr><td><?php echo ++$inc; ?></td><td><?php echo $isbn; ?></td><td><?php echo $product_name; if($invoice_line_data->transactiontype=="R"){echo ",Return Due Date:".$invoice_line_data->rentDueDate."";} ?></td><td style="border:1px solid;"><?php echo $invoice_line_data->amount; ?></td>
							<td><?php echo $invoice_line_data->sgst; ?></td>
							<td><?php echo $invoice_line_data->cgst; ?></td>
							<td><?php echo $invoice_line_data->gstamount; ?></td>
							<td><?php echo $invoice_line_data->igst; ?></td>
							<td><?php echo $invoice_line_data->baseprice; ?></td>
							<td><?php echo round((($invoice_line_data->discount+$tax)/$invoice_line_data->amount)*100,2).'%'; //echo ($invoice_line_data[$i]['Discount']+$tax); ?></td>
							<td><?php echo $invoice_line_data->quantity; ?></td>
							<td><?php echo $invoice_line_data->transactiontype ?></td>
							<td><?php if($invoice_line_data->transactiontype=="R") echo $invoice_line_data->price*$invoice_line_data->quantity;  ?></td>
							<td><?php echo $pay_line=($invoice_line_data->amount-$invoice_line_data->discount)*$invoice_line_data->quantity; $payment+=$pay_line;  ?></td>
							<td><?php echo $invoice_line_data->updated_at; ?></td>
							<?php  if($order->payment_status=="unpaid" ){ ?>
							<td>
								<button  type='button'  style="border:none; padding:0px;" title='delete' class='delete_order_item' data-order-id='<?php echo $invoice_line_data->id ; ?>'><i class="fa fa-trash-o" style="font-size:24px;color:red"></i></button>
							
								
							</td>
							<?php } ?>
							
							</tr>
							<?php
						
						}//// end for open and close invoice
					}
							}
							
							
							?>
				</table>
				</div>
				<br />
			<?php echo "<p style='float: right;margin-right: 82px;'><Strong>Total Quantity: </Strong><span id='totqty'>".$tqty."</span>"; ?></p>
			<?php echo "Amount exclusive Tax : <span id='totbaseprice'>".$_baseprice."</span>"; ?><br/>
			<?php echo "SGST : <span id='totsgst'>".$_sgst."</span>"; ?><br/>
			<?php echo "CGST: <span id='totcgst'>".$_cgst,"</span>"; ?><br/>
			<?php echo "GST : <span id='totgst'>".$_gst."</span>"; ?><br/>
			<?php echo "IGST: <span id='totigst'>".$_igst."</span>"; ?><br/>
			Total Discount: <span id="totdiscount"><?php echo $_discount+$_igst+$_gst; ?></span>
			<p style="color:red;font-weight:bold"><?php echo "Total Amount inclusive Tax : <span id='totamountinctax'>".$payment."</span>"; ?></p><br/>
	
			<div class="col-sm-12 col-lg-12">
				<div class="col-sm-6 col-lg-6">
					<p id = "pdiscount" <?php if($order->coupon_code==''){echo 'style="display:none"';}else{ echo 'style="display:block"';} ?>>Discounted Amount:&nbsp; <span style="font-weight:bold;" class="discount"><?php if($order->coupon_discount != ''){ echo $order->coupon_discount; } ?></span></p>	
					<p style="color:red;font-weight:bold">Final Amount:&nbsp; <span style="font-weight:bold" class="famount"><?php  if($order->payment_status=="unpaid") {echo $payment-$order->coupon_discount;}else{ echo $order->grand_total; } ?></span></p>
					 <?php  if($order->payment_status=="unpaid" && $order->invoicelookuptype == 'C') { 
						//$advamount = sum($order->amount);
						$advamount = $order->amount;
						?> 
						<p style="color:red;margin-bottom:0px;">Recieved Amount: <?php echo ($advamount); ?></p>
					<p style="color:red">Balance Amount: <?php echo ($payment-$order->coupon_discount - $advamount); ?></p>
					
					<?php 
					$finalPaymentforcredit = $payment-$order->coupon_discount;
					$balPaymentforcredit = $payment-$order->coupon_discount - $advamount;
					} ?>
				</div>
				
				
				
				<div class="col-sm-6 col-lg-6">
					
				</div>
				
			</div>
			
					<br/>


			
			</div>
            <div>
				<label style="float:left; margin-right:10px;">Coupon:</label> 
				<input type="text" name="couponCode" class="coupon" value="<?php if($order->coupon_code!=''){echo $order->coupon_code;} ?>" style="float:left; margin-left:10px;" />
				<a class="removeCoupon" title="Remove Coupon" style="color: red;font-weight: bold;float: left;margin-left: 5px;font-size: 15px;border: 1px solid;width: 20px;height: 28px;padding: 4px;display:none;" href="{{url('admin/ARinvoice_header_workbench/deletecoupon')}}/{{ $order->id }}">X</a>
				<button <?php if($order->payment_status=='unpaid'){echo "";}else{echo "disabled";} ?> class="btn btn-default applycoupon" style="float:left; margin-left:10px;">Apply Coupon</button>
				<span class = "cerror"  style="float:left; margin-left:10px;"></span>
		</div>
        <br>
        <hr>
	
		</br>
		<?php
			if($order->payment_status=="unpaid")
			{
			?>
        <form id="ProductSave" method="post" action="{{ route('order.ProductSave') }}">
				  
			<div id="msg" class="alert alert-danger" style="display:none;"></div>	  
			<table style="width:800px;"><tbody><tr id="booktype">
			<td valign="top">Choose*</td><td><select name="transaction_type" id="transaction_type_id">
				<option value="S">Sale</option>
				<option value="R" selected="selected">Rent</option>

				</select></td></tr>
				<tr id="bookquty"><td valign="top">Quantity*</td>
				<td><input type="number" id="bqty" name="qty" value="1" required="required" min="1" ></td>
				</tr>
				@csrf
				<input type="hidden" name="bundleproduct" id="bundleproduct" value="">
				
				
				<tr><td colspan="2"><div id="changeValues"></div></td></tr>
				<tr>
				
				<td><input type="submit" id="Add_Book" name="save" value="Add Book"></td>
				
				
						<input type="hidden" name="orderId" id="orderId" value="<?php echo $order->id ?>">
						<input type="hidden" name="invoice_number" id="invoicenumber" value="<?php echo $order->ordersource.$order->invoice_number ?>">
					<input type="hidden" name="customerid" id="customerId" value="<?php echo $order->user_id ?>">
					<td>
					<input type="text" name="keyword" id="keyword-box" required="required" style="width: 500px;" placeholder="Search Book"><div id="suggesstion-box"></div>
					<input type="hidden" name="variant" id="variant-box" value="">
					</td></tr>
			</tbody></table>
			<hr>
		</form>
		<?php } ?>
	<div style="float:right;">
		<form method="post" >
			  <!-- coupon function ------------------->
			<input type="hidden" name = "cerror" id = "cerror" value="<?php if($order->coupon_discount != ''){echo "ok"; }else{ echo "error"; } ?>" />
			<input type="hidden" name="wamount" id = "wamount" value="<?php echo $payment; ?>" /> 
			<input type="hidden" value="<?php if($order->coupon_discount != ''){ echo ($payment - $order->coupon_discount);}else{echo $payment; }?>" id = "famount" name = "famount" />
			<input type="hidden" value="<?php if($order->coupon_code == ''){echo "";}else{echo $order->coupon_discount ;} ?>" id = "discount" name = "discount" />
			<input type="hidden" value="<?php if($order->coupon_code == ''){echo "0";}else{echo $order->coupon_code; } ?>" id = "couponCode" name = "couponCode" />
			<!-- <input type="hidden" value="<?php echo $virtuemart_product_ids; ?>"name="virtuemart_produ"discountct_ids" id="virtuemart_product_ids" /> -->
		<!--  end coupon ---------------------->
			</form></div><table>
	
			
				</table><table><tbody><tr><td>
				<?php ///change for status;
		//print_r($_SESSION);
		
		if($order->payment_status!="unpaid"){
		 ?>
		<label>Change Status:</label>
		<select id="changestatus" class="changestatus" name="changestatus" >
		<option value="unpaid" <?php if($order->payment_status=="unpaid"){echo "selected";} ?>>Open</option>
		<option value="cancel"  <?php if($order->payment_status=="cancel"){echo "selected";} ?>>Cancel</option>
		<option value="paid"  <?php if($order->payment_status=="paid"){echo "selected";} ?>>Paid</option>
		</select>
		<?php } ?>
		<?php 
		//////////////Modal button for credit memo /////////////////////
		 if($order->invoicelookuptype == 'C') { ?>
		<button type="button" class="btn btn-info btn-mm" data-toggle="modal" data-target="#myModal">Deposit Advance</button>
		<?php } ?>
		<select name="ModeOfPayment" id="myselect">
		<option value='Clearing' <?php if($order->payment_type == "Clearing"){echo "selected";} ?>>Clearing/Cash</option>
		<option value='Check' <?php if($order->payment_type =="Check"){echo "selected";} ?>>Check</option>
		<option value='CreditCard' <?php if($order->payment_type=="CreditCard"){echo "selected";} ?>>Credit/Debit Card</option>
		<option value='Electronic' <?php if($order->payment_type=="Electronic"){echo "selected";} ?>>Electronic</option>
		<option value='Wire' <?php if($order->payment_type=="Wire"){echo "selected";} ?>>Wire</option>
		<option value='paytm' <?php if($order->payment_type=="paytm"){echo "selected";} ?>>Paytm</option>
		<option value='Check-cce' <?php if($order->payment_type=="Check-cce"){echo "selected";} ?>>Check-CCE</option>
		</select>
		<input type="text"  placeholder="paydate" value="{{ $order->paydate }}" name="psaydate" id="paydate" />
		Description:
		<textarea name="description" id="desc"><?php echo $order->description; ?></textarea>
		<select name="action" onchange="PrintDiv(this.value)">
			<?php  if($order->payment_status=="unpaid") {?>
			<option value="1" >Close and Print Invoice</option>
					<?php } ?>
							<option value="2">Print Invoice</option>
			
			<option value="3" selected="selected">Close And Email Invoice</option>
			<option value="4">Save Only Invoice</option>
		</select> 
		
		<?php
		$allopen = \App\Order::where('invoicelookuptype','!=','C')->where('payment_status','unpaid')->where('id','!=', $order->id);
		
		//print_r($allopen); InvoiceLookupType
		?>
		<?php 
		//////////////for credit memo will not close AR till Paument has not be done/////////////////////
		 if($order->invoicelookuptype == 'C') { 
			 if(round($balPaymentforcredit) <= 0){
			 ?>
				<input type="submit" value="Save Description" onclick=savedesc(); name="save_d"  /> 
				<input type="submit" value="Ok" name="close"  />
				<input type="hidden" name="payment_amount_close" value="<?php echo $payment; ?>" />
				<?php 
				}else{ ?>
				<input type="submit" value="Save Only" name="saveonly"  />
				<?php
				echo "<p style='color:red;float:right;'>You can not close AP Invoive bill untill Full payment will not be recieved.</p>";
				}
		}else{ ?>
				<input type="submit" value="Save Description" onclick=savedesc(); name="save_d"  /> 
				<input type="submit" value="Ok" id="Save" name="close"  />
				<input type="hidden" name="payment_amount_close" value="<?php echo $payment; ?>" />	
					
		<?php	} ?>
		
		</td></tr>
		<tr><td>
		
		<input type="hidden" class="couponid" name="couponid">
		<input type="hidden" class="coupondiscount" name="coupondiscount">
		<input type="hidden" class="finalamount finalamt" name="finalamount">
		</td></tr>
		</tbody></table>
		</form>
       
        </div>
    </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style='width:800px;'>

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Advance deposite</h4>
      </div>
      <div class="modal-body">
       <!-- Modal body-->
     <form method='post' action = "{{ route('order.advancepay')}}">
  @csrf
   
    <?php
	$ssql = DB::table('arcredit')->where('invoiceid',$order->id)->get();
   $rrr = array();
   foreach($ssql as $ql)
   {
	   $item = array(
		   'amount' => $ql->amount,
		   'cheque' => $ql->cheque,
		   'paymentdate' => $ql->paymentdate,
		   'description' => $ql->description,
		   'clearancedate' => $ql->clearancedate,
		   'ModeOfPayment' => $ql->ModeOfPayment,
		   'updatedby' => $ql->updatedby
	   );
	   array_push($rrr,$item);
   }
    //print_r($rrr);
    //$finalPaymentforcredit = $payment-$invoice_header_data[0]['coupon_discount'];
	//$balPaymentforcredit = $payment-$invoice_header_data[0]['coupon_discount'] - $advamount;
	foreach($rrr as $r):
	echo "<div><p style='color:red;margin-bottom:0px;line-height:16px'>Paid Amount: ".$r['amount']." on ".$r['paymentdate']." Clearance Date:".$r['clearancedate'].", Mode Of Payment:".$r['ModeOfPayment']." and recieved By ".$r['updatedby'];
	if($r['cheque'] != ''){
		echo ", Cheque Number: ".$r['cheque'];
		}
	echo "</p>";
	echo "<p style='margin:top:0px;margin-bottom:10px;color:red;line-height:16px'>Description: ".$r['description']."</p></div>";
	endforeach;
    ?>
    <div class="row">
    <div class="form-group col-sm-8">
    <label for="advance" style="line-height:20px">Advance Amount(Balance: <?php echo $balPaymentforcredit; ?>):</label>
    <input type="number" required class="form-control" max="<?php echo round($balPaymentforcredit); ?>" value="" id="advance" name="advance" />
  </div>
  <div class="form-group col-sm-4">
    <label for="ModeOfPayment">ModeOfPayment </label>
    <select id="ModeOfPayment" required class="form-control" name="ModeOfPayment">
		<option value='Clearing'>Clearing/Cash</option>
		<option value='Check'>Check</option>
		<option value='CreditCard'>Credit/Debit Card</option>
		<option value='Electronic'>Electronic</option>
		<option value='Wire'>Wire</option>
		<option value='paytm'>Paytm</option>
		<option value='Check-cce'>Check-CCE</option>
	</select>
  </div>
  </div>
  
  <div class="row">
  <div class="form-group col-sm-6">
    <label for="clearancedate">Clearance Date:</label>
    <input type="text" required class="form-control"  id="clearancedate" name="clearancedate" />
  </div>
  
   <div class="form-group col-sm-6">
    <label for="cheque">Cheque:</label>
    <input type="text" required class="form-control"  id="cheque" name="cheque" />
  </div>
  
  </div>
  <input type="hidden" name="balance" value="<?php echo $balPaymentforcredit; ?>" />
  <input type="hidden" name="id" value="<?php echo $order->id; ?>" />
  <input type="hidden" name="invoice_number" value="<?php echo $order->invoice_number; ?>" />
  <div class="form-group col-sm-12">
    <label for="notes">Notes:</label>
    <textarea class="form-control" id="notes" name="notes"  maxlength="100"></textarea>
  </div>
  <div class="col-sm-12">
  <button type="submit" class="btn btn-primary" value="advancepay" <?php if(round($balPaymentforcredit) == 0){ echo "disabled";} ?> name="advancepay">Submit</button>
</div>
</form> 
</br></br>
       <a href="creditinvoice.php" target="_blank" class="btn btn-info">Print advance</a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

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
  $('#paydate').datepicker({ dateFormat: 'yy-mm-dd'});
  $('#clearancedate').datepicker({ dateFormat: 'yy-mm-dd'});
$('#keyword-box').val('');
$('#Save').click(function(){
	var id = {{ $order->id }};
	var modepayment = $( "#myselect option:selected" ).text();;
	var paydate = $('#paydate').val();
	var desc = $('#desc').val();
	var status = $( "#changestatus option:selected" ).text();
	//alert(status);
	var payment = $('.famount').text();
	 $.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/ARinvoice_header_workbench/InvoiceEmail') }}',
		data: { id:id, modepayment:modepayment, status:status,paydate:paydate, desc:desc, status:status, payment:payment, _token: "{{ csrf_token() }}" },
        //dataType: 'json',
        success: function (data) {
			alert("Email send");
			location.reload();
		}
		});
});
  function savedesc()
  {
	 var desc = $('#desc').val();
	 var id = {{ $order->id }};
	 $.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/ARinvoice_header_workbench/getDescription') }}',
		data: { desc:desc, id:id, _token: "{{ csrf_token() }}" },
        //dataType: 'json',
        success: function (data) {
			//console.log(data);
			alert('send Sms on your Mobile');
			//$('#desc').val();
		}
	 });
	 
  }

  $('.changestatus').change(function(e){
	var inv = "<?php echo $order->id; ?>";
	$.ajax({
				async : false,
				url : '{{url('admin/ARinvoice_header_workbench/statusChangeOrder')}}',
				type : "POST",
				data : {'orderid' : inv, 'status' : $(this).val(),  _token: "{{ csrf_token() }}" },
				dataType : 'text',
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
					if(dataType == '1'){
						location.reload();
						}
				}
			});
	});
// AJAX call for autocomplete 
$(document).ready(function(){
	$("#keyword-box").keyup(function(){
	var trxn_type=document.getElementById("transaction_type_id").value;
	$.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/ARinvoice_header_workbench/getbookdetail') }}',
		data: { keyword: $(this).val(),transaction_type: trxn_type, invoice_type: 's', store_id: '0',role:'1', _token: "{{ csrf_token() }}" },
        //dataType: 'json',
        success: function (data) {
            data = JSON.parse(data);
			if(data.status == 'yes')
			{
				$("#suggesstion-box").show();
				$("#suggesstion-box").html(data.html);
				$("#keyword-box").css("background","#FFF");
			}
			else
			{
				$("#suggesstion-box").html('<b>No Record</b>');
			}
           
        },error:function(){ 
             console.log(data);
        }
    });
	
		});
	//});
});
$("body").on('click','.var_prod', function(){
      return false; // prevents default action
      });

$("body").on('click','.selectbook_prod', function(){
	var _val = $(this).attr('data-prod');
	var _varn = 'null';
	var _tarn = $(this).attr('data-tran');
	var _type = $(this).attr('data-type');
	var _inv = $(this).attr('data-inv');

	selectbook(_val, _varn, _tarn, _type, _inv);
});

$("body").on('click','.selectbook_var', function(e){
	var _val = $(this).attr('data-prod');
	var _varn = $(this).attr('data-varn');
	var _tarn = $(this).attr('data-tran');
	var _type = $(this).attr('data-type');
	var _inv = $(this).attr('data-inv');

	selectbook(_val, _varn, _tarn, _type, _inv);

	e.stopPropagation();

});


//To select book name
function selectbook(val,vart,trxn,ptype,invoice_type) {
//alert(vart);
$("#suggesstion-box").hide();
	if(ptype == 'book'){
$('#bundleproduct').val("0");
$("#keyword-box").val(val);
$("#variant-box").val(vart);
$('#bookquty').show();
$('#booktype').show();
getBookDetails(val,vart,trxn,invoice_type);
 //$("#transaction_type_id").addClass("disable-select");
}
if(ptype == 'bundle'){
	$("#keyword-box").val(trxn);
	$('#bundleproduct').val(val);
	$('#bookquty').hide();
	$('#booktype').hide();
	$('.bmrp').hide();
	$('.brent').hide();
	}
}

function PrintDiv(x) { 

	
	if(
		x == 1 || x == '1' ||
		x == 2 || x == '2'
	){
  		var divToPrint = document.getElementById('divtoprint');
		window.open("{{ url('admin/ARinvoice_header_workbench/ARInvoice')}}/{{$order->id}}", '_blank', 'width=900,height=700');
	}
	
	$.ajax({
		url: "{{ url('admin/ARinvoice_header_workbench/getInvoicePrint')}}",
		type: 'POST',
		data: {
			"_token": "{{ csrf_token() }}",
			"_val": x,
			"_orderid": {{$order->id}}
		}
	}).done(function(data) {
		console.log(data);
		
		if(x == '4' || x == 4)
		{
			// alert("download");

			var link = document.createElement("a");
			// If you don't know the name or want to use
			// the webserver default set name = ''
			link.setAttribute('download', name);
			link.href = data;
			document.body.appendChild(link);
			link.click();
			link.remove();
		}

		if(x==3)
		{
			alert('close and email invoice');
		}
		location.reload();
		
	});
}


$(".mcoupon").select2({
  placeholder: "Select product categories"
});


 $('.quantity').change(function(e){
	 var _id = $(this).attr('data-item-id');

	 // alert(_id);

	// get base price
	var _price = $(".order-item-price-"+_id).val();
	var _base_price = $(".order-item-base-"+_id).val();
	var _qty = $(".order-item-qty-"+_id).val();

	var _final_amount = _base_price * _qty;

	var _discount = $(".order-item-discount-"+_id).val();
	
	var _final_amount = _base_price * _qty;

	if(_discount > 0)
	{
		_discount = _final_amount * _discount / 100;

		_final_amount = _final_amount - _discount;
	}

	$(".order-item-final-"+_id).val(_final_amount);
	$('#payline-'+_id).val(_final_amount);
	// 

	// var line = $(this).attr('data-line');
	// var qty = $(this).val();
	// //alert(qty);
	// var payline = $('#payline-'+line).attr('data-val');
	// var newpayline = parseFloat(payline) * parseInt(qty);
	// $('#payline-'+line).val(newpayline);
	
	});
	
$('.TransactionType').change(function(e){
	var _id = $(this).attr('data-item-id');
	var _type = $(".order-item-type-"+_id).val();
	var _rid = $(this).attr('data-item-id');
	if(_type == 'R'){
		$('.linerent-'+_rid).attr('type','text');
		}else{
		$('.linerent-'+_rid).attr('type','hidden');	
		}

	// var line = $(this).attr('data-line');
	// var salerent = $(this).val();
	// if(salerent == 'R'){
	// 	$('#sr-'+line).attr('type','text');
	// 	}else{
	// 	$('#sr-'+line).attr('type','hidden');	
	// 	}
	
	});

$('.applycoupon').click(function(e){
	e.preventDefault();
	var amt = $('#wamount').val();
	//alert(amt);
	var coupon = $('.coupon').val();
	
	$.ajax({
				async : false,
				url: '{{ url('admin/ARinvoice_header_workbench/applycoupon') }}',
				type : "POST",
				data : {'amount' : amt, 'coupon' : coupon,'order_id':'<?php echo $order->id; ?>', 'customerId':'<?php echo $order->user_id; ?>', _token: "{{ csrf_token() }}" },
				dataType : 'text',
				timeout : 1000,
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
					if(dataType == 3){
						$('.cerror').html('<p style="color:red;font-weight:bold">Order Amout has not meet with coupon minimum amount requirement.</p>');
						$('#cerror').val('error');
						$('.discount').html(0);
						$('.famount').html(parseFloat(amt));
						$('#discount').val(0);
						$('#famount').val(parseFloat(amt));
						$('#pdiscount').hide();
						$('#couponCode').val('');
					}else if(dataType == 4){
						$('.cerror').html('<p style="color:red;font-weight:bold">Empty Request.</p>');
						$('#cerror').val('error');
						$('.discount').html(0);
						$('.famount').html(parseFloat(amt));
						$('#discount').val(0);
						$('#famount').val(parseFloat(amt));
						$('#pdiscount').hide();
						$('#couponCode').val('');
					}else if(dataType == 5){
						$('.cerror').html('<p style="color:red;font-weight:bold">Coupon does not exist.</p>');
						$('#cerror').val('error');
						$('.discount').html(0);
						$('.famount').html(parseFloat(amt));
						$('#discount').val(0);
						$('#famount').val(parseFloat(amt));
						$('#pdiscount').hide();
						$('#couponCode').val('');
					}else if(dataType == 6){
						$('.cerror').html('<p style="color:red;font-weight:bold">Coupon does not applicable for this customers.</p>');
						$('#cerror').val('error');
						$('.discount').html(0);
						$('.famount').html(parseFloat(amt));
						$('#discount').val(0);
						$('#famount').val(parseFloat(amt));
						$('#pdiscount').hide();
						$('#couponCode').val('');
					}else if(dataType == 9){
						$('.cerror').html('<p style="color:red;font-weight:bold">Coupon does not applicable.</p>');
						$('#cerror').val('error');
						$('.discount').html(0);
						$('.famount').html(parseFloat(amt));
						$('#discount').val(0);
						$('#famount').val(parseFloat(amt));
						$('#pdiscount').hide();
						$('#couponCode').val('');
					}else{
						$('.cerror').html('<p style="color:green;font-weight:bold">Coupon has Applied successfully</p>');
						$('.removeCoupon').show();
						$('#cerror').val('ok');
						$('.discount').html(dataType);
						$('.famount').html(parseFloat(amt) - parseFloat(dataType));
						$('#discount').val(dataType);
						$('#famount').val(parseFloat(amt) - parseFloat(dataType));
						$('#pdiscount').show();
						$('#couponCode').val(coupon);
						$('.applycoupon').css('visibility','hidden');
						}
	
				}
			});
	
	});	
	$('#msg').hide();

	//transaction_type_id
$('#transaction_type_id').change(function(e){
	var mobile				= $('#keyword-box').val();
	var vart				= $('#vart').val();
	if(mobile==''){return false;}
	var trxn				= $('#transaction_type_id').val();
	var invoice_type='<?php echo $order->invoicelookuptype; ?>';
	$qty_a=$( "input[name='qty_a']" ).val();
	$('#bqty').attr('max',$qty_a);
	// if(trxn == 'R'){
	// 	$('#bqty').attr('max',1);
	// 	}else{
	// 	$('#bqty').attr('max',1000);	
	// 		}				
$.ajax({
				async : false,
				url : '{{ url('admin/ARinvoice_header_workbench/get_book_detail_invoice') }}', 
				type : "POST",
				data : {'keyword' : mobile,'vart' : vart, 'trxn' : trxn, 'invoice_type':invoice_type,'customerId':'<?php echo $order->user_id; ?>','orderId':'<?php echo $order->id; ?>', _token: "{{ csrf_token() }}" },
				dataType : 'text',
				timeout : 1000,
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
				//alert(dataType);
				$('#changeValues').html();
				if(dataType != 'out')
				{
					$('#changeValues').html(dataType);
				}
			
				if(dataType == 'out')
					{
						$('#msg').show();
						$('#Add_Book').prop('disabled', true);
						$('#msg').html('<b>Product Out Of Stock</b>');
					}

				}
			});
	});

	
function getBookDetails(val,vart,trxn,invoice_type){
	//alert(vart);
var mobile				= $('#keyword-box').val();
//alert(trxn);
//var trxn				= $('#transaction_type_id').val();
if(invoice_type == 'C'){trxn = 'S';$('#transaction_type_id').prop('readonly',true);}
$('#Add_Book').prop('disabled', false);			
$.ajax({
				async : false,
				url: '{{ url('admin/ARinvoice_header_workbench/get_book_detail_invoice') }}', 
				type : "POST",
				data : {'keyword' : mobile,'vart' : vart, 'trxn' : trxn, 'invoice_type':invoice_type,'customerId':'<?php echo $order->user_id; ?>','orderId':'<?php echo $order->id; ?>', _token: "{{ csrf_token() }}" },
				dataType : 'text',
				timeout : 1000,
				
				success:function(dataType) {
				//console.log(dataType);
				$('#changeValues').html();
				if(dataType != 'out')
				{
					$('#changeValues').html(dataType);
				}
					$('#transaction_type_id').val(trxn);
					// $qty_a=$( "input[name='qty_a']" ).val();
					// 	$('#bqty').attr('max',$qty_a);
					if(trxn == 'R'){
						$qty_a=$( "input[name='qty_a']" ).val();
						$('#bqty').attr('max',$qty_a);
						}
						
					if(invoice_type == 'C'){trxn = 'S';$('#transaction_type_id').prop('readonly',true);$("#transaction_type_id option[value='R']").remove();}
					$("#keyword-box").focus();
					if(dataType == 'out')
					{
						//alert();
						$('#msg').show();
						$('#msg').html('<b>Product Out Of Stock</b>');
						$('#Add_Book').prop('disabled', true);
						
					}
				}

			});
}

// $( ".test li" ).mouseenter(function() {
// 	//alert();
// 	$(this).closest("li").css("background-color","red");
// })
//   .mouseleave(function() {
// 	$(this).closest("li").css("background-color","yellow");
// });

$('.sgst,.cgst,.igst,.gst,.discount').prop('readonly',true);
$('.sgst,.cgst,.igst,.gst,.discount').css('border','none');
$('.sgst,.cgst,.igst,.gst,.discount').css('background', 'none');

$(".save_order_item").click(function(){
	var _item_id = $(this).attr('data-order-id');

	var _price = $(".order-item-price-"+_item_id).val();
	var _base_price = $(".order-item-base-"+_item_id).val();
	var _qty = $(".order-item-qty-"+_item_id).val();
	var _type = $(".order-item-type-"+_item_id).val();
	var _discount = $(".order-item-discount-"+_item_id).val();
	var _linerent = $('.linerent-'+_item_id).val();
	var _payline = $('#payline-'+_item_id).val();
	var _sgst = $('#sgst-'+_item_id).val();
	var _cgst = $('#cgst-'+_item_id).val();
	var _gst = $('#gst-'+_item_id).val();
	var _igst = $('#igst-'+_item_id).val();
	
	var _final_amount = _base_price * _qty;

	if(_discount > 0)
	{
		_discount = _final_amount * _discount / 100;

		_final_amount = _final_amount - _discount;
		
	}

	//alert(_final_amount);

	 //alert(_final_amount+ " :" +_item_id + " : " + _price + " : " + _base_price + " : " + _qty + " : " + _type);

	// save

	$.post("{{ url('admin/ARinvoice_header_workbench/updateline') }}", {
			"_token": "{{ csrf_token() }}",
			"orderid": "{{$order->id}}",
			 "id": _item_id,
			 "TransactionType": _type,
			 "quantity": _qty,
			 "amount": _price,
			 "baseprice": _base_price,
			 "final_amount": _final_amount,
			 "sr":_linerent	,
			 "sgst":_sgst,
			 "cgst":_cgst,
			 "gst":_gst,
			 "igst":_igst,
			 "discount":_discount,
			 "payline":_payline
		}
	)
	.done(function( data ) {
		data = JSON.parse(data);
		//console.log(data);
			$('#payline-'+_item_id).attr('data-val',_payline);
			$('#discount-'+_item_id).val(data.discount);
			$('#baseprice-'+_item_id).val(data.baseprice);
			alert(data.msg);
			setTimeout(function(){// wait for 5 secs(2)
           location.reload(); // then reload the page.(3)
      }, 1000); 
		
	});
	
});

$(".delete_order_item").click(function(){
	
	var _item_id = $(this).attr('data-order-id');
	//alert(_item_id);
	$.post("{{ url('admin/ARinvoice_header_workbench/destroyLine') }}", {
			"_token": "{{ csrf_token() }}",
			 "id": _item_id,
			}
	)
	.done(function( data ) {
		if(data == "done")
		{
			//$(".tr_parent_"+_item_id).remove();
			alert('Order line has been deleted successfully');
			setTimeout(function(){// wait for 5 secs(2)
           location.reload(); // then reload the page.(3)
      }, 1000); 

		}
	});
});	

</script>
@endsection