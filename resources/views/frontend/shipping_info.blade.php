@extends('frontend.layouts.app')

@section('content')
<div id="page-content" <?php if(!isset($_POST['email'])){ ?>onload="document.refresh(true);document.reload(true);" <?php } ?>>
        <section class="slice-xs sct-color-2 border-bottom">
            <div class="container container-sm">
                <div class="row cols-delimited justify-content-center">
                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-shopping-cart"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. {{__('My Cart')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="la la-map-o"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon mb-0 c-gray-light">
                                <i class="la la-truck"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Delivery info')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-credit-card"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">4. {{__('Payment')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-check-circle"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">5. {{__('Confirmation')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-8">
                        <form id="address_info_form" class="old form-default" data-toggle="validator" action="{{ route('checkout.store_shipping_infostore') }}" role="form" method="POST">
                        
                            @csrf
                                @if(Auth::check())
                                    <div class="row gutters-5">
                                        @foreach (Auth::user()->addresses as $key => $address)
                                            <div class="col-md-6" id="shippiingaddr-{{ $address->id }}">
                                                <label class="aiz-megabox d-block bg-white">
                                                    <input type="radio" class="address<?php echo $key ?>" data-postalcode="{{ $address->postal_code }}" name="address_id" value="{{ $address->id }}" @if ($address->set_default)
                                                        checked
                                                    @endif required>
                                                    <input type = "hidden" name="modifiedship" value="" class="modifiedship"/>
                                                    <span class="d-flex p-3 aiz-megabox-elem">
                                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                        <span class="flex-grow-1 pl-3">
                                                            <div>
                                                                <span class="alpha-6">Mobile No:</span>
                                                                <span class="strong-600 ml-2">{{ $address->phone }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="alpha-6">Email:</span>
                                                                <span class="strong-600 ml-2">{{ $address->email }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="alpha-6">Name:</span>
                                                                <span class="strong-600 ml-2">{{ $address->name }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="alpha-6">Father Name:</span>
                                                                <span class="strong-600 ml-2">{{ $address->father_name }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="alpha-6">Address:</span>
                                                                <span class="strong-600 ml-2">{{ $address->address }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="alpha-6">Tehsil:</span>
                                                                <span class="strong-600 ml-2">{{ $address->tehsil }}</span>
                                                            </div>
                                                           
                                                            <div>
                                                                <span class="alpha-6">State:</span>
                                                                <span class="strong-600 ml-2">{{ $address->state }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="alpha-6">District:</span>
                                                                <span class="strong-600 ml-2">{{ $address->district }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="alpha-6">Country:</span>
                                                                <span class="strong-600 ml-2">IN</span>
                                                            </div>
                                                            <div>
                                                                <span class="alpha-6">Landmark:</span>
                                                                <span class="strong-600 ml-2" id="landmark<?php echo $key ?>">{{ $address->landmark }}</span>
                                                            </div>
                                                            <div>
                                                                <span class="alpha-6">Postal Code:</span>
                                                                <span class="strong-600 ml-2" id="postal<?php echo $key ?>">{{ $address->postal_code }}</span>
                                                            </div>
                                                           
                                                        </span>
                                                    </span>
                                                </label>
                                                <div class='error' id="addrerror-{{ $address->id }}"></div>
                                            </div>
                                           
                                        @endforeach
                                        <input type="hidden" name="checkout_type" value="logged">
                                        <div class="col-md-6 mx-auto" onclick="add_new_address()">
                                            <div class="border p-3 rounded mb-3 c-pointer text-center bg-white">
                                                <i class="la la-plus la-2x"></i>
                                                <div class="alpha-7">Add New Address</div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card">
                                    <div class="card-body">
                                    <h3 class="heading heading-3 strong-400 mb-0">
                                        <span>Same Information Use For Registration And Billing</span>
                                    </h3>
                                    <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Name')}}</label>
                                                    <input type="text" class="form-control" name="name" id="name"  placeholder="{{__('Name')}}" required>
                                                    <input type="hidden" class="form-control" name="user_id" id="UserId" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Email')}}</label>
                                                    <input type="text" class="form-control" name="email" id="email" placeholder="{{__('Email')}}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Address')}}</label>
                                                    <input type="text" class="form-control" name="address" id="address"  placeholder="{{__('Address')}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('City')}}</label>
                                                    <input type="text" class="form-control" name="city" id="city"  placeholder="{{__('City')}}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Select your State')}}</label>
                                                    <select onChange="getdistrict(this.value);" class="form-control custome-control" data-live-search="true" id="state" name="state" required>
                                                       <option value="">Select State</option>
                                                        @foreach (\App\State::where('isactive', 'yes')->get() as $key => $state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('District')}}</label>
                                                    <select name="district" id="district-list" class="form-control" required>
                                                         <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('Postal code')}}</label>
                                                    <div class="input-group mb-3 col-md-10">
                                                    <input type="number" id="postal_code" min="0" class="form-control postalcode" placeholder="{{__('Postal code')}}" name="postal_code" required>
                                                        <!-- <div class="input-group-prepend" style="display: block;">
                                                            <span class="input-group-text check" id="">check</span>
                                                        </div> -->
                                                    </div>
                                                    <div class="error"></div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('Phone')}}</label><br>
                                                    <input type="tel" onkeypress="return isNumberKey(event)" onchange="return isNumberKey(event)" id="phone-code" class="form-control" placeholder="{{ __('Mobile Number') }}" name="phone" required>
                                                    <!-- <input type="number" min="0" class="form-control" placeholder="{{__('Phone')}}" name="phone" required> -->
                                                    
                                                </div>
                                                <input type="hidden" name="country_code"  id="country_code" value="IN">
                                                <input type="hidden" name="country"  id="country" value="IN">
                                            </div>
                                        </div>
                                        <input type="hidden" name="checkout_type" value="guest">
                                    </div>
                                    </div>
                                @endif
                            <div class="row align-items-center pt-4">
                                <div class="col-md-6">
                                    <a href="{{ route('home') }}" class="link link--style-3">
                                        <i class="ion-android-arrow-back"></i>
                                        {{__('Return to shop')}}
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                @if(Auth::check())

                                <button id="address_info_form_og" type="submit" class="btn btn-styled btn-base-1">{{__('Continue to Delivery Info')}}</button>
                                @endif   
                                   @if(Auth::guest())
                                   <button id="address_info_form_og" style="display: none;" type="submit" class="btn btn-styled btn-base-1">{{__('Continue to Delivery Info')}}</button>
                                    <a id="address_info_form_guest" type="submit" style="color:white;" class="btn btn-styled btn-base-1">{{__('Continue to Delivery Info')}}</a>
                               @endif
                                    </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-4 ml-lg-auto cart_summary_rgt">
                        @include('frontend.partials.cart_summary')
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{__('New Address')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3">
                             <div class="row" >
                                <div class="col-md-2">
                                    <label>{{__('Mobile No.')}}<span class="required">*</span></label>
                                </div>
                                <div class="mb-3 col-md-10">
                                    <input type="tel"  onkeypress="return isNumberKey(event)" onchange="return isNumberKey(event)" id="phone-code" class="form-control" placeholder="{{ __('Mobile Number') }}" name="phone" required>
                                                    <!-- <input type="number" min="0" class="form-control" placeholder="{{__('Phone')}}" name="phone" required> -->
                                                    
                                </div>
                                    <input type="hidden" name="country_code"  id="country_code" value="IN">
                                    <input type="hidden" name="country"  id="country" value="IN">
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-2">
                                    <label>{{__('Password')}}<span class="required">*</span></label>
                                </div>
                                <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{__('Password')}}" name="password" value="" required>
                                </div>
                            </div> -->
                            
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{__('Email')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="email" class="form-control mb-3" placeholder="{{__('abc@example.com')}}" name="email" value="" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{__('Name')}}<span class="required">*</span></label>
                                </div>
                                <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{__('Name')}}" name="name" value="" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{__('Father Name')}}<span class="required">*</span></label>
                                </div>
                                <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{__('Father Name')}}" name="father_name" value="" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{__('Address')}}<span class="required">*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control textarea-autogrow mb-3" placeholder="{{__('Your Address')}}" rows="1" name="address" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{__('Tehsil')}}</label>
                                </div>
                                <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{__('Tehsil')}}" name="tehsil" value="" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{__('State')}}<span class="required">*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <div class="mb-3">
                                    <select onChange="getdistrict(this.value);" class="form-control custome-control" data-live-search="true" id="state" name="state" required>
                                        <option value="">Select State</option>
                                        @foreach (\App\State::where('isactive', 'yes')->get() as $key => $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                     <label class="control-label">{{__('District')}}<span class="required">*</span></label>
                                </div>
                                <div class="col-md-10">
                                     <div class="form-group has-feedback">
                                           
                                            <select name="district" id="district-list" class="form-control" required>
                                                <option value="">Select</option>
                                            </select>
                                    </div>
                                </div>
                            </div>
                            <input type = "hidden" name="modifiedship" value="" class="modifiedship"/>
                          
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{__('Landmark')}}<span class="required">*</span></label>
                                </div>
                                <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{__('Landmark')}}" name="landmark" value="" required>
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{__('Pincode')}}<span class="required">*</span></label>
                                </div>
                                <div class="input-group mb-3 col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{__('Your PinCode')}}" name="postal_code" value="" required>
                                    <!-- <div class="input-group-prepend" style="display: block;">
                                        <span class="input-group-text check" id="">check</span>
                                    </div> -->
                                </div>
                                <div class="error" style="color:red;"></div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-base-1">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $flat_rate_admin = 0;$flat_rate_seller=0;$local_rate=0  ?>
 @php
                                $admin_products = array();
                                $seller_products = array();
                                foreach (Session::get('cart') as $key => $cartItem){
                                    if(\App\Product::find($cartItem['id'])->added_by == 'admin'){
                                        //array_push($admin_products, $cartItem['id']);
                                        $admin_products[] = array("id"=>$cartItem['id'],"quantity"=>$cartItem['quantity']);
                                    }
                                    else{
                                        $product_ids = array();
                                        if(array_key_exists(\App\Product::find($cartItem['id'])->user_id, $seller_products)){
                                            $product_ids = $seller_products[\App\Product::find($cartItem['id'])->user_id];
                                        }
                                        array_push($product_ids, $cartItem['id']);
                                        $seller_products[\App\Product::find($cartItem['id'])->user_id] = $product_ids;
                                    }
                                }
                            @endphp
                            
                            @foreach ($admin_products as $cartproduct)
                            
                             <?php 
                                             //print_r($admin_products);die;
                                            if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'product_wise_shipping') {
												$flat_rate_admin += \App\Product::find($cartproduct['id'])->shipping_cost*$cartproduct['quantity'] ;
                                                   $local_rate += \App\Product::find($cartproduct['id'])->shipping_local_cost*$cartproduct['quantity'];
											}else{
                                                   $flat_rate_admin += \App\Product::find($cartproduct['id'])->shipping_cost ;
                                                   $local_rate += \App\Product::find($cartproduct['id'])->shipping_local_cost;
											   }
                                                    ?>
                                                    
                                                    @endforeach
                                                    @foreach ($seller_products as $key => $seller_product)
							@foreach ($seller_product as $id)
                                                            
                                                            <?php 

                                                           $flat_rate_seller += \App\Product::find($id)->shipping_cost ;
                                                            $local_rate += \App\Product::find($id)->shipping_local_cost;
                                                                ?>
                                                                
                                                                @endforeach	
                                                               @endforeach	  
							<?php $total_flate_rate = $flat_rate_admin + $flat_rate_seller;  ?>
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
                    <a  id="Resend_code" href="#">{{__('Resend Code')}}</a>
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
                    <button type="submit" class="btn btn-styled btn-base-1 w-100 btn-md">{{ __('Verify') }}</button>
                </div>
            </form>
        </div>
    </div>
<?php
$coupon_discount = 0;
if(Session::has('coupon_discount')){
                        $coupon_discount= Session::get('coupon_discount');
}
?>
@endsection

@section('script')

<script type="text/javascript">
var cur = '<?= session('currencySymbol') ?>';
var flatrate = parseFloat('<?= $total_flate_rate ?>');
var coupon_discount = parseFloat('<?= $coupon_discount ?>');
function getdistrict(val) {
    $.post( "{{ route('checkout.getdistrict') }}",{ state_id: val,_token: "{{ csrf_token() }}" }, function( data ) {
       data = JSON.parse(data);
       // console.log(data.data);
       var formoption = "";
        $.each( data.data, function( key, value ) {
            //console.log(value.id + ": " + value.name );
            formoption += "<option value='" + value.id + "'>" + value.name+ "</option>";
          
        });
        $("#district-list").html(formoption);
	
	});
}

    // $('#address_info_form').trigger("reset");
    
    function add_new_address(){
        $('#new-address-modal').modal('show');
    }

    $('.error').text('');
    $('#cod').text('');
    //$('#shipping').text(cur+flatrate.toFixed(2));
    //$('#shipping').attr('data-val',flatrate.toFixed(2));
    var isPhoneShown = true;

    var input = document.querySelector("#phone-code");
    if (input != null) {
        var iti = intlTelInput(input, {
        separateDialCode: true,
        preferredCountries: []
    });

   $(document).ready(function(){
    $('#cod').text('0');
    $('#shipping').text(cur+flatrate.toFixed(2));
    $('#shipping').attr('data-val',flatrate.toFixed(2));
   });

    var countryCode = iti.getSelectedCountryData();
    $('input[name=country_code]').val(countryCode.dialCode);

    input.addEventListener("countrychange", function() {
        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);
    });
}

function ajaxPostalcode(postcode){
	 $.post( "{{ route('checkout.check_postal_code') }}",{ postcode: postcode,_token: "{{ csrf_token() }}" }, function( data ) {
                    //console.log(data);
                    //data = JSON.parse(data);
                    $('.cart_summary_rgt').html('');
                    $('.cart_summary_rgt').html(data);
                  //  console.log('testest');
                    //$('.error').html('Cash on delivery Available').css('color','green');
                    
                    // if(data == "false")
                    // {
                    //     $('.error').html('Cash on delivery not Available').css('color','red ');
                    // }
                        
                    });
	}
if ($("input[name='address_id']:checked").val()) {
	var postcode = $("input[name='address_id']:checked").attr('data-postalcode'); //$('#postal'+address).text();
       ajaxPostalcode(postcode);
	}

     $("input[name='address_id']").change(function(){
        var address = $(this).val();
       // alert(address);
       var postcode = $(this).attr('data-postalcode'); //$('#postal'+address).text();
        ajaxPostalcode(postcode);
         
    });
     

    $('.postalcode').focusout(function(e){
        e.preventDefault();
    var postcode =$('.postalcode').val();
     // alert(postcode);
      if(postcode != '')
      {
           ajaxPostalcode(postcode)
    
      }
      else{
          alert('please enter postcode');
      }
    });
    $('#Resend_code').click(function(e){
        e.preventDefault();
        var _email = $("input[name=email]").val();
        var country_code = $("input[name=country_code]").val();
        var mobile_no = $("input[name=phone]").val();
        var phone = country_code+mobile_no;
        //console.log(phone);
        $.post( "{{ route('guest.verification.phone.resend') }}",{ email: _email, phone: phone, _token: "{{ csrf_token() }}" }, function( data ) {
            //console.log(data);
            data = JSON.parse(data);
            alert( "Otp Resend " + data.phone);
        });

    });
    $("#verify_phone_guest").submit(function(e){
        e.preventDefault();
     $.post("{{ route('verification.submit_guest') }}",$( "#verify_phone_guest" ).serialize()).done(function( data ) {
            data = JSON.parse(data);
            console.log(data.status);
            if(data.status == "true")
            {
                // show popup
                
               $('#OtpVerification').modal('hide');
               $("#address_info_form_guest").hide();
                $("#address_info_form_og").show();
                $('#UserId').val(data.user_id);
                $("#address_info_form").submit();
               
            }
            else
            {
                alert("Wrong OTP! Please Retry" );
            }
     });

       
    });

    $("#address_info_form_guest").click(function(){

        // if all fields are filled
        //$("#address_info_form_guest").validate();
        var name=$("#name").val();
		var email=$("#email").val();
        var address=$("#address").val();
        var country_code=$("#country_code").val();
        var state=$("#state").val();
        var district=$("#district-list").val();
        var postal_code=$("#postal_code").val();
        var phone=$("#phone-code").val();
        
        if((name=="") || (email=="") || (address=="") || (state=="") || (district=="")  || (postal_code=="") || (phone==""))
        {
            alert('Please Fill All Fields');
        }else{

            $.post("{{ route('checkout.guest_register') }}",$("#address_info_form" ).serialize()).done(function( data ) {
                data = JSON.parse(data);
                console.log(data.status);
                if(data.status == "sent")
                {
                    $('#email_verify').val(data.email);
                    $('#phone_verify').val(data.phone);
                    $('#email_resend').val(data.email);
                    $('#phone_resend').val(data.phone);
                    // show popup
                $('#OtpVerification').modal();
                $('#address_info_form_guest').attr("disabled", false);
                }
                else{
                    alert(data.message);
                }
                
            
            });
        }
    });

    function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)){
        
            return false;
    }
    if($('#phone-code').val().length > 10){
			var msg = 'Please use 10 digit mobile number';
            alert(msg);
           
				return false;
			}
       
       
    return true;
}

</script>

<script>

jQuery( document ).ready(function( $ ) {
    
   $(window).on('popstate', function() {
      location.reload(true);
      //alert('testtt');
   });

});

</script>
@endsection
