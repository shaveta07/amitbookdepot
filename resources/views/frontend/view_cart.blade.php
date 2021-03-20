@extends('frontend.layouts.app')

@section('content')
<section class="slice-xs sct-color-2 border-bottom">
        <div class="container container-sm">
            <div class="row cols-delimited justify-content-center">
                <div class="col">
                    <div class="icon-block icon-block--style-1-v5 text-center active">
                        <div class="block-icon mb-0">
                            <i class="la la-shopping-cart"></i>
                        </div>
                        <div class="block-content d-none d-md-block">
                            <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. {{__('My Cart')}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="icon-block icon-block--style-1-v5 text-center">
                        <div class="block-icon c-gray-light mb-0">
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


    <section class="py-4 gry-bg" id="cart-summary">
        <div class="container">
            @if(Session::has('cart'))
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-xl-8">
                    <!-- <form class="form-default bg-white p-4" data-toggle="validator" role="form"> -->
                    <div class="form-default bg-white p-4">
                        <div class="">
                            <div class="">
                                <table class="table-cart border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="product-image"></th>
                                            <th class="product-name">{{__('Product')}}</th>
                                            <th class="product-price d-none d-lg-table-cell">{{__('Price')}}</th>
                                            <th class="product-quanity d-none d-md-table-cell">{{__('Quantity')}}</th>
                                            <th class="product-total">{{__('Total')}}</th>
                                            <th class="product-remove"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $total = 0;
                                        @endphp
                                        @foreach (Session::get('cart') as $key => $cartItem)
                                            @php
                                            $product = \App\Product::find($cartItem['id']);
                                            $total = $total + $cartItem['price']*$cartItem['quantity'];
                                            $product_name_with_choice = $product->name;
                                            if ($cartItem['variant'] != null) {
                                                $product_name_with_choice = $product->name.' - '.$cartItem['variant'];
                                            }
                                            // if(isset($cartItem['color'])){
                                            //     $product_name_with_choice .= ' - '.\App\Color::where('code', $cartItem['color'])->first()->name;
                                            // }
                                            // foreach (json_decode($product->choice_options) as $choice){
                                            //     $str = $choice->name; // example $str =  choice_0
                                            //     $product_name_with_choice .= ' - '.$cartItem[$str];
                                            // }
                                            @endphp
                                            <tr class="cart-item">
                                                <td class="product-image">
                                                    <a href="#" class="mr-3">
                                                        <img loading="lazy"  src="{{ asset($product->thumbnail_img) }}">
                                                    </a>
                                                </td>

                                                <td class="product-name">
                                                    <span class="pr-4 d-block">{{ $product_name_with_choice }}</span>
                                                </td>

                                                <td class="product-price d-none d-lg-table-cell">
                                                    <span class="pr-3 d-block">{{ single_price($cartItem['price']) }}</span>
                                                </td>

                                                <td class="product-quantity d-none d-md-table-cell">
                                                    @if($cartItem['digital'] != 1)
                                                        <div class="input-group input-group--style-2 pr-4" style="width: 130px;">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-number" type="button" data-type="minus" data-field="quantity[{{ $key }}]">
                                                                    <i class="la la-minus"></i>
                                                                </button>
                                                            </span>
                                                                <input type="text" name="quantity[{{ $key }}]" class="form-control input-number" placeholder="1" value="{{ $cartItem['quantity'] }}" min="1" max="10" onchange="updateQuantity({{ $key }}, this)">
                                                                <span class="input-group-btn">
                                                                <button class="btn btn-number" type="button" data-type="plus" data-field="quantity[{{ $key }}]">
                                                                    <i class="la la-plus"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="product-total">
                                                    <span>{{ single_price(($cartItem['price']+$cartItem['tax'])*$cartItem['quantity']) }}</span>
                                                </td>
                                                <td class="product-remove">
                                                    <a href="#" onclick="removeFromCartView(event, {{ $key }})" class="text-right pl-4">
                                                        <i class="la la-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row align-items-center pt-4">
                            <div class="col-md-6">
                                <a href="{{ route('home') }}" class="link link--style-3">
                                    <i class="la la-mail-reply"></i>
                                    {{__('Return to shop')}}
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                @if(Auth::check())
                                    <a href="{{ route('checkout.shipping_info') }}" class="btn btn-styled btn-base-1">{{__('Continue to Shipping')}}</a>
                                @else
                                    <button class="btn btn-styled btn-base-1" onclick="showCheckoutModal()">{{__('Continue to Shipping')}}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- </form> -->
                </div>

                <div class="col-xl-4 ml-lg-auto">
                    @include('frontend.partials.cart_summary')
                </div>
            </div>
            @else
                <div class="dc-header">
                    <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                </div>
            @endif
        </div>
    </section>

   
<!-- Registration Modal -->
    <div class="modal fade" id="RegistrationCheckout" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{__('Registration')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" id= "register_form" role="form" action="#" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group--style-1">
                                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{ __('Name') }}" name="name" required>
                                        <span class="input-group-addon">
                                            <i class="text-md la la-user"></i>
                                        </span>
                                        @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                </div>
                                        </div>

                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                <div class="form-group phone-form-group">
                                    <div class="input-group input-group--style-1">
                                        <input type="tel" id="phone-code" class="border-right-0 form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="{{ __('Mobile Number') }}" name="phone">
                                            <span class="input-group-addon">
                                                <i class="text-md la la-phone"></i>
                                            </span>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                    </div>
                                </div>

                                        <input type="hidden" name="country_code" value="">

                                <!-- <div class="form-group email-form-group">
                                    <div class="input-group input-group--style-1">
                                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ __('Email') }}" name="email">
                                            <span class="input-group-addon">
                                                <i class="text-md la la-envelope"></i>
                                            </span>
                                            @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                            @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-link p-0" type="button" onclick="toggleEmailPhone(this)">Use Email Instead</button>
                                 </div>
                                @else
                                            <div class="form-group">
                                                <div class="input-group input-group--style-1">
                                                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ __('Email') }}" name="email">
                                                    <span class="input-group-addon">
                                                        <i class="text-md la la-envelope"></i>
                                                    </span>
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif -->

                                        <div class="form-group">
                                            <!-- <label>{{ __('password') }}</label> -->
                                            <div class="input-group input-group--style-1">
                                                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" name="password" required>
                                                <span class="input-group-addon">
                                                    <i class="text-md la la-lock"></i>
                                                </span>
                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <!-- <label>{{ __('confirm_password') }}</label> -->
                                            <div class="input-group input-group--style-1">
                                                <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required >
                                                <span class="input-group-addon">
                                                    <i class="text-md la la-lock"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}">
                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="invalid-feedback" style="display:block">
                                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="checkbox pad-btm text-left">
                                            <input class="magic-checkbox" type="checkbox" name="checkbox_example_1" id="checkboxExample_1a" required>
                                            <label for="checkboxExample_1a" class="text-sm">{{__('By signing up you agree to our terms and conditions.')}}</label>
                                        </div>

                                        <div class="text-right mt-3">
                                            <button id="register_button" type="submit" class="btn btn-styled btn-base-1 w-100 btn-md">{{ __('Create Account') }}</button>
                                        </div>
                                    </form>

                    </div>
                    @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                        <div class="or or--1 mt-3 text-center">
                            <span>or</span>
                        </div>
                        <div>
                        @if (\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1)
                            <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="btn btn-styled btn-block btn-facebook btn-icon--2 btn-icon-left px-4 mb-3">
                                <i class="icon fa fa-facebook"></i> {{__('Login with Facebook')}}
                            </a>
                        @endif
                        @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1)
                            <a href="{{ route('social.login', ['provider' => 'google']) }}" class="btn btn-styled btn-block btn-google btn-icon--2 btn-icon-left px-4 mb-3">
                                <i class="icon fa fa-google"></i> {{__('Login with Google')}}
                            </a>
                        @endif
                        @if (\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                            <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="btn btn-styled btn-block btn-twitter btn-icon--2 btn-icon-left px-4">
                                <i class="icon fa fa-twitter"></i> {{__('Login with Twitter')}}
                            </a>
                        @endif
                        </div>
                    @endif
                  
                </div>
            </div>
        </div>
    </div>


    




    <!-- Guest Modal -->
    <div class="modal fade" id="GuestCheckout" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h6 class="modal-title" id="exampleModalLabel">{{__('Login')}}</h6> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <!-- <div class="card"> -->
                            <div class="text-center px-35 pt-5">
                                <h1 class="heading heading-4 strong-500">
                                    {{__('Login using OTP.')}}
                                </h1>
                            </div>
                            <div class="px-5 py-3 py-lg-4">
                                <div class="">
                                    <form class="form-default" id="otp_login" role="form" action="#" method="POST">
                                        @csrf
                                        @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                            <div class="form-group phone-form-group">
                                                <div class="input-group input-group--style-1">
                                                    <input type="tel" id="phone-code" onkeypress="return isNumberKey(event)" onchange="return isNumberKey(event)" required class="border-right-0 form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="{{ __('Mobile Number') }}" name="phone">
                                                    <span class="input-group-addon">
                                                        <i class="text-md la la-phone"></i>
                                                    </span>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone') }}</strong>
                                                    </span>
                                                </div>
                                            </div>

                                            <input type="hidden" name="country_code" value="">
                                        @endif
                                       
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-styled btn-base-1 btn-md w-100">{{ __('Send OTP') }}</button>
                                        </div>
                                    </form>
                                    <form class="form-default" id="otpform" role="form" action="{{ route('user.otpverify') }}" method="POST" style="display:none">
                                        @csrf
                                       
                                        <div class="form-group">
                                            <div class="input-group input-group--style-1">
                                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                                    <input type="text" onkeypress="return isNumberKey(event)" class="form-control form-control-sm {{ $errors->has('otp') ? ' is-invalid' : '' }}" value="" placeholder="{{__('Otp')}}" name="otp" id="otp" >
                                                  
                                                @endif
                                                <span class="input-group-addon">
                                                    <i class="text-md la la-user"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="otpmob" id="otpmob" value=""/>
                                                    <input type="hidden" name="user_id" id="user_id" value=""/>
                                        <div class="text-center">
                                            <button type="submit" id="otp_verify" class="btn btn-styled btn-base-1 btn-md ">{{ __('Verify OTP') }}</button>
                                            <button type="button" style="background-color:#5a8213; border:#5a8213;" id="resend_otp" class="btn btn-styled btn-base-1 btn-md ">{{ __('Resend OTP') }}</button>
                                        </div>
                                        
                                    </form>
                                 </div>
                            </div>
                     
                        </div>
                    <div class="p-3">
                         <div class="text-center px-35 pt-5">
                                <h1 class="heading heading-4 strong-500">
                                    {{__('Login using Email/Password')}}
                                </h1>
                            </div>
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group--style-1">
                                    <input type="email" name="email" class="form-control" placeholder="{{__('Email')}}">
                                    <span class="input-group-addon">
                                        <i class="text-md la la-user"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group--style-1">
                                    <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
                                    <span class="input-group-addon">
                                        <i class="text-md la la-lock"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <a href="{{ route('password.request') }}" class="link link-xs link--style-3">{{__('Forgot password?')}}</a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-styled btn-base-1 px-4">{{__('Sign in')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="text-center pt-3">
                        <p class="text-md">
                            {{__('Need an account?')}} <a onclick="showRegisterationModal()" class="strong-600">{{__('Register Now')}}</a>
                        </p>
                        <!-- <p class="text-md">
                            {{__('Need an account?')}} <a href="{{ route('user.registration') }}" class="strong-600">{{__('Register Now')}}</a>
                        </p> -->
                    </div>
                    @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                        <div class="or or--1 my-3 text-center">
                            <span>or</span>
                        </div>
                        <div class="p-3 pb-0">
                            @if (\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1)
                                <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="btn btn-styled btn-block btn-facebook btn-icon--2 btn-icon-left px-4 mb-3">
                                    <i class="icon fa fa-facebook"></i> {{__('Login with Facebook')}}
                                </a>
                            @endif
                            @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1)
                                <a href="{{ route('social.login', ['provider' => 'google']) }}" class="btn btn-styled btn-block btn-google btn-icon--2 btn-icon-left px-4 mb-3">
                                    <i class="icon fa fa-google"></i> {{__('Login with Google')}}
                                </a>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                            <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="btn btn-styled btn-block btn-twitter btn-icon--2 btn-icon-left px-4 mb-3">
                                <i class="icon fa fa-twitter"></i> {{__('Login with Twitter')}}
                            </a>
                            @endif
                        </div>
                    @endif
                    @if (\App\BusinessSetting::where('type', 'guest_checkout_active')->first()->value == 1)
                        <div class="or or--1 mt-0 text-center">
                            <span>or</span>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('checkout.shipping_info') }}" class="btn btn-styled btn-base-1">{{__('Guest Checkout')}}</a>
                        </div>
                    @endif
                <!-- </div> -->
            </div>
        </div>
    </div>

  <!-- Otp Modal -->
    <div class="modal fade"  role="dialog" id="OtpVerification" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <form class="form-default" role="form" action="#" id="verify_phone_user" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" name="email" id="email_verify" value="">
                                    <input type="hidden" class="form-control" name="phone" id="phone_verify" value="">
                            <div class="form-group">
                                <!-- <label>{{ __('name') }}</label> -->
                                <div class="input-group input-group--style-1">
                                    <input type="text" class="form-control" name="verification_code"required>
                                   
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
@endsection

@section('script')
    <script type="text/javascript">
    function removeFromCartView(e, key){
        e.preventDefault();
        removeFromCart(key);
    }

    function updateQuantity(key, element){
        $.post('{{ route('cart.updateQuantity') }}', { _token:'{{ csrf_token() }}', key:key, quantity: element.value}, function(data){
            updateNavCart();
            $('#cart-summary').html(data);
        });
    }

    function showCheckoutModal(){
        $('#GuestCheckout').modal();
        
    }
    function showRegisterationModal(){
        $('#GuestCheckout').modal('hide');
        $('#RegistrationCheckout').modal();
    }
    
 $(document).ready(function(){
        var isPhoneShown = true;

        var input = document.querySelector("#phone-code");
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

       
            $('.email-form-group').hide();
            $('#OtpVerification').modal('hide');
            
            $("#register_form").submit(function(e){
                e.preventDefault();
                $.post("{{ route('cart.cart_register') }}",$( "#register_form" ).serialize()).done(function( data ) {
                    data = JSON.parse(data);
                   // console.log(data);
                    // show popup
                    $('#phone_verify').val(data.phone);
                    $('#email_resend').val(data.email);
                    $('#phone_resend').val(data.phone);
                    $('#RegistrationCheckout').modal('hide');
                    $('#OtpVerification').modal('show');
                    
                   
                });
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

    $("#otp_login").submit(function(e){
        e.preventDefault();
        if($('#phone-code').val().length > 10){
			var msg = 'Please use 10 digit mobile number';
            $.toaster({ priority : 'danger', title : 'Error', message : msg});
				return false;
			}
        $.post("{{ route('user.otplogin') }}",$( "#otp_login" ).serialize()).done(function( data ) {
                data = JSON.parse(data);
                //console.log(data.status);
                if(data.status == "true")
                {
                    $('#otp_login').hide();
                    $('#otpmob').val(data.phone);
                    $('#otpform').show();
                    $.toaster({ priority : 'success', title : 'Success', message : data.message});
                   // alert(data.message);
                }
                else{
                    //alert(data.message);
                    $.toaster({ priority : 'danger', title : 'Error', message : data.message});
                }
        });
    });


    $("#resend_otp").click(function(e){
        e.preventDefault();
       var otpmob=$('#otpmob').val();
        $.post("{{ route('user.resend_code') }}",{ otpmob: otpmob,_token: "{{ csrf_token() }}" }, function( data ) {
                data = JSON.parse(data);
                //console.log(data.status);
                if(data.status == "true")
                {
                   // $('#otp_login').hide();
                    $('#otpmob').val(data.phone);
                   // $('#otpform').show();
                   $.toaster({ priority : 'success', title : 'Success', message : data.message});
                }
                else{
                    $.toaster({ priority : 'danger', title : 'Error', message : data.message});
                }
        });
    });

   

    function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

        $("#verify_phone_user").submit(function(e){
            e.preventDefault();
        $.post("{{ route('verification.phone_user') }}",$( "#verify_phone_user" ).serialize()).done(function( data ) {
            data = JSON.parse(data);
          //  console.log(data);
            if(data.status == "true")
            {
                // show popup
               $('#OtpVerification').modal('hide');
               location.reload(true);
               
               
            }
            else
            {
                alert("Wrong OTP! Please Retry" );
            }
     });

       
    });
        });

        function autoFillSeller(){
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }
        function autoFillCustomer(){
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }

        function toggleEmailPhone(el){
            if(isPhoneShown){
                $('.phone-form-group').hide();
                $('.email-form-group').show();
                isPhoneShown = false;
                $(el).html('Use Phone Instead');
            }
            else{
                $('.phone-form-group').show();
                $('.email-form-group').hide();
                isPhoneShown = true;
                $(el).html('Use Email Instead');
            }
        }


    </script>

@endsection
