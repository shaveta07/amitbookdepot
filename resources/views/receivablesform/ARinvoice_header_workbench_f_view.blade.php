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
			@if(session()->get('msg') != null)
			<div class="alert alert-danger">{{ session()->get('msg') }}</div>
			@endif
        </div>
        <div class="panel-body">
        
				<table>
					<tbody><tr>
					<td>
					<table style="width:800px;" border="0">
						<tbody><tr><td>Invoice Number:</td><td>{{$order->invoicenumber}}</td>
						<td>Customer ID:</td><td>{{$order->customerid}}</td></tr>
						<tr><td>Invoice Type:</td><td>{{$order->invoicelookuptype}}</td>
						<td>Invoice Date:</td><td>{{$order->invoicedate}}</td></tr>
						<tr><td>Invoice Status:</td><td>
                        @if($order->status == 'O'){{_('Open')}} @endif
                        @if($order->status == 'P'){{_('Paid')}} @endif
                        @if($order->status == 'C'){{_('cancel')}} @endif
                        </td>
						<td>Description:</td><td><{{$order->description}}/td></tr>
						<!-- <tr><td>Last Updated By:</td><td>braj@gmail.com</td><td>Last Updated Date:</td><td>{{$order->updated_at}}</td></tr> -->
						<tr><td>GSTIN</td><td>{{$order->gstin}}</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						</tbody>
					</table><br>

					</td>
					
					</tr>
					</tbody>
				</table>
				<div  class="table-responsive">
				<table id="invtbl" style= "border-color: rgb(17, 2, 1);" class="table table-bordered" >
			  
						<tr><th>S.N.</th><th>ISBN ID</th><th>Book</th>
						<th>Quantity</th>
						<th>Paid From</th>
						<th>Pay Date</th>
						<th>Final Amount</th>
                        <th>Application Number</th>
                        <th>Recieved Amount</th>
                        <th>Status</th>
                        <th>Activity</th>
                        <th>Form Link</th></tr>
							<?php
							$inc=0;
							$payment=0;
							
							$invoice_line_da = \App\ArInvoiceLinesF::where('invoiceid',$order->invoiceid)->get();
						
							
							if(sizeof($invoice_line_da)>0) {
							//die("bcddd");
							foreach($invoice_line_da as $invoice_line_data ){
								$isbn='';
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
								
							?>
                            <tr><td style="border:1px solid;"><?php echo ++$inc; ?></td><td style="border:1px solid;"><?php echo $isbn; ?></td><td style="border:1px solid;"><?php echo $product_name; ?></td>
                            <!--<td style="border:1px solid;"><?php //echo $invoice_line_data[$i]['Amount']; ?></td> -->
                            <!--<td style="border:1px solid;"><?php //echo $invoice_line_data[$i]['Discount']; ?></td> -->
                            <td style="border:1px solid;"><?php echo $invoice_line_data->quantity; ?></td>
                            <!--<td style="border:1px solid;"><?php //echo $invoice_line_data[$i]['TransactionType']; ?></td>-->
                            <form method="post">
                            <td style="border:1px solid;"> <?php if($isbn != 'misleniousfrm'){ ?>
                            <select class="pm_type" data-recieve="<?php echo $invoice_line_data->recievedamount; ?>" data-sc="<?php echo $pay_line1=($invoice_line_data->amount-$invoice_line_data->discount)*$invoice_line_data->quantity;  ?>" id="pm_type-<?php echo $invoice_line_data->lineid; ?>" data-id="<?php echo $invoice_line_data->lineid; ?>" name="pm_type[<?php echo $invoice_line_data->lineid; ?>]" id="pm_type[<?php echo $invoice_line_data->lineid; ?>]">
                            <option value="<?php echo $invoice_line_data->paybank; ?>"><?php echo $invoice_line_data->paybank; ?></option>
                            <option value=""></option>
                            <option value="SBT AS">SBT AS</option>
                            <option value="HDFC AS">HDFC AS</option>
                            <option value="HDFC GD">HDFC GD</option>
                            <option value="SBOP ABD">SBOP ABD</option>
                            <option value="HDFC CREDIT CARD">HDFC CREDIT CARD</option>
                            <option value="HDFC AMIT SINGH">HDFC AMIT SINGH</option>
                            <option value="OTHERS">OTHERS</option>
                            <option value="BANK CHALAN">BANK CHALAN</option>
                            <option value="CUSTOMER CARD/BANKING">CUSTOMER CARD/BANKING</option>
                        </select>
                        <?php } ?>
                            </td>
                            
                            <td style="border:1px solid;">
                                <?php if($invoice_line_data->isbn != 'misleniousfrm'){ ?>
                                <input type="date" id="pay_date-<?php echo $invoice_line_data->lineid; ?>" name="pay_date[<?php echo $invoice_line_data->lineid; ?>]" class='calendar' value="<?php echo $invoice_line_data->paydate; ?>" />
                                <?php } ?>
                                </td>
                            <!--<td style="border:1px solid;"><?php // if($invoice_line_data[$i]['TransactionType']=="R") echo $invoice_line_data[$i]['ItemPrice']*$invoice_line_data[$i]['Quantity'];  ?></td> -->
                            <td style="border:1px solid;">
                                
                                <?php echo $pay_line=($invoice_line_data->amount-$invoice_line_data->discount)*$invoice_line_data->quantity;   ?>
                                
                                </td>
                            <td style="border:1px solid;">
                                <?php if($invoice_line_data->isbn != 'misleniousfrm'){ ?>
                                <input type="text" id="application-<?php echo $invoice_line_data->lineid; ?>" name="application[<?php echo $invoice_line_data->lineid; ?>]" value="<?php echo $invoice_line_data->application;  ?>" />
                                <?php } ?>
                                </td>
							<?php $formlink = \App\Product::select('formlink')->where('id',$invoice_line_data->itemid);
							  ?>
                            <td style="border:1px solid;">
                                <?php if($invoice_line_data->isbn != 'misleniousfrm'){ 
                                    $rrr = \App\Formstatus::where('lineid',$invoice_line_data->lineid)->get();
                                  
                                    ?>
                                    <input <?php if(count($rrr) > 0 && Auth::user()->email == 'amitbookdepot.net@gmail.com'){ echo "readonly"; }  ?> type="text" class="recievedamount" data-id="<?php echo $invoice_line_data->lineid; ?>" name="recievedamount[<?php echo $invoice_line_data->lineid; ?>]" id="recievedamount-<?php echo $invoice_line_data->lineid; ?>" class="form-control" value="<?php echo $invoice_line_data->recievedamount; ?>"/>
                                
                                <?php $payment += $invoice_line_data->recievedamount; } ?>
                                </td>
                            <td style="border:1px solid;">
                                <?php if($invoice_line_data->isbn != 'misleniousfrm'){ ?>
                                <select class="cstatus" id="status-<?php echo $invoice_line_data->lineid; ?>" data-id = "<?php echo $invoice_line_data->lineid; ?>">
                                    <option value="" <?php if($invoice_line_data->status == ''){echo "selected"; } ?>> No Status</option>
                                    <option value="cancel" <?php if($invoice_line_data->status == 'cancel'){echo "selected"; } ?>> Canceled</option>
                                    <option value="incomplete" <?php if($invoice_line_data->status == 'incomplete'){echo "selected"; } ?>>In Complete</option>
                                    <option value="completed" <?php if($invoice_line_data->status == 'completed'){echo "selected"; } ?>>Completed</option>
                                </select>
                                <?php } ?>
                            </td>
                            <td style="border:1px solid;">
                                
                                <?php
                                //list($user) = get_query_list($con,"SELECT updatedby FROM `formstatus` a WHERE id=(select MIN(id) FROM formstatus b WHERE a.lineid = b.lineid GROUP BY lineid) AND lineid='".$invoice_line_data[$i]['LineID']."'  ORDER BY id ASC");
                               
                                $user2 = \App\ArInvoiceLinesF::select('lastattempt')->where('lineid',$invoice_line_data->lineid)->first();
								
								if($invoice_line_data->isbn != 'misleniousfrm'){ ?>
                                <a href="javascript::void(0)" class="activity btn btn-primary" id="activity-<?php echo $invoice_line_data->lineid; ?>" data-id = "<?php echo $invoice_line_data->lineid; ?>"> Activity</a><br/>
                                <?php
                                $rsts = \App\Formstatus::where('lineid',$invoice_line_data->lineid)->where('updatedby',Session::get('user'))->get();
                               
                                if($user2->lastattempt == Auth::user()->email || count($rsts) > 0 || Auth::user()->user_type == 'admin' || $user2 == ''){
                                ?>
                                <a href="javascript::void(0)" class="saveline btn btn-info" id="saveline-<?php echo $invoice_line_data->lineid; ?>" data-id = "<?php echo $invoice_line_data->lineid; ?>"> SaveLine</a>
                                <?php } ?>
                                <?php } ?>
                            </td>
                            
                            <td style="border:1px solid;">
                                <?php if($invoice_line_data->isbn != 'misleniousfrm'){ ?>
                                <?php if($formlink != '' && $invoice_line_data->status != 'completed'){ ?>
                                <a target ="_blank" href="saveformline.php?invoice_number=<?php echo $order->invoicenumber; ?>&itemid=<?php echo $invoice_line_data->itemid; ?>&id=<?php echo $invoice_line_data->lineid; ?>">Form Link</a>
                                <?php } ?>
                                <?php } ?>
                                </td>
                                
                            <?php  
                           
                            if(Session::get('user') != "amitbookdepot.net@gmail.com" || Session::get('role') == 1 || (count($rrr) == 0 && $Session::get('user') == 'amitbookdepot.net@gmail.com')){
                            if($order->status =="O" && $invoice_line_data->status != 'completed'){ ?>
                            <td>
                                <!-- <a href="delete-ARInvoice-line-f.php?id=<?php echo $invoice_line_data->lineid; ?>&invoice_number=<?php echo $order->invoicenumber; ?>" title="Delete">[X]</a> -->
                                <button  type='button'  style="border:none; padding:0px;" title='delete' class='delete_order_item' data-order-id='<?php echo $invoice_line_data->lineid ; ?>'><i class="fa fa-trash-o" style="font-size:24px;color:red"></i></button>
                                </td>
                                        <?php } } ?>
                            
                            </tr>
                      <?php              
					}
							}
							
							
							?>
				</table>
				</div>
				<br />
                <?php echo "Total Amount to be paid : <span id='totamt'>".$payment."</span>"; ?>
			
		</br>
		<?php
			if($order->status=="O") 
			{
			?>
        <form id="ProductSave" method="post" action="{{ route('ArInvoicesAllF.ProductSave') }}">
				  
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
		<?php } ?>
	<div style="float:right;">
		<form method="post" >
			 
				<?php ///change for status;
		
		
		if($order->status!="O"){
		 ?>
		<label>Change Status:</label>
		<select id="changestatus" class="changestatus" name="changestatus" >
		<option value="O" <?php if($order->status=="O"){echo "selected";} ?>>Open</option>
		<option value="C"  <?php if($order->status=="C"){echo "selected";} ?>>Cancel</option>
		<option value="P"  <?php if($order->status=="P"){echo "selected";} ?>>Paid</option>
		</select>
		<?php } ?>
		
		<select name="ModeOfPayment" id="myselect">
		<option value='Clearing' <?php if($order->payment_type == "Clearing"){echo "selected";} ?>>Clearing/Cash</option>
		<option value='Check' <?php if($order->payment_type =="Check"){echo "selected";} ?>>Check</option>
		<option value='CreditCard' <?php if($order->payment_type=="CreditCard"){echo "selected";} ?>>Credit/Debit Card</option>
		<option value='Electronic' <?php if($order->payment_type=="Electronic"){echo "selected";} ?>>Electronic</option>
		<option value='Wire' <?php if($order->payment_type=="Wire"){echo "selected";} ?>>Wire</option>
		
		</select>
		
		Description:
		<textarea name="description" id="desc"><?php echo $order->description; ?></textarea>
		<select name="action" onchange="PrintDiv(this.value)" >
			<?php  if($order->status=="O") {?>
			<option value="1" >Close and Save Invoice</option>
					<?php } ?>
							<option value="2">Print Invoice</option>
			
		</select> 
		<input type="button" value="Save Description" class="btn btn-primary" onclick=savedesc(); name="save_d"  /> 
				<input type="button" value="Ok" id="Save" class="btn btn-danger" name="close"  />
				<input type="hidden" id="payment_close" name="payment_amount_close" value="<?php echo $payment; ?>" />
		
		
		</form>
       
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
  $('#calendar').datepicker({ dateFormat: 'yy-mm-dd'});
  $('#clearancedate').datepicker({ dateFormat: 'yy-mm-dd'});
$('#keyword-box').val('');
$('#Save').click(function(){
	var id = {{ $order->invoiceid }};
	var modepayment = $( "#myselect option:selected" ).text();;
	var amt = $('#payment_close').val();
	var desc = $('#desc').val();
	// var status = $( "#changestatus option:selected" ).text();
	//alert(status);
	var payment = $('.famount').text();
	 $.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/ARinvoice_header_workbench_f/InvoiceEmail') }}',
		data: { id:id, modepayment:modepayment, desc:desc, status:status, amt:amt, _token: "{{ csrf_token() }}" },
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
	 var amt =  $('#payment_close').val();
	 $.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/ARinvoice_header_workbench_f/getDescription') }}',
		data: { desc:desc, id:id, amt:amt,  _token: "{{ csrf_token() }}" },
        //dataType: 'json',
        success: function (data) {
			//console.log(data);
			alert('send Sms on your Mobile');
			location.reload();
			//$('#desc').val();
		}
	 });
	 
  }

  function PrintDiv(x) { 

	
if(
	x == 1 || x == '1' ||
	x == 2 || x == '2'
){
	  var divToPrint = document.getElementById('divtoprint');
	window.open("{{ url('admin/ARinvoice_header_workbench_f/ARInvoice')}}/{{$order->invoiceid}}", '_blank', 'width=900,height=700');
}

$.ajax({
	url: "{{ url('admin/ARinvoice_header_workbench_f/getInvoicePrint')}}",
	type: 'POST',
	data: {
		"_token": "{{ csrf_token() }}",
		"_val": x,
		"_orderid": {{$order->invoiceid}}
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

  $('.changestatus').change(function(e){
	var inv = "<?php echo $order->invoiceid; ?>";
	$.ajax({
				async : false,
				url : '{{url('admin/ARinvoice_header_workbench_f/statusChangeOrder')}}',
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
		url: '{{ url('admin/ARinvoice_header_workbench_f/getbookdetail') }}',
		data: { keyword: $(this).val(),transaction_type: trxn_type, invoice_type: 's', store_id: '1',role:'1', _token: "{{ csrf_token() }}" },
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
				url : '{{ url('admin/ARinvoice_header_workbench_f/get_book_detail_invoice') }}', 
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
$.ajax({
				async : false,
				url: '{{ url('admin/ARinvoice_header_workbench_f/get_book_detail_invoice') }}', 
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


$('.cstatus').change(function(e){
var inv = "<?php echo $order->invoicenumber; ?>";
var line = $(this).attr('data-id');
var sts = $(this).val();
var user = "<?php echo Auth::user()->email; ?>";
var userId = "<?php echo Auth::user()->id; ?>";

if($('#pm_type-'+line).val() == ''){
	alert('Paid From is Empty');
	return false;
	}

if($('#pay_date-'+line).val() == ''){
	alert('Paid Date is Empty');
	return false;
	}
	
if($('#application-'+line).val() == ''){
	alert('Application Form number is Empty');
	return false;
	}

if(sts == ''){
	return false;
	}
	$.ajax({
				async : false,
				url: '{{ url('admin/ARinvoice_header_workbench_f/change_status_formline') }}', 
				type : "POST",
				data : {'invoice_line' : line,'status':sts,'user':user,'userId':userId, _token: "{{ csrf_token() }}" },
				dataType : 'text',
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
					if(dataType == '1'){
						setTimeout(function(){// wait for 5 secs(2)
						location.reload(); // then reload the page.(3)
					}, 1000); 
						}
					if(dataType == '2'){
						alert('You can not update this status. Status will update by First attempt user. Please click on activity to see about user.');
						}
				}
			});

});

$('.saveline').click(function(e){

var inv = "<?php echo $order->invoicenumber; ?>";
var line = $(this).attr('data-id');
var sts = $('#status-'+line).val();
var user = "<?php echo Auth::user()->email; ?>";
var userId = "<?php echo Auth::user()->id; ?>";


if($('#status-'+line).val() == 'completed'){

if($('#pm_type-'+line).val() == ''){
	alert('Paid From is Empty');
	return false;
	}

if($('#pay_date-'+line).val() == ''){
	alert('Paid Date is Empty');
	return false;
	}
	
if($('#application-'+line).val() == ''){
	alert('Application Form number is Empty');
	return false;
	}
}
if($('#status-'+line).val() == ''){
	return false;
	}
		//alert('testt');
	$.ajax({
				
				url: '{{ url('admin/ARinvoice_header_workbench_f/save_status_formline') }}', 
				type : "POST",
				data : {'invoice_line' : line,'status':sts,'user':user,'userId':userId,'bank':$('#pm_type-'+line).val(),'pay_date':$('#pay_date-'+line).val(),'application':$('#application-'+line).val(),'recievedamount':$('#recievedamount-'+line).val(), _token: "{{ csrf_token() }}"  },
				dataType : 'text',
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
					if(dataType == '1'){
						setTimeout(function(){// wait for 5 secs(2)
						location.reload(); // then reload the page.(3)
					}, 1000); 
						}
					if(dataType == '2'){
						alert('You can not update this status. Status will update by First attempt user. Please click on activity to see about user.');
						}
				}
			});

});


$('.activity').click(function(e){
var line = $(this).attr('data-id');
var role = '<?php echo Auth::user()->user_type ?>';
if(role != "admin"){
	//return false;
	}
$.ajax({
				url: '{{ url('admin/ARinvoice_header_workbench_f/get_status_formline') }}', 
				type : "POST",
				data : {'invoice_line' : line, _token: "{{ csrf_token() }}" },
				dataType : 'html',
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
					alert(dataType);
				}
			});
});

$('.calendar').datepicker({ dateFormat: 'yy-mm-dd' });
$('.pm_type').change(function(e){
	var paym = $(this).val();
	var data_recieve = parseInt($(this).attr('data-recieve'));
	var data_sc = parseInt($(this).attr('data-sc'));
	if((data_recieve > data_sc) && (paym == 'OTHERS' || paym == 'BANK CHALAN' || paym == 'CUSTOMER CARD/BANKING')){
		alert('Please check amount carefully');
		}
		
	if((data_recieve <= data_sc) && (paym != 'OTHERS' && paym != 'BANK CHALAN' && paym != 'CUSTOMER CARD/BANKING')){
		alert('Please check amount carefully');
		}
		
	});

$('.recievedamount').change(function(e){
	var did = $(this).attr('data-id');
	$('#pm_type-'+did).attr('data-recieve',$(this).val());
	});
	
$('.recievedamount').change(function(e){
	var line = $(this).attr('data-id');
	var amt = $(this).val();
	var user = "<?php echo Auth::user()->email ?>";
	var userId = "<?php echo Auth::user()->id; ?>";
	$.ajax({
			
				url: '{{ url('admin/ARinvoice_header_workbench_f/save_recieved_amount') }}',
				type : "POST",
				data : {'invoice_line' : line,'recievedamount':amt,'user':user,'userId':userId,  _token: "{{ csrf_token() }}"  },
				dataType : 'text',
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
					if(dataType == '1'){
						setTimeout(function(){// wait for 5 secs(2)
							location.reload(); // then reload the page.(3)
						}, 1000); 
						}
					if(dataType == '2'){
						alert('You can not update this status. Status will update by First attempt user. Please click on activity to see about user.');
						}
				}
			});
});





$(".delete_order_item").click(function(){
	
	var _item_id = $(this).attr('data-order-id');
	//alert(_item_id);
	$.post("{{ url('admin/ARinvoice_header_workbench_f/destroyLine') }}", {
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