<?php
/*
Author: Diljot Singh
Version: 1.2
Description: AR Invoice line creation 
1.1 - 17 , 39 Customer ID mandatory for payment method (instituite and supplier)
1.2 - Save description added on 19 Nov 16
*/
include_once('includes/session_check.php');
require_once("DAL/jcon.php");
require_once("DAL/CustomerDAL.php");
$customerdal = new CustomerDAL();
//
require_once("DAL/BookDAL.php");
$bookdal = new BookDAL();
//

$invoice_number=$_GET['invoice_number'];
// 
require_once("DAL/ARInvoiceDAL.php");
$arinvoicedal = new ARInvoiceDAL();

// get store from user name
$store_id_array=$arinvoicedal->userStore($_SESSION['user']);
$store_id=$store_id_array[0]['loc_id'];
// get invoice header detail
$invoice_header_data=$arinvoicedal->viewInvoiceHeader($invoice_number,$store_id);
$invoice_id=$invoice_header_data[0]['InvoiceID'];
// close invoice
if(isset($_POST['close']))
{
// validate supplier and institute should have payment method
if(($invoice_header_data[0]['CustomerID']==17 or $invoice_header_data[0]['CustomerID']==39) and $_POST['ModeOfPayment']=='')
{
$msg="<div class='error'>Mode of Payment mandatory for Institutes and Supplier </div>";
}
else{
//
if($_POST['action']==1)
{
$arinvoicedal->closeInvoice($invoice_id,$_POST['payment_amount_close'],$_POST['ModeOfPayment'],$_POST['description']);
/////Braj Code
if($_POST['crins'] == 'Y'){
	$sql = "insert into prepaidcredit (prebookingid ,credit, updateddate)values('".$_POST['orderid']."','".$_POST['credit']."','".date('Y-m-d H:i:s')."')";
	executequery($con,$sql);
	}else{
	$sql = "update prepaidcredit set credit = '".$_POST['credit']."', updateddate = '".date('Y-m-d H:i:s')."' where prebookingid = '".$_POST['orderid']."'";	
	executequery($con,$sql);
	}
header("Location: ARinvoice-lines-workbench_prebooking.php?sts=print&invoice_number=".$invoice_number);
}
else if($_POST['action']==2){
	 $arinvoicedal->saveDescription($_POST['description'],$invoice_id,$_POST['payment_amount_close'],$_POST['ModeOfPayment'],$_POST['paydate']);
	 header("Location: ARinvoice-lines-workbench_prebooking.php?sts=print&invoice_number=".$invoice_number);
 }
else
{
//close and print invoice
$arinvoicedal->closeInvoice($invoice_id,$_POST['payment_amount_close'],$_POST['ModeOfPayment'],$_POST['description'],$_POST['paydate']);
//Braj code
if($_POST['crins'] == 'Y'){
	$sql = "insert into prepaidcredit (prebookingid ,credit, updateddate)values('".$_POST['orderid']."','".$_POST['credit']."','".date('Y-m-d H:i:s')."')";
	executequery($con,$sql);
	}else{
	$sql = "update prepaidcredit set credit = '".$_POST['credit']."', updateddate = '".date('Y-m-d H:i:s')."' where prebookingid = '".$_POST['orderid']."'";	
	executequery($con,$sql);
	}
	
header("Location: ARInvoice_EmailPre.php?id=".$invoice_number);
}
//header("Location: ARinvoice.php?id=".$invoice_number);
//$url_invoice="ARinvoice.php?id=".$invoice_number;
//$action_url="<script>window.open('".$url_invoice."');</script>";
//header("Location: ARinvoice.php?id=".$invoice_number);
} // validate supplier and institute 
}
if(isset($_GET['sts']) && $_GET['sts'] == 'print'){
$url_invoice="ARinvoice.php?id=".$invoice_number;
echo $action_url="<script>window.open('".$url_invoice."');</script>";
}
//
if(isset($_POST['save_d']))
{
$arinvoicedal->saveDescription($_POST['description'],$invoice_id,null,$_POST['ModeOfPayment'],$_POST['paydate']);
//$msg= "<div class='success'>Changes Saved Successfully , please click <a href='ARinvoice-lines-workbench.php?invoice_number=".$invoice_number."'>here</a> to see the changes</div>";
//die($msg);
// redirect to same page to fetch lines data
header("Location: ARinvoice-lines-workbench_prebooking.php?invoice_number=".$invoice_number);
}
//
if(isset($_POST['save']))
{
//print_r($_POST);die();
if($_POST['bundleproduct'] == "0"){
$msg="";
$prepay=$_POST['prepay'];
$sale_rent=$_POST['sale_rent']; // from ajax
$transaction_type=$_POST['transaction_type']; // from form
$quantity=$_POST['qty'];
$quantity_a=$_POST['qty_a']; // qty available in stock
// check qty
if($quantity_a<$quantity) $msg="<div class='error'>Quantity not available !!</div>";
// books for rent can also be sold , but books for sale cannot be leased
if($transaction_type=="S")
{
//$transaction_type=$sale_rent;
//$transaction_type=$sale_rent;
$item_price=$_POST['SellingPrice'];  // selling price
list($igst,$gst) = get_query_list($con,"select igst,gst from Books where BookId = ".$_POST['keyword']);
$tax= 0;
if($igst != 0 and $igst != '' ){
	$tax=$igst;
	}else if($gst != 0 and $gst != '' ){
	$tax=$gst;
	}
$baseprice = $item_price * 100/(100+$tax);

$item_id=$_POST['keyword']; // book id
if($sale_rent=="S" and $transaction_type=="R") $msg="<div class='error'>Book on sale cannot be given on rent</div>";
$amount=$_POST['mrp']; // MRP
//if(isset($_POST['security'])) {$amount=$_POST['security']; $item_price=$_POST['security'];}
if($amount<=0 and $prepay!="-1") $msg="<div class='error'>Choose Sale/Rent</div>";
$discount=$amount-$item_price;
$security=0;
$due_date="00-00-00";
$description="";
if($msg=="")
{
//$insert_line=$arinvoicedal->addInvoiceLine($invoice_id, $item_id, $transaction_type, $item_price, $quantity, $discount, $security, $amount,$due_date,$description,$_SESSION['user']);
//
$insert_line=$arinvoicedal->addInvoiceLinegst($invoice_id, $item_id, $transaction_type, $item_price, $quantity, $discount,$baseprice, $security, $amount,$due_date,$description,$_SESSION['user']);
$qty_inventory=$quantity_a-$quantity;

executequery($con,"INSERT INTO `aparlog` (`id`, `bookid`,`qty`,`updateqty`, `apid`, `arid`, `updatedby`, `updateddate`) VALUES (NULL, '$item_id','$quantity_a','-".$quantity."', NULL, '$invoice_id', '".$_SESSION['user']."', CURRENT_TIMESTAMP)");
//executequery($con,"INSERT INTO `books_log` SELECT * FROM Books WHERE BookId = $item_id");

$inventory_book_update=$bookdal->updateBookQuantity($qty_inventory,$item_id); // update inventory
list($vm_id) = get_query_list("select virtuemart_product_id from Books where BookId = '".$item_id."'");
updateqtytositeminus($vm_id,$quantity,$item_id,'',$invoice_id);  /// update quantity to website

if(!$inventory_book_update) {$msg="<div class='error'>Inventory Update Failed</div>"; die();}
if($insert_line) $msg="<div class='success'>Line Added Successfully</div>";
else $msg="<div class='error'>Failed</div>";
}
}
else
{
// due date for rent 
$date = date("Y-m-d");
$due_date = strtotime(date("Y-m-d", strtotime($date)) . " +15 days");
$due_date = date("Y-m-d",$due_date);
//
$sale_rent=$_POST['sale_rent']; // from ajax
$transaction_type=$_POST['transaction_type']; // from form
//$transaction_type=$sale_rent;
if($sale_rent=="S" and $transaction_type=="R") $msg="<div class='error'>Book on sale cannot be given on rent</div>";
$item_id=$_POST['keyword']; // book id

$amount=$_POST['security']; // security
$security=$_POST['security']; // security
$discount=0;
$rent=$_POST['rent'];
$item_price=$rent;
$refund=$item_price-$rent;
// item price will have rent amount , amount will have security or MRP
$description="";
if($amount<=0) $msg="<div class='error'>Choose Sale/Rent</div>";

if($msg=="")
{
$insert_line=$arinvoicedal->addInvoiceLine($invoice_id, $item_id, $transaction_type, $item_price, $quantity, $discount, $security, $amount,$due_date,$description,$_SESSION['user']);
//
$qty_inventory=$quantity_a-$quantity;

executequery($con,"INSERT INTO `aparlog` (`id`, `bookid`,`qty`,`updateqty`, `apid`, `arid`, `updatedby`, `updateddate`) VALUES (NULL, '$item_id','$quantity_a','-".$quantity."', NULL, '$invoice_id', '".$_SESSION['user']."', CURRENT_TIMESTAMP)");
//executequery($con,"INSERT INTO `books_log` SELECT * FROM Books WHERE BookId = $item_id");

$inventory_book_update=$bookdal->updateBookQuantity($qty_inventory,$item_id); // update inventory

list($vm_id) = get_query_list("select virtuemart_product_id from Books where BookId = '".$item_id."'");
updateqtytositeminus($vm_id,$quantity,$item_id,'',$invoice_id);  /// update quantity to website

if(!$inventory_book_update) {$msg="<div class='error'>Inventory Update Failed</div>"; die();}
//
if($insert_line) $msg="<div class='success'>Line Added Successfully</div>";
else $msg="<div class='error'>Failed</div>";
}
}

}else{
	/////////////Start Bundle product/////////////////////////////////////
 $bundle_id = $_POST['bundleproduct'];
$bundle_lines = get_all_array($con,"SELECT * FROM `bundle_product_line` where bundle_id = '$bundle_id'");
//print_r($bundle_lines);
//die;
$flag = 1;$notavail=array();
foreach($bundle_lines as $bundle_line):
$arr = array();
$arr['InvoiceID'] = $invoice_id;

$arr['ItemID'] = $bundle_line['bookid'];
$arr['TransactionType'] = $bundle_line['booktype'];
list($mrp,$discount,$sellingprice,$bookqty,$saleRent,$rentAmount,$rentsecurity,$virtuemart_product_id)=get_query_list($con,"select Mrp,Discount,SellingPrice,Quantity,SaleRent,rent_amount,rent_security,virtuemart_product_id from Books where BookId = ".$bundle_line['bookid']);
//$arr['ItemPrice'] = $sellingprice;
$arr['Quantity'] = $bundle_line['quantity'];




if($bundle_line['booktype'] == 'S'){
$due_date = '0000-00-00';	
$arr['Security'] = '0';
$arr['ItemPrice'] = $sellingprice;
$arr['Amount'] = $mrp;
$arr['RentDueDate'] = $due_date;
$arr['Discount'] = $discount;
}else{
$date = date("Y-m-d");
$due_date = strtotime(date("Y-m-d", strtotime($date)) . " +15 days");
$due_date = date("Y-m-d",$due_date);
$arr['Security'] = $rentsecurity;	
$arr['Amount'] = $rentsecurity;
$arr['ItemPrice'] = $rentAmount;
$arr['RentDueDate'] = $due_date;
$arr['Discount'] = 0;
}


$arr['Description'] = '';
$arr['LastUpdateDate'] = date('Y-m-d H:i:s');
$arr['LastUpdate'] = $_SESSION['userId'];
$arr['IsDeleted'] = 'N';
$qty_inventory=$bookqty-$bundle_line['quantity'];
if($qty_inventory >= 0){
if(insertquery($con,'AR_Invoice_Lines',$arr)){
	$flag = 2;

executequery($con,"INSERT INTO `aparlog` (`id`, `bookid`,`qty`,`updateqty`, `apid`, `arid`, `updatedby`, `updateddate`) VALUES (NULL,'".$bundle_line['bookid']."', '$bookqty','-".$bundle_line['quantity']."', NULL, '$invoice_id', '".$_SESSION['user']."', CURRENT_TIMESTAMP)");
//executequery($con,"INSERT INTO `books_log` SELECT * FROM Books WHERE BookId = '".$bundle_line['bookid']."'");

$inventory_book_update=$bookdal->updateBookQuantity($qty_inventory,$bundle_line['bookid']); // update inventory
updateqtytositeminus($virtuemart_product_id,$bundle_line['quantity'],$bundle_line['bookid'],'',$invoice_id);  /// update quantity to website
	}
}else{
/// sufficient Quantity not available	
	$notavail[] = $bundle_line['bookid'];
}


endforeach;

if(count($notavail)>0){
	$msg="<div class='success'>Line Added Successfully</div>";
	$invn = $_GET['invoice_number'];
	$notavailable = implode(",",$notavail);
	header("Location:ARinvoice-lines-workbench_prebooking.php?invoice_number=$invn&notavail=$notavailable");
	
	}else{
	$msg="<div class='success'>Line Added Successfully</div>";
	$invn = $_GET['invoice_number'];
	header("Location:ARinvoice-lines-workbench_prebooking.php?invoice_number=$invn");
	}

}
/////////////End Bundle product/////////////////////////////////////
	


}
// get book detail for displaying invoice line
$invoice_line_data=$arinvoicedal->displayInvoiceLine($invoice_id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AR Invoice Line For PreOrder</title>
<?php include('includes/links.php'); ?>
<style>
#book-list{float:left;list-style:none;margin:0;padding:0;width:650px;}
#book-list li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
#book-list li:hover{background:#FFFF00;}
.disable-select {
display:none;
}
</style>

</head>
<body>
<?php if(isset($action_url)) echo $action_url; ?>
    <div id="wrapper">
<?php include('includes/navigation.php'); ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">AR Invoice Lines for Preorder</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			 <table style="width:800px;" border="0">
		    <tr><td>Invoice Number:</td><td><?php echo $invoice_header_data[0]['InvoiceNumber']; ?></td>
		    <td>Customer ID:</td><td><a href="edit-customer.php?customer-id=<?php echo $invoice_header_data[0]['CustomerID']; ?>" target="_blank"><?php echo $invoice_header_data[0]['CustomerID']; ?></a></td></tr>
		    <tr><td>Invoice Type:</td><td><?php if($invoice_header_data[0]['InvoiceLookupType']=="S") echo "Standard"; elseif($invoice_header_data[0]['InvoiceLookupType']=="P") echo "Prepayment"; elseif($invoice_header_data[0]['InvoiceLookupType']=="C") echo "Credit Memo"; else echo "Debit Memo";  ?></td>
		    <td>Invoice Date:</td><td><?php echo $invoice_header_data[0]['InvoiceDate']; ?></td></tr>
		    <tr><td>Invoice Status:</td><td><?php if($invoice_header_data[0]['Status']=="O") echo "Open"; else if($invoice_header_data[0]['Status']=="P") echo "Paid"; else echo "Cancelled"; ?></td>
		    <td>Description:</td><td><?php echo $invoice_header_data[0]['Description']; ?></td></tr>
		    <?php list($mobile1) = get_query_list($con,"select Mobile1 FROM `AR_Customers` where CustomerID = '".$invoice_header_data[0]['CustomerID']."'"); ?>
		    <tr><td>Customer Mobile:</td><td><?php echo $mobile1; //hidemobile($_SESSION['userId'],$mobile,$_SESSION['Role']); ?></td>
		    <?php list($gstin) = get_query_list($con,"select gstin from AR_Customers where CustomerID = '".$invoice_header_data[0]['CustomerID']."'"); 
		    if($gstin != ''){
		    ?>
		    <td>GSTIN</td><td><?php echo $gstin; ?></td>
		    <?php }else{
				echo "<td>&nbsp;</td><td>&nbsp;</td>";
				} ?>
		    </tr>
			</table><br />
          <table style="width:990px;" >
		  <tr><th style="border:1px solid;">S.N.</th><th style="border:1px solid;">ISBN ID</th><th style="border:1px solid;">Book</th><th style="border:1px solid;">MRP/Security</th>
		  <th style="border:1px solid;">SGST</th>
		  <th style="border:1px solid;">CGST</th> 
		  <th style="border:1px solid;">GST</th>
		  <th style="border:1px solid;">IGST</th>
		  <th style="border:1px solid;">Base Price</th>
		  <th style="border:1px solid;">Discount</th><th style="border:1px solid;">Quantity</th><th style="border:1px solid;">Sale/Rent</th><th style="border:1px solid;">Rent</th><th style="border:1px solid;">Final Amount</th><th style="border:1px solid;">LastupdateddQTY</th><th style="border:1px solid;">LastLines</th></tr>
		    <?php
			$payment=0;$inc=0;$_igst=$_sgst=$_gst=$_cgst=$_baseprice=$_discount=0;
			list($pamount) = get_query_list($con,"select Amount from AR_Invoices_All where InvoiceNumber = '".$_GET['invoice_number']."'");
			//echo "select Amount from AR_Invoices_All set where InvoiceNumber = '".$_GET['invoice_number']."'";
			for($i=0;$i<count($invoice_line_data);$i++)
			{
				$_igst = $_igst+$invoice_line_data[$i]['igst']*$invoice_line_data[$i]['Quantity'];
			$_cgst = $_cgst+$invoice_line_data[$i]['cgst']*$invoice_line_data[$i]['Quantity'];
			$_sgst = $_sgst+$invoice_line_data[$i]['sgst']*$invoice_line_data[$i]['Quantity'];
			$_gst = $_gst+$invoice_line_data[$i]['gst']*$invoice_line_data[$i]['Quantity'];
			$_baseprice = $_baseprice+$invoice_line_data[$i]['baseprice']*$invoice_line_data[$i]['Quantity'];
			$_discount = $_discount + $invoice_line_data[$i]['Discount']*$invoice_line_data[$i]['Quantity'];
			?>
			<tr><td style="border:1px solid;"><?php echo ++$inc; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Isbn1']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Name']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Amount']; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['sgst']; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['cgst']; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['gst']; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['igst']; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['baseprice']; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Discount']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['Quantity']; ?></td><td style="border:1px solid;"><?php echo $invoice_line_data[$i]['TransactionType']; ?></td><td style="border:1px solid;"><?php if($invoice_line_data[$i]['TransactionType']=="R") echo $invoice_line_data[$i]['ItemPrice']*$invoice_line_data[$i]['Quantity'];  ?></td><td style="border:1px solid;"><?php echo $pay_line=($invoice_line_data[$i]['Amount']-$invoice_line_data[$i]['Discount'])*$invoice_line_data[$i]['Quantity']; $payment+=$pay_line;  ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['lastupdateddate']; ?></td>
			<td style="border:1px solid;"><?php echo $invoice_line_data[$i]['lastupdatedline']; ?></td>
			<?php  if($invoice_header_data[0]['Status']=="O"){ ?>
			<td><a href="delete-ARInvoice-line.php?id=<?php echo $invoice_line_data[$i]['LineID']; ?>&invoice_number=<?php echo $invoice_number; ?>" title="Delete">[X]</a></td>
			<?php } ?>
			</tr>
			<?php
			}
			?>
			</table><br />
			<div class="col-sm-12">
			<div class="col-sm-6"> 
				
				
			<?php //echo "Amount exclusive Tax : ".$_baseprice; ?><br/>
			<?php echo "Amount exclusive Tax : ".($pamount-($_sgst+$_cgst+$_gst+$_igst)); ?><br/>
			<?php echo "SGST : ".$_sgst; ?><br/>
			<?php echo "CGST: ".$_cgst; ?><br/>
			<?php echo "GST : ".$_gst; ?><br/>
			<?php echo "IGST: ".$_igst; ?><br/>
			<?php echo "Total Discount: ".$_discount; ?><br/>
			<?php echo "Total Amount inclusive Tax : ".$pamount; ?><br/>
			<?php //echo "Total Amount inclusive Tax : ".$payment; ?><br/>
			<?php 
			//print_r($invoice_header_data[0]);
			list($paidamount) = get_query_list($con,"SELECT sum(paid) FROM `prebooking_payment` where invoiceid = '".$invoice_header_data[0]['preorderid']."'");
			//echo $invoice_header_data[0]['preorderid'];
			list($discount) = get_query_list($con,"select coupon_discount from prebooking where InvoiceID = '".$invoice_header_data[0]['preorderid']."'");
			//echo "test".$discount;
			$total_paid = $paidamount+$discount;
			$rrr = get_all_array($con,"SELECT * FROM `AR_Invoices_All` where Status = 'P' and preorderid = '".$invoice_header_data[0]['preorderid']."'");
			$pamount = 0;
			foreach($rrr as $r):
			list($t) = get_query_list($con,"select sum(ItemPrice*Quantity) from AR_Invoice_Lines where IsDeleted='N' and InvoiceID = '".$r['InvoiceID']."'");
			$pamount = $pamount+$t;
			endforeach;
			$credit = 0;
			/*
			if(count($rrr) > 0){
				list($credit) = get_query_list($con,"select credit from prepaidcredit where prebookingid = '".$invoice_header_data[0]['preorderid']."'");	
				}else{
				$credit = $paidamount + $discount;	
				}
			*/
			list($credit) = get_query_list($con,"select credit from prepaidcredit where prebookingid = '".$invoice_header_data[0]['preorderid']."'");
			list($amts) = get_query_list($con,"SELECT sum(paid) FROM `prebooking_payment` where invoiceid = '".$invoice_header_data[0]['preorderid']."'");
			list($samount)=get_query_list($con,"select sum(Amount) from AR_Invoices_All where preorderid = '".$invoice_header_data[0]['preorderid']."'");
			$crins = 'N';
			if($credit == ''){
				$credit = $paidamount + $discount;
				//echo echo "test";
				$crins = 'Y';
				}
			//echo $credit;
			$current_payment=  $balance = $payment - $credit; ///// total amount to be paid
			$balancecredit = 0;
			if($balance < 0){
				$current_payment = 0;   /////if Credit is greater then current
				$balancecredit = abs($balance);
			}else{
				$balancecredit = 0;
				}
			?>
			<?php echo "Total Amount to be pay: ".$current_payment; ?><br/>
			<?php
			/////insert query for reedem
			//$pay = ($payment-$discount);
			//$advancepaid = $paidamount - $reedem; 
			
			?>
			
			<?php echo "Balanced in Advance Payment: ".($amts - $samount) ; ?><br/>
			
			</div>
			
			<div class="col-sm-6"> 
				<div class="row">
				<label>Order Number of this AR:</label>
				<div>
				
				<?php list($ordernumber)= get_query_list($con,"select InvoiceNumber from prebooking where InvoiceID = '".$invoice_header_data[0]['preorderid']."'"); 
				//echo $ordernumber;
				?>
				<a target="_blank" href="preOrderBookingLines.php?ar=exist&print&invoice_number=<?php echo $ordernumber; ?>"><?php echo $ordernumber; ?></a>
				</div>
				</div>
			</div>
			</div>
			<?php
			if($invoice_header_data[0]['Status']=="O")
			{
			?>
		  <form method="post"><?php if(isset($msg)) echo $msg; ?>
		<table style="width:800px;"><tr  id="booktype"><td valign="top">Choose*</td><td><select name="transaction_type" id="transaction_type_id">
		  <option value="S">Sale</option>
		  <option value="R">Rent</option>
		  </select></td></tr>
		  <tr id="bookquty"><td valign="top">Quantity*</td><td><input type="text" name="qty" value="1" required="required" /></td></tr>
		<input type="hidden" name="bundleproduct" id="bundleproduct"  value="" />
		<div id="changeValues"></div>
		<?php if(!isset($action_url)) { ?>
		<tr><td><input type="submit" name="save" value="Add Book" /></td></tr>
		<?php } ?>
	<tr><td></td><td><input type="text" name="keyword" id="keyword-box" required="required"  style="width: 500px;" placeholder="Search Book" /><div id="suggesstion-box"></div></td></tr>
	<table>
	<hr />
		</form>	
		<?php } ?>
		<div style="float:right;">
		<form method="post">
		<input type="hidden" name="credit" value="<?php echo $balancecredit; ?>" />
		<input type="hidden" name="orderid" value="<?php echo $invoice_header_data[0]['preorderid']; ?>" />
		<input type="hidden" name="crins" value="<?php echo $crins; ?>" />
		<select name="ModeOfPayment">
		<option value='Clearing' <?php if($invoice_header_data[0]['ModeOfPayment']=="Clearing"){echo "selected";} ?>>Clearing/Cash</option>
		<option value='Check' <?php if($invoice_header_data[0]['ModeOfPayment']=="Check"){echo "selected";} ?>>Check</option>
		<option value='CreditCard' <?php if($invoice_header_data[0]['ModeOfPayment']=="CreditCard"){echo "selected";} ?>>Credit/Debit Card</option>
		<option value='Electronic' <?php if($invoice_header_data[0]['ModeOfPayment']=="Electronic"){echo "selected";} ?>>Electronic</option>
		<option value='Wire' <?php if($invoice_header_data[0]['ModeOfPayment']=="Wire"){echo "selected";} ?>>Wire</option>
		<option value='paytm' <?php if($invoice_header_data[0]['ModeOfPayment']=="paytm"){echo "selected";} ?>>Paytm</option>
		<option value='Check-cce' <?php if($invoice_header_data[0]['ModeOfPayment']=="Check-cce"){echo "selected";} ?>>Check-CCE</option>
		</select>
		<input type="text" readonly placeholder="paydate" value="<?php echo $invoice_header_data[0]['paydate']; ?>" name="paydate" id="paydate" />
		Description:
		<textarea name="description"><?php echo $invoice_header_data[0]['Description']; ?></textarea>
		<select name="action">
				<?php  if($invoice_header_data[0]['Status']=="O") {?>
		<option value="1" >Close and Print Invoice</option>
				<?php } ?>
		<option value="2">Print Invoice</option>
		<option value="3" selected="selected">Close And Email Invoice</option>
		</select>
		<input type="submit" value="Save Description" name="save_d" /> 
		<input type="submit" value="Ok" name="close" />
		<input type="hidden" name="payment_amount_close" value="<?php echo $payment; ?>" />
		</form></div>
	<!--<h1><a href="ARinvoice.php?id=<?php //echo $invoice_number; ?>" target="_blank">Print Invoice</a></h1>  -->
	<div style="clear:both"></div>
        </div>
		   	<div style="clear:both"></div>     <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
<?php include('includes/footer.php'); ?>
<!--  for auto complete jquery plugin -->
<!-- script src="js/jquery-2.1.1.min.js" type="text/javascript"></script -->
<script>

// AJAX call for autocomplete 
$(document).ready(function(){
	$("#keyword-box").keyup(function(){
	var trxn_type=document.getElementById("transaction_type_id").value;
		$.ajax({
		type: "POST",
		url: "ajax_files/get_book_detail_ISBN.php",
		data:'keyword='+$(this).val()+'&transaction_type='+trxn_type+'&invoice_type=<?php echo $invoice_header_data[0]['InvoiceLookupType']; ?>&store_id=<?php echo $invoice_header_data[0]['store_id']; ?>&role=<?php echo $_SESSION['Role']; ?>',
		beforeSend: function(){
			$("#keyword-box").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
		},
		success: function(data){
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#keyword-box").css("background","#FFF");
		}
		});
	});
});
//To select book name
function selectbook(val,trxn,ptype) {
	
$("#suggesstion-box").hide();
	if(ptype == 'book'){
$('#bundleproduct').val("0");
$("#keyword-box").val(val);
$('#bookquty').show();
$('#booktype').show();
getBookDetails(val,trxn);
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
function getBookDetails(val,trxn){
	//alert(trxn);
var mobile				= $('#keyword-box').val();
//var trxn				= $('#transaction_type_id').val();
					
$.ajax({
				async : false,
				url : 'ajax_files/get_book_detail_invoice.php',
				type : "POST",
				data : {'keyword' : mobile, 'trxn' : trxn },
				dataType : 'text',
				timeout : 1000,
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
			//alert(dataType);
		$('#changeValues').html();
				$('#changeValues').html(dataType);
				$('#transaction_type_id').val(trxn);
										}
			});
}
</script>
 <link rel='stylesheet' type='text/css' href='//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css'/>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  <script>
	  $('#paydate').datepicker({ dateFormat: 'yy-mm-dd'});
  </script>
</body>
</html>
