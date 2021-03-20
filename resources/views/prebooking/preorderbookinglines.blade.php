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
     
            <h3 class="panel-title">{{__('Pre Order Booking Lines')}}</h3>
			
        </div>
        <div class="panel-body">
		<div>
		@if(session()->get('msg') != null)
			<div class="alert alert-danger">{{ session()->get('msg') }}</div>
			@endif
		</div>
        <div id="divtoprint">
				<table>
					<tbody><tr>
					<td>
					<table style="width:800px;" border="0">
						<tbody><tr><td>Order Number:</td><td>{{$order->invoicenumber}}</td>
						<td>Customer ID:</td><td>{{$order->customerid}}</td></tr>
						<tr><td>Order Type:</td><td>{{$order->invoicelookuptype}}</td>
						<td>Order Date:</td><td>{{$order->invoicedate}}</td></tr>
						<tr><td>Order Status:</td><td>
						@if($order->status == 'O')
						{{_('Open')}}
						@elseif($order->status == 'C') {{_('Cancelled')}}
						@else {{_('Closed')}}
						@endif
						</td>
						<td>Description:</td><td><{{$order->description}}/td></tr>
                        <?php
                        $custid = $order->customerid;
                        $customer = \App\Customer::where('id',$custid)->first();
                        $user = \App\User:: where('id',$customer->user_id)->first();
                       
                        ?><tr><td>Customer Mobile</td><td>@if(isset($user->phone)){{$user->phone}}@endif</td>
                        <td>gstin:</td><td>@if(isset($user->gstin)){{$user->gstin}}@endif</td>
						
                        </tr>	
						</tbody>
					</table><br>

					</td>
					
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
                        <th>Delivered Quaninty</th>
                        <th>Available Quaninty</th>
                       
						<th>Added date</th>
                        <th>Check</th>
                        <th></th></tr>
							<?php
							$inc=0;
							$payment=0;$tqty=0;
							$virtuemart_product_ids = '';
							$_igst=$_sgst=$_gst=$_cgst=$_baseprice=0;$_discount=0;
							$invoice_line_da = \App\PrebookingLine::where('invoiceid',$order->invoiceid)->get();
						
						
							if(sizeof($invoice_line_da)>0) {
							//die("bcddd");
							foreach($invoice_line_da as $invoice_line_data ){
                                $isbn='';
                              //  $product_name ='';
								$product = \App\Product::where('id',$invoice_line_data->itemid)->first(); 
								
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
							$_gst = $_gst+$invoice_line_data->gst*$invoice_line_data->quantity;
							$_baseprice = $_baseprice+$invoice_line_data->baseprice*$invoice_line_data->quantity;
							$_discount = $_discount + $invoice_line_data->discount*$invoice_line_data->quantity;
							$tax = $invoice_line_data->gst + $invoice_line_data->igst;
							//$tax = $invoice_line_data[$i]['gst'] + $invoice_line_data[$i]['igst'];
							$tqty = $tqty+$invoice_line_data->quantity;
							//die('tyhgfj');
						
						
							?>
							<tr><td><?php echo ++$inc; ?></td><td><?php echo $isbn; ?></td><td><?php echo $product_name; if($invoice_line_data->transactiontype=="R"){echo ",Return Due Date:".$invoice_line_data->rentDueDate."";} ?></td><td style="border:1px solid;"><?php echo $invoice_line_data->amount; ?></td>
							<td><?php echo $invoice_line_data->sgst; ?></td>
							<td><?php echo $invoice_line_data->cgst; ?></td>
							<td><?php echo $invoice_line_data->gst; ?></td>
							<td><?php echo $invoice_line_data->igst; ?></td>
							<td><?php echo $invoice_line_data->baseprice; ?></td>
							<td><?php echo round($invoice_line_data->discount+$tax) ?></td>
							<td><?php echo $invoice_line_data->quantity; ?></td>
							<td><?php echo $invoice_line_data->transactiontype ?></td>
							<td><?php if($invoice_line_data->transactiontype=="R") echo $invoice_line_data->price*$invoice_line_data->quantity;  ?></td>
							<td><?php echo $pay_line=($invoice_line_data->amount-$invoice_line_data->discount)*$invoice_line_data->quantity; $payment+=$pay_line;  ?></td>
							<td><?php echo $invoice_line_data->delivered_qty; ?></td>
                            <td>
                            <?php 
                            $checkavailqty = \App\Product::where('id', $invoice_line_data->itemid)->first();
                            echo $availabeQty = $checkavailqty->current_stock;
                            ?>
                            </td>
                           
                            <td><?php echo $invoice_line_data->lastupdateddate; ?></td>
                            <td style="border:1px solid;">
                            <input type="checkbox" name="chkbx[]" data-avlqty="<?php echo $availabeQty; ?>" <?php if(($invoice_line_data->quantity-$invoice_line_data->delivered_qty) == 0){echo "Disabled";} ?> data-qty="<?php echo ($invoice_line_data->quantity-$invoice_line_data->delivered_qty); ?>" data-amountsingle="<?php echo $invoice_line_data->baseprice;  ?>" data-amount="<?php echo $pay_line;  ?>" value="<?php echo $invoice_line_data->lineid; ?>" id="chkbx<?php echo $invoice_line_data->lineid; ?>" class="chkbx" />
                            </td>
							<?php 
			                if(($invoice_line_data->quantity-$invoice_line_data->delivered_qty) != 0){ ?>
							<td>
								<button  type='button'  style="border:none; padding:0px;" title='delete' class='delete_order_item' data-order-id='<?php echo $invoice_line_data->lineid ; ?>'><i class="fa fa-trash-o" style="font-size:24px;color:red"></i></button>
							
								
							</td>
							<?php } ?>
							
							</tr>
							<?php
						
						
					}
							}
							
							
							?>
				</table>
				</div>
				<br />
                <div style="clear:both" ></div>
			<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-12">
                        <?php echo "<div style='margin-top:10px;'><label>Total Amount to be pay : </label> &nbsp;<span>".$payment."</span></div>"; ?>
                        </div>
			<div class="col-sm-12">
                <div class="col-sm-6"> 
                    <?php echo "Amount exclusive Tax : ".$_baseprice; ?><br/>
                <?php echo "SGST : ".$_sgst; ?><br/>
                <?php echo "CGST: ".$_cgst; ?><br/>
                <?php echo "GST : ".$_gst; ?><br/>
                <?php echo "IGST: ".$_igst; ?><br/>
                Total Tax: <?php echo $_discount+$_gst+$_igst; ?><br/>
                <div id = "pdiscount" <?php if($order->coupon_code==''){echo 'style="display:none"';}else{ echo 'style="display:block"';} ?>><label>Discounted Amount:&nbsp;</label> <span style="font-weight:bold;" class="discount"><?php if($order->coupon_discount != ''){ echo $order->coupon_discount; } ?></span></div>	
                <?php $fpayment = 0; ?>
                <div><label>Final Amount(incl. Tax):&nbsp;</label> <span style="font-weight:bold" class="famount"><?php  if($order->status=="O") { if($order->amount == '' || $order->amount == 0){echo $payment;$fpayment=$payment;}else{ echo $order->amount; $fpayment=$order->amount;}}else{ echo $order->amount; $fpayment=$order->amount; } ?></span></div>
                <?php
            $paidamount = \App\PrebookingPayment::select('paid')->where('invoiceid',$order->invoiceid)->sum('paid');
            
                if($paidamount == ''){$paidamount = 0;}
                ?>
                <div><label>Paid Amount:&nbsp;</label><span><?php echo $paidamount; ?></span></div>
                <div><label>Balanced Amount:&nbsp;</label><span><?php echo $blanceamt = $order->amount - ($paidamount+$order->creditamt); ?></span></div>
                <?php if($order->invoicelookuptype == 'C'){ ?>
                    
                    
                <div style="color:green"><label>Credit Amount:&nbsp;</label><span><?php echo $order->creditamt; ?></span></div>
                <?php 
                $paidamount = abs($blanceamt);
            }
                //print_r($invoice_header_data);
                //endif;
                ?>
                </div>
            <?php $allarr = \App\Order::select('id','invoice_number','payment_status')->where('preorderid',$order->invoiceid)->get();
           
				if(sizeof($allarr)>0){
				 ?>
			<div class="col-sm-6">
                <div style="border-left:1px solid #ccc;">
                    <label>Generated AR:</label>
                    <div class="arlist">
                    <ul>
                    <?php foreach($allarr as $pr): ?>
                    <li><a target="_blank" href="{{url('admin/PreOrderBooking/arinvoiceprebookview')}}/<?php echo $pr->invoice_number; ?>"><?php echo $pr->invoice_number; ?></a> - <?php if($pr->payment_status == 'paid'){ echo "colsed"; } if($pr->payment_status == 'unpaid'){ echo "Open"; } if($pr->payment_status == 'close'){ echo "Cancel"; } ?></li>
                    <?php  endforeach; ?>
                    </ul>
                    </div>
                </div>
			</div>
			
			<?php } ?>
			<div class="col-sm-6">
				<!--------- save description ------------------>
			
				<div class="form-group">
				<label for="description">Description</label>
				<textarea  class="form-control" placeholder="Description" id="description" name="description"><?php echo $order->description; ?></textarea>
				</div>
				<button type="submit" class="btn btn-purple" value="Save Description" onclick=savedesc();  name="save_d" >Save Description</button>
				
			
			</div>
			</div>
			
			</div>
			
				</div>
           </div>
			
           <div class="col-sm-12" >          
                <div>
                    <label style="float:left; margin-right:10px;">Coupon:</label> 
                    <input type="text" name="couponCode" class="coupon" value="<?php if($order->coupon_code!=''){echo $order->coupon_code;} ?>" style="float:left; margin-left:10px;" />
                    <a class="removeCoupon" title="Remove Coupon" style="color: red;font-weight: bold;float: left;margin-left: 5px;font-size: 15px;border: 1px solid;width: 20px;height: 28px;padding: 4px;display:none;" href="{{url('admin/PreOrderBooking/deletecoupon')}}/{{ $order->invoiceid }}">X</a>
                    <button  <?php if($order->status=="O"){echo "";}else{echo "disabled";} ?> class="btn btn-primary applycoupon" style="float:left; margin-left:10px;">Apply Coupon</button>
                    <span class = "cerror"  style="float:left; margin-left:10px;"></span>
                </div>
            </div>
        <div class="col-sm-12">
			<div style="padding-top:20px;">
            <?php
                $pstatus = $order->status;
                
				// check status is closed, cancel or open
				?>
			<?php if($order->invoicelookuptype == 'C'){ ?>
			<button class="btn btn-primary" style="float: left;margin-right: 20px;" class="printme" id="PrtCr" > Print Credit </button>
			<?php } ?>
			<button style="float: left;margin-right: 20px;" <?php if($pstatus == 'Y' || $pstatus == 'C'){echo "disabled";} ?> type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">PrePayment</button>
			<button style="float: left;margin-right: 20px;" class="btn btn-primary" id="PrtInv">Print Invoice</button>
			<button style="float: left;margin-right: 20px;" class="btn btn-purple" id="PrtAdv">Print Advance</button>
			<input type="hidden" id="balance_amt" name="balance" value="<?php echo $order->amount - $paidamount; ?>" />
			
			<?php
			$bal = '';
			if($paidamount > 0 || $order->invoicelookuptype=="C"){
			$bal = $blanceamt;//$invoice_header_data[0]['Amount'] - $paidamount;
			}
				
			 if($order->status=="O"){	
			?>
			<a class="btn btn-danger" style="float:left; margin-right:20px;" href="{{url('admin/PreOrderBooking/Cancelorder')}}/<?php echo $order->invoiceid ?>">Cancel Order</a>
			<?php  } ?>
			<form method="post" action="{{route('PreOrderBooking.GenerateAr')}}">
			@csrf
			<input type="hidden" name="balance" value="<?php echo $bal; ?>" />
			<input type="hidden" name="paidinv" id="paidinv" value="<?php echo $paidamount; ?>" />
			<input type="hidden" name="discountamt" id="discountamt" value="<?php echo $order->coupon_discount; ?>" />
			<input type="hidden" name="selectedbook" value="" id="selectedbook" class="selectedbook" />
			<input class="btn btn-primary" id="arinv" style="float:left"   <?php  if($bal>0 || ($paidamount == 0 && $order->invoicelookuptype == 'S')){ echo "disabled";}else if(($fpayment == 0 || $fpayment < $paidamount) && $order->invoicelookuptype == 'C'){ echo "disabled";} ?> type="submit" <?php if($pstatus == 'C'){echo "disabled";} ?> value="Generate AR" name="arinv"  />
			<input type="hidden" name="invoice_number" id="invoicenumber" value="<?php echo $order->invoicenumber ?>">
			</form>
        </div>
        </div>
		<div style="clear:both" ></div>
		<hr>
        <div class="col-sm-3">
			
		<label>Change Status:</label>
		<select id="changestatus" class="changestatus" name="changestatus" >
		<option value="O" <?php if($order->status=="O"){echo "selected";} ?>>Open</option>
		<option value="C"  <?php if($order->status=="C"){echo "selected";} ?>>Cancel</option>
		<option value="P"  <?php if($order->status=="P"){echo "selected";} ?>>Paid</option>
		</select>
        </div>
        <div style="clear:both" ></div>
<hr>
        <?php
			if($order->status=="O")
			{
			?>
            <div class="addbookform" style="margin-top:20px;padding-bottom:20px;border-bottom:1px solid #999;">
				<h3 style="color: #ef6464;font-weight: bold;">Add Books to Line</h3>
        <form id="ProductSave" method="post" action="{{ route('PreOrderBooking.ProductSave') }}">
				  
			<div id="msg" class="alert alert-danger"></div>	  
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
				
				
						<input type="hidden" name="orderId" id="orderId" value="<?php echo $order->invoiceid ?>">
						<input type="hidden" name="invoice_number" id="invoicenumber" value="<?php echo $order->invoicenumber ?>">
					<input type="hidden" name="customerid" id="customerId" value="<?php echo $order->customerid ?>">
					<td>
					<input type="text" name="keyword" id="keyword-box" required="required" style="width: 500px;" placeholder="Search Book"><div id="suggesstion-box"></div>
					<input type="hidden" name="variant" id="variant-box" value="">
					</td></tr>
			</tbody></table>
			<hr>
		</form>
        <div style="clear:both" ></div>
		
        </div>
		<?php } ?>
		
   
        </div>
    </div>

	 <!------ Modal ---->
	 <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Payment Detail</h4>
            </div>
            <div class="modal-body">
                <?php
                $payHistory = \App\PrebookingPayment::where('invoiceid',$order->invoiceid)->get();
              
                        $paid=0;
                        if($payHistory ==""){
                ?>
                <p>Payment History</p>
						<?php } ?>
                <div class="row">
                    <ul class="payhistory">
                    <?php
                            
                        foreach($payHistory as $payh):
                            echo "<li>";
                            $emailid = $user->email;
                            
                            echo $payh->paid.'INR on '.$payh->paiddate .' Using '.$payh->modeofpayment.' By '.$emailid;
                            echo "</li>";
                            $paid = $paid+$payh->paid;
                        endforeach;
                    
                    ?>
                    </ul>
                
                </div>
                
				<form class="form-default" id="prebookings" action="{{ route('PreOrderBooking.PreBookingUpdate') }}"" method="POST">
				<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                <input type="hidden" name = "cerror" id = "cerror" value="<?php if($order->coupon_discount != ''){echo "ok"; }else{ echo "error"; } ?>" />
                    <input type="hidden" name="wamount" id = "wamount" value="<?php echo $payment; ?>" /> 
                    <input type="hidden" value="<?php if($order->coupon_discount != ''){ echo ($payment - $order->coupon_discount);}else{echo $payment; }?>" id = "famount" name = "famount" />
                    <input type="hidden" value="<?php if($order->coupon_code == ''){echo "";}else{echo $order->coupon_discount; } ?>" id = "discount" name = "discount" />
                    <input type="hidden" value="<?php if($order->coupon_code == ''){echo "0";}else{echo $order->coupon_code; } ?>" id = "couponCode" name = "couponCode" />
                    <input type="hidden" value="<?php echo $virtuemart_product_ids; ?>"name="virtuemart_product_ids" id="virtuemart_product_ids" />
                    <input type="hidden" value="<?php echo $paid; ?>" name="paidamount" id="paidamount" />
                    
                        <?php
                        if($order->invoicelookuptype == 'C'){
                        ?>
                        <div class="form-group" style="line-height: 25px !important; margin-bottom: 15px;">
                    <label for="prepayment">Credit Amount*</label>
                    <input type="number" min="0" step="0.01" id="creditamt" name="creditamt" value=<?php echo $order->creditamt; ?> class="form-control creditamt" />
                    </div>
                        <?php
                        }else{
                    ?>
                    <input type="hidden" id="creditamt" name="creditamt" value="" />
                    <?php		
                        }
                        ?>	
                
                
                <div class="form-group" style="line-height: 25px !important; margin-bottom: 15px;">
                <label for="prepayment">PayNow- Balance amount( INR<span id="spanfinal"><?php echo ($order->amount - ($paidamount+$order->creditamt)); ?></span>)</label>
                <input type="number" <?php if($order->invoicelookuptype == 'S'){ ?> max="<?php echo ($fpayment-$paid); ?>" <?php } ?>  step="0.01" id="prepayment" name="prepayment" class="form-control" />
                </div>
                
                <div class="form-group" style="line-height: 25px !important; margin-bottom: 15px;">
                <label for="ModeOfPayment">Payment Mode</label>
                <select id="ModeOfPayment" name="ModeOfPayment" class="form-control">
                <option value='Clearing'>Clearing/Cash</option>
                <option value='Check'>Check</option>
                <option value='CreditCard'>Credit/Debit Card</option>
                <option value='Electronic'>Electronic</option>
                <option value='Wire'>Wire</option>
                <option value='paytm'>Paytm</option>
                <option value='Check-cce'>Check-CCE</option>
                </select>
                </div>
                
                <div class="form-group" style="line-height: 25px !important; margin-bottom: 15px;">
                <label for="description">Description</label>
                <textarea  class="form-control" placeholder="Description" id="description" name="description"><?php echo $order->description; ?></textarea>
                </div>
                
                <div class="form-group" style="line-height: 25px !important; margin-bottom: 15px;">
                    <label for="notifyme">Notify Me: </label>
                    <select class="form-control" id="notifyme" name="notifyme" >
                <option value="yes">Yes</option>
                <option value="no">No</option>
                </select>
                </div>
                
                <!-- input  class="btn btn-primary" type="submit" value="Save Description" name="save_d" / --> 
                <input class="btn btn-primary"  type="submit" value="Ok" name="close" />
                
                <input type="hidden" name="payment_amount_close" value="<?php echo $payment; ?>" />
                <input type="hidden" name="invoiceid" value="<?php echo $order->invoiceid; ?>" />
				<input type="hidden" name="invoiceno" value="<?php echo $order->invoicenumber; ?>" />
                
                <input type="hidden" class="couponid" name="couponid" />
                <input type="hidden" class="coupondiscount" name="coupondiscount" />
                <input type="hidden" class="finalamount" name="finalamount" />
                
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>

        </div>
        </div>
            <!---modal end---->
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
$('input.chkbx').change(function(e){
	var checkedVals = $('input.chkbx:checkbox:checked').map(function() {
    return this.value;
}).get();
$('#selectedbook').val(checkedVals.join(","));
var x=0;
var singleamount = 0;
var amount = 0;
var qty=0;
var avlqty = 0;
 $('input.chkbx:checkbox:checked').each(function(){
      singleamount += parseInt($(this).attr("data-amountsingle"));
      amount += parseInt($(this).attr("data-amount"));
      qty += parseInt($(this).attr("data-qty"));
      avlqty += parseInt($(this).attr("data-avlqty"));
    });

var paid = parseInt($('#paidinv').val());
//console.log(paid + '' + singleamount);
//$('#arinv').prop('disabled',false);	
//alert(paid-singleamount+'---'+qty);
if(paid >= singleamount && qty >0 && avlqty>0){
	$('#arinv').prop('disabled',false);
	}else{
	$('#arinv').prop('disabled',true);	
	}


});

// $("#prebookings").submit(function(e){
//         e.preventDefault();
//      $.post("{{ route('PreOrderBooking.PreBookingUpdate') }}",$("#preBookings").serialize()).done(function( data ) {
// 		location.reload();
// 		});
//     });
          // console.log(data);
$('#Save').click(function(){
	var id = {{ $order->invoiceid }};
	var modepayment = $( "#myselect option:selected" ).text();
	var paydate = $('#paydate').val();
	var desc = $('#desc').val();
	var status = $( "#changestatus option:selected" ).text();
	//alert(status);
	var payment = $('.famount').text();
	 $.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/PreOrderBooking/InvoiceEmail') }}',
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
	 var id = {{ $order->invoiceid }};
	 $.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/PreOrderBooking/getDescription') }}',
		data: { desc:desc, id:id, _token: "{{ csrf_token() }}" },
        //dataType: 'json',
        success: function (data) {
			//console.log(data);
			alert('Update decription &  Sms send on your Mobile');
			//$('#desc').val();
		}
	 });
	 
  }

  $('.changestatus').change(function(e){
	var inv = "<?php echo $order->invoiceid; ?>";
	$.ajax({
				async : false,
				url : '{{url('admin/PreOrderBooking/statusChangeOrder')}}',
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
		url: '{{ url('admin/PreOrderBooking/getbookdetail') }}',
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
$("#PrtInv").click(function(value) {
   //there i call the print function with the value attr of the clicked obj
   PrintDiv(2);
 });

function PrintDiv(x) { 

//alert('test');
var balance_amt = $('#balance_amt').val();
	  if(x==2 ||x == '2'){
		var divToPrint = document.getElementById('divtoprint');
	var popupWin = window.open('', '_blank', 'width=900,height=700');
	popupWin.document.open();  
	$.ajax({
		url: "{{ url('admin/PreOrderBooking/invoice')}}/{{$order->invoiceid}}/{{$order->amount - $paidamount}}",
		type: 'GET',
		data: {
			"_token": "{{ csrf_token() }}",
			"_val": x,
			"_orderid": {{$order->invoiceid}},
			"_bal" : balance_amt
		}
		}).done(function(html) {
	
	popupWin.document.write(html);
	popupWin.document.close();
});	  

	
		  
   //var divToPrint = document.getElementById('divtoprint');
   //var popupWin = window.open('', '_blank', 'width=600,height=600');
  // popupWin.document.open();
 //  popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
   // popupWin.document.close();
}else{
	return false;
	}
		}
		$("#PrtAdv").click(function(value) {
   //there i call the print function with the value attr of the clicked obj
   Printadv(2);
 });
		function Printadv(x){
		//$('.printadvance').print();
		var popupWin = window.open('', '_blank', 'width=900,height=700');
		popupWin.document.open(); 
		$.ajax({
		url: "{{ url('admin/PreOrderBooking/AdvanceInvoice')}}/{{$order->invoiceid}}/{{$paidamount}}",
		type: 'GET',
		data: {
			"_token": "{{ csrf_token() }}",
			"_val": x,
			"_orderid": {{$order->invoiceid}},
			"_amt" : {{$paidamount}}
		}
		}).done(function(html) {
	
		popupWin.document.write(html);
        popupWin.document.close();
	});	  
		
		}

		$("#PrtCr").click(function(value) {
   //there i call the print function with the value attr of the clicked obj
   PrintDivCR(2);
 });

		function PrintDivCR(x) { 

	
if(x==2 ||x == '2'){
  var divToPrint = document.getElementById('divtoprint');
var popupWin = window.open('', '_blank', 'width=900,height=700');
popupWin.document.open();  
$.ajax({
url: "{{ url('admin/PreOrderBooking/arinvoicepre')}}/{{$order->invoiceid}}",
type: 'GET',
data: {
			"_token": "{{ csrf_token() }}",
			"_orderid": {{$order->invoiceid}}
		}
}).done(function(html) {

popupWin.document.write(html);
popupWin.document.close();
});	  
	
//var divToPrint = document.getElementById('divtoprint');
//var popupWin = window.open('', '_blank', 'width=600,height=600');
// popupWin.document.open();
//  popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
// popupWin.document.close();
}else{
return false;
}
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
				url: '{{ url('admin/PreOrderBooking/applycoupon') }}',
				type : "POST",
				data : {'amount' : amt, 'coupon' : coupon,'order_id':'<?php echo $order->invoiceid; ?>', 'customerId':'<?php echo $order->customerid; ?>', _token: "{{ csrf_token() }}" },
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
                        $('#spanfinal').html(parseFloat(amt));
						$('#prepayment').attr('max',parseFloat(amt));
					}else if(dataType == 4){
						$('.cerror').html('<p style="color:red;font-weight:bold">Empty Request.</p>');
						$('#cerror').val('error');
						$('.discount').html(0);
						$('.famount').html(parseFloat(amt));
						$('#discount').val(0);
						$('#famount').val(parseFloat(amt));
						$('#pdiscount').hide();
                        $('#couponCode').val('');
                        $('#spanfinal').html(parseFloat(amt));
						$('#prepayment').attr('max',parseFloat(amt));
					}else if(dataType == 5){
						$('.cerror').html('<p style="color:red;font-weight:bold">Coupon does not exist.</p>');
						$('#cerror').val('error');
						$('.discount').html(0);
						$('.famount').html(parseFloat(amt));
						$('#discount').val(0);
						$('#famount').val(parseFloat(amt));
						$('#pdiscount').hide();
                        $('#couponCode').val('');
                        $('#spanfinal').html(parseFloat(amt));
						$('#prepayment').attr('max',parseFloat(amt));
					}else if(dataType == 6){
						$('.cerror').html('<p style="color:red;font-weight:bold">Coupon does not applicable for this customers.</p>');
						$('#cerror').val('error');
						$('.discount').html(0);
						$('.famount').html(parseFloat(amt));
						$('#discount').val(0);
						$('#famount').val(parseFloat(amt));
						$('#pdiscount').hide();
                        $('#couponCode').val('');
                        $('#spanfinal').html(parseFloat(amt));
						$('#prepayment').attr('max',parseFloat(amt));
					}else if(dataType == 9){
						$('.cerror').html('<p style="color:red;font-weight:bold">Coupon does not applicable.</p>');
						$('#cerror').val('error');
						$('.discount').html(0);
						$('.famount').html(parseFloat(amt));
						$('#discount').val(0);
						$('#famount').val(parseFloat(amt));
						$('#pdiscount').hide();
                        $('#couponCode').val('');
                        $('#spanfinal').html(parseFloat(amt));
						$('#prepayment').attr('max',parseFloat(amt));
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
                        $('#spanfinal').html(parseFloat(amt) - parseFloat(dataType));
						$('#prepayment').attr('max',parseFloat(amt) - parseFloat(dataType))
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
	if(trxn == 'R'){$('#qty').attr("max",1);$('#qty').attr("min",1)}else{$('#qty').attr("max",'');$('#qty').attr("min",1)}					
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
				url : '{{ url('admin/PreOrderBooking/get_book_detail_invoice') }}', 
				type : "POST",
				data : {'keyword' : mobile,'vart' : vart, 'trxn' : trxn, 'invoice_type':invoice_type,'customerId':'<?php echo $order->customerid; ?>','orderId':'<?php echo $order->invoiceid; ?>', _token: "{{ csrf_token() }}" },
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
$('#msg').html('');		
$('#msg').hide();	

$.ajax({
				async : false,
				url: '{{ url('admin/PreOrderBooking/get_book_detail_invoice') }}', 
				type : "POST",
				data : {'keyword' : mobile,'vart' : vart, 'trxn' : trxn, 'invoice_type':invoice_type,'customerId':'<?php echo $order->customerid; ?>','orderId':'<?php echo $order->invoiceid; ?>', _token: "{{ csrf_token() }}" },
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

	$.post("{{ url('admin/PreOrderBooking/updateline') }}", {
			"_token": "{{ csrf_token() }}",
			"orderid": "{{$order->invoiceid}}",
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
	$.post("{{ url('admin/PreOrderBooking/destroyLine') }}", {
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