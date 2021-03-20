@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Pre Booking Form')}}</h3>
            @if(session()->get('msg') != null)
			<div class="alert alert-danger">{{ session()->get('msg') }}</div>
			@endif
        </div>
        <div class="preorder"><?php if(isset($msg)) echo $msg; ?>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="#" id="preBookingform" method="POST">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label" for="type">{{__('Store Name')}}</label>
                    <select class="form-control demo-select2-placeholder" name="store" id="Store" required>
                         <option value="1">Amit Book Depot</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                       <label class="control-label" for="name">{{__('Mobile* ')}}</label>
                        <input type="text" id="mobile1" class="form-control" onkeyup="setmobile(this)" name="phone" value="" required>
                        <input type="hidden" name="mobile1" id="mobile" value="" />
						<input type="hidden" name="mobile2" id="mobile2" value="" />
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="name">{{__('Name*')}}</label>
                        <input type='text' name="c_name" id="cust_name"  class="form-control" required="required" >
                        <input type="hidden" id="customer_id" name="customer_id" value="">
                        <input type="hidden" id="user_id" name="user_id" value="">
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="name">{{__('Father name')}}</label>
                        <input type="text" class="form-control" name="father_name" id="father_name" value="" >
                       
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label" for="name">{{__('Address')}}</label>
                        <input type="text" class="form-control" id="Address" name="address" value="" >
                    </div>
                    <div class="col-sm-3">
                       <label class="control-label" for="name">{{__('Email')}}</label>
                        <input type="email" class="form-control" id="Email" name="email" value="" >
                    </div>
                    
                    <div class="col-sm-3">
                        <label class="control-label" for="name">{{__('Tehsil')}}</label>
                        <input type="text" class="form-control" id="Tehsil" name="tehsil" value="" >
                    </div>
                    
                </div>
               
                
                <div class="form-group">
                <div class="col-sm-4">
                       <label class="control-label" for="name">{{__('District')}}</label>
                        <input type="text" class="form-control" id="District" name="district" value="" >
                    </div>
                    <!-- <div class="col-sm-3">
                       <label class="control-label" for="name">{{__('City')}}</label>
                        <input type="text" class="form-control" id="City" name="city" value="" >
                    </div> -->
                    <div class="col-sm-4">
                       <label class="control-label" for="name">{{__('Landmark')}}</label>
                        <input type="text" class="form-control" id="Landmark" name="landmark" value="" >
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="type">{{__('State')}}</label>
                        <select class="form-control demo-select2-placeholder" name="state" id="State"   >
                        <option value="">{{__('State')}}</option>
                            @foreach(\App\State::all() as $state)
								<option value="{{$state->name}}">{{__($state->name)}}</option>
							@endforeach
                        </select>
                    </div>
                  
                </div>
                <div class="form-group">
                    <div class="col-sm-4">      
                        <label class="control-label" for="name">{{__('Pincode')}}</label>
                        <input type="text" class="form-control" id="Zipcode" name="postal_code" value="" >
                    </div>
                    <div class="col-sm-4">
                    <label class="control-label" for="type">{{__('Prebooking type*')}}</label>
                    <select name="invoice_type" id="invoice_type" name="type"  class="form-control">
									<option value="S">Standard</option>
									<option value="C">Credit</option>
								</select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="name">{{__('GSTIN')}}</label>
                        <input type="text" class="form-control" id="GSTIN" name="gstin" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                        <label class="control-label" for="type">{{__('Category')}}</label>
                        <select class="form-control demo-select2-placeholder" name="category" id="Category" >
                            <option value="">{{__('Select Category')}}</option>
                            @foreach(\App\CustomerCategory::all() as $category)
								<option value="{{$category->id}}">{{__($category->name)}}</option>
							@endforeach
                                
                        </select>
                    </div>
                    
                    <div class="col-sm-4">
                        <label class="control-label" for="type">{{__('Institutes')}}</label>
                        <select class="form-control demo-select2-placeholder" name="institute" id="Institute" >
                                <option value="">{{__('Institutes')}}</option>
                                @foreach(\App\Institute::all() as $institute)
                                    <option value="{{$institute->id}}">{{__($institute->name)}}</option>
                                @endforeach
                                    
                        </select>
                    </div>

                 
                    
                    <div class="invoice form-group col-sm-4 col-lg-4" style="display:none">
							
								<label>AR Invoice NUM :</label>
								<input type='text' name="invoicenum" id="invoicenum" class="form-control" />
							
						</div>
                </div>

               
               <div class="form-group">
                    <label class="control-label">{{__('Description')}}</label>
                    <textarea rows="4" cols="50" name="description" class="editor" data-buttons='bold,underline,italic,hr,|,ul,ol,|,align,paragraph,|,image,table'></textarea>
                </div>
               
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Save and Next')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->
        </div>
            <!-- Modal-->
    <div class="modal" tabindex="-1" role="dialog" id="OtpVerification">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <div class="text-center px-35 pt-5" style="margin:auto; padding:0px !important;">
                    <h3 class="heading heading-4 strong-500">
                            {{__('Phone Verification')}}
                    </h3>
                    <p>Verification code has been sent. Please Enter OTP.</p>
                    <a  id="Resend_code" style ="text-decoration:underline;" href="#">{{__('Resend Code')}}</a>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    
                <div class="col-12 col-lg">
                    <form class="form-default" role="form" action="#" id="verify_phone_guest" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" name="email" id="email_verify" value="">
                        <input type="hidden" class="form-control" name="phone" id="phone_verify" value="">
                        <input type="hidden" class="form-control" name="invoice" id="InvoiceType" value="">
                            <div class="form-group">
                                <!-- <label>{{ __('name') }}</label> -->
                                <div class="input-group input-group--style-1">
                                    <input type="text" class="form-control" name="verification_code">
                                   
                                    <span class="input-group-addon">
                                        <i class="text-md la la-key"></i>
                                    </span>
                                </div>
                            </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Verify') }}</button>
                </div>
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
		
    <script type="text/javascript">
    $("#preBookingform")[0].reset(); 
    $('#OtpVerification').modal('hide');
function setmobile(obj){
	$( "#mobile").val($(obj).val());
	}
	
$("#mobile1").autocomplete({

        source: '{{ url('admin/PreOrderBooking/CustomerForPrebooking')}}',
        focus: function( event, ui ) {
            console.log(ui);
                 //"xxxxx" + item.Mobile1.substring(5)
                 $( "#mobile1"  ).val( "xxxxxxx" + ui.item.phone.substring(7) );
                  $( "#mobile"  ).val( ui.item.phone );
                  //$( "#mobile2"  ).val( ui.item.phone );
                  $( "#cust_name"  ).val( ui.item.name );
                  $( "#father_name"  ).val( ui.item.father_name );
                  $("#customer_id").val(ui.item.customer_id);
                  $("#user_id").val(ui.item.user_id);
                  $("#Category").val(ui.item.categoryId);
                  $( "#Institute"  ).val( ui.item.instituteId );
                  $("#Email").val(ui.item.email);
                 $("#Email").prop("readonly", true);
                 $("#District").val(ui.item.district);
                  $("#Landmark").val(ui.item.landmark);
				  $("#Address").val(ui.item.address);
				  //$("#address2").val(ui.item.Address2);
				  $("#Tehsil").val(ui.item.tehsil);
				  $("#State").val(ui.item.state).trigger('change');;
				  $("#Zipcode").val(ui.item.postalcode);
				  $("#GSTIN").val(ui.item.gstin);
				  $('#Category').val(ui.item.categoryId).trigger('change');
                  $('#Institute').val(ui.item.instituteId).trigger('change');
				  
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
               .append( "<a>" + "xxxxxxx" + item.phone.substring(7) + " - " + item.name + " + "  + item.email + "</a>" )
               .appendTo( ul );

				}else{
					return $( "<li>" )
               .append( "No Customer Found... " )
               .appendTo( ul );
				}
        
            };
            
            

function showmobile(obj,user,mobile,role){
	//jQuery(obj).html(mobile);
	$.ajax({
				//async : false,
				url : 'ajax_files/get_customershowmobile.php',
				type : "POST",
				data : {'userid' : user},
				dataType : 'text',
				timeout : 1000,
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
				if(dataType == 1){
					jQuery(obj).html(mobile);
					}else if(dataType == 2){
						alert("Your Limit has reached to Maximum. Please contact Administrator");
						}
										}
			});
	}
	

function copyme(obj){
		$('#mobile1').val($(obj).val());
    }

    $('#invoice_type').change(function(e){
	if($(this).val() == 'C'){
		$('.mid').removeClass('col-sm-4 col-lg-4');
		$('.mid').addClass('col-sm-3 col-lg-3');
		$('.invoice').show();
		}else{
		$('.mid').removeClass('col-sm-3 col-lg-3');
		$('.mid').addClass('col-sm-4 col-lg-4');	
		$('.invoice').hide();
			}
	});

    
    $("#preBookingform").submit(function(e){
        e.preventDefault();
     $.post("{{ route('PreOrderBooking.PreBookingStore') }}",$("#preBookingform").serialize()).done(function( data ) {
            data = JSON.parse(data);
          // console.log(data);
           if(data.status == "newuser")
            {
                // show popup
                
               $('#OtpVerification').modal('show');
               $('#email_verify').val(data.email);
               $('#phone_verify').val(data.phone);
               $('#InvoiceType').val(data.invoiceType);
              
            }
            if(data.status == "olduser"){
                var newInvcNum = data.newInvcNum;
           // alert(newInvcNum);
                var orderid = data.order_id;
              
                $("#preBookingform")[0].reset(); 
               window.location.replace("{{ url('admin/PreOrderBooking/PreOrderBookingLines')}}/"+newInvcNum);

            }
            if(data.status == "emailmsg"){
                 alert(data.msg);
                // window.location.replace("{{ url('admin/PreOrderBooking')}}/");

            }
             
     });
    });

     $('#Resend_code').click(function(e){
        e.preventDefault();
        var _email = $("input[name=email]").val();
       // var country_code = $("input[name=country_code]").val();
        var mobile_no = $("input[name=phone]").val();
        var phone = "+91"+mobile_no;
        //console.log(phone);
        $.post( "{{ route('PreOrderBooking.resend_code') }}",{ email: _email, phone: phone, _token: "{{ csrf_token() }}" }, function( data ) {
            //console.log(data);
            data = JSON.parse(data);
            alert( data.message);
        });

    });
    $("#verify_phone_guest").submit(function(e){
        e.preventDefault();
     $.post("{{ route('PreOrderBooking.verifyPhonePrebooking') }}",$( "#verify_phone_guest" ).serialize()).done(function( data ) {
            data = JSON.parse(data);
           // console.log(data.status);
            if(data.status == "true")
            {
                // show popup
                var newInvcNum = data.newInvcNum;
               $('#OtpVerification').modal('hide');
               //$('#customer_id').val(data.user_id);
               $("#preBookingform")[0].reset(); 
               var orderid = data.order_id;
               window.location.replace("{{ url('admin/PreOrderBooking/PreOrderBookingLines')}}/" +newInvcNum);
               
            }
            else
            {
                alert("Wrong OTP! Please Retry" );
            }
     });

       
    });


    </script>
@endsection
