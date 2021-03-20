@extends('layouts.app')

@section('content')
<style>

.gsts input[type=text]{width:50%}
.gsts input[type=radio]{height:10px !important}
.overlay {
  position: absolute;
  background: rgba(255,0,0,0.7);
  left: 0.7em;
  right: 0.7em;
  height: 3.2em;
  text-align: center;
  margin-top: -44px;
}
</style>

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Create AP Invoice')}}</h3>
			@if(session()->get('msg') != null)
			<div class="alert alert-danger">{{ session()->get('msg') }}</div>
			@endif
        </div>
        <div class="panel-body">
            <div class="row col-sm-12">
            <form method="post" action="{{url('admin/APinvoice_header_workbench/store')}}"  enctype="multipart/form-data">
            @csrf
                <table class="table table-responsive form_table">
			  	     <tr><td>Supplier * : </td><td>
                        <select name="supplier" required="required" >
                        <option value=""></option>
                        <?php
                        for($i=0;$i<count($supplier_data);$i++)
                        {
                        ?>
                        <option value="<?php echo $supplier_data[$i]['SupplierID']; ?>"><?php echo $supplier_data[$i]['Name']; ?></option>
                        <?php }  ?>
                        </select>
			        </td>
                    <td>Invoice Number* </td><td><input type="text" name="invoice_number" /></td>
                    </tr>
		            <tr>
                        <td>Invoice Date* :</td><td> <input type="text" name="date" value="<?php echo date("m/d/Y"); ?>" id="invdate" class="datepicker" readonly="readonly" /></td>
						<td><b>No GST:&nbsp;</b><input style="height:10px !important;border: 1px solid #ccc;" type="checkbox" value="nogst" id="nogst" name="nogst" /></td>
                        <td><strong>Total Amount without GST</strong> <input type="text" readonly id="totalwithoutgst" name="totalwithoutgst" /></td><td> <strong>Total Amount with GST</strong> <input readonly type="text" name="amount" id="totalwithgst" /></td>
                    </tr>

                    <tr><td colspan="5">
                        <table class="table-responsive gsts">
                            <tr style="background:#ccc"><td>0% GST<div class="ngst"></div></td><td><input type="text" readonly name="gst0" id="gst0" value="0" placeholder="0% GST" /></td><td><strong>cgst0:&nbsp;</strong><input type="text" readonly name="cgst0" id="cgst0" value="0" placeholder="0% CGST" /></td><td><strong>SGST0&nbsp;</strong><input type="text" readonly name="sgst0" id="sgst0" value="0" placeholder="0% SGST" /></td><td><strong>IGST0:&nbsp;</strong><input  readonly type="text" name="igst0" id="igst0" value="0" data-id="igst0" placeholder="0% IGST" /></td><td><strong>TaxOn:&nbsp;</strong><input type="text" name="taxon0" id="taxon0" value="0" data-id="taxon0" placeholder="0% Tax On" /></td><td>&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon0gst" id="taxon0gst" class="taxon5gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" name="taxon0gst" id="taxon0igst"  value="igst" class="taxon0gst" /></td></tr>
                            <tr><td>5% GST<div class="ngst"></div></td><td><input type="text" readonly name="gst5" id="gst5" value="0" placeholder="5% GST" /></td><td><strong>cgst2.5:&nbsp;</strong><input type="text" readonly name="cgst2_5" id="cgst2_5" value="0" placeholder="2.5% CGST" /></td><td><strong>SGST2.5&nbsp;</strong><input type="text" readonly name="sgst2_5" id="sgst2_5" value="0" placeholder="2.5% SGST" /></td><td><strong>IGST5:&nbsp;</strong><input  readonly type="text" name="igst5" id="igst5" value="0" data-id="igst5" placeholder="5% IGST" /></td><td><strong>TaxOn:&nbsp;</strong><input type="text" name="taxon5" id="taxon5" value="0" data-id="taxon5" placeholder="5% Tax On" /></td><td>&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon5gst" id="taxon5gst" class="taxon5gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" name="taxon5gst" id="taxon5igst"  value="igst" class="taxon5gst" /></td></tr>
                            <tr style="background:#ccc"><td>12% GST<div class="ngst"></div></td><td><input type="text" readonly name="gst12" id="gst12" value="0" placeholder="12% GST" /></td><td><strong>cgst6:&nbsp;&nbsp;&nbsp;</strong><input type="text" readonly name="cgst6" id="cgst6" value="0" readonly placeholder="6% CGST" /></td><td><strong>SGST6 &nbsp;&nbsp;&nbsp;</strong><input type="text" name="sgst6" readonly id="sgst6" value="0" placeholder="6% SGST" /></td><td><strong>IGST12:&nbsp;&nbsp;&nbsp;</strong><input type="text" readonly name="igst12" data-id="igst12" id="igst12" value="0" placeholder="12% IGST" /></td><td><strong>TaxOn:&nbsp;</strong><input type="text" name="taxon12" id="taxon12" value="0" data-id="taxon12" placeholder="12% Tax On" /></td><td>&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon12gst" id="taxon12gst" class="taxon12gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" class="taxon12gst" name="taxon12gst" id="taxon12igst"  value="igst" /></td></tr>
                            <tr><td>18% GST<div class="ngst"></div></td><td><input type="text" readonly name="gst18" id="gst18" value="0" placeholder="18% GST" /></td><td><strong>cgst9:&nbsp;&nbsp;&nbsp;</strong><input type="text" name="cgst9" readonly id="cgst9" value="0" placeholder="9% CGST" /></td><td><strong>SGST9 &nbsp;&nbsp;&nbsp;</strong><input type="text" readonly name="sgst9" id="sgst9" value="0" placeholder="9% SGST" /></td><td><strong>IGST18:&nbsp;&nbsp;&nbsp;</strong><input type="text" readonly name="igst18" data-id="igst18" id="igst18" value="0" placeholder="18% IGST" /></td><td><strong>TaxOn:&nbsp;</strong><input type="text" name="taxon18" id="taxon18" value="0" data-id="taxon18" placeholder="18% Tax On" /></td><td>&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon18gst" id="taxon18gst" class="taxon18gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" class="taxon18gst" name="taxon18gst" id="taxon18igst"  value="igst" /></td></tr>
                            <tr style="background:#ccc"><td>28% GST<div class="ngst"></div></td><td><input type="text" readonly name="gst28" id="gst28" value="0" placeholder="28% GST" /></td><td><strong>cgst14:&nbsp;</strong><input type="text" readonly name="cgst14" id="cgst14" value="0" placeholder="14% CGST" /></td><td><strong>SGST14 &nbsp;</strong><input type="text" readonly name="sgst14" id="sgst14" value="0" placeholder="14% SGST" /></td><td><strong>IGST28:&nbsp;</strong><input type="text" readonly name="igst28" id="igst28" value="0"  data-id="igst28" placeholder="28% IGST" /></td><td><strong>TaxOn:&nbsp;</strong><input type="text" name="taxon28" id="taxon28" value="0" data-id="taxon28" placeholder="28% Tax On" /></td><td>&nbsp;&nbsp;&nbsp;<span>GST</span>:<input type="radio" name="taxon28gst" id="taxon28gst" class="taxon28gst" value="gst" checked /> &nbsp;&nbsp;&nbsp;<span>IGST</span>:<input type="radio" class="taxon28gst" name="taxon28gst" id="taxon28igst"  value="igst" /></td></tr>

                        </table>
                    </td></tr>

                        <tr><td>SGST : </td><td><input type="number" step="0.1" readonly name="sgst" value="" id="sgst" class="sgst"  /></td>
                        <td>CGST: </td><td><input type="number" name="cgst" readonly  step="0.1" id="cgst" /></td></tr>

                        <tr><td>GST : </td><td><input type="number" name="gst" readonly step="0.1" readonly value="" id="gst" class="gst"  /></td>
                        <td>IGST: </td><td><input type="number" name="igst" readonly id="igst" step="0.1" /></td></tr>

                        <tr><td>Description</td><td><textarea name="description"></textarea></td><td>Upload Invoice</td>
                        <td>  <div id="image">

						</div></td></tr>
		  
                    <tr><td>Invoive Type:</td><td>
                    <select name="invoicetype">
                    <option value="S">Standard Invoice</option>
                    <option value="C">Credit Note Invoice</option>
                    </select></td>
                    <td>AP Type*</td>
                    <td>
                    <select required name="aptype">
                        <option value="">Select AP Type</option>
                        <option value="purchase">Purchase</option>
                        <option value="expanse">Expanse</option>
                        <option value="withdraw">Withdraw</option>
                    </select>
                    </td>
                    </tr>
                    <tr><td></td>
		        <td><input type="submit" value="Save and Next" name="save" /></td><td></td><td></td></tr>
                </table>
	         </form>	
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd', maxDate: new Date() });
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
$('#taxon0,#taxon5,#taxon12,#taxon18,#taxon28').keyup(function(){
		
		var dataid = $(this).attr('data-id');
		var wt = $(this).val();
		if(isNaN(wt) || wt==''){
			wt=0;
			}
		var amount = parseFloat(wt);
		var tax=0;
		if(dataid == 'taxon5'){
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
			if(isNaN(taxon0)){
				taxon0 = 0;
				}
				
				var taxon5 = $('#taxon5').val();
			if(isNaN(taxon5)){
				taxon5 = 0;
				}
				
				var taxon12 = $('#taxon12').val();
			if(isNaN(taxon12)){
				taxon12 = 0;
				}
				
				var taxon18 = $('#taxon18').val();
			if(isNaN(taxon18)){
				taxon18 = 0;
				}
				
				var taxon28 = $('#taxon28').val();
			if(isNaN(taxon28)){
				taxon28 = 0;
				}
			
		var withoutgst	= parseFloat(taxon0) + parseFloat(taxon5)+parseFloat(taxon12)+parseFloat(taxon18)+parseFloat(taxon28);
		//console.log("test = "+withoutgst);
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
		$('#totalwithgst').val(withoutgst+totalgst+totaligst);
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
	$('#totalwithgst').val($('#totalwithoutgst').val());
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
			$('#totalwithgst').val($(this).val());
			}
		});
</script>
@endsection