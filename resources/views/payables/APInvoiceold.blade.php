<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AP Invoice</title>
	<link href="css/invoice_css.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body onLoad="window.print();">
        <div id="wrapper">

        <div id="page-wrapper">
            <div class="row">
			    <div class="col-lg-12">
        
				<!-- /.col-lg-12 -->
            </div><?php if($invoice_header_data[0]['SupplierID']=='38' and $_SESSION['Role']!="1" and $_SESSION['Role'] != 2) die("Restricted Access");  ?>
			 <table style="width:800px;" border="0">
		    <tr><td>Invoice Number:</td><td><a href="{{url('admin/APinvoice_header_workbench/APInvoiceold')}}/<?php echo $invoice_id; ?>" target="_blank" title="View Image"><?php echo $invoice_header_data[0]['InvoiceNumber']; ?></a></td>
		    <td>Supplier Name:</td><td><?php echo $supplier->name ?></td></tr>
		    <tr><td>Invoice Amount:</td><td><?php echo $invoice_header_data[0]['Total']; ?></td>
		    <td>Invoice Date:</td><td><?php echo $invoice_header_data[0]['Date']; ?></td></tr>
		    <tr><td>Invoice Status:</td><td><?php if($invoice_header_data[0]['Status']=="O") echo "Open/Unpaid"; else if($invoice_header_data[0]['Status']=="P") echo "Paid"; else echo "Cancelled"; ?></td>
		    <td>Description:</td><td><?php echo $invoice_header_data[0]['Description']; ?></td></tr>
			</table><br />
          <table style="width:990px;" >
		  <tr><th style="border:1px solid;">S.N.</th><th style="border:1px solid;">ISBN ID</th><th style="border:1px solid;">Book</th><th style="border:1px solid;">Quantity</th>
		  <th style="border:1px solid;">MRP</th>
		  <th style="border:1px solid;">Cost Price</th>
		  <th style="border:1px solid;">Old/New</th>
		  <th style="border:1px solid;">LastBooks</th>
		  <th style="border:1px solid;">LastLines</th>
		  </tr>
		<?php
		$total_amt=0;$inc=0;
		for($i=0;$i<count($invoice_line_data);$i++)
		{
		?>		<tr><td style="border:1px solid;"><?php echo ++$inc; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Isbn1']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Name'].",&nbsp;Author: ".$invoice_line_data[$i]['Author']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Quantity']; ?></td>
		<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Mrp']; ?></td>
		<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Cp']; $total_amt+=$invoice_line_data[$i]['Cp']; ?></td>
		<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Version']; ?></td>
		<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['lastupdateddate']; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['lastline']; ?></td>
		<?php if($invoice_header_data[0]['Status']=="O") {?>
        <td><button  type='button'  style="border:none; padding:0px;" title='delete' class='delete_order_item' data-id='<?php echo $invoice_line_data[$i]['LineID'] ; ?>' data-invoice-id='<?php echo $invoice_id ; ?>' data-supplier-id='<?php echo $supplier_id ; ?>'><i class="fa fa-trash-o" style="font-size:24px;color:red"></i></button></td>
        <?php } ?></tr>
		<?php
		}
		?>
			</table><br />
			<p>Total Amount to be paid:  <?php echo $total_amt; ?> INR</p>
			
			
			
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
<script>
$(document).ready(function(){
$(".delete_order_item").click(function(){
	
	var _item_id = $(this).attr('data-id');
	var _invoice_id = $(this).attr('data-invoice-id');
	var _supplier_id = $(this).attr('data-supplier-id');
	//alert(_item_id);
	$.post("{{ url('admin/APinvoice_header_workbench/destroyLine') }}", {
			"_token": "{{ csrf_token() }}",
			 "id": _item_id,
			 "invoice_id": _invoice_id,
			 "supplier_id": _supplier_id,
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
});	
</script>
</html>
