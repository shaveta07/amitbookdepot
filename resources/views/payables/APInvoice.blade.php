<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AP Invoice</title>
	
</head>
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
<body onLoad="window.print();">
    <div id="wrapper">
        <div id="page-wrapper">
       
<table style="padding-top:20px;"><tr><td>
          <table rule="none" frame="box" >
          <tr><td><b>Amit Book Depot</b></td></tr>
		  <tr><td>SCO Sector 34-A Chandigarh-160022</td></tr>
		  <tr><td><b>Phone</b> : 0172-2665665</td></tr>
		  <tr><td><b>Website </b>: amitbookdepot.com </td></tr><tr><td><b>GSTIN </b>: </td></tr>
		  </table>
		  </td><td valign="top">
			 <table rule="none" frame="box" style="width:600px;">
		    <tr><td>Invoice Number:  <?php echo $invoice_header_data[0]['InvoiceNumber']; ?></td>
			<td>Party Mobile:  <?php echo $invoice_header_data[0]['Mobile1'];  ?></td></tr>
			<tr><td>Email:<?php echo $invoice_header_data[0]['Email1'];  ?></td>
			<td>Address: 
			<?php echo $invoice_header_data[0]['Address1'];  ?>&nbsp; ,<?php echo $invoice_header_data[0]['Address2'];  ?>&nbsp; <?php  echo $invoice_header_data[0]['City'];  ?>
			&nbsp; , <?php echo $invoice_header_data[0]['State'];  ?>  <?php  echo $invoice_header_data[0]['zipcode'];  ?>
			</td>
			</tr>
		    <tr><td>Invoice Date:  <?php echo $invoice_header_data[0]['Date']; ?></td><td>Party Name : <?php echo $invoice_header_data[0]['Name']; ?></td></tr>
		    <tr><td>Invoice Status:  <?php if($invoice_header_data[0]['Status']=="O") echo "Open"; else if($invoice_header_data[0]['Status']=="P") echo "Paid"; else echo "Cancelled"; ?></td></tr><tr>
		    
		    <td>Description:</td><td><?php echo $invoice_header_data[0]['Description']; ?></td></tr>
			</table>
			</td></tr></table>
<div style="">
      
          <table style="width:860px;" class="gridtable">
		  <tr><th style="border:1px solid;">ISBN ID</th><th style="border:1px solid;">Book</th><th style="border:1px solid;">Quantity</th>
		 
		  <th style="border:1px solid;">Old/New</th></tr>
		<?php
		$total_amt=0;$qty=0;
		for($i=0;$i<count($invoice_line_data);$i++)
		{
			$qty = $qty + $invoice_line_data[$i]['Quantity'];
		?>		<tr><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Isbn1']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Name'].",&nbsp;Author: ".$invoice_line_data[$i]['Author']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Quantity']; ?></td>

		<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Version']; ?></td></tr>
		<?php
		}
		?>
			</table><br />
			<p>
            Quantity: <?php echo $qty; ?><br/>
			Total Amount without GST: <?php echo $invoice_header_data1->Total-$invoice_header_data1->gst; ?> <br/>
			IGST: <?php echo $invoice_header_data1->igst; ?> <br/>
			SGST: <?php echo $invoice_header_data1->igst; ?> <br/>
			CGST: <?php echo $invoice_header_data1->cgst; ?> <br/>
			GST: <?php echo $invoice_header_data1->gst; ?> </p>
			<p>Total Amount Paid to Seller(INC GST) :  <?php echo $invoice_header_data1->Total; //$total_amt; ?> INR</p>
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
		</p>
		</div>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

</body>
</html>
