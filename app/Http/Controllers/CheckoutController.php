<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Shipping;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\PaytmController;
use Illuminate\Support\Facades\Hash;
use App\Order;
use App\BusinessSetting;
use App\Currency;
use App\Coupon;
use App\CouponUsage;
use App\User;
use App\Customer;
use App\Address;
use App\District;
use App\State;
use Session;

class CheckoutController extends Controller
{

    public function __construct()
    {
        //
        $currency = Currency::where('id',BusinessSetting::where('type', 'home_default_currency')->first()->value)->first()->symbol;
		Session::put('currencySymbol', $currency);
		Session::put('currency', Currency::where('id',BusinessSetting::where('type', 'home_default_currency')->first()->value)->first());

    }

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {
        if ($request->payment_option != null) {

            $orderController = new OrderController;
            $orderController->store($request);

            $request->session()->put('payment_type', 'cart_payment');

            if($request->session()->get('order_id') != null){
                if($request->payment_option == 'paypal'){
                    $paypal = new PaypalController;
                    return $paypal->getCheckout();
                }
                elseif ($request->payment_option == 'stripe') {
                    $stripe = new StripePaymentController;
                    return $stripe->stripe();
                }
                elseif ($request->payment_option == 'sslcommerz') {
                    $sslcommerz = new PublicSslCommerzPaymentController;
                    return $sslcommerz->index($request);
                }
                elseif ($request->payment_option == 'instamojo') {
                    $instamojo = new InstamojoController;
                    return $instamojo->pay($request);
                }
                elseif ($request->payment_option == 'razorpay') {
                    $razorpay = new RazorpayController;
                    return $razorpay->payWithRazorpay($request);
                }
                elseif ($request->payment_option == 'paystack') {
                    $paystack = new PaystackController;
                    return $paystack->redirectToGateway($request);
                }
                elseif ($request->payment_option == 'voguepay') {
                    $voguePay = new VoguePayController;
                    return $voguePay->customer_showForm();
                }
                elseif ($request->payment_option == 'paytm') {
                    $paytm = new PaytmController;
                    return $paytm->index();
                }
                elseif ($request->payment_option == 'cash_on_delivery') {
                    $request->session()->put('cart', collect([]));
                    // $request->session()->forget('order_id');
                    $request->session()->forget('delivery_info');
                    $request->session()->forget('coupon_id');
                    $request->session()->forget('coupon_discount');

                    flash("Your order has been placed successfully")->success();
                	return redirect()->route('order_confirmed');
                }
                elseif ($request->payment_option == 'wallet') {
                    $user = Auth::user();
                    $user->balance -= Order::findOrFail($request->session()->get('order_id'))->grand_total;
                    $user->save();
                    return $this->checkout_done($request->session()->get('order_id'), null);
                }
                else{
                    $order = Order::findOrFail($request->session()->get('order_id'));
                    $order->manual_payment = 1;
                    $order->save();

                    $request->session()->put('cart', collect([]));
                    // $request->session()->forget('order_id');
                    $request->session()->forget('delivery_info');
                    $request->session()->forget('coupon_id');
                    $request->session()->forget('coupon_discount');

                    flash(__('Your order has been placed successfully. Please submit payment information from purchase history'))->success();
                	return redirect()->route('order_confirmed');
                }
            }
        }else {
            flash(__('Select Payment Option.'))->success();
            return back();
        }
    }

    //redirects to this method after a successfull checkout
    public function checkout_done($order_id, $payment)
    {
        $order = Order::findOrFail($order_id);
        $order->payment_status = 'paid';
        $order->payment_details = $payment;
        $order->save();

        if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
            $affiliateController = new AffiliateController;
            $affiliateController->processAffiliatePoints($order);
        }

        if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated) {
            $clubpointController = new ClubPointController;
            $clubpointController->processClubPoints($order);
        }

        if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
            $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = 'paid';
                $orderDetail->save();
                if($orderDetail->product->user->user_type == 'seller'){
                    $seller = $orderDetail->product->user->seller;
                    $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price*(100-$commission_percentage))/100;
                    $seller->save();
                }
            }
        }
        else{
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = 'paid';
                $orderDetail->save();
                if($orderDetail->product->user->user_type == 'seller'){
                    $commission_percentage = $orderDetail->product->category->commision_rate;
                    $seller = $orderDetail->product->user->seller;
                    $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price*(100-$commission_percentage))/100;
                    $seller->save();
                }
            }
        }

        $order->commission_calculated = 1;
        $order->save();

        Session::put('cart', collect([]));
        // Session::forget('order_id');
        Session::forget('payment_type');
        Session::forget('delivery_info');
        Session::forget('coupon_id');
        Session::forget('coupon_discount');

        flash(__('Payment completed'))->success();
        return redirect()->route('order_confirmed');
    }

    public function get_shipping_info(Request $request)
    {
        if(Session::has('cart') && count(Session::get('cart')) > 0){
            $categories = Category::all();
            session(['shipping_price' => 0, 'codprice'=>0, 'tax'=>0 ]);
            session(['is_cod_available' => false]);
            return view('frontend.shipping_info', compact('categories'))->with('show_cod', false);
        }
        flash(__('Your cart is empty'))->success();
        session(['shipping_price' => 0,'codprice'=>0, 'tax'=>0 ]);
        session(['is_cod_available' => false]);
        return back();
    }
/*
    public function guest_register(Request $request)
    {
        if($request->state)
        {
            $state=State::where('id',$request->state)->first();
            $statename=$state->name;

        }
        if($request->district)
        {
            $district=District::where('id',$request->district)->first();
            $districtname=$district->DistrictName;

        }
        
        
        if(User::where('email', $request->email)->orwhere('phone', '+'.$request->country_code.$request->phone)->first() == null){
            
           $password = str_random(15);
                $user = User::insertGetId([
                    'name'  =>  $request->name,
                    'email' =>  $request->email,
                    'password' => Hash::make($password),
                    'verification_code' => rand(100000, 999999),
                    'address' =>  $request->address,
                    'country' =>  $request->country,
                    'state' =>  $statename,
                    'district' =>  $districtname,
                    'postal_code' =>  $request->postal_code,
                    'phone' => '+'.$request->country_code.$request->phone,
                    //'checkout_type' =>  $request->checkout_type
                ]);
              //  print_r($user); die();
            //    $user->save();
               
                if($user){
                $customer = new Customer;
                $customer->user_id = $user;
                $customer->save();
               $user_data= User ::where('id',$user)->first();
             // print_r($user_data->phone); die();
                $address = Address::insert([
                    'user_id' => $user_data->id,
                    'address' => $user_data->address,
                    'country' => $user_data->country,
                    'state' => $user_data->state,
                    'district' => $user_data->district,
                    'postal_code' => $user_data->postal_code,
                    'phone' => $user_data->phone,
                    'email' => $user_data->email,
                    'set_default' => 0

                ]);
            }
        }
        else
        {
           
            $password = str_random(15);
                
            $update_user = User::where('email', $request->email)->orwhere('phone', '+'.$request->country_code.$request->phone)->update([
                'name'  =>  $request->name,
                'email' =>  $request->email,
                'password' => Hash::make($password),
                'verification_code' => rand(100000, 999999),
                'address' =>  $request->address,
                'country' =>  $request->country,
                'state' =>  $statename,
                'district' =>  $districtname,
                'postal_code' =>  $request->postal_code,
                'phone' => '+'.$request->country_code.$request->phone,
               // 'checkout_type' =>  $request->checkout_type
            ]);
        
            if($update_user == '1')
                {
                    $updated_user = User::where('email', $request->email)->orwhere('phone', '+'.$request->country_code.$request->phone)->first();
                    $update_address = Address::where('user_id', $updated_user->id)->update([
                        'address' => $updated_user->address,
                        'country' => $updated_user->country,
                        'state' => $updated_user->state,
                        'district' => $updated_user->district,
                        'postal_code'=> $updated_user->postal_code,
                        'phone' =>$updated_user->phone,
                        'email' => $updated_user->email,

                    ]);
                    $phone=$updated_user->phone;
                    $user_data = User::where('phone', $phone)->where('email',$updated_user->email)->first();
                }
           
        }
        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated){
            $otpController = new OTPVerificationController;
            $otpController->send_code($user_data);
            $data= array('email'=>$user_data->email, 'phone' => $user_data->phone, 'status'=>'sent');
            return json_encode($data);
        }
        
    }
    */
 public function guest_register(Request $request)
    {
        if($request->state)
        {
            $state=State::where('id',$request->state)->first();
            $statename=$state->name;

        }
        if($request->district)
        {
            $district=District::where('id',$request->district)->first();
            $districtname=$district->DistrictName;

        }
        $userid=0;
        
        if(User::where('email', $request->email)->orwhere('phone', '+'.$request->country_code.$request->phone)->first() == null){
        
            $password = str_random(15);
                $userid=$user = User::insertGetId([
                    'name'  =>  $request->name,
                    'email' =>  $request->email,
                    'password' => Hash::make($password),
                    'verification_code' => rand(100000, 999999),
                    'address' =>  $request->address,
                    'country' =>  $request->country,
                    'city' =>  $request->city,
                    'state' =>  $statename,
                    'district' =>  $districtname,
                    'postal_code' =>  $request->postal_code,
                    'phone' => '+'.$request->country_code.$request->phone,
                    //'checkout_type' =>  $request->checkout_type
                ]);
               
            

        }
        else
        {
            
           // pringchfhgft_r($statename);
            $password = str_random(15);
                
            $userid = $update_user = User::where('email', $request->email)->orwhere('phone', '+'.$request->country_code.$request->phone)->update([
                'name'  =>  $request->name,
                'email' =>  $request->email,
                'password' => Hash::make($password),
                'verification_code' => rand(100000, 999999),
                'address' =>  $request->address,
                'country' =>  $request->country,
                'city' =>  $request->city,
                'state' =>  $statename,
                'district' =>  $districtname,
                'postal_code' =>  $request->postal_code,
                'phone' => '+'.$request->country_code.$request->phone,
               // 'checkout_type' =>  $request->checkout_type
            ]);
            
            if($update_user)
                {
                    $phone='+'.$request->country_code.$request->phone;
                    $user = User::where('phone', $phone)->where('email',$request->email)->first();
                    $userid = $user->id;
                    
                }
                
                
           
        }
        $customer = Customer::where('user_id', $userid)->first();
        if($customer){ ; }else{ $customer = new Customer; }
        
                $customer->user_id = $userid;
                $customer->save();
                
                $addressModel = Address::where('phone', '+'.$request->country_code.$request->phone)->where('email',$request->email)->where('postal_code',$request->postal_code)->first();
                
                if($addressModel){
                ;
			}else{$addressModel = new Address;}
                $addressModel->user_id = $userid;
                $addressModel->email = $request->email;
                $addressModel->address = $request->address;
                $addressModel->country = $request->country;
                $addressModel->city = $request->city;
                $addressModel->state = $statename;
                $addressModel->district = $districtname;
                $addressModel->postal_code = $request->postal_code;
                $addressModel->phone = '+'.$request->country_code.$request->phone;
                
                 
                $addressModel->save();
                
               
                
        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated){
           $user = User::where('id',$userid)->first();
            $otpController = new OTPVerificationController;
            $otpController->send_code($user);
            $data= array('email'=>$user->email, 'phone' => $user->phone, 'status'=>'sent');
            return json_encode($data);
        }
        
    }


    public function updateshippingcost(Request $request)
    {
        if($request->cod)
        {
            session(['shipping_price'=> $request->total_shipping, 'total'=> $request->total, 'is_cod_available' => false]);
        
        }
        else
        {
            session(['shipping_price'=> $request->total_shipping, 'total'=> $request->total]); 
        }
        $data=array('status'=>"true", 'shipping_price'=> single_price($request->total_shipping), 'total'=> single_price($request->total));
        return json_encode($data);
    }
    public function checkManual(Request $request){

        // return json_encode($request->all());

        $manual_pay = \App\ManualPaymentMethod::where('heading',$request->payment_type)->first();
        // return json_encode($manual_pay);
        if($manual_pay != null)
        {
            
            $bank_info = $manual_pay->bank_info;
            session(['bank_info' => $bank_info, 'type' => $manual_pay->type]);
            $data = array('status'=>"true", 'bank_info'=>$bank_info, 'type' => $manual_pay->type,'id' => $manual_pay->id, 'heading' =>$manual_pay->heading);
           return json_encode($data);
        }
        else
        {
            $data = array('status'=>"false", 'bank_info'=>'', 'type' => '', 'heading'=>'');
            return json_encode($data);
        }
    }
    
    public function checkforpayment(Request $request){
       
		if($request->payment_type == 'cash_on_delivery'){
			session(['addcodprice'=>Session::get('codprice')]);
			}else{
			session(['addcodprice'=>0]);	
			}
		return view('frontend.partials.cart_summary',compact('data'));
		}
		
   public function updateShipping(Request $request){
	   $shipping_type = $request->shipping_type;
	   session(['shipping_type' => $shipping_type]);
	   if($shipping_type == 'pickup_point'){
		   session(['final_shipping_price'=>0]);
		   }else{
		   session(['final_shipping_price'=>Session::get('shipping_price')]);
		}
	   return view('frontend.partials.cart_summary',compact('data'));
	   }
	   
    public function check_postal_code(Request $request)
    {
            $postalcode = $request->postcode;
            //print_r($postalcode); die();
            if(\App\BusinessSetting::where('type', 'cash_payment')->first()->value == 1)
            {
                $shipping = Shipping::where('startpin', '<=', (int) $postalcode)->where('endpin', '>=', (int) $postalcode)->first();
                // return json_encode($shipping);
                if($shipping)
                {
                    
                    if($shipping->iscod == "yes")
                    {
                         session(['shipping_price' => $shipping->price, 'codprice'=>$shipping->codprice,'addcodprice'=>0,'shipping_type'=>'','final_shipping_price'=>$shipping->price ]);
                        session(['is_cod_available' => true]);
                        $data = array(
                            'status'    =>  "true",
                            'cod'   =>  $shipping->iscod,
                            'pinshipprice' => $shipping->price,
                            'isproductshipping' =>  $shipping->isproductshipping,
                            'codprice' => $shipping->codprice,
                            'addcodprice'=>0
                        );
                        return view('frontend.partials.cart_summary',compact('data'));
                    }
                    else
                    {
                        session(['final_shipping_price'=>$shipping->price,'shipping_price' => $shipping->price,'codprice'=>'0','addcodprice'=>0,'shipping_type'=>'']);
                        session(['is_cod_available' => false]);
                        $data=array('status'=>"false",'cod'=>$shipping->iscod, 'pinshipprice' => $shipping->price, 'isproductshipping'=>$shipping->isproductshipping, 'codprice' => $shipping->codprice);
                        return view('frontend.partials.cart_summary',compact('data'));
                    }
                }
                else
                {
                    
                    session(['shipping_price' => '0','addcod'=>0,'shipping_type'=>'','final_shipping_price'=>0]);
                    session(['is_cod_available' => false]);
                    $data=array();
                    return view('frontend.partials.cart_summary',compact('data'));

                }
                
            }
            session(['shipping_price' => '0','addcod'=>0]);
             session(['is_cod_available' => false]);
             $data=array();
            return view('frontend.partials.cart_summary',compact('data'));
           
           
    }

    public function store_shipping_info(Request $request)
    {
        if($request->state)
            {
                $state=State::where('id',$request->state)->first();
                $statename=$state->name;

            }
            if($request->district)
            {
                $district=District::where('id',$request->district)->first();
                $districtname=$district->DistrictName;

            }
            
            
      
        if (Auth::check()) {
            $address = Address::findOrFail($request->address_id);
            $data['name'] = Auth::user()->name;
            $data['email'] = $address->email;
            $data['address'] = $address->address;
            $data['country'] = $address->country;
            $data['city'] = $address->city;
            $data['state'] = $address->state;
            $data['district'] = $address->district;
            $data['postal_code'] = $address->postal_code;
            $data['phone'] = $address->phone;
            $data['checkout_type'] = $request->checkout_type;
        }
        else {
            $data['name'] = $request->name;
            $data['user_id'] = $request->user_id;
            $data['email'] = $request->email;
            $data['country_code'] = $request->country_code;
            $data['address'] = $request->address;
            $data['country'] = $request->country;
            $data['city'] = $request->city;
            $data['state'] = $statename;
            $data['district'] = $districtname;
            $data['postal_code'] = $request->postal_code;
            $data['phone'] = $request->phone;
            $data['checkout_type'] = $request->checkout_type;
            
        }

        $shipping_info = $data;
        $request->session()->put('shipping_info', $shipping_info);

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach (Session::get('cart') as $key => $cartItem){
            $subtotal += $cartItem['price']*$cartItem['quantity'];
            $tax += $cartItem['tax']*$cartItem['quantity'];
            if (BusinessSetting::where('type', 'shipping_type')->first()->value == 'order_wise_shipping') {
                $shipping = Session::get('shipping_price');
              //  print_r($shipping); die();
            }
            else
            {
                  $shipping += $cartItem['shipping']*$cartItem['quantity'];
            }
           
        }

        $total = $subtotal + $tax + $shipping;
      
        if(Session::has('coupon_discount')){
                $total -= Session::get('coupon_discount');
        }
       
        $request->session()->put('total', $total);
        if(\Auth::check()){
			updateCartSetup();
		}else{
        $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
        if($user != null){
            updateCartSetup();
            auth()->login($user, false);
            
        }
	}
	$currency = Currency::where('id',BusinessSetting::where('type', 'home_default_currency')->first()->value)->first();
	
        return view('frontend.delivery_info')->with('show_cod,currency', false);
        // return view('frontend.payment_select', compact('total'));
    }


    public function getdistrict(Request $request)
    {
        $district=District::where('state_id', $request->state_id)->get();
        if($district != null)
        {
            $data = [];

            foreach($district as $dist)
            {
                $dist_id=$dist->id;
                $dist_name=$dist->DistrictName;

                $item = [
                    "id"    =>  $dist_id,
                    "name"  =>  $dist_name
                ];

                array_push($data, $item);    
            }

            $response = array('status'=> "true", 'data' => $data);

            return json_encode($response);
        }
        
    }
    public function store_delivery_info(Request $request)
    {
        if(Session::has('cart') && count(Session::get('cart')) > 0){
            $cart = $request->session()->get('cart', collect([]));
            $cart = $cart->map(function ($object, $key) use ($request) {
                if(\App\Product::find($object['id'])->added_by == 'admin'){
                    if($request['shipping_type_admin'] == 'home_delivery'){
                        $object['shipping_type'] = 'home_delivery';
                        $object['shipping'] = \App\Product::find($object['id'])->shipping_cost;
                    }
                    else{
                        $object['shipping_type'] = 'pickup_point';
                        $object['pickup_point'] = $request->pickup_point_id_admin;
                        $object['shipping'] = 0;
                    }
                }
                else{
                    if($request['shipping_type_'.\App\Product::find($object['id'])->user_id] == 'home_delivery'){
                        $object['shipping_type'] = 'home_delivery';
                        $object['shipping'] = \App\Product::find($object['id'])->shipping_cost;
                    }
                    else{
                        $object['shipping_type'] = 'pickup_point';
                        $object['pickup_point'] = $request['pickup_point_id_'.\App\Product::find($object['id'])->user_id];
                        $object['shipping'] = 0;
                    }
                }
                return $object;
            });

            $request->session()->put('cart', $cart);
            $shipping_price=$request->total_shipping;
            $total=$request->total_price;
            $ship_type=$request->ship_type;
            $codprice=Session::get('codprice');
            $is_cod_available=Session::get('is_cod_available');
            if($ship_type == "home_delivery")
            {
                session(['total_shipping_price'=>$shipping_price, 'total'=>$total, 'codprice'=>$codprice, 'is_cod_available'=>$is_cod_available]);
            }
            else
            {
                session(['total_shipping_price'=>$shipping_price, 'total'=>$total, 'codprice'=>0, 'is_cod_available'=>false]);
            }
            

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            foreach (Session::get('cart') as $key => $cartItem){
                $subtotal += $cartItem['price']*$cartItem['quantity'];
                $tax += $cartItem['tax']*$cartItem['quantity'];
                if (BusinessSetting::where('type', 'shipping_type')->first()->value == 'order_wise_shipping') {
                    $shipping = Session::get('shipping_price');
                  //  print_r($shipping); die();
                }
                else
                {
                      $shipping += $cartItem['shipping']*$cartItem['quantity'];
                }
            }

            $total = $subtotal + $tax + $shipping;

            if(Session::has('coupon_discount')){
                    $total -= Session::get('coupon_discount');
            }

            //dd($total);
            
            $currency = Currency::where('id',BusinessSetting::where('type', 'home_default_currency')->first()->value)->first();
			
            return view('frontend.payment_select', compact('total','currency'))->with('show_cod', true);
        }
        else {
            flash('Your Cart was empty')->warning();
            return redirect()->route('home');
        }
    }

    public function get_payment_info(Request $request)
    {
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach (Session::get('cart') as $key => $cartItem){
            $subtotal += $cartItem['price']*$cartItem['quantity'];
            $tax += $cartItem['tax']*$cartItem['quantity'];
            $shipping += $cartItem['shipping']*$cartItem['quantity'];
        }

        $total = $subtotal + $tax + $shipping;

        if(Session::has('coupon_discount')){
                $total -= Session::get('coupon_discount');
        }

        return view('frontend.payment_select', compact('total'));
    }

    public function apply_coupon_code(Request $request){
        //dd($request->all());
        $coupon = Coupon::where('code', $request->code)->first();

        if($coupon != null){
            if(strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date){
                if(CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null){
                    $coupon_details = json_decode($coupon->details);

                    if ($coupon->type == 'cart_base')
                    {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach (Session::get('cart') as $key => $cartItem)
                        {
                            $subtotal += $cartItem['price']*$cartItem['quantity'];
                            $tax += $cartItem['tax']*$cartItem['quantity'];
                            $shipping += $cartItem['shipping']*$cartItem['quantity'];
                        }
                        $sum = $subtotal+$tax+$shipping;

                        if ($sum > $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount =  ($sum * $coupon->discount)/100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            }
                            elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                            $request->session()->put('coupon_id', $coupon->id);
                            $request->session()->put('coupon_discount', $coupon_discount);
                            flash('Coupon has been applied')->success();
                        }
                    }
                    elseif ($coupon->type == 'product_base')
                    {
                        $coupon_discount = 0;
                        foreach (Session::get('cart') as $key => $cartItem){
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if($coupon_detail->product_id == $cartItem['id']){
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += $cartItem['price']*$coupon->discount/100;
                                    }
                                    elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount;
                                    }
                                }
                            }
                        }
                        $request->session()->put('coupon_id', $coupon->id);
                        $request->session()->put('coupon_discount', $coupon_discount);
                        flash('Coupon has been applied')->success();
                    }
                }
                else{
                    flash('You already used this coupon!')->warning();
                }
            }
            else{
                flash('Coupon expired!')->warning();
            }
        }
        else {
            flash('Invalid coupon!')->warning();
        }
        return back();
    }

    public function remove_coupon_code(Request $request){
        $request->session()->forget('coupon_id');
        $request->session()->forget('coupon_discount');
        return back();
    }

    public function order_confirmed(){
        $order = Order::findOrFail(Session::get('order_id'));
        return view('frontend.order_confirmed', compact('order'));
    }
}
