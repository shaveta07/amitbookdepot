@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('AP Invoice Header Workbench - Old Books')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('APInvoiceAlls.ApInvoiceOld2Store') }}" id="preBookingform" method="POST">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label" for="type">{{__('Store Name')}}</label>
                    <select class="form-control demo-select2-placeholder" name="store" id="Store" required>
                         <option value="AmitBookDepot">Amit Book Depot</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                       <label class="control-label" for="name">{{__('Supplier Mobile* ')}}</label>
                        <input type="text" id="mobile1" class="form-control" onkeyup="setmobile(this)" name="mobile" value="" required>
                        <input type="hidden" name="mobile1" id="mobile" value="" />
						<input type="hidden" name="mobile2" id="mobile2" value="" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="name">{{__('Customer Name*')}}</label>
                        <input type="text" class="form-control" name="name" id="cust_name" value="" required>
                        <input type="hidden" id="supplier_id" name="supplier_id" value="-1" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="type">{{__('Category')}}</label>
                        <select name="type" id="cutomertype"  name="category" class="form-control">
								<option value="c">Customer</option>
								
								</select>
                        <!-- <select class="form-control demo-select2-placeholder" name="category" id="Category">
                            <option value="">{{__('Select Category')}}</option>
                            @foreach(\App\CustomerCategory::all() as $category)
								<option value="{{$category->id}}">{{__($category->name)}}</option>
							@endforeach
                                
                        </select> -->
                    </div>
                    <div class="col-sm-3">
                    <label class="control-label" for="type">{{__('Quantity*')}}</label>
                    <input type='text' name="quantity" id="quantity"  class="quantity" required="required" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                       <label class="control-label" for="name">{{__('Email* ')}}</label>
                        <input type="email" class="form-control" id="Email" name="email" value="" >
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="name">{{__('Address1')}}</label>
                        <input type="text" class="form-control" id="address1" name="address1" value="" >
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="type">{{__('Invoice Date')}}</label>
                        <input type="text" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                       <label class="control-label" for="name">{{__('City')}}</label>
                        <input type="text" class="form-control" id="City" name="city" value="" >
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="name">{{__('Zipcode')}}</label>
                        <input type="text" class="form-control" id="Zipcode" name="postal_code" value="" >
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="type">{{__('State')}}</label>
                        <select class="form-control demo-select2-placeholder" name="state" id="State"   >
                        <option value="">{{__('State')}}</option>
                            @foreach(\App\State::all() as $state)
								<option value="{{$state->name}}">{{__($state->name)}}</option>
							@endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="name">{{__('GSTIN')}}</label>
                        <input type="text" class="form-control" id="GSTIN" name="gstin" value="" >
                    </div>
                </div>
               <div class="form-group">
                    <div class="col-sm-3">
                       <label class="control-label" for="name">{{__('Bank Name')}}</label>
                       <input type='text' name="bankname"id="bankname" class="form-control" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="name">{{__('IFSC Code')}}</label>
                        <input type='text' name="ifsc"id="ifsc" class="form-control" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="type">{{__('Bank Account Name')}}</label>
                        <input type="text" name="bankaccountname" id="bankaccountname" class="form-control"  />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="name">{{__('Bank Account Number')}}</label>
                        <input type='text' name="bankaccountnumber"id="bankaccountnumber" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                        <label class="control-label" for="name">{{__('Description')}}</label>
                        <textarea name="description" id="description"  class="form-control ckeditor"></textarea>
            </div>
            </div>
            
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Save and Next')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->
        

    </div>
</div>

@endsection

@section('script')
<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/black-tie/jquery-ui.css" />
<script
			  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
			  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
			  crossorigin="anonymous"></script>
		
    <script type="text/javascript">
    $('#date').datepicker({ dateFormat: 'yy-mm-dd'});
   
function setmobile(obj){
	$( "#mobile").val($(obj).val());
	}
	
$("#mobile1").autocomplete({

        source: '{{ url('admin/APinvoice_header_workbench/get_customerForOldBooking')}}',
        focus: function( event, ui ) {
            console.log(ui);
                 //"xxxxx" + item.Mobile1.substring(5)
                 $( "#mobile1"  ).val( "xxxxxxx" + ui.item.mobile1.substring(7) );
                  $( "#mobile"  ).val( ui.item.mobile1 );
                  $( "#mobile2"  ).val( ui.item.mobile2 );
                  $( "#cust_name"  ).val( ui.item.name );
                  $("#customer_id").val(ui.item.supplierid);
                  $( "#category"  ).val( ui.item.type );
                  $("#Email").val(ui.item.email1);
				  $("#address1").val(ui.item.address1);
				  $("#address2").val(ui.item.address2);
				  $("#City").val(ui.item.city);
				  $("#State").val(ui.item.state).trigger('change');;
				  $("#Zipcode").val(ui.item.zipcode);
				  $("#GSTIN").val(ui.item.gstin);
				 // $('#cutomertype').val(ui.item.type).trigger('change');
                  $("#bankname").val(ui.item.bankname);
                  $("#ifsc").val(ui.item.ifsc);
                  $("#bankaccountname").val(ui.item.bankaccountname);
                  $("#bankaccountnumber").val(ui.item.bankaccountnumber);
                  $("#description").val(ui.item.description);
				//   if(ui.item.user_type == 'customer'){
				// 		$('#invoice_type').val('C');
				// 	}else{
				// 		$('#invoice_type').val('S');
				// 	}
                     return false;
                     
               },
               select: function( event, ui ) {
				   if(ui.item.BookId != 0){
				   
				   
				   var qt = parseInt(ui.item.Quantity);
				   if(qt<1){
					   isokay = 2;
					   
					}else{
						formFlag = 1;
						
						//$("#cost-"+bookcount).val(parseInt(ui.item.SellingPrice) * parseInt($("#subscriptionperiod").val()));
						//if($("#subscriptionperiod").val() ==''){$("#cost-"+bookcount).val(parseInt(ui.item.SellingPrice));}
						}
				   
				   
				  
				  // $("#cost").val(parseInt(ui.item.SellingPrice));
			   }
			   return false;
				   }
				   
            }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
				//$( "#mobile" ).val($("#mobile1").val());
				if(item.customerID != ''){

               return $( "<li>" )
               .append( "<a>" + "xxxxxxx" + item.mobile1.substring(7) + " - " + item.name + " + "  + item.email1 + "</a>" )
               .appendTo( ul );

				}else{
					return $( "<li>" )
               .append( "No Supplier Found... " )
               .appendTo( ul );
				}
        
            };
            
            


	

function copyme(obj){
		$('#mobile1').val($(obj).val());
    }



    </script>
@endsection
