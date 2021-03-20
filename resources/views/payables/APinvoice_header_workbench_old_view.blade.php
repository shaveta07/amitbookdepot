@extends('layouts.app')

@section('content')
<style>
.gstgrp input[type=text]{width:90px;}
.gstgrp input[type=radio]{height:10px !important}
.overlay1 {
  position: absolute;
  background: rgba(255,0,0,0.7);
  left: 0.7em;
  right: 0.7em;
  height: 10.2em;
  text-align: center;

}
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
            <h3 class="panel-title">{{__('AP Invoice Lines Workbench Old')}}</h3>
			@if(session()->get('msg') != null)
			<div class="alert alert-danger">{{ session()->get('msg') }}</div>
			@endif
        </div>
        <div class="panel-body">
     
            <table class="table table-responsive" >
				 <?php if($invoice_header_data->title != ''){
					 $invid = $invoice_header_data->invoiceid;
					 
					  ?>
					 
				
				 <?php } ?>
				 <tr><td>Invoice Number:</td><td><?php if(isset($invoice_header_data->image)){ ?><a <?php if($invoice_header_data->invoicetype == 'C'){ echo " style='color:red' "; }?> href="{{ url('public')}}/<?php echo c; ?>" target="_blank" title="View Image"><?php echo $invoice_header_data->invoice_number; ?></a><?php }  ?><?php echo $invoice_header_data->invoice_number; ?></td>
                <td>Supplier ID:</td><td><a href="edit-supplier.php?id=<?php echo $supplier_id; ?>" target="_blank"><?php echo $supplier_id; ?></a></td></tr>
                <tr><td>Invoice Amount:</td><td><?php echo $invoice_header_data->total; ?></td>
                <td>Invoice Date:</td><td><?php echo $invoice_header_data->Date ?></td></tr>
                
                <tr><td>Invoice Status:</td><td><?php if($invoice_header_data->Status=="O") echo "Open/Unpaid"; else if($invoice_header_data->Status=="P") echo "Paid"; else echo "Cancelled"; ?></td>
                <td>Description:</td><td><?php echo $invoice_header_data->description; ?></td></tr>
               
			</table>
            <br />
			<table class="table table-responsive" >
		         <tr><th style="border:1px solid;">S.N.</th><th style="border:1px solid;">ISBN ID</th><th style="border:1px solid;">Book</th><th style="border:1px solid;">Quantity</th>
                 <th style="border:1px solid;">MRP</th>
		        <th style="border:1px solid;">Cost Price</th>
                <th style="border:1px solid;">Old/New</th>
                <th style="border:1px solid;">LastBooks</th><th style="border:1px solid;">LastLines</th></tr>
                <?php
                $total_amt=0;$inc=0;
               
				if(sizeof($invoice_line_data)>0)
				{
              
                foreach($invoice_line_data as $invoice_line_da)
                {
					$isbn='';
					$product = \App\Product::where('id',$invoice_line_da->proid)->first(); 
       
					if($invoice_line_da->variation != NULL && $invoice_line_da->variation != 'null')
					{
						
						$productStock = \App\ProductStock::where('variant',$invoice_line_da->variation)->where('product_id',$product->id)->first();
						if($productStock)
						{
							$isbn = $productStock->isbn;
							$product_name =  $product->name.'+'.$invoice_line_da->variation;
						}
					}
					else
					{
						$isbn = $product->isbn;
						$product_name = $product->name;
					}
                ?>		<tr><td style="border:1px solid;"><?php echo $inc+=1; ?></td>
                <td style="border:1px solid;"><?php echo $isbn; ?></td>
                <td style="border:1px solid;"><?php echo $product_name ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_da->quantity; ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_da->mrp; ?></td>
		        <td style="border:1px solid;"><?php echo $invoice_line_da->cp; $total_amt+=$invoice_line_da->cp; ?></td>
                <td style="border:1px solid;"><?php echo $invoice_line_da->version; ?></td>
                
                <td style="border:1px solid;"><?php echo $invoice_line_da->updated_at; ?></td>
			    <td style="border:1px solid;"><?php echo $invoice_line_da->lastupdateddate; ?></td>
		
                <?php if($invoice_header_data->Status="O") {?><td>
					<button  type='button'  style="border:none; padding:0px;" title='delete' class='delete_order_item' data-id='<?php echo $invoice_line_da->lineid ; ?>' data-invoice-id='<?php echo $invoice_id ; ?>' data-supplier-id='<?php echo $supplier_id ; ?>'><i class="fa fa-trash-o" style="font-size:24px;color:red"></i></button>
				</td><?php } ?></tr>
                <?php
                }
            }
           
		               ?>
			</table><br />

            <p>Total Amount to be paid:  <?php echo $total_amt; ?></p>
			
			<?php
			if($invoice_header_data->Status=="O")
			{
			?>
			<div class="row">
                <form id="ProductSave" method="post" action="{{ route('APInvoiceAlls.ProductSaveOld') }}">
				@csrf
				<div id="msg" class="alert alert-danger" style="display:none;"></div>
                    <table style="width:800px;">
                    <tr><td valign="top">Quantity*</td><td><input type="text" name="qty" id='qty' required="required" value='1'  onchange='return getCP();' /></td></tr>
                    <tr><td valign="top">Percentage</td><td><select name="percentage" id="percentage" onchange='return getCP();' >
                    <option value='0'>Choose %age </option>
                    <option value='.90'>90</option>
                    <option value='.80'>80</option>
                    <option value='.70'>70</option>
                    <option value='.60'>60</option>
                    <option value='.50'>50</option>
                    <option value='.40'>40</option>
                    <option value='.30'>30</option>
                    <option value='.20'>20</option>
                    <option value='.10'>10</option>
                    </select></td></tr>
                    <tr><td valign="top">Cost Price*</td><td><input type="text" name="cp" required="required" id='cp' /></td></tr>
                    <div id="changeValues"></div>
                    <tr><td><input type="submit" id="Add_Book" name="save"  value="Add Book" /></td></tr>
                    <tr><td></td><td>
					<input type="text" name="keyword" id="keyword-box" required="required"  style="width: 500px;" placeholder="Search Book" />
					<input type="hidden" name="invoice_id" id="InvoiceId" value="<?php echo $invoice_id ?>">
					<input type="hidden" name="supplier_id" id="SupplierId" value="<?php echo $supplier_id ?>">
					<input type="hidden" name="variant" id="variant-box" value="">
					<div id="suggesstion-box"></div></td></tr>
                  
				    </table>
                </form>		<hr />
		<?php } ?>
		<div style="float:right;">
		
            <form method="post" id="bottomform">
                            <?php ///change for status;
           
		if($invoice_header_data->Status != "O"){
		 ?>
		<label>Change Status:</label>
		<select id="changestatus" class="changestatus" name="changestatus">
		<option value="O" <?php if($invoice_header_data->Status=="O"){echo "selected";} ?>>Open</option>
		<option value="C"  <?php if($invoice_header_data->Status=="C"){echo "selected";} ?>>Cancel</option>
		<option value="P"  <?php if($invoice_header_data->Status=="P"){echo "selected";} ?>>Paid</option>
		</select>
		<?php } ?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="{{url('admin/APinvoice_header_workbench/APInvoiceold')}}/<?php echo $invoice_id; ?>" id="printap" class="btn btn-primary" >Print OLD AP</a>
		<?php 
		//////////////Modal button for credit memo /////////////////////
		 if($invoice_header_data->invoicelookuptype == 'C') { ?>
		<button type="button" class="btn btn-info btn-mm" data-toggle="modal" data-target="#myModal">Deposit Advance</button>
		<?php } ?>
		<select id="ModeOfPayment" name="ModeOfPayment">
		<?php if(strpos($invoice_number,"SAL") !== false){echo "required";} ?>
		<?php if(strpos($invoice_number,"SAL") !== false){ ?>
		<option value=''>Select Mode of Payment</option>
		<?php } ?>
		<option value='Clearing'  <?php if($invoice_header_data->modeofpayment == 'Clearing'){echo "selected";} ?>>Clearing/Cash</option>
		<option value='Check' <?php if($invoice_header_data->modeofpayment == 'Check'){echo "selected";} ?>>Check</option>
		<option value='CreditCard' <?php if($invoice_header_data->modeofpayment == 'CreditCard'){echo "selected";} ?>>Credit/Debit Card</option>
		<option value='Electronic' <?php if($invoice_header_data->modeofpayment == 'Electronic'){echo "selected";} ?>>Electronic</option>
		<option value='Wire' <?php if($invoice_header_data->modeofpayment == 'Wire'){echo "selected";} ?> >Wire</option>
		</select>
		
		<input type="text" name="payinfo" <?php if($invoice_header_data->Status != 'O'){ echo "readonly"; } ?> id="payinfo" value="<?php echo $invoice_header_data->payinfo; ?>" required class="payinfo" placeholder="Payment Information" />
		<input type="text" name="paydate" <?php if($invoice_header_data->Status != 'O'){ echo "readonly"; }else{echo 'id="paydate"'; } ?>  value="<?php echo $invoice_header_data->paydate; ?>" required class="paydate" placeholder="Payment Date" />
        <?php if(strpos($invoice_number,"SAL") !== false){ ?>
		<input type="text" name="bank_commission" <?php if($invoice_header_data->Status != 'O'){ echo "readonly"; }else{echo 'id="bank_commission"'; } ?>  value="<?php echo $invoice_header_data->bank_commission; ?>"  <?php if(strpos($invoice_number,"SAL") !== false){echo "required";} ?> class="bank_commission" placeholder="Commission Amount" />
		<select name="payaccount" id="payaccount"  <?php if(strpos($invoice_number,"SAL") !== false){echo "required";} ?>>
		<option value="">Select Account</option>
		<option value="cce"  <?php if($invoice_header_data->payaccount == 'cce'){echo "selected";} ?>>CCE</option>
		<option value="current" <?php if($invoice_header_data->payaccount == 'current'){echo "selected";} ?>>current</option>
		<option value="paytm" <?php if($invoice_header_data->payaccount == 'paytm'){echo "selected";} ?>>PayTM</option>
		<option value="cash" <?php if($invoice_header_data->payaccount == 'cash'){echo "selected";} ?>>Cash</option>
		</select>
		<?php }else{ ?>
			<input type="text" name="bank_commission" <?php if($invoice_header_data->Status != 'O'){ echo "readonly"; }else{echo 'id="bank_commission"'; } ?>  value="<?php echo $invoice_header_data->bank_commission; ?>" class="bank_commission" placeholder="Commission Amount" />
		<select name="payaccount" <?php if(strpos($invoice_number,"SAL") !== false){echo "required";} ?>>
		<option value="cash" <?php if($invoice_header_data->payaccount == 'cash'){echo "selected";} ?>>Cash</option>
		<option value="cce"  <?php if($invoice_header_data->payaccount == 'cce'){echo "selected";} ?>>CCE</option>
		<option value="current" <?php if($invoice_header_data->payaccount == 'current'){echo "selected";} ?>>current</option>
		<option value="paytm" <?php if($invoice_header_data->payaccount == 'paytm'){echo "selected";} ?>>PayTM</option>
		
		</select>
		<?php } ?>
		Description:
		<textarea name="description"><?php echo $invoice_header_data->description; ?></textarea>
		<input type="hidden" value="<?php echo $invoice_id; ?>" name="InvoiceID" />
		<input type="button" value="Save" id="Save" name="save_d" /> 
		<?php if($invoice_header_data->Status=="O") {?>
		<input type="button" id ="Close" value="Save & Close" name="close"   />
		
		
		<?php } 
		
		?>
		
		</form>

		</div>	


       

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
     <form method='post' href="{{ route('APInvoiceAlls.advancepay')}}">
  
   
    <?php
    	$ssql = DB::table('arcredit')->where('invoiceid',$invoice_id)->get();
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
  <input type="hidden" name="id" value="<?php echo $invoice_id; ?>" />
  <input type="hidden" name="invoice_number" value="<?php echo $invoice_header_data->invoice_number; ?>" />
  <div class="form-group col-sm-12">
    <label for="notes">Notes:</label>
    <textarea class="form-control" id="notes" name="notes"  maxlength="100"></textarea>
  </div>
  <div class="col-sm-12">
  <button type="submit" class="btn btn-default" value="advancepay" <?php if(round($balPaymentforcredit) == 0){ echo "disabled";} ?> name="advancepay">Submit</button>
</div>
</form> 
       <a href="creditinvoice.php?invoice_number=<?php echo $_GET['invoice_number']; ?>" target="_blank" class="btn btn-info">Print advance</a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
 
 @endsection

@section('script')   
<script type="text/javascript">
$('.paydate').datepicker({ dateFormat: 'yy-mm-dd'});
$("#bottomform")[0].reset();
$('#cp').val('');

function getCP(){
var percentage=document.getElementById('percentage').value;
var qty=document.getElementById('qty').value;
var mrp=document.getElementById('mrp').value;
total=(mrp*qty);
amount=total*percentage;
//amount=total-less;
document.getElementById("cp").value = amount;
}

    $(document).ready(function(){
		$('#msg').hide();
		$('#msg').html('');
	$("#keyword-box").keyup(function(){
		$.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/APinvoice_header_workbench/getbookdetail') }}',
		data: { keyword: $(this).val(), store_id: '0',role:'1', _token: "{{ csrf_token() }}" },
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
});
//To select book name
$("#Save").click(function(){
	var invoice_id = {{ $invoice_id }};
	var supplier_id = {{ $supplier_id }};
	var desc = $('#desc').val();
	var modepayment = $( "#ModeOfPayment option:selected" ).val();
	var paydate = $('#paydate').val();
	var payinfo = $('#payinfo').val();
	var bank_commission = $('#bank_commission').val();
	var payaccount =$( "#payaccount option:selected" ).val();
	//var status = $( "#changestatus option:selected" ).val();
	//alert(status);
	//var payment = $('.famount').text();
	 $.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/APinvoice_header_workbench/saveApInvoice') }}',
		data: { invoice_id:invoice_id, supplier_id:supplier_id, payaccount:payaccount, desc:desc, modepayment:modepayment, paydate:paydate,  payinfo:payinfo, bank_commission:bank_commission, _token: "{{ csrf_token() }}" },
        //dataType: 'json',
        success: function (data) {
			alert('Ap Saved');
			$("#bottomform")[0].reset();
			setTimeout(function(){// wait for 5 secs(2)
           location.reload(); // then reload the page.(3)
      }, 1000); 
		}
		});
});

$("#Close").click(function(){
	var invoice_id = {{ $invoice_id }};
	var supplier_id = {{ $supplier_id }};
	var desc = $('#desc').val();
	var modepayment = $( "#ModeOfPayment option:selected" ).val();
	var paydate = $('#paydate').val();
	var payinfo = $('#payinfo').val();
	var bank_commission = $('#bank_commission').val();
	var payaccount =$( "#payaccount option:selected" ).val();
	//var status = $( "#changestatus option:selected" ).val();
	//alert(status);
	//var payment = $('.famount').text();
	 $.ajax({
        type: 'POST', //THIS NEEDS TO BE GET
		url: '{{ url('admin/APinvoice_header_workbench/closeApInvoice') }}',
		data: { invoice_id:invoice_id, supplier_id:supplier_id, payaccount:payaccount, desc:desc, modepayment:modepayment, paydate:paydate,  payinfo:payinfo, bank_commission:bank_commission, _token: "{{ csrf_token() }}" },
        //dataType: 'json',
        success: function (data) {
			alert('Ap Saved & Closed');
			$("#bottomform")[0].reset();
			setTimeout(function(){// wait for 5 secs(2)
           location.reload(); // then reload the page.(3)
      }, 1000); 
		}
		});
});

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

$("body").on('click','.var_prod', function(){
      return false; // prevents default action
      });

$("body").on('click','.selectbook_prod', function(){
	var _val = $(this).attr('data-prod');
	var _varn = 'null';
	var _tarn = $(this).attr('data-tran');

	selectbook(_val, _varn,_tarn);
});

$("body").on('click','.selectbook_var', function(e){
	var _val = $(this).attr('data-prod');
	var _varn = $(this).attr('data-varn');
	var _tarn = $(this).attr('data-tran');
	selectbook(_val, _varn,_tarn);

	e.stopPropagation();

});



function selectbook(val,vart,trxn){
$("#keyword-box").val(val);
$("#suggesstion-box").hide();
$("#variant-box").val(vart);
getBookDetails(val,vart,trxn);
 //$("#transaction_type_id").addClass("disable-select");
}

function getBookDetails(val,vart,trxn){
var mobile				= $('#keyword-box').val();
$('#Add_Book').prop('disabled', false);					
$.ajax({
				async : false,
				url: '{{ url('admin/APinvoice_header_workbench/get_book_detail_invoice_old') }}', 
				type : "POST",
				data : {'keyword' : mobile,'vart' : vart,'trxn' : trxn, _token: "{{ csrf_token() }}" },
				dataType : 'text',
				timeout : 1000,
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
					$('#changeValues').html();
				if(dataType != 'out')
				{
					$('#changeValues').html(dataType);
				}
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

$('.changestatus').change(function(e){
	var inv = "<?php echo $invoice_id; ?>";
	$.ajax({
				async : false,
				url : '{{url('admin/APinvoice_header_workbench/statusChangeOrder')}}',
				type : "POST",
				data : {'invoiceid' : inv, 'status' : $(this).val(),  _token: "{{ csrf_token() }}" },
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
    
</script>
@endsection  