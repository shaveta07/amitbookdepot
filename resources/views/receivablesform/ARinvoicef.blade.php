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
</head>
<body onLoad="window.print()">
    <div id="wrapper">
        <div id="page-wrapper">
<table><tr><td>
          <table rule="none" frame="box">
		  <tr><td><b>Amit Book Depot</b></td></tr>
		  <tr><td>SCO Sector 34-A Chandigarh-160022</td></tr>
		  <tr><td><b>Phone</b> : 0172-2665665</td></tr>
		  <tr><td><b>Website </b>: amitbookdepot.com </td></tr><tr><td><b>GSTIN </b>: </td></tr>
		  </table>
		  </td><td valign="top">
			 <table rule="none" frame="box" style="width:600px;">
		    <tr><td>Invoice Number:</td><td><?php echo $invoice_header_data->invoicenumber; ?></td>
		 <!--   <td>Customer Mobile:</td><td><?php //echo $customer_detail[0]['Mobile1'];  ?></td></tr> -->
			<tr><td>Customer Name:</td><td><?php echo $c_name=$customer_detail->name; ?></td>
		    <td>Customer Address:</td><td><?php echo $customer_detail->address.", ".$customer_detail->city;  ?></td></tr>
		    <tr><td>Invoice Type:</td><td><?php if($invoice_header_data->invoicelookuptype=="S") echo "Standard"; else echo "Prepayment"; ?></td>
		    <td>Invoice Date:</td><td><?php echo $invoice_header_data->invoicedate; ?></td></tr>
		    <tr><td>Invoice Status:</td><td><?php if($invoice_header_data->status=="O") echo "Open"; else if($invoice_header_data->status=="P") echo "Paid"; else echo "Cancelled"; ?></td>
		    <td>Description:</td><td><?php echo $invoice_header_data->description; ?></td></tr>
			</table>
			</td></tr></table>
<div style="">
          <table style="900px; border-color: rgb(17, 2, 1);" class="gridtable table table-bordered">
		  <tr><th style="border:1px solid;">ISBN ID</th><th style="border:1px solid;">Book</th><th style="border:1px solid;" >MRP/Security</th>

		  <th style="border:1px solid;">Discount</th><th style="border:1px solid;" >Quantity</th><th style="border:1px solid;" >Recieved Amount</th><th style="border:1px solid;">Application</th><th style="border:1px solid;">Pay Date</th></tr>
		    <?php
			$payment=0;$payment=0;$_igst=$_sgst=$_gst=$_cgst=$_baseprice=$_discount=0;$recieved=0;
			foreach($invoice_line_data as $key => $data)
			{
                $isbn='';
                        $product = \App\Product::where('id',$data->itemid)->first(); 
                        if($data->variation != NULL && $data->variation != 'null')
                        {
                            
                            $productStock = \App\ProductStock::where('variant',$data->variation)->where('product_id',$product->id)->first();
                            if($productStock)
                            {
                                $isbn = $productStock->isbn;
                                $name = $productStock->variant;
                            }
                        }
                        else
                        {
                            $isbn = $product->isbn;
                            $name = $product->name;
                        }
				$_igst = $_igst+$data->igst*$data->quantity;
			$_cgst = $_cgst+$data->cgst*$data->quantity;
			$_sgst = $_sgst+$data->sgst*$data->quantity;
			$_gst = $_gst+$data->gst*$data->quantity;
			$_baseprice = $_baseprice+$data->baseprice*$data->quantity;
			$_discount = $_discount + $discount*$data->quantity;
			?>
			<tr><td style="border:1px solid;" ><?php echo $isbn; ?></td><td style="border:1px solid;"><?php echo $name;
if($data->transactiontype =="R") echo "&nbsp;,Return Due Date: ".$data->rentduedate;
			?></td><td style="border:1px solid;"><?php echo $data->amount; ?></td>

			<td style="border:1px solid;"><?php echo $data->discount; ?></td>
			<td style="border:1px solid;"><?php echo $data->quantity; ?></td>
			
			<td style="border:1px solid;"><?php echo $data->recievedamount; $recieved = $recieved + $data->recievedamount; ?></td>
			<td style="border:1px solid;"><?php echo $data->application; ?></td>
			<td style="border:1px solid;"><?php echo $data->paydate;  ?></td>
			<?php  if($data->status=="O"){ ?>
			<td style="border:1px solid;"><a href="delete-ARInvoice-line.php?id=<?php echo $data->lineid; ?>&invoice_number=<?php echo $invoice_number; ?>" title="Delete">[X]</a></td>
			<?php } ?>
			</tr>
			<?php
			}
			?>
			</table>
			<?php echo "Service Charge : ".$_baseprice; ?><br/>
			<?php /*  echo "SGST : ".$_sgst; ?><br/>
			<?php echo "CGST: ".$_cgst; ?><br/>
			<?php echo "GST : ".$_gst; ?><br/>
			<?php echo "IGST: ".$_igst; */ ?><br/>
			<?php echo "Total Discount: ".$_discount; ?><br/>
			<?php echo "Total Amount to be paid : ".$payment; ?> INR<br />
			<?php echo "Total Recieved Amount : ".$recieved; ?> INR<br />
		<p style="font-size:10px;">
This is computer generated invoice and do not require any stamp or signature.<br />
<u>Terms and conditions</u><br />
<ol style="font-size:10px;">
<li>Amit Book Depot type candidate details and upload documents/photo/signature during the filling of online application form in the presence of client/candidate/customer 
or their representative only.</li>
<li>It will be the solely responsibility of customer/client/candidate or their representative to dictate candidate details, to check the typed details and uploaded documents/photo/signature
 very carefully before the final submission of details/application form as the details cannot be edited after final submission of application form.</li>
<li>If any typing mistakes, error or mistakes in uploading documents/photo/signature found, Customer/client/candidate or their representative will be solely responsible.</li>
<li>It will be solely responsibility of Customer/client/candidate or their representative to keep their login details safe.</li>
<li>It will be the solely responsibility of Customer/client/candidate or their representative to be aware of the further procedure of application submission process ,
to download the admit card and the counseling procedure etc.</li>
<li>Due to the transactions are processed by the external banking sites, there may be delays in updating your payment, it will be the solely responsibility of Customer/client/candidate or 
their representative to check the payment status by logging in on college/concerned website. if any issue regarding payment/payment failure arise amit book depot will not be responsible</li>
<li>All disputes are subject to Chandigarh jurisdiction only.</li>
</ol>

		</p>
		</div>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

</body>
</html>

</style>