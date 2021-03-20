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
	@if(session()->get('msg') != null)
			<div class="alert alert-danger">{{ session()->get('msg') }}</div>
			@endif
        <div class="panel-heading">
			
            <h3 class="panel-title">{{__('AP Invoice Lines Workbench')}}</h3>
		
        </div>
        <div class="panel-body">
     
            <table class="table table-responsive" >
				 <?php if($invoice_header_data->title != ''){
					 $invid = $invoice_header_data->invoiceid;
					 
					  ?>
					 
				 <tr><td colspan="2"><h3>Title: <a href="{{url('admin/APinvoice_header_workbench/instantapupdate')}}/{{$invid}}"><?php echo $invoice_header_data->title; ?></a></h3></td>
				 <td colspan="2">  Payment Date: <?php echo $invoice_header_data->paydate; ?> </td></tr>
				 <?php } ?>
                <tr><td>Invoice Number:</td><td><a <?php if($invoice_header_data->invoicetype == 'C'){ echo " style='color:red' "; }?> href="{{ url('public')}}/{{$invoice_header_data->image}}" target="_blank" title="View Image"><?php echo $invoice_header_data->invoice_number; ?></a></td>
                <td>Supplier ID:</td><td><?php echo $supplier_id; ?></td></tr>
                <tr><td>Invoice Amount(INC GST):</td><td><?php echo $invoice_header_data->Total; ?></td>
                <td>Invoice Date:</td><td><?php echo $invoice_header_data->Date ?></td></tr>
                <tr><td>IGST:</td><td><?php echo $invoice_header_data->igst ?></td>
                <td>CGST:</td><td><?php echo $invoice_header_data->cgst ?></td></tr>
                
                <tr><td>Invoice Status:</td><td><?php if($invoice_header_data->Status=="O") echo "Open/Unpaid"; else if($invoice_header_data->Status=="P") echo "Paid"; else echo "Cancelled"; ?></td>
                <td>Description:</td><td><?php echo $invoice_header_data->description; ?></td></tr>
                <tr><td>Last Updated By:</td><td><?php $data =$invoice_header_data->lastUupdateby; 
               ?></td><td>Last Updated Date:</td><td><?php echo $invoice_header_data->lastupdatedate; ?></td></tr>
			</table>
            <br />
			<table class="table table-responsive" >
		         <tr><th style="border:1px solid;">S.N.</th><th style="border:1px solid;">ISBN ID</th><th style="border:1px solid;">Book</th><th style="border:1px solid;">Quantity</th><th style="border:1px solid;">Old/New</th><th style="border:1px solid;">UpdateQTYDate</th><th style="border:1px solid;">LastLines</th></tr>
                <?php
                $inc=0;$tqty=0;
               
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
                <td style="border:1px solid;"><?php echo $invoice_line_da->version; ?></td>
                <?php $tqty = $tqty+$invoice_line_da->quantity; ?>
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

            <?php if($invoice_header_data->title == ''){ /////////////Not for instant AP ///////////// ?>
			<?php echo "<p style='float: right;margin-right: 82px;'><Strong>Total Quantity: </Strong>".$tqty; ?></p>
			<?php
			if($invoice_header_data->Status=="O")
			{
			?>
			<div class="row">
                <form id="ProductSave" method="post" action="{{ route('APInvoiceAlls.ProductSave') }}">
				@csrf
				<div id="msg" class="alert alert-danger" style="display:none;"></div>
                    <table style="width:800px;"><tr><td valign="top">Choose*</td>
                    <td>
                        <select name="version">
                        <option value="N">New</option>
                        <option value="O">Old</option>
                        </select>
                    </td></tr>
                    <tr><td valign="top">Quantity*</td><td><input type="text" name="qty" required="required" /></td></tr>
                    <div id="changeValues"></div>
                    <tr><td><input type="submit" id="Add_Book" name="save"  value="Add Book" /></td></tr>
                    <tr><td></td><td>
					<input type="text" name="keyword" id="keyword-box" required="required"  style="width: 500px;" placeholder="Search Book" />
					<input type="hidden" name="invoice_id" id="InvoiceId" value="<?php echo $invoice_id ?>">
					<input type="hidden" name="supplier_id" id="SupplierId" value="<?php echo $supplier_id ?>">
					<input type="hidden" name="variant" id="variant-box" value="">
					<div id="suggesstion-box"></div></td></tr>
                  
				    </table>
                </form>	
				</div>	<hr />
		<?php } ?>
		<div style="float:right;">
		
            <form method="post" id="bottomform" >
                            <?php ///change for status;
            //print_r($_SESSION);
            $er="";
            if($invoice_header_data->image == ""){
                $er .= "Please Upload Invoice<br/>";
                } 
                if($invoice_header_data->Total == ""){
                $er .= "Please Add Amount<br/>";
                }
                if($invoice_header_data->supplier_id == ""){
                $er .= "Please Add Supplier<br/>";
                }
                if($invoice_header_data->igst == ""){
                //$er .= "Please Add IGST<br/>";
                }
                if($invoice_header_data->cgst == ""){
                //$er .= "Please Add CGST<br/>";
                }
                if($invoice_header_data->gst == ""){
                //$er .= "Please Add GST<br/>";
                }
            
            
		if($invoice_header_data->Status != "O"){
		 ?>
		<label>Change Status:</label>
		<select id="changestatus" class="changestatus" name="changestatus">
		<option value="O" <?php if($invoice_header_data->Status=="O"){echo "selected";} ?>>Open</option>
		<option value="C"  <?php if($invoice_header_data->Status=="C"){echo "selected";} ?>>Cancel</option>
		<option value="P"  <?php if($invoice_header_data->Status=="P"){echo "selected";} ?>>Paid</option>
		</select>
		<?php } ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="{{url('admin/APinvoice_header_workbench/APInvoice')}}/<?php echo $invoice_id; ?>" id="printap" class="btn btn-primary" >Print AP</a>
		<?php if($invoice_header_data->Status=="O"){ ?>
		<a href="javascript:void(0)" id="updateHeader" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Update Header</a>
		<?php } ?>
		
		
		<select id="ModeOfPayment" name="ModeOfPayment">
		<option value=''>Select Mode of Payment</option> 
		<option value='Clearing'  <?php if($invoice_header_data->modeofpayment == 'Clearing'){echo "selected";} ?>>Clearing/Cash</option>
		<option value='Check' <?php if($invoice_header_data->modeofpayment == 'Check'){echo "selected";} ?>>Check</option>
		<option value='CreditCard' <?php if($invoice_header_data->modeofpayment == 'CreditCard'){echo "selected";} ?>>Credit/Debit Card</option>
		<option value='Electronic' <?php if($invoice_header_data->modeofpayment == 'Electronic'){echo "selected";} ?>>Electronic</option>
		<option value='Wire' <?php if($invoice_header_data->modeofpayment == 'Wire'){echo "selected";} ?> >Wire</option>
		</select>
		
		<input type="text" name="payinfo" <?php if($invoice_header_data->Status != 'O'){ echo "readonly"; } ?> id="payinfo" value="<?php echo $invoice_header_data->payinfo; ?>" required class="payinfo" placeholder="Payment Information" />
		<input type="text" name="paydate" <?php if($invoice_header_data->Status != 'O'){ echo "readonly"; }else{echo 'id="paydate"'; } ?>  value="<?php echo $invoice_header_data->paydate; ?>" required class="paydate" placeholder="Payment Date" />
		<input type="text" name="bank_commission" <?php if($invoice_header_data->Status != 'O'){ echo "readonly"; }else{echo 'id="bank_commission"'; } ?>  value="<?php echo $invoice_header_data->bank_commission; ?>" required class="bank_commission" placeholder="Commission Amount" />
		<select id="payaccount" name="payaccount" required>
		<option value="">Select Account</option>
		<option value="cce"  <?php if($invoice_header_data->payaccount == 'cce'){echo "selected";} ?>>CCE</option>
		<option value="current" <?php if($invoice_header_data->payaccount == 'current'){echo "selected";} ?>>current</option>
		<option value="paytm" <?php if($invoice_header_data->payaccount == 'paytm'){echo "selected";} ?>>PayTM</option>
		<option value="cash" <?php if($invoice_header_data->payaccount == 'cash'){echo "selected";} ?>>Cash</option>
		</select>
		Description:
		<textarea name="description"><?php echo $invoice_header_data->description; ?></textarea>
		<input type="hidden" value="<?php echo $invoice_id; ?>" name="InvoiceID" />
		<input type="button" value="Save" id="Save" name="save_d" /> 
		<?php if($invoice_header_data->Status=="O") {?>
		<input type="button" id ="Close" value="Save & Close" name="close"   />
		
		
		<?php } 
		if($er != ''){
			echo "<div style='color:red;float:right;margin-left:10px;'><p>$er</p></div>";
			}
		?>
		
		</form>

		</div>	
		<?php } ?>

        <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:70%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">AP Invoice Header</h4>
      </div>
      <div class="modal-body">
         <form method="post" action="{{ route('APInvoiceAlls.ProductUpdate') }}" enctype="multipart/form-data"><div class="error"><?php echo $msg; ?></div>
         @csrf
          <div class="form-group">
                <label for="supplier">Supplier*:</label>
                <select name="supplier" id="supplier" required="required" class="form-control">
                        <option value=""></option>
                <?php
                 if(sizeof($supplier_data)>0) {
                foreach($supplier_data as $sup_data)
                {
                ?>
                <option <?php if($sup_data->supplierid == $supplier_id){ echo "selected = 'selected'"; } ?> value="<?php echo $sup_data->supplierid; ?>"><?php echo $sup_data->name; ?></option>
                <?php } }  ?>
                </select>
         </div>
		   
		   <div class="form-group">
			   
			<label for="invoice_number">Invoice Number* </label>
			<input type="text" value="<?php echo $invoice_header_data->invoice_number; ?>" class="form-control" id="invoice_number" name="invoice_number" <?php if($invoice_header_data->invoice_number != ''){echo "";} ?> required="required"  />
			<input type="hidden" name="InvoiceID" value="<?php echo $invoice_id; ?>" />
			<input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>" />
			</div>
			<div class="form-group">
		    <label for="date">Invoice Date* :</label>
		    <input data-provide="datepicker" type="text" value="<?php echo $invoice_header_data->Date; ?>" id="date" class="form-control tcal" name="date"   /></td>
			</div>
			<br/>
			<span><b>No GST:&nbsp;</b></span><input style="height:10px !important;border: 1px solid #ccc;" type="checkbox" value="nogst" id="nogst" name="nogst" />
			<br/>
			<div class="row">
			<div class="form-group col-sm-12 col-lg-12 col-xs-12">
				<div class="ngst"></div>
                <div class="row gstgrp" style="background:#eee;border-top:1px solid #aaa;margin-top:10px;">&nbsp;&nbsp;<strong>0% GST</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" readonly name="gst0" id="gst0" value="0" placeholder="0% GST" />&nbsp;&nbsp;&nbsp;<strong>CGST:</strong>&nbsp;<input type="text" readonly name="cgst0" id="cgst0" value="0" placeholder="0% CGST" />&nbsp;&nbsp;&nbsp;<strong>SGST:</strong>&nbsp;<input type="text" readonly name="sgst0" id="sgst0" value="0" placeholder="0% SGST" />&nbsp;&nbsp;&nbsp;<strong>IGST:</strong>&nbsp;<input type="text" readonly name="igst0" data-id="igst0" id="igst0" value="0" placeholder="0% IGST" />&nbsp;<strong>TaxOn0:&nbsp;&nbsp;&nbsp;</strong><input type="text" name="taxon0" value="<?php echo $invoice_header_data->taxon0 ?>" id="taxon0" data-id="taxon0" placeholder="0% Tax On" />&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon0gst" id="taxon0gst" class="taxon0gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" name="taxon0gst" id="taxon0igst"  value="igst" class="taxon0gst" /></div>
                <div class="row gstgrp" style="background:#ccc;">&nbsp;&nbsp;<strong>5% GST</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" readonly name="gst5" id="gst5" value="<?= $invoice_header_data->gst5 ?>" placeholder="5% GST" />&nbsp;&nbsp;&nbsp;<strong>CGST:</strong>&nbsp;<input type="text" readonly name="cgst2_5" id="cgst2_5" value="<?= $invoice_header_data->cgst2_5 ?>" placeholder="2.5% CGST" />&nbsp;&nbsp;&nbsp;<strong>SGST:</strong>&nbsp;<input type="text" readonly name="sgst2_5" id="sgst2_5" value="<?= $invoice_header_data->sgst2_5 ?>" placeholder="2.5% SGST" />&nbsp;&nbsp;&nbsp;<strong>IGST:</strong>&nbsp;<input type="text" readonly name="igst5" data-id="igst5" id="igst5" value="<?= $invoice_header_data->igst5 ?>" placeholder="5% IGST" />&nbsp;<strong>TaxOn5:&nbsp;&nbsp;&nbsp;</strong><input type="text" name="taxon5" value="<?php echo $invoice_header_data->taxon5 ?>" id="taxon5" data-id="taxon5" placeholder="5% Tax On" />&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon5gst" id="taxon5gst" class="taxon5gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" name="taxon5gst" id="taxon5igst"  value="igst"  <?php if($invoice_header_data->igst5 != '' && $invoice_header_data->igst5 != '0' && $invoice_header_data->igst5 != '0.00'){echo 'checked';} ?> class="taxon5gst" /></div>
                <div class="row gstgrp" style="background:#eee">&nbsp;&nbsp;<strong>12% GST</strong>&nbsp;&nbsp;<input type="text" readonly name="gst12" id="gst12" value="<?= $invoice_header_data->gst12 ?>" placeholder="12% GST" />&nbsp;&nbsp;&nbsp;<strong>CGST:</strong>&nbsp;<input type="text" readonly name="cgst6" id="cgst6" value="<?= $invoice_header_data->cgst6 ?>" placeholder="6% CGST" />&nbsp;&nbsp;&nbsp;<strong>SGST:</strong>&nbsp;<input type="text" name="sgst6" readonly id="sgst6" value="<?= $invoice_header_data->sgst6?>" placeholder="6% SGST" />&nbsp;&nbsp;&nbsp;<strong>IGST:</strong>&nbsp;<input type="text" readonly name="igst12" id="igst12" data-id="igst12" value="<?= $invoice_header_data->igst12 ?>" placeholder="12% IGST" />&nbsp;<strong>TaxOn12:&nbsp;</strong><input type="text" name="taxon12" id="taxon12"  data-id="taxon12" value="<?php echo $invoice_header_data->taxon12 ?>" placeholder="12% Tax On" />&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon12gst" id="taxon12gst" class="taxon12gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" class="taxon12gst" name="taxon12gst" id="taxon12igst"  value="igst"  <?php if($invoice_header_data->igst12 != '' && $invoice_header_data->igst12 != '0' && $invoice_header_data->igst12 != '0.00'){echo 'checked';} ?>  /></div>
                <div class="row gstgrp" style="background:#ccc">&nbsp;&nbsp;<strong>18% GST</strong>&nbsp;&nbsp;<input type="text" readonly name="gst18" id="gst18" value="<?= $invoice_header_data->gst18 ?>" placeholder="18% GST" />&nbsp;&nbsp;&nbsp;<strong>CGST:</strong>&nbsp;<input type="text" readonly name="cgst9" id="cgst9" value="<?= $invoice_header_data->cgst9 ?>" placeholder="9% CGST" />&nbsp;&nbsp;&nbsp;<strong>SGST:</strong>&nbsp;<input type="text" name="sgst9" readonly id="sgst9" value="<?= $invoice_header_data->sgst9 ?>" placeholder="9% SGST" />&nbsp;&nbsp;&nbsp;<strong>IGST:</strong>&nbsp;<input type="text" readonly name="igst18"  id="igst18" data-id="igst18" value="<?= $invoice_header_data->igst18 ?>" placeholder="18% IGST" />&nbsp;<strong>TaxOn18:&nbsp;</strong><input type="text" name="taxon18" id="taxon18" data-id="taxon18" value="<?php echo $invoice_header_data->taxon18 ?>" placeholder="18% Tax On" />&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon18gst" id="taxon18gst" class="taxon18gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" class="taxon18gst" name="taxon18gst" id="taxon18igst"  value="igst"   <?php if($invoice_header_data->igst18 != '' && $invoice_header_data->igst18 != '0' && $invoice_header_data->igst18 != '0.00'){echo 'checked';} ?> /></div>
                <div class="row gstgrp" style="background:#eee;border-bottom:1px solid #aaa;margin-bottom:10px;">&nbsp;&nbsp;<strong>28% GST</strong>&nbsp;&nbsp;<input type="text" readonly name="gst28" id="gst28" value="<?= $invoice_header_data->gst28 ?>" placeholder="28% GST" />&nbsp;&nbsp;&nbsp;<strong>CGST:</strong>&nbsp;<input type="text" readonly name="cgst14" id="cgst14" value="<?= $invoice_header_data->cgst14 ?>" placeholder="14% CGST" />&nbsp;&nbsp;&nbsp;<strong>SGST:</strong>&nbsp;<input type="text" name="sgst14" readonly id="sgst14" value="<?= $invoice_header_data->sgst14 ?>" placeholder="14% SGST" />&nbsp;&nbsp;&nbsp;<strong>IGST:</strong>&nbsp;<input type="text" readonly name="igst28"  id="igst28" data-id="igst28" value="<?= $invoice_header_data->igst28 ?>" placeholder="28% IGST" />&nbsp;<strong>TaxOn28:&nbsp;</strong><input type="text" value="<?php echo $invoice_header_data->taxon28 ?>" name="taxon28" id="taxon28" data-id="taxon28" placeholder="28% Tax On" />&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon28gst" id="taxon28gst" class="taxon28gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" class="taxon28gst" name="taxon28gst" id="taxon28igst"  value="igst"  <?php if($invoice_header_data->igst28 != '' && $invoice_header_data->igst28 != '0' && $invoice_header_data->igst28 != '0.00'){echo 'checked';} ?>  /></div>

			</div>
			</div>
			
			
			<div class="row">
			<div class="form-group col-sm-6 col-lg-6 col-xs-6">
			<label for="igst">IGST*: </label>
			<input type="text" readonly value="<?php echo $invoice_header_data->igst; ?>" name="igst" required  id="igst" class="form-control" />
			</div>
			<div class="form-group col-sm-6 col-lg-6 col-xs-6">
			<label for="igst">GST*: </label>
			<input type="text" readonly value="<?php echo $invoice_header_data->gst; ?>" name="gst"  required id="gst" class="form-control" />
			</div>
			</div>
			
			<div class="row">
			<div class="form-group col-sm-6 col-lg-6 col-xs-6">
			<label for="igst">SGST*: </label>
			<input type="text" readonly value="<?php echo $invoice_header_data->sgst; ?>" name="sgst"  required id="sgst" class="form-control" />
			</div>
			<div class="form-group col-sm-6 col-lg-6 col-xs-6">
			<label for="cgst">CGST(*) </label>
			<input type="text" readonly value="<?php echo $invoice_header_data->cgst; ?>" name="cgst"  required id="cgst" class="form-control" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="bank_commission">Bank Commission(*) </label>
			<input type="text" value="<?php echo $invoice_header_data->bank_commission; ?>" name="bank_commission"  required id="bank_commission" class="form-control" />
			</div>
			
			<div class="form-group">
			<label for="amount">Total Amount without GST </label>
			<input type="number" readonly value="<?php echo $invoice_header_data->totalwithoutgst; ?>" name="totalwithoutgst" required id="totalwithoutgst" class="form-control" />
			</div>
			
			<div class="form-group">
			<label for="amount">Total Amount with GST </label>
			<input type="text" readonly value="<?php echo $invoice_header_data->Total; ?>" name="amount"  step="0.1" required id="amount" class="form-control" />
			</div>
			
			
			<div class="form-group">
			<label for="status">Status: </label>
			<select name="status" required id="status" class="form-control" >
				<option value="O" <?php if($invoice_header_data->Status =="O" || $invoice_header_data->Status ==""){ echo "selected = 'selected'";} ?>>Open</option>
				<option value="P" <?php if($invoice_header_data->Status =="P"){ echo "selected = 'selected'";} ?>>Paid</option>
				<option value="C" <?php if($invoice_header_data->Status =="C"){ echo "selected = 'selected'";} ?>>Cancel</option>
			</select>
			</div>
			
			<div class="form-group">
			<label for="status">Ap Type: </label>
			<select name="aptype" required id="aptype" class="form-control" >
				<option value="purchase" <?php if($invoice_header_data->type =="purchase" || $invoice_header_data->aptype==""){ echo "selected = 'selected'";} ?>>Purchase</option>
				<option value="expanse" <?php if($invoice_header_data->type=="expanse"){ echo "selected = 'selected'";} ?>>Expanse</option>
				<option value="withdraw" <?php if($invoice_header_data->type =="withdraw"){ echo "selected = 'selected'";} ?>>Withdraw</option>
			</select>
			</div>
		
			<div class="form-group">
			<label for="invoicetype">Invoice Type: </label>
			<select name="invoicetype" required id="invoicetype" class="form-control" >
				<option value="S" <?php if($invoice_header_data->invoicetype=="S"){ echo "selected = 'selected'";} ?>>Standard</option>
				<option value="C" <?php if($invoice_header_data->invoicetype=="C"){ echo "selected = 'selected'";} ?>>CI</option>
				
			</select>
			</div>
			<div class="form-group">
		  <label for="description">Description</label>
		  <textarea id="description" name="description"  class="form-control"><?php echo $invoice_header_data->description; ?></textarea></td>
		  </div>
		  <div class="form-group">
		  <label for="image">Upload Invoice</label>
          
		 
			<div id="image">
				@if ($invoice_header_data->image != null || $invoice_header_data->image != '')
					Invoice uploaded: <a href="{{ url('admin/APinvoice_header_workbench/invoice')}}/{{$invoice_header_data->image}}" target="_blank">Invoive link</a>
					<input type="hidden" name="previous_img" value="{{ $invoice_header_data->image }}">
					
				@endif
			</div>
		  </div>
		  
		  <input type="submit" value="Update" class="btn btn-default" name="update" />
	  </form>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

        </div>
    </div>
 </div>  
 @endsection

@section('script')   
<script type="text/javascript">
$('#date').datepicker({ dateFormat: 'yy-mm-dd', maxDate: new Date() });
$('#paydate').datepicker({ dateFormat: 'yy-mm-dd'});
//$('#bottomform')[0].reset();
$('#taxon0,#taxon5,#taxon12,#taxon18,#taxon28').keyup(function(){
		
		var dataid = $(this).attr('data-id');
		var wt = $(this).val();
		if(isNaN(wt) || wt==''){
			wt=0;
			}
		var amount = parseFloat(wt);
		var tax=0;
		if(dataid == 'taxon0'){
			var taxtype = $("input[name='taxon5gst']:checked").val();
			
				$('#gst0').val(0);
				$('#sgst0').val(0);
				$('#cgst0').val(0);
				$('#igst0').val(0);
				
			
			}
			
		if(dataid == 'taxon5'){
			var taxtype = $("input[name='taxon5gst']:checked").val();
			if(taxtype == 'gst'){
				$('#gst5').val(amount*5/100);
				$('#sgst2_5').val(amount*2.5/100);
				$('#cgst2_5').val(amount*2.5/100);
				
				}else{
				$('#igst5').val(amount*5/100);
					}
			
			}
		
		if(dataid == 'taxon12'){
			var taxtype = $("input[name='taxon12gst']:checked").val();
			if(taxtype == 'gst'){
				$('#gst12').val(amount*12/100);
				$('#sgst6').val(amount*6/100);
				$('#cgst6').val(amount*6/100);
				
				}else{
				$('#igst12').val(amount*12/100);
					}
			
			}
			
		if(dataid == 'taxon18'){
			var taxtype = $("input[name='taxon18gst']:checked").val();
			if(taxtype == 'gst'){
				$('#gst18').val(amount*18/100);
				$('#sgst9').val(amount*9/100);
				$('#cgst9').val(amount*9/100);
				
				}else{
				$('#igst18').val(amount*18/100);
					}
			
			}
			
		if(dataid == 'taxon28'){
			var taxtype = $("input[name='taxon28gst']:checked").val();
			if(taxtype == 'gst'){
				$('#gst28').val(amount*28/100);
				$('#sgst14').val(amount*14/100);
				$('#cgst14').val(amount*14/100);
				
				}else{
				$('#igst28').val(amount*28/100);
					}
			
			}
			var taxon0 = $('#taxon0').val();
			if(isNaN(taxon0) || taxon0 == ''){
				taxon0 = 0;
				}
				
				var taxon5 = $('#taxon5').val();
			if(isNaN(taxon5) || taxon5 == ''){
				taxon5 = 0;
				}
				
				var taxon12 = $('#taxon12').val();
			if(isNaN(taxon12) || taxon12 == ''){
				taxon12 = 0;
				}
				
				var taxon18 = $('#taxon18').val();
			if(isNaN(taxon18) || taxon18 == ''){
				taxon18 = 0;
				}
				
				var taxon28 = $('#taxon28').val();
			if(isNaN(taxon28) || taxon28 == ''){
				taxon28 = 0;
				}
			
		var withoutgst	= parseFloat(taxon0) + parseFloat(taxon5)+parseFloat(taxon12)+parseFloat(taxon18)+parseFloat(taxon28);
			//console.log("test = "+taxon28);
			//console.log("test = "+taxon0);
			//console.log("test = "+taxon5);
			//console.log("test = "+taxon12);
			//console.log("test = "+taxon18);
		var totalgst	= parseFloat($('#gst0').val()) + parseFloat($('#gst5').val())+parseFloat($('#gst12').val())+parseFloat($('#gst18').val())+parseFloat($('#gst28').val());
		var totalsgst = parseFloat($('#sgst0').val()) + parseFloat($('#sgst2_5').val())+parseFloat($('#sgst6').val())+parseFloat($('#sgst9').val())+parseFloat($('#sgst14').val());
		var totalcgst = parseFloat($('#cgst0').val()) + parseFloat($('#cgst2_5').val())+parseFloat($('#cgst6').val())+parseFloat($('#cgst9').val())+parseFloat($('#cgst14').val());
		var totaligst = parseFloat($('#igst0').val()) + parseFloat($('#igst5').val())+parseFloat($('#igst12').val())+parseFloat($('#igst18').val())+parseFloat($('#igst28').val());
		$('#gst').val(totalgst);
		$('#igst').val(totaligst);
		$('#sgst').val(totalsgst);
		$('#cgst').val(totalcgst);
		if ($('#nogst').is(':checked')) {
			return false;
			}
			
		$('#totalwithoutgst').val(withoutgst);
		$('#amount').val(withoutgst+totalgst+totaligst);
		});
		
		$('.taxon0gst,.taxon5gst,.taxon12gst,.taxon18gst,.taxon28gst').change(function(e){
			var v = $(this).val();
			var vid = $(this).attr('class');
			//trig = #taxon5,#taxon12,#taxon18,#taxon28
			if(vid = 'taxon0gst'){
				//trig = #taxon5,#taxon12,#taxon18,#taxon28
				$('#gst0').val(0);
				$('#sgst0').val(0);
				$('#cgst0').val(0);
				$('#igst0').val(0);
				$('#taxon0').keyup();
				}
			if(vid = 'taxon5gst'){
				//trig = #taxon5,#taxon12,#taxon18,#taxon28
				$('#gst5').val(0);
				$('#sgst2_5').val(0);
				$('#cgst2_5').val(0);
				$('#igst5').val(0);
				$('#taxon5').keyup();
				}
				
			if(vid = 'taxon12gst'){
				$('#gst12').val(0);
				$('#sgst6').val(0);
				$('#cgst6').val(0);
				$('#igst12').val(0);
				$('#taxon12').keyup();
				}
			if(vid = 'taxon18gst'){
				$('#gst18').val(0);
				$('#sgst9').val(0);
				$('#cgst9').val(0);
				$('#igst18').val(0);
				$('#taxon18').keyup();
				}
			if(vid = 'taxon28gst'){
				$('#gst28').val(0);
				$('#sgst14').val(0);
				$('#cgst14').val(0);
				$('#igst28').val(0);
				$('#taxon28').keyup();
				}
			});
	function checkgst(){
		

if ($('#nogst').is(':checked')) {
	$('.ngst').addClass('overlay');
	$('#totalwithoutgst').prop('readonly',false);
	//$('#totalwithgst').prop('readonly',false);
	$('.taxon0gst').prop('disabled',true);
	$('.taxon5gst').prop('disabled',true);
	$('.taxon12gst').prop('disabled',true);
	$('.taxon18gst').prop('disabled',true);
	$('.taxon28gst').prop('disabled',true);
	$('#taxon0').prop('readonly',true);
	$('#taxon5').prop('readonly',true);
	$('#taxon12').prop('readonly',true);
	$('#taxon18').prop('readonly',true);
	$('#taxon28').prop('readonly',true);
	$('#taxon0').val(0);
	$('#taxon5').val(0);
	$('#taxon12').val(0);
	$('#taxon18').val(0);
	$('#taxon28').val(0);
	$('#amount').val($('#totalwithoutgst').val());
	$('#taxon0,#taxon5,#taxon12,#taxon18,#taxon28').keyup();
	
	}else{
		$('.ngst').removeClass('overlay');
	$('#totalwithoutgst').prop('readonly',true);
	//$('#totalwithgst').prop('readonly',true);
	$('.taxon0gst').prop('disabled',false);
	$('.taxon5gst').prop('disabled',false);
	$('.taxon12gst').prop('disabled',false);
	$('.taxon18gst').prop('disabled',false);
	$('.taxon28gst').prop('disabled',false);
	$('#taxon0').prop('readonly',false);
	$('#taxon5').prop('readonly',false);
	$('#taxon12').prop('readonly',false);
	$('#taxon18').prop('readonly',false);
	$('#taxon28').prop('readonly',false);
		}


		
		}
		
	checkgst();
	$('#nogst').change(function(e){
		checkgst();
		});
	$('#totalwithoutgst').keyup(function(e){
		if ($('#nogst').is(':checked')) {
			$('#amount').val($(this).val());
			}
		});
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

$("#image").spartanMultiImagePicker({
			fieldName:        'image',
			maxCount:         1,
			rowHeight:        '80px',
            allowedExt:       'png|jpg',
			groupClassName:   'col-md-4 col-sm-4 col-xs-6',
			maxFileSize:      '',
			dropFileLabel : "Drop Here",
			onExtensionErr : function(index, file){
				console.log(index, file,  'extension err');
				alert('Please only input png or jpg type file')
			},
			onSizeErr : function(index, file){
				console.log(index, file,  'file size too big');
				alert('File size too big');
			}
		});

        $('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });

//To select book name
$("#Save").click(function(){
	
	$('#ModeOfPayment').prop('required',false);
	$('#paydate').prop('required',false);
	$('#payinfo').prop('required',false);
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
			$('#bottomform')[0].reset();
			alert('Apinvoice Saved');
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
			$('#bottomform')[0].reset();
			alert('Apinvoice Saved & Closed');
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
				url: '{{ url('admin/APinvoice_header_workbench/get_book_detail_invoice') }}', 
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