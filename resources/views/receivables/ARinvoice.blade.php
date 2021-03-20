<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AR Invoice Number <?php echo $order->code; ?></title>
	
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
         <table>
             <tr style="border:1px solid #666;"><td style="border-top:1px solid #666" colspan="4"><h3>BILL of Supply</h3></td></tr>
            <tr><td>
	
          <table rule="none" frame="box">
		  <tr><td><b>Amit Book Depot</b></td></tr>
		  <tr><td>SCO Sector 34-A Chandigarh-160022</td></tr>
		  <tr><td><b>Phone</b> : 0172-2665665</td></tr>
		  <tr><td><b>Website </b>: amitbookdepot.com </td></tr><tr><td><b>GSTIN </b>: </td></tr>
		  </table>
		  </td><td valign="top">
			 <table rule="none" frame="box" style="width:600px;">
		    <tr><td><b>Order Number</b>:</td><td><?php echo $order->code; ?></td>
			<?php if($order->invoicelookuptype=="C"){ ?><td>Customer Mobile:</td><td><?php echo $customer_detail->phone;  ?></td><?php }else{echo "<td></td><td></td>"; } ?></tr> 
			<tr><td><b>Customer Name</b>:</td><td><?php echo $c_name=$customer_detail->name; ?></td>
		    <td><b>Customer Address</b>:</td><td><?php echo $customer_detail->address.", ".$customer_detail->city;  ?></td></tr>
		    <tr><td><b>Invoice Type</b>:</td><td><?php if($order->invoicelookuptype =="S") echo "Standard"; else if($order->invoicelookuptype=="C") echo "Credit Memo"; else echo "Prepayment"; ?></td>
		    <td><b>Invoice Date</b>:</td><td><?php echo $order->invoicedate; ?></td></tr>
		    <tr><td><b>Invoice Status</b>:</td><td><?php if($order->payment_status=="unpaid") echo "Open"; else if($order->payment_status =="paid") echo "Paid"; else echo "Cancelled"; ?></td>
		    <td><b>GSTIN:</b></td><td><?= $customer_detail->gstin; ?></td></tr>
		    <tr><td><b>Description</b>:</td><td><?php echo $order->description; ?></td><td></td><td></td></tr>
			
			</table>
			</td></tr>
			
			</table>
            <div style="">
                <table style= "border-color: rgb(17, 2, 1);" class="table table-bordered">
                <tr><th style="width:80px; border:1px solid;">ISBN ID</th><th style="width:450px;border:1px solid;" >Book</th><th style="border:1px solid;" >MRP/ Security</th>
                <th style="border:1px solid;">SGST/U</th>
                <th style="border:1px solid;">CGST/U</th> 
                <th style="border:1px solid;">GST/U</th>
                <th style="border:1px solid;">IGST/U</th>
                <th style="border:1px solid;">BP/U</th>
                <th style="border:1px solid;">Discount/U(%)</th><th style="border:1px solid;" >Qty</th><th style="border:1px solid;">S/R</th><th style="border:1px solid;" >Rent</th><th style="border:1px solid;" >Final Amount</th></tr>
                <?php
                $payment=0;$_igst=$_sgst=$_gst=$_cgst=$_baseprice=$_discount = 0;$tqty=0;
                for($i=0;$i<count($invoice_line_data);$i++)
                {
                    $isbn='';
                        $product = \App\Product::where('id',$invoice_line_data[$i]['product_id'])->first(); 
                        if($invoice_line_data[$i]['variation'] != NULL && $invoice_line_data[$i]['variation'] != 'null')
                        {
                            
                            $productStock = \App\ProductStock::where('variant',$invoice_line_data[$i]['variation'])->where('product_id',$product->id)->first();
                            if($productStock)
                            {
                                $isbn = $productStock->isbn;
                            }
                        }
                        else
                        {
                            $isbn = $product->isbn;
                        }
                $_igst = $_igst+$invoice_line_data[$i]['igst']*$invoice_line_data[$i]['quantity'];
                $_cgst = $_cgst+$invoice_line_data[$i]['cgst']*$invoice_line_data[$i]['quantity'];
                $_sgst = $_sgst+$invoice_line_data[$i]['sgst']*$invoice_line_data[$i]['quantity'];
                $_gst = $_gst+$invoice_line_data[$i]['gstamount']*$invoice_line_data[$i]['quantity'];
                $_baseprice = $_baseprice+$invoice_line_data[$i]['baseprice']*$invoice_line_data[$i]['quantity'];
                $_discount = $_discount + $invoice_line_data[$i]['discount']*$invoice_line_data[$i]['quantity'];
                $tax = $invoice_line_data[$i]['gstamount'] + $invoice_line_data[$i]['igst'];
                $tqty = $tqty+$invoice_line_data[$i]['quantity'];
                ?>
                <tr><td style="border:1px solid;" >{{ $isbn }}</td><td style="min-width:450px !important;border:1px solid;"><?php echo $product->name;
                if($invoice_line_data[$i]['transactiontype']=="R") echo "&nbsp;,Return Due Date: ".date_format(date_create($invoice_line_data[$i]['rentduedate']),"d-M-Y");
                ?></td><td style="border:1px solid;"><?php echo round($invoice_line_data[$i]['amount'],2); ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_data[$i]['sgst']; ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_data[$i]['cgst']; ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_data[$i]['gstamount']; ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_data[$i]['igst']; ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_data[$i]['baseprice']; ?></td>

                <td style="border:1px solid;"><?php echo round((($invoice_line_data[$i]['discount']+$tax)/$invoice_line_data[$i]['amount'])*100,2).'%'; ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_data[$i]['quantity']; ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_data[$i]['transactiontype']; ?></td><td style="border:1px solid;"><?php if($invoice_line_data[$i]['transactiontype']=="R") echo round($invoice_line_data[$i]['price']*$invoice_line_data[$i]['quantity'],2);  ?></td><td style="border:1px solid;" ><?php echo $pay_line=round(($invoice_line_data[$i]['amount']-$invoice_line_data[$i]['discount'])*$invoice_line_data[$i]['quantity'],2); $payment+=$pay_line;  ?></td>
                <?php  if($order->payment_status=="unpaid"){ ?>
                    <td>
                        <button  type='button'  style="border:none; padding:0px;" title='delete' class='delete_order_item' data-order-id='<?php echo $invoice_line_data[$i]['id'] ; ?>'><i class="fa fa-trash-o" style="font-size:24px;color:red"></i></button>
                        
                    </td>
                    <?php } ?>
                </tr>

                <?php
                }
                ?>

                </table>
                <div class="row">
                <div style="float:left; " class="col-sm-6">
                <?php echo "<p style='float: left;'><Strong>Total Quantity: </Strong>".$tqty; ?></p>
                </div>
                <div style="float:right;" class="col-sm-6">
                <?php echo "Amount exclusive Tax : ".$_baseprice; ?><br/>
                <?php echo "SGST : ".$_sgst; ?><br/>
                <?php echo "CGST: ".$_cgst; ?><br/>
                <?php echo "GST : ".$_gst; ?><br/>
                <?php echo "IGST: ".$_igst; ?><br/>
                <?php echo "Total Discount: ".($_discount+$_gst+$_igst); ?><br/>
               
               

                <?php 

                if($order->couponcode != ''){
                echo "<b>Sub Total(incl. Tax)</b>: ".$payment." INR<br/>";
                echo "<b>Discount</b>: ".$order->coupon_discount." INR<br/>";
                echo "<b>Coupon Code</b>: ".$order->couponcode."<br/>";
                //$payment = $invoice_header_data[0]['Amount'];
                echo "<b>Total Amount to be paid </b>: ".($payment - $order->coupon_discount).' INR';
                }else{
                //$payment = $invoice_header_data[0]['Amount'];
                echo "<b>Total Amount to be paid</b> : ".$payment.' INR'; ?>
                <?php  if($order->payment_status=="unpaid" && $order->invoicelookuptype == 'C') { 
                    list($advamount) = sum($order->amount);
                    ?>
                    <p style="color:red;margin-bottom:0px;margin-top:0px;">Recieved Amount: <?php echo ($advamount).' INR'; ?></p>
                <p style="color:red;margin-bottom:0px;margin-top:0px;">Balance Amount: <?php echo ($payment-$order->coupon_discount - $advamount).' INR'; ?></p>

                <?php 
                $finalPaymentforcredit = $payment-$order->coupon_discount;
                $balPaymentforcredit = $payment-$order->coupon_discount - $advamount;
                } 

                }
                ?> </div>
                </div></br>
               </br>
                <p><span style="font-weight: 400; margin-left:-5px;">This is computer generated invoice and do not require any stamp or signature.</span><span style="font-weight: 400;"><br /></span><span style="font-weight: 400;">Returns Policy:</span><span style="font-weight: 400;"><br /></span><span style="font-weight: 400;">We only replace items if they are defective or damaged. Our policy lasts 3 days. If 3 days have gone by since your purchase, unfortunately we cannot offer you a exchange.</span><span style="font-weight: 400;"><br /></span><span style="font-weight: 400;">To be eligible for a exchange/replacement, your item must be:</span></p>
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
       
</body>
</html>
