@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-5">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
                    <div class="card">
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
                                                    <input type="tel" id="phone-code" onkeypress="return isNumberKey(event)" onchange="return isNumberKey(event)" required class="border-right-0 h-100 w-100 form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="{{ __('Mobile Number') }}" name="phone">
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
                    
                        <div class="card">
                            <div class="text-center px-35 pt-5">
                                <h1 class="heading heading-4 strong-500">
                                    {{__('Login using Mobile/Email or Password.')}}
                                </h1>
                            </div>
                            <div class="px-5 py-3 py-lg-4">
                                <div class="">
                                    <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                            <span>{{ __('Use country code before number') }}</span>
                                        @endif
                                        <div class="form-group">
                                            <div class="input-group input-group--style-1">
                                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                                    <input type="text" class="form-control form-control-sm {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{__('Email Or Phone')}}" name="email" id="email">
                                                @else
                                                    <input type="email" class="form-control form-control-sm {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ __('Email') }}" name="email">
                                                @endif
                                                <span class="input-group-addon">
                                                    <i class="text-md la la-user"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group input-group--style-1">
                                                <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{__('Password')}}" name="password" id="password">
                                                <span class="input-group-addon">
                                                    <i class="text-md la la-lock"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="checkbox pad-btm text-left">
                                                        <input id="demo-form-checkbox" class="magic-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                        <label for="demo-form-checkbox" class="text-sm">
                                                            {{ __('Remember Me') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 text-right">
                                                <a href="{{ route('password.request') }}" class="link link-xs link--style-3">{{__('Forgot password?')}}</a>
                                            </div>
                                        </div>


                                        <div class="text-center">
                                            <button type="submit" class="btn btn-styled btn-base-1 btn-md w-100">{{ __('Login') }}</button>
                                        </div>
                                    </form>
                                
                                </div>
                            </div>
                            <div class="text-center px-35 pb-3">
                                <p class="text-md">
                                    {{__('Need an account?')}} <a href="{{ route('user.registration') }}" class="strong-600">{{__('Register Now')}}</a>
                                </p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="text-center px-35 pt-5">
                                <h1 class="heading heading-4 strong-500">
                                    {{__('Login using Social Media.')}}
                                </h1>
                            </div>
                            
                            @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                             
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
                    @if (env("DEMO_MODE") == "On")
                        <div class="bg-white p-4 mx-auto mt-4">
                            <div class="">
                                <table class="table table-responsive table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <td>{{__('Seller Account')}}</td>
                                            <td><button class="btn btn-info" onclick="autoFillSeller()">Copy credentials</button></td>
                                        </tr>
                                        <tr>
                                            <td>{{__('Customer Account')}}</td>
                                            <td><button class="btn btn-info" onclick="autoFillCustomer()">Copy credentials</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')


<script src="{{ asset('frontend/js/jquery.toaster.js') }}"></script>
<script src="{{ asset('frontend/js/toaster.jquery.json') }}"></script>
    <script type="text/javascript">
    
        function autoFillSeller(){
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }
        function autoFillCustomer(){
            $('#email').val('customer@example.com');
            $('#password').val('123456');
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

    $('#otpform').hide();
    $('#resendform').hide();
    
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
    </script>
@endsection
