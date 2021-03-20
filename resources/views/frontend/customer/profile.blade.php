@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Manage Profile')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('profile') }}">{{__('Manage Profile')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="" action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{__('Basic info')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{__('Your Name')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{__('Your Name')}}" name="name" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{__('Your Email')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="email" class="form-control mb-3" placeholder="{{__('Your Email')}}" name="email" value="{{ Auth::user()->email }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{__('Photo')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="photo" id="file-3" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                            <label for="file-3" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{__('Choose image')}}
                                                </strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{__('Your Password')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="password" class="form-control mb-3" placeholder="{{__('New Password')}}" name="new_password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{__('Confirm Password')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="password" class="form-control mb-3" placeholder="{{__('Confirm Password')}}" name="confirm_password">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{__('Update Profile')}}</button>
                            </div>

                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{__('Addresses')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row gutters-10">
                                        @foreach (Auth::user()->addresses as $key => $address)
                                            <div class="col-lg-6">
                                                <div class="border p-3 pr-5 rounded mb-3 position-relative">
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
                                                    @if ($address->set_default)
                                                        <div class="position-absolute right-0 bottom-0 pr-2 pb-3">
                                                            <span class="badge badge-primary bg-base-1">Default</span>
                                                        </div>
                                                    @endif
                                                    <div class="dropdown position-absolute right-0 top-0">
                                                        <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">
                                                            <i class="la la-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                            @if (!$address->set_default)
                                                                <a class="dropdown-item" href="{{ route('addresses.set_default', $address->id) }}">Make This Default</a>
                                                            @endif
                                                            {{-- <a class="dropdown-item" href="">Edit</a> --}}
                                                            <a class="dropdown-item" href="{{ route('addresses.destroy', $address->id) }}">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-6 mx-auto" onclick="add_new_address()">
                                            <div class="border p-3 rounded mb-3 c-pointer text-center bg-light">
                                                <i class="la la-plus la-2x"></i>
                                                <div class="alpha-7">Add New Address</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

@endsection

@section('script')
<script type="text/javascript">
    function add_new_address(){
        $('#new-address-modal').modal('show');
    }
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
var isPhoneShown = true;

    var input = document.querySelector("#phone-code");
    if (input != null) {
        var iti = intlTelInput(input, {
        separateDialCode: true,
        preferredCountries: []
    });

   var countryCode = iti.getSelectedCountryData();
    $('input[name=country_code]').val(countryCode.dialCode);

    input.addEventListener("countrychange", function() {
        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);
    });
}
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
@endsection
