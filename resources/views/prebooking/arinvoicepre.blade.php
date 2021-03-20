<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AR Invoice Number <?php echo $invoice_number; ?></title>
	<link href="css/invoice_css.css" rel="stylesheet">
</head>
<body onLoad="window.print()">
    <div id="wrapper">
        <div id="page-wrapper">
<table><tr><td>
       
		<table rule="none" frame="box" >
          <tr><td><b>Amit Book Depot</b></td></tr>
		  <tr><td>SCO Sector 34-A Chandigarh-160022</td></tr>
		  <tr><td><b>Phone</b> : 0172-2665665</td></tr>
		  <tr><td><b>Website </b>: amitbookdepot.com </td></tr><tr><td><b>GSTIN </b>: </td></tr>
		  </table>
		  </td><td valign="top">
			 <table rule="none" frame="box" style="width:600px;">
		    <tr><td><b>Invoice Number</b>:</td><td><?php if(isset($invoice_header_data->invoicenumber))echo $invoice_header_data->invoicenumber; ?></td>
			<?php if(isset($invoice_header_data->invoicenumber)) { if($invoice_header_data->invoicelookuptype=="C"){ ?><td>Customer Mobile:</td><td><?php echo $customer_detail->phone;  ?></td><?php }else{echo "<td></td><td></td>"; } }?></tr> 
			<tr><td><b>Customer Name</b>:</td><td><?php if(isset($customer_detail->name)) echo $c_name=$customer_detail->name; ?></td>
		    <td><b>Customer Address</b>:</td><td><?php if(isset($customer_detail->address )) echo $customer_detail->address.$customer_detail->city;  ?></td></tr>
		    <tr><td><b>Invoice Type</b>:</td><td><?php  if(isset($invoice_header_data->invoicelookuptype)) { if($invoice_header_data->invoicelookuptype=="S") echo "Standard"; else if($invoice_header_data->invoicelookuptype=="C") echo "Credit Memo"; else echo "Prepayment"; } ?></td>
		    <td><b>Invoice Date</b>:</td><td><?php if(isset($invoice_header_data->invoicedate)) echo $invoice_header_data->invoicedate; ?></td></tr>
		    <tr><td><b>Invoice Status</b>:</td><td><?php if(isset($invoice_header_data->status)) { if($invoice_header_data->status=="O") echo "Open"; else if($invoice_header_data->status=="P") echo "Paid"; else echo "Cancelled"; } ?></td>
		    <td><b>Description</b>:</td><td><?php if(isset($invoice_header_data->description)) echo $invoice_header_data->description; ?></td></tr>
			</table>
			</td></tr></table>
<div style="">
	
	<?php
    $credit = \App\Prebooking::select('creditamt')->where('invoicenumber',$invoice_number)->first();
    $amount = \App\Prebooking::select('amount')->where('invoicenumber',$invoice_number)->first();
	$creditamt = $credit->creditamt-$amount->amount;
echo "<p> Credit Amount = ".$creditamt."</p>";
	 /*  ?>
          <table style="900px;" class="gridtable">
		  <tr><th style="width:80px">ISBN ID</th><th style="width:450px" >Book</th><th >MRP/ Security</th><th >Discount</th><th >Qty</th><th >S/R</th><th >Rent</th><th >Final Amount</th></tr>
		    <?php
			$payment=0;
			for($i=0;$i<count($invoice_line_data);$i++)
			{
			?>
			<tr><td ><?php echo $invoice_line_data[$i]['Isbn1']; ?></td><td style="min-width:450px !important"><?php echo $invoice_line_data[$i]['Name'];
if($invoice_line_data[$i]['TransactionType']=="R") echo "&nbsp;,Return Due Date: ".date_format(date_create($invoice_line_data[$i]['RentDueDate']),"d-M-Y");
			?></td><td ><?php echo round($invoice_line_data[$i]['Amount'],2); ?></td><td ><?php echo round($invoice_line_data[$i]['Discount'],2); ?></td><td ><?php echo $invoice_line_data[$i]['Quantity']; ?></td><td ><?php echo $invoice_line_data[$i]['TransactionType']; ?></td><td ><?php if($invoice_line_data[$i]['TransactionType']=="R") echo round($invoice_line_data[$i]['ItemPrice']*$invoice_line_data[$i]['Quantity'],2);  ?></td><td ><?php echo $pay_line=round(($invoice_line_data[$i]['Amount']-$invoice_line_data[$i]['Discount'])*$invoice_line_data[$i]['Quantity'],2); $payment+=$pay_line;  ?></td>
			<?php  if($invoice_header_data[0]['Status']=="O"){ ?>
			<td><a href="delete-ARInvoice-line.php?id=<?php echo $invoice_line_data[$i]['LineID']; ?>&invoice_number=<?php echo $invoice_number; ?>" title="Delete">[X]</a></td>
			<?php } ?>
			</tr>
			<?php
			}
			?>
			
			</table>
			<?php 
			if($invoice_header_data[0]['coupon_code'] != ''){
			echo "<b>Sub Total</b>: ".$payment." INR<br/>";
			echo "<b>Discount</b>: ".$invoice_header_data[0]['coupon_discount']." INR<br/>";
			echo "<b>Coupon Code</b>: ".$invoice_header_data[0]['coupon_code']."<br/>";
			//$payment = $invoice_header_data[0]['Amount'];
			echo "<b>Total Amount to be paid </b>: ".($payment - $invoice_header_data[0]['coupon_discount']).' INR';
			}else{
			//$payment = $invoice_header_data[0]['Amount'];
			echo "<b>Total Amount to be paid</b> : ".$payment.' INR'; ?>
			<?php  if($invoice_header_data[0]['Status']=="O" && $invoice_header_data[0]['InvoiceLookupType'] == 'C') { 
				list($advamount) = get_query_list($con,"select sum(amount) from arcredit where invoiceid = $invoice_id");
				?>
				<p style="color:red;margin-bottom:0px;margin-top:0px;">Recieved Amount: <?php echo ($advamount).' INR'; ?></p>
			<p style="color:red;margin-bottom:0px;margin-top:0px;">Balance Amount: <?php echo ($payment-$invoice_header_data[0]['coupon_discount'] - $advamount).' INR'; ?></p>
			
			<?php 
			$finalPaymentforcredit = $payment-$invoice_header_data[0]['coupon_discount'];
			$balPaymentforcredit = $payment-$invoice_header_data[0]['coupon_discount'] - $advamount;
			} 
			
		}
		*/
		/*
		 amt300
		 * id1020
		 * invoiceno40716
		 */ 
		 
			?>
			<p><span style="font-weight: 400;">This is computer generated invoice and do not require any stamp or signature.</span><span style="font-weight: 400;"><br /></span><span style="font-weight: 400;">Returns Policy:</span><span style="font-weight: 400;"><br /></span><span style="font-weight: 400;">We only replace items if they are defective or damaged. Our policy lasts 3 days. If 3 days have gone by since your purchase, unfortunately we cannot offer you a exchange.</span><span style="font-weight: 400;"><br /></span><span style="font-weight: 400;">To be eligible for a exchange/replacement, your item must be:</span></p>
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
<style>
input,select,select2 select2-container{border: 1px solid #999 !important;
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
td{padding: 0 !important;font-size:13px}
th{padding: 0 !important;font-size:14px}
</style>
