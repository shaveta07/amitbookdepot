<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\SubSubCategory;
use App\Category;
use App\BusinessSetting;
use App\OtpConfiguration;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\User;
use App\Customer;
use Auth\Register;
use Session;
use App\Color;
use Cookie;
use Auth;

class CartController extends Controller
{
	public function __construct()
    {
		
	}
    public function index(Request $request)
    {
		Session::put('shipping_info', collect([]));
        Session::forget('order_id');
        Session::forget('payment_type');
        Session::forget('delivery_info');
        Session::forget('coupon_id');
        Session::forget('coupon_discount');
        Session::forget('shipping_price');
        Session::forget('codprice');
        Session::forget('tax');
        Session::forget('total');
        
        if (Auth::guest())
        {
            session(['redirect_to_checkout' => true]);
        }
        else
        {
            $value = session('redirect_to_checkout');
            if($value == true)
            {
                session(['redirect_to_checkout' => false]);
                return redirect()->route('checkout.shipping_info')->with('show_cod', false);
            }
        }
        //dd($cart->all());
        $categories = Category::all();
        return view('frontend.view_cart', compact('categories'));
    }

    public function cart_register(Request $request)
    {
        
        // if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        //     if(User::where('email', $request->email)->first() != null){
        //         flash('Email already exists.');
        //        // return back();
        //     }
        // }
        if (User::where('phone', '+'.$request->country_code.$request->phone)->first() != null) {
            flash('Phone already exists.');
          //  return back();
        }
        
        //$this->validator($request->all())->validate();
        $user = User::create([
            'name'  =>  $request->name,
            //'email' =>  $request->email,
            'password' => Hash::make($request->password),
            'verification_code' => rand(100000, 999999),
            'phone' => '+'.$request->country_code.$request->phone
        ]);
       
        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();

        // if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
        //     $user->email_verified_at = date('Y-m-d H:m:s');
        //     $user->save();
           
        // }
        // else {
        //     $user->sendEmailVerificationNotification();
        // }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated){
            $otpController = new OTPVerificationController;
            $otpController->send_code($user);
        }

        //$user = User :: create($request->all());
        $data=array('id'=>$user->id, 'email'=>$user->email, 'phone'=>$user->phone);
        return json_encode($data);
  }


    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.addToCart', compact('product'));
    }

    public function updateNavCart(Request $request)
    {
        return view('frontend.partials.cart');
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);

        $data = array();
        $data['id'] = $product->id;
        $str = '';
        $tax = 0;

        //check the color enabled or disabled for the product
        if($request->has('color')){
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }

        if ($product->digital != 1) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if($str != null){
                    $str .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }

        $data['variant'] = $str;

        if(Auth::guest())
        {
            $bulk_type = "customer";
        }
        else
        {
			$userData = Auth::user();
            $bulk_type = $userData->user_type;
        }

        $overide_price = null;
        if($str != null && $product->variant_product){
            $product_stock = $product->stocks->where('variant', $str)->first();
            if($product_stock)
            {
                $bulk_info = \App\ProductBulks::where('product_stock_id', $product_stock->id)->orderBy('qtyrange', 'asc')->where('customertype', $bulk_type)->get();
                if($bulk_info)
                {
                    //echo json_encode($bulk_info);
                    foreach ($bulk_info as $bulk)
                    {               
                        if($bulk->qtyrange <= $request->quantity)   
                        {
                            
                            $overide_price = $bulk->overideprice;
                            // break;
                        }
                    }
                }
            }
            if($overide_price != null)
            {
                $price = $overide_price;    
            }
            else
            {
                $price = $product_stock->price;
            }
            // $price = "1111";
            $quantity = $product_stock->qty;

            if($quantity >= $request['quantity']){
                // $variations->$str->qty -= $request['quantity'];
                // $product->variations = json_encode($variations);
                // $product->save();
            }
            else{
                return view('frontend.partials.outOfStockCart');
            }
        }
        else{
            $price = $product->unit_price;
        }

        //discount calculation based on flash deal and regular discount
        //calculation of taxes
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1  && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $price -= ($price*$flash_deal_product->discount)/100;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }
        if (!$inFlashDeal) {
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }

        if($product->tax_type == 'percent'){
            $tax = ($price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $tax = $product->tax;
        }

        $data['quantity'] = $request['quantity'];
        $data['price'] = $price;
        // $data['price'] = "444";
        if($request['bundleprice'] > 0){
		    $data['price'] =	$request['bundleprice']/$request['quantity'];
		}
        $data['tax'] = $tax;
        $data['shipping'] = $product->shipping_cost;
        $data['product_referral_code'] = null;
        $data['digital'] = $product->digital;

        if ($request['quantity'] == null){
            $data['quantity'] = 1;
        }

        if(Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
            $data['product_referral_code'] = Cookie::get('product_referral_code');
        }

        if($request->session()->has('cart') && $request['isbundle'] == 'no'){
            $foundInCart = false;
            $cart = collect();

            foreach ($request->session()->get('cart') as $key => $cartItem){
                if($cartItem['id'] == $request->id){
                    if($cartItem['variant'] == $str){
                        $foundInCart = true;
                        $cartItem['quantity'] += $request['quantity'];

                        $overide_price = null;

                        $product_stock = $product->stocks->where('variant', $str)->first();
                        if($product_stock)
                        {
                            $bulk_info = \App\ProductBulks::where('product_stock_id', $product_stock->id)->orderBy('qtyrange', 'asc')->where('customertype', $bulk_type)->get();
                            if($bulk_info)
                            {
                               // echo json_encode($bulk_info);
                                foreach ($bulk_info as $bulk)
                                {               
                                    if($bulk->qtyrange <= $cartItem['quantity'])   
                                    {
                                        
                                        $overide_price = $bulk->overideprice;
                                        // break;
                                    }
                                }
                            }
                        }
                        if($overide_price != null)
                        {
                            $cartItem['price'] = $overide_price;
                        }


                    }
                }
                $cart->push($cartItem);
            }

            if (!$foundInCart) {
                $cart->push($data);
            }
            $request->session()->put('cart', $cart);
        }
        elseif ($request->session()->has('cart') && $request['isbundle'] == 'yes') {
            $foundInCart = false;
            $cart = collect();

            foreach ($request->session()->get('cart') as $key => $cartItem){
                if($cartItem['id'] == $request->id){
                    if($cartItem['variant'] == $str){
                        $foundInCart = true;
                        $cartItem['quantity'] += $request['quantity'];

                        $overide_price = null;

                        $product_stock = $product->stocks->where('variant', $str)->first();
                        if($product_stock)
                        {
                            $bulk_info = \App\ProductBulks::where('product_stock_id', $product_stock->id)->orderBy('qtyrange', 'asc')->where('customertype', $bulk_type)->get();
                            if($bulk_info)
                            {
                                //echo json_encode($bulk_info);
                                foreach ($bulk_info as $bulk)
                                {               
                                    if($bulk->qtyrange <= $cartItem['quantity'])   
                                    {
                                        
                                        $overide_price = $bulk->overideprice;
                                        // break;
                                    }
                                }
                            }
                        }
                        if($overide_price != null)
                        {
                            $cartItem['price'] = $overide_price;
                        }
                    }
                }
                $cart->push($cartItem);
            }

            if (!$foundInCart) {
                $cart->push($data);
            }
            $request->session()->put('cart', $cart);
         }
        else{
            $cart = collect([$data]);
            $request->session()->put('cart', $cart);
        }

        return view('frontend.partials.addedToCart', compact('product', 'data'));
    }


    //removes from Cart
    public function removeFromCart(Request $request)
    {
        if($request->session()->has('cart')){
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
        }

        return view('frontend.partials.cart_details');
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cart = $request->session()->get('cart', collect([]));
        $userData = array();
        $cart = $cart->map(function ($object, $key) use ($request) {
            if($key == $request->key){

                $qty_range = null;
                $overide_price = null;

                if(Auth::guest())
                {
                    $bulk_type = "customer";
                }
                else
                {
					$userData = Auth::user();
                    $bulk_type = $userData->user_type;
                }

                $stock_info = \App\ProductStock::where('product_id', $object['id'])->where('variant', $object['variant'])->first();
                if($stock_info)
                {
                    $bulk_info = \App\ProductBulks::where('product_stock_id', $stock_info->id)->orderBy('qtyrange', 'asc')->where('customertype', $bulk_type)->get();
                    if($bulk_info)
                    {
                       // echo json_encode($bulk_info);
                        foreach ($bulk_info as $bulk)
                        {               
                            if($bulk->qtyrange <= $request->quantity)   
                            {
                                
                                $overide_price = $bulk->overideprice;
                                // break;
                            }
                        }
                    }
                }

                if($overide_price != null)
                {
                    $object['price'] = $overide_price;    
                }
                else
                {
                    $product_info = \App\Product::find($object['id']);
                    if($product_info)
                    {
                       $object['price'] = $product_info->unit_price;
                    }
                }

                // $object['price'] = 4;
                

                $object['quantity'] = $request->quantity;
            }
            return $object;
        });
        $request->session()->put('cart', $cart);
        //$data=$request->session()->all();
        //print_r($data); die;
        return view('frontend.partials.cart_details');
    }

}
