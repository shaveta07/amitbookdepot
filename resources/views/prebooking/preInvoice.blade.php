<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AR Invoice</title>
	
</head>
<style>

</style>
<body onLoad="window.print();">
    <div id="wrapper">
        <div id="page-wrapper">
        <?php if($invoice_header_data->name=='Salary' ) die("Restricted Access");  ?>
<table style="padding-top:20px;"><tr><td>
          <table rule="none" frame="box" >
          <tr><td><b>Amit Book Depot</b></td></tr>
		  <tr><td>SCO Sector 34-A Chandigarh-160022</td></tr>
		  <tr><td><b>Phone</b> : 0172-2665665</td></tr>
		  <tr><td><b>Website </b>: amitbookdepot.com </td></tr><tr><td><b>GSTIN </b>: </td></tr>
		  </table>
		  </td><td valign="top">
          <table rule="none" frame="box" style="width:600px;">
		    <tr><td>Order Number:</td><td><?php echo $invoice_header_data->invoicenumber; ?></td>
		 <!--   <td>Customer Mobile:</td><td><?php //echo $customer_detail[0]['Mobile1'];  ?></td></tr> -->
			<tr><td>Customer Name:</td><td><?php echo $c_name=$customer_detail->name; ?></td>
		    <td>Customer Address:</td><td><?php echo $customer_detail->address, $customer_detail->city;  ?></td></tr>
		    <tr><td>Order Type:</td><td><?php if($invoice_header_data->invoicelookuptype=="S") echo "Standard"; else echo "Prepayment"; ?></td>
		    <td>Order Date:</td><td><?php echo $invoice_header_data->invoicedate; ?></td></tr>
		    <tr><td>Order Status:</td><td><?php if($invoice_header_data->status=="O") echo "Open"; else if($invoice_header_data->status=="P") echo "Closed"; else echo "Cancelled"; ?></td>
		    <td>Description:</td><td><?php echo $invoice_header_data->description; ?></td></tr>
			
		    <tr><td>Customer Mobile:</td><td><?php echo $customer_detail->phone; ?></td><td>&nbsp;</td><td>&nbsp;</td></tr>
			</table>
			</td></tr></table>
<div style="">
      
<table style="900px;" class="gridtable">
		  <tr><th style="border:1px solid;">ISBN ID</th><th style="border:1px solid;">Book</th><th style="border:1px solid;">MRP/Security</th><th style="border:1px solid;" >Discount</th><th style="border:1px solid;">Quantity</th><th style="border:1px solid;" >Sale/Rent</th><th style="border:1px solid;">Rent</th><th style="border:1px solid;">Final Amount</th><th style="border:1px solid;">Delivered Quantity</th></tr>
		    <?php
			$payment=0;
			for($i=0;$i<count($invoice_line_data);$i++)
			{
			?>
			<tr><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Isbn1']; ?></td><td style="border:1px solid;" ><?php echo $invoice_line_data[$i]['Name'];
if($invoice_line_data[$i]['TransactionType']=="R") echo "&nbsp;,Return Due Date: ".date_format(date_create($invoice_line_data[$i]['RentDueDate']),"d-M-Y");
			?></td><td style="border:1px solid;" ><?php echo $invoice_line_data[$i]['Amount']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Discount']; ?></td><td style="border:1px solid;" ><?php echo $invoice_line_data[$i]['Quantity']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['TransactionType']; ?></td><td style="border:1px solid;"><?php if($invoice_line_data[$i]['TransactionType']=="R") echo $invoice_line_data[$i]['ItemPrice']*$invoice_line_data[$i]['Quantity'];  ?></td><td style="border:1px solid;"><?php echo $pay_line=($invoice_line_data[$i]['Amount']-$invoice_line_data[$i]['Discount'])*$invoice_line_data[$i]['Quantity']; $payment+=$pay_line;  ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['delivered_qty']; ?></td>
			<?php  if($invoice_header_data->status=="O"){ ?>
			<td style="border:1px solid;">
            <button  type='button'  style="border:none; padding:0px;" title='delete' class='delete_order_item' data-order-id='<?php echo $invoice_line_data[$i]['lineid'] ; ?>'><i class="fa fa-trash-o" style="font-size:24px;color:red"></i></button>
            </td>
			<?php } ?>
			</tr>
			<?php
			}
			?>
			
			</table><br />
			<?php 
			if($invoice_header_data->coupon_code != ''){
			echo "Sub Total: ".$payment."<br/>";
			//echo "Discount: ".$invoice_header_data[0]['coupon_discount']."<br/>";
			echo "Coupon Code: ".$invoice_header_data->coupon_code."<br/>";
			//$payment = $invoice_header_data[0]['Amount'];
			echo "Total Amount to be pay : ".($payment - $invoice_header_data->coupon_discount);
			?>
			<div class="col-sm-12">
			<div id = "pdiscount" <?php if($invoice_header_data->coupon_code==''){echo 'style="display:none"';}else{ echo 'style="display:block"';} ?>><label>Discounted Amount:&nbsp;</label> <span style="font-weight:bold;" class="discount"><?php if($invoice_header_data->coupon_discount != ''){ echo $invoice_header_data->coupon_discount; } ?></span></div>	
			<?php $fpayment = 0; ?>
			<div><label>Final Amount:&nbsp;</label> <span style="font-weight:bold" class="famount"><?php  if($invoice_header_data->status=="O") { if($invoice_header_data->amount == ''){echo $payment;$fpayment=$payment;}else{ echo $invoice_header_data->amount; $fpayment=$invoice_header_data->amount;}}else{ echo $invoice_header_data->amount; $fpayment=$invoice_header_data->amount; } ?> INR</span></div>
			<?php
            $paidamount = \App\PrebookingPayment::select('paid')->where('invoiceid',$invoice_header_data->invoiceid)->sum('paid');
		 
			if($paidamount > 0):
			?>
			<div><label>Paid Amount:&nbsp;</label><span><?php echo $paidamount; ?> INR</span></div>
			<div><label>Balanced Amount:&nbsp;</label><span><?php echo $invoice_header_data->amount - $paidamount; ?> INR</span></div>
			<?php
			endif;
			?>
			</div>
			
			<?php
			}else{
				
			//$payment = $invoice_header_data[0]['Amount'];
			echo "Total Amount to be pay : ".$payment;
			?>
			<div class="col-sm-12">
			<div id = "pdiscount" <?php if($invoice_header_data->coupon_code==''){echo 'style="display:none"';}else{ echo 'style="display:block"';} ?>><label>Discounted Amount:&nbsp;</label> <span style="font-weight:bold;" class="discount"><?php if($invoice_header_data->coupon_discount != ''){ echo $invoice_header_data->coupon_discount; } ?></span></div>	
			<?php $fpayment = 0; ?>
			<div><label>Final Amount:&nbsp;</label> <span style="font-weight:bold" class="famount"><?php  if($invoice_header_data->status=="O") { if($invoice_header_data->amount == ''){echo $payment;$fpayment=$payment;}else{ echo $invoice_header_data->amount; $fpayment=$invoice_header_data->amount;}}else{ echo $invoice_header_data->amount; $fpayment=$invoice_header_data->amount; } ?> INR</span></div>
			<?php
		   $paidamount = \App\PrebookingPayment::select('paid')->where('invoiceid',$invoice_header_data->invoiceid)->sum('paid');
				if($paidamount > 0){
			?>
			<div><label>Paid Amount:&nbsp;</label><span><?php echo $paidamount; ?> INR</span></div>
			<?php $cramt = 0; if($invoice_header_data->invoicelookuptype == 'C'){ ?>
				<div style="color:green"><label>Credit Amount:&nbsp;</label><span><?php echo $invoice_header_data->creditamt; ?></span></div>
			<?php 
			$cramt = $invoice_header_data->creditamt;
			} ?>
			<div><label>Balanced Amount:&nbsp;</label><span><?php echo $invoice_header_data->amount - $paidamount - $cramt; ?> INR</span></div>
			<?php
				}else{
				
?>
<div style="color:green"><label>Credit Amount:&nbsp;</label><span><?php echo $invoice_header_data->creditamt; ?></span></div>
				<?php
				}
			?>
			</div>
			<?php 
		}
			?> <br /> 		<p><span style="font-weight: 400;">This is computer generated invoice and do not require any stamp or signature.</span><span style="font-weight: 400;"><br /></span><span style="font-weight: 400;">Returns Policy:</span><span style="font-weight: 400;"><br /></span><span style="font-weight: 400;">We only replace items if they are defective or damaged. Our policy lasts 3 days. If 3 days have gone by since your purchase, unfortunately we cannot offer you a exchange.</span><span style="font-weight: 400;"><br /></span><span style="font-weight: 400;">To be eligible for a exchange/replacement, your item must be:</span></p>
<ol>
<li style="font-weight: 400;"><span style="font-weight: 400;">Unused and in the same condition that you received it. It must also be in the original packaging.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">We require a Bill or proof of purchase.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">You will be responsible for paying for your own delivery charges for exchange your item. Delivery charges are non ¬refundable. If you receive a refund, the cost of delivery charges will be deducted from your refund.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">Several types of books are exempt from exchanged. Like sample papers, question banks (revision books), magazines, sale items and the books which has been arranged on special demand cannot be exchanged.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">There are certain situations where only partial refunds are granted: (if applicable)</span></li>
<ol>
<li style="font-weight: 400;"><span style="font-weight: 400;">Any item not in its original condition, is damaged or missing parts for reasons not due to our error.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">Any item that is exchanged more than 3 days after billing.</span></li>
</ol>
<li style="font-weight: 400;"><span style="font-weight: 400;">If books has been taken on rent any one method of customer verification is mandatory to get the return amount back</span></li>
</ol>
<p><span style="font-weight: 400;">(i)show original bill</span></p>
<p><span style="font-weight: 400;">(ii) original photo id proof  of the customer(whom the books has been issued to)</span></p>
<p><span style="font-weight: 400;">(iii)customer can get verified himself/herself by receiving OTP on the registered mobile number(which has been given at the time of renting books)</span></p>
<ol>
<li style="font-weight: 400;"><span style="font-weight: 400;">Rented Books will not be taken back after the due date ( due date is mentioned on bill)</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">At the time of the return rented books should be in acceptable condition otherwise books will not be taken back</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">it is mandatory to keep rent description sticker pasted to get money back of rented books</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">All disputes are subject to Chandigarh jurisdiction only</span></li>
</ol>
		</div>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

</body>
</html>