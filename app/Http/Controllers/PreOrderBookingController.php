<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OTPVerificationController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\AffiliateController;
use App\Prebooking;
use App\Customer;
use App\PrebookingLine;
use App\PrebookingPayment;
use App\Product;
use App\Author;
use App\Color;
use App\Order;
use App\OrderDetail;
use App\CouponUsage;
use App\OtpConfiguration;
use App\User;
use App\BusinessSetting;

use Auth;
use Session;
use DB;
use PDF;
use Mail;
use App\Mail\InvoiceEmailManager;
use CoreComponentRepository;

class PreOrderBookingController extends Controller
{
    public function index(Request $request)
    {
        return view('prebooking.preorderbooking');
    }
      /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function Create(Request $request)
    {
        
       // return view('prebooking.preorderbooking');
    }


     /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */

   
    public function FindOrderBooking(Request $request)
    {
        $sort_search = null;
//$data = Prebooking::orderBy('prebookings.invoiceid', 'desc')->get();
        $data = DB::table('prebookings')->select('prebookings.invoicenumber','users.phone','prebookings.store_id','prebookings.customerid','prebookings.invoicedate','prebookings.status','prebookings.invoicelookuptype','prebookings.amount', 'prebookings.invoiceid')
            ->join('customers as customer', 'customer.id', '=', 'prebookings.customerid')
            ->join('users as users', 'users.id', '=', 'customer.user_id')
            ->orderBy('prebookings.invoiceid', 'desc')->get();
        
        return view('prebooking.findorderbooking',compact('data', 'sort_search'));
    }
    public function FindOrderBookingdata(Request $request)
    {
        $sort_search = null;
        $startdate = $request->startdate;
        $enddate = $request->enddate;
       
        if(isset($request->search)){
            

            if($request->isbn != ''){
             
                if($request->startdate != ''){
                    $data = DB::table('prebookings')
                        ->select('prebookings.invoicenumber', 'users.phone','prebookings.store_id','prebookings.customerid','prebookings.invoicedate','prebookings.status','prebookings.invoicelookuptype','prebookings.amount', 'prebookings.invoiceid')
                        ->join('prebooking_lines', 'prebookings.invoiceid', '=', 'prebooking_lines.invoiceid')
                        ->join('products', 'products.id', '=', 'prebooking_lines.itemid')
                        ->join('customers as customer', 'customer.id', '=', 'prebookings.customerid')
                        ->join('users as users', 'users.id', '=', 'customer.user_id')
                        ->where('products.isbn',  'like', '%' . $request->isbn . '%')
                        ->whereBetween('prebookings.invoicedate', [$startdate,$enddate])
                        ->where('users.phone',  'like', '%' . $request->phone . '%')
                        ->where('prebookings.invoicenumber',  'like', '%' . $request->invoicenum . '%')
                       
                        ->groupBy('prebookings.invoiceid')
                        ->orderBy('prebookings.invoiceid', 'desc')
                        ->get();
            
            
                }
                else{
                   
                    $data = DB::table('prebookings')
                            ->select('prebookings.invoicenumber','users.phone','prebookings.store_id','prebookings.customerid','prebookings.invoicedate','prebookings.status','prebookings.invoicelookuptype','prebookings.amount', 'prebookings.invoiceid')
                            ->join('prebooking_lines', 'prebookings.invoiceid', '=', 'prebooking_lines.invoiceid')
                            ->join('customers as customer', 'customer.id', '=', 'prebookings.customerid')
                            
                            ->join('users as users', 'users.id', '=', 'customer.user_id')
                             ->groupBy('prebookings.invoiceid')
                            ->orderBy('prebookings.invoiceid', 'desc')
                            ->get();
                }
            }
            else{
                
                if($request->startdate != ''){
           
                    $data = DB::table('prebookings')
                    ->select('prebookings.invoicenumber','users.phone','prebookings.store_id','prebookings.customerid','prebookings.invoicedate','prebookings.status','prebookings.invoicelookuptype','prebookings.amount', 'prebookings.invoiceid')
                    ->join('prebooking_lines', 'prebookings.invoiceid', '=', 'prebooking_lines.invoiceid')
                    ->join('products', 'products.id', '=', 'prebooking_lines.itemid')
                    ->join('customers as customer', 'customer.id', '=', 'prebookings.customerid')
                    ->join('users as users', 'users.id', '=', 'customer.user_id')
                    // ->where('products.isbn',  'like', '%' . $request->isbn . '%')
                    // ->whereBetween('prebookings.invoicedate', [$startdate,$enddate])
                    ->when($request->startdate, function($query) use ($request){
                        $query->orwhereBetween('prebookings.invoicedate', [$request->startdate,$request->enddate]);
                        
                    })
                    ->when($request->isbn, function($query) use ($request){
                        $query->where('products.isbn', 'like', '%' . $request->isbn . '%');
                        
                    })
                    ->when($request->phone, function($query) use ($request){
                        $query->where('users.phone', 'like', '%' . $request->phone . '%');
                        
                    })
                    ->when($request->invoicenum, function($query) use ($request){
                        $query->where('prebookings.invoicenumber', 'like', '%' . $request->invoicenum . '%');
                        
                    })
                    
                    ->groupBy('prebookings.invoiceid')
                    ->orderBy('prebookings.invoiceid', 'desc')
                    ->get();
                
       
                }
                else{
           
                    $data = DB::table('prebookings')
                        ->select('prebookings.invoicenumber','users.phone','prebookings.store_id','prebookings.customerid','prebookings.invoicedate','prebookings.status','prebookings.invoicelookuptype','prebookings.amount', 'prebookings.invoiceid')
                        ->join('customers as customer', 'customer.id', '=', 'prebookings.customerid')
                        ->join('users as users', 'users.id', '=', 'customer.user_id')
                        ->orderBy('prebookings.invoiceid', 'desc')
                        ->get();
        
                }
        
            }
        }
        else
        {
            $data = DB::table('prebookings')->select('prebookings.invoicenumber','users.phone','prebookings.store_id','prebookings.customerid','prebookings.invoicedate','prebookings.status','prebookings.invoicelookuptype','prebookings.amount', 'prebookings.invoiceid')
            ->join('customers as customer', 'customer.id', '=', 'prebookings.customerid')
            ->join('users as users', 'users.id', '=', 'customer.user_id')
            ->orderBy('prebookings.invoiceid', 'desc')->get();
        }
   
      
            
        return view('prebooking.findorderbooking',compact('data', 'sort_search'));
    }

    public function SearchInpFind(Request $request)
    {
        $sort_search = null;
         if ($request->has('searchinput')){
            $sort_search = $request->search;
            $data = $data->where('creditamt', 'like', '%'.$sort_search.'%')
                                    ->where('invoicenumber', 'like', '%'.$sort_search.'%')
                                    ->where('invoicedate', 'like', '%'.$sort_search.'%');
                                   
        }
    }

     /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function CreditBooking(Request $request)
    {
      
        $sort_search = null;
        
        $prebooking = Prebooking::where('invoicelookuptype','C')->orderBy('invoiceid','desc')->get();
        
        return view('prebooking.creditbooking', compact('prebooking', 'sort_search'));
    }

    public function CreditBookingdata(Request $request)
    {
        $sort_search = null;
       $startdate = $request->startdate;
       $enddate = $request->enddate;
       
       if(isset($request->search)){
         
       $prebooking = DB::table('prebookings')
       ->join('customers', 'customers.id', '=', 'prebookings.customerid')
        ->join('users', 'users.id', '=', 'customers.user_id')
        // ->where('invoicelookuptype','C')
        ->Where(function($query) use ($request) {
            $query->Where('prebookings.invoicelookuptype', '=','C');
           
        })
        ->when($request->startdate, function($query) use ($request){
            $query->whereBetween('prebookings.invoicedate', [$request->startdate,$request->enddate]);
           
        })
        ->when($request->phone, function($query) use ($request){
            $query->where('users.phone', 'like', '%' . "+91".$request->phone. '%');
            
        })
        ->when($request->invoicenum, function($query) use ($request){
            $query->orWhere('prebookings.invoicenumber', 'like', '%' . $request->invoicenum . '%');
           
        })
       
       
    //    ->orwhereBetween('invoicedate', [$startdate,$enddate])
    //    ->orwhere('users.phone',  'like', '%' . $request->phone . '%')
    //    ->orwhere('prebookings.invoicenumber',  'like', '%' . $request->invoicenum . '%')
       ->orderBy('prebookings.invoiceid','desc');
       }
       else
       {
        $prebooking = Prebooking::where('invoicelookuptype','C')->orderBy('invoiceid','desc');
       }
     
      
    // if ($request->has('search')){
    //     $sort_search = $request->search;
    //     $prebooking = $prebooking->where('creditamt', 'like', '%'.$sort_search.'%')
    //                                 ->where('invoicenumber', 'like', '%'.$sort_search.'%')
    //                                 ->where('invoicedate', 'like', '%'.$sort_search.'%');
                                   
    // }
     $prebooking = $prebooking->get();
    // return json_encode($prebooking);
     return view('prebooking.creditbooking', compact('prebooking', 'sort_search'));
    }

     /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function PendingDelivery(Request $request)
    {
     
        $sort_search =null;
        $pending =null;
        $pendingdata = DB::table('prebooking_lines as l')
        ->select('l.quantity','l.delivered_qty','l.invoiceid','l.itemid','l.variation','pre.*')
        ->Join('prebookings as pre', 'pre.invoiceid', '=', 'l.invoiceid')
        ->Join('products', 'id', '=', 'l.itemid')
        ->where('l.isdeleted','N')
        ->Where('l.quantity', '>', 'IFNULL(l.delivered_qty,0)')
        ->orderBy('l.lineid', 'desc');
        
       
        if ($request->has('search')){
            $pending = $pendingdata->get();
            $sort_search = $request->search;
            $pendingdlvy = $pendingdata->where('l.variation', 'like', '%'.$sort_search.'%')
                                        ->orwhere('products.isbn', 'like', '%'.$sort_search.'%')
                                        ->orwhere('products.name', 'like', '%'.$sort_search.'%')
                                        ->orwhere('pre.invoicenumber', 'like', '%'.$sort_search.'%')
                                        ->orwhere('pre.invoicedate', 'like', '%'.$sort_search.'%')
                                        ->orwhere('l.quantity', 'like', '%'.$sort_search.'%')
                                        ->orwhere('l.delivered_qty', 'like', '%'.$sort_search.'%');   
        }
         $pendingdlvy = $pendingdata->get();
        return view('prebooking.pendingdelivery', compact('pendingdlvy', 'pending', 'sort_search'));
        
    }


    public function CustomerForPrebooking(Request $request)
    {
        $phone = $request->term;

         $customers =DB::table('users')
        ->select('customers.id as customerid', 'users.*')
        ->leftJoin('customers', 'users.id', '=', 'customers.user_id')
        ->where('user_type','customer')
        ->Where('phone', 'like', '%' . $phone . '%')
        ->orWhere('name', 'like', '%' . $phone . '%')
        ->orderBy('customers.id', 'desc')
        ->limit(20)
        ->get();
        
       // User::where('user_type','customer')->Where('phone', 'like', '%' . $phone . '%')->Where('name', 'like', '%' . $phone . '%')->orderBy('id', 'desc')->limit(20)->get();

       // return json_encode($customers);
        $item = [];
        foreach($customers as $customer){
            $data = array('phone' => $customer->phone,
            'email' => $customer->email, 
            'name' => $customer->name,
            'father_name' => $customer->father_name,
            'tehsil' => $customer->tehsil,
            'customer_id' => $customer->customerid,
            'user_id' => $customer->id,
            'categoryId' => $customer->category_id,
            'instituteId' => $customer->institute_id,
            'address' => $customer->address,
          //  'address2' => $customer->address2,
          'district' => $customer->district,
          'landmark' => $customer->landmark,
            'postalcode' => $customer->postal_code,
            'state' => $customer->state,
            'gstin' => $customer->gstin,
            //'cutomer_id' => $customer->id,
            'user_type' => $customer->user_type
       );
         array_push($item,$data);
        }
       
    return json_encode($item);
       
    }

    public function arinvoiceprebookView($invoice_number)
    {
        $order = Order::where('invoice_number',$invoice_number)->first();
     
        return view('prebooking.ARinvoice-lines-workbench_prebooking',compact('order'))->with('msg', null);
    }

    public function PreBookingStore(Request $request)
    {
        $msg="";
        $sts = "";
      
        $store_id = isset($request->store_id)?$request->store_id:'1';
        $custId = $request->customer_id;
       
       $user = Customer::where('id',$custId)->first();
    
        $inst_id = $request->institute;
      
        $cat_id = $request->category;
        $state = $request->state;
        // $invoice_type=$request->type;
        // $description=$request->description;
        // $arinvoicenum = $request->invoicenum;

        $gstin = $request->gstin;
        if($state == '')
        {
            $state = Null;
        }
        if($gstin == '')
        {
            $gstin = Null;
        }
        if($request->city == '')
        {
            $request->city = Null;
        }

        // if (isset($request->mobile1) && strpos($request->mobile1, 'x') !== false) {
        //     $msg ="Please re-enter Mobile number";
        //     $sts = "mobile";

        //     return redirect('/admin/PreOrderBooking')->with(['msg'=> $msg, 'sts' =>$sts ]);
           
        // }


        // return $cat_id;
            $userid = $request->user_id;
        if($custId != '')
        {
            
           $user = User::where('id',$user->user_id)->where('email_verified_at', '!=' , Null)->update([
               'name' => $request->c_name,
              'address' => $request->address,
               'country' => 'IN',
               'postal_code' => $request->postal_code,
               'institute_id' => $inst_id,
               'category_id' => $cat_id,
               'state' => $state,
               'gstin' => $gstin,
               'father_name' => $request->father_name,
               'tehsil' => $request->tehsil,
               'district' => $request->district,
               'landmark' => $request->landmark

           ]);

           if($msg=="")
            {
                $order = new Prebooking;
                if($request->invoicenum == '')
                    {
                        $request->invoicenum = "";
                    }
                $order->arinvoicenum = $request->invoicenum;
                    $order->invoicelookuptype = $request->invoice_type;
                    $order->customerid = $custId;
                    $order->storelocationid = $store_id;
                    $order->invoicedate = strtotime('now');
                   
                    if($request->description == '')
                    {
                        $request->description = 'Null';
                    }
                    $order->description = $request->description;
                    $order->status = 'O';
                    $order->lastupdateby = Auth::user();
                    $order->lastupdate = NOW();
                    $order->store_id = $store_id;
                    //get last record
                    $record = Prebooking::orderBy('invoiceid', 'desc')->first();
                  

                    if ( $record == '' ){
                       
                        $newInvcNum = '1';
                      
                   } else {
                       //increase 1 with last invoice number
                       $expNum = $record->invoicenumber;
                      
                        $newInvcNum = $expNum+1;

                   }
                    
                      
                        $order->invoicenumber = $newInvcNum;
                        $order->arinvoicenum =  $request->invoicenum ;
                        $order->invoicedate = date('Y-m-d');
                       // return json_encode($order);
                        if($order->save()){
                            
                            $data= array('status'=>'olduser', 'order_id' => $order->invoiceid, 'cust_id' => $custId,  'description' => $request->description, 'newInvcNum' =>$newInvcNum);
                            return json_encode($data);
                        }
            }
        }   
        else
        {
           
        //     $mobile1 = $request->phone;
        //     if(strlen($mobile1)!=10)
        //     $msg="Mobile Number should be of 10 digits !!";
        //     $data= array('phone'=>$request->phone, 'status'=>'emailmsg','msg'=>$msg);
        //     return json_encode($data);
        //     $duplicate= User::where('phone', $mobile1)->where('category_id', $cat_id)->first();
       
        //     if(! empty($duplicate))
        //    {
        //         $duplicate_check = $duplicate->count();
                
                
        //         if($duplicate_check>0)
        //         {
        //         $msg="Mobile Number Duplicate with chosen Category !!";
        //         $data= array('phone'=>$request->phone, 'status'=>'emailmsg','msg'=>$msg);
        //         return json_encode($data);
        //         }
        //      }
           
            if($request->category=="") 
            $msg="Select Category";

            

            $user = User::insertGetId([
                'user_type' => 'customer',
                'name' => $request->c_name,
                'email' => $request->email,
                'address' => $request->address,
                'password' => $request->phone,
                'country' => 'IN',
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'phone' => '+91'.$request->phone,
                'verification_code' => rand(100000, 999999),
                'institute_id' => $inst_id,
                'category_id' => $cat_id,
                'state' => $state,
                'gstin' => $gstin,
               'father_name' => $request->father_name,
               'tehsil' => $request->tehsil,
               'district' => $request->district,
               'landmark' => $request->landmark
            ]);
           
           $user = User::where('phone','+91'.$request->phone)->where('email',$request->email)->first();
            //print_r($user); die();
            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated){
                $otpController = new OTPVerificationController;
                $otpController->send_code($user);
                $data= array('email'=>$user->email, 'phone' => $user->phone, 'invoiceType'=>$request->invoice_type, 'cust_id' => $custId, 'description' => $request->description, 'status'=>'newuser');
                return json_encode($data);
            }
        }
    
    }

    public function verify_phone_prebooking(Request $request){

        $user = User :: where('email', $request->email)->where('phone', $request->phone)->first();
        if($user)
        {
            if ($user->verification_code == $request->verification_code) {
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                $insertedId = $user->id;
                if($insertedId)
                {
                    $customer = new Customer;
                    $customer->user_id = $insertedId;
                    $customer->save();

                }

                $order = new Prebooking;
                if($request->invoicenum == '')
                    {
                        $request->invoicenum = "";
                    }
                $order->arinvoicenum = $request->invoicenum;
                    $order->invoicelookuptype = $request->invoice_type;
                    $order->customerid = $customer->id;
                    $order->storelocationid = '1';
                    $order->invoicedate = strtotime('now');
                   
                    if($request->description == '')
                    {
                        $request->description = 'Null';
                    }
                    $order->description = $request->description;
                    $order->status = 'O';
                    $order->lastupdateby = Auth::user();
                    $order->lastupdate = NOW();
                    $order->store_id = '1';
                    //get last record
                    $record = Prebooking::orderBy('invoiceid', 'desc')->first();
                  

                    //check first day in a year
                    if ( $record == '' ){
                       
                         $newInvcNum = '1';
                       
                    } else {
                        //increase 1 with last invoice number
                        $expNum = $record->invoicenumber;
                       
                         $newInvcNum = $expNum+1;

                    }
                    
                      
                        $order->invoicenumber = $newInvcNum;
                        $order->arinvoicenum =  $request->invoicenum ;
                        $order->invoicedate = date('Y-m-d');

                if($order->save()){
                    $data= array('status'=>'true', 'newInvcNum' => $newInvcNum, 'user_id' =>$insertedId, 'order_id' => $order->invoiceid, 'cust_id'=>$customer->id,  'description' => $request->description);
                return json_encode($data);
                }
            }
            else{
                $data=array('status'=>'false', 'user_id'=>'', 'order_id' => '', 'description' => '');
                return json_encode($data);
            }
        }
        else
        {
            return 'User not exists';
        }
    }


    public function PreOrderBookingLines($invoice_number)
    {
        $order = Prebooking::where('invoicenumber',$invoice_number)->first();
        
        return view('prebooking.preorderbookinglines',compact('order'))->with('msg', null);
    }

    public function getbookdetail(Request $request)
    {
		
	
        $keywords = array();
      
        if($request->transaction_type == 'R')
        {
           
           $on_rent = "yes";
           
        }
        else
        {
            $on_rent = "no";
        }

        $response = [];

        // search in products

        $products = Product::where('published', 1)
            ->where('name', 'like', '%'.$request->keyword.'%')
            ->orwhere('isbn', 'like', '%'.$request->keyword.'%')
            ->orwhere('oldisbn', 'like', '%'.$request->keyword.'%')
            ->orwhere('meta_title', 'like', '%'.$request->keyword.'%')
            ->where('onrent', $on_rent)
            ->get();

        foreach($products as $prod)
        {
            $product_variations = [];

            if($prod->onrent == 'yes')
            {
                $rent = 'R';
            }
            else
            {
                $rent = 'S';
            }
            
            $variations = \App\ProductStock::where('product_id', $prod->id)->get();
            foreach($variations as $variant)
            {
                $v_item = [
                    "id"            =>  $variant->id,
                    "product_id"    =>  $variant->product_id,
                    "variant"       =>  $variant->variant,
                    "price"         =>  $variant->price,
                    "isbn"          =>  $variant->isbn,
                    "qty"           =>  $variant->qty,
                    "mrp"           =>  $variant->mrp
                ];

                array_push($product_variations, $v_item);
            }

            $p_item = [
                "id"            =>  $prod->id,
                "name"          =>  $prod->name,
                "isbn"          =>  $prod->isbn,
                "oldisbn"       =>  $prod->oldisbn,
                "mrp"           =>  $prod->mrp,
                "current_stock" =>  $prod->current_stock,
                "unit_price"    =>  $prod->unit_price,
                "onrent"        =>  $prod->onrent,
                "variations"    =>  $product_variations,
                "source"        => "product"
            ];

            array_push($response, $p_item);
        }

        // search in stocks
        $product_stocks = DB::table('product_stocks')
            ->where('variant', 'like', '%'.$request->keyword.'%')
            ->orwhere('isbn', 'like', '%'.$request->keyword.'%')
            ->get();

        foreach($product_stocks as $product_stock)
        {
            $prod = Product::where('id', $product_stock->product_id)->first();

            if($prod)
            {
                $product_variations = [];

                $variant = \App\ProductStock::where('product_id', $prod->id)->where('id', $product_stock->id)->first();

                if($variant)
                {
                    $v_item = [
                        "id"            =>  $variant->id,
                        "product_id"    =>  $variant->product_id,
                        "variant"       =>  $variant->variant,
                        "price"         =>  $variant->price,
                        "isbn"          =>  $variant->isbn,
                        "qty"           =>  $variant->qty,
                        "mrp"           =>  $variant->mrp
                    ];
    
                    array_push($product_variations, $v_item);
                }

                $p_item = [
                    "id"            =>  $prod->id,
                    "name"          =>  $prod->name,
                    "isbn"          =>  $prod->isbn,
                    "oldisbn"       =>  $prod->oldisbn,
                    "mrp"           =>  $prod->mrp,
                    "current_stock" =>  $prod->current_stock,
                    "unit_price"    =>  $prod->unit_price,
                    "onrent"        =>  $prod->onrent,
                    "variations"    =>  $product_variations,
                    "source"        => "variation"
                ];
    
                array_push($response, $p_item);
            }
        }

        // $response = array_values(array_unique(array_column($response, 'id')));

        // remove duplicate
        $ids = array_column($response, 'id');
        $ids = array_unique($ids);
        $response = array_filter($response, function ($key, $value) use ($ids) {
            return in_array($value, array_keys($ids));
        }, ARRAY_FILTER_USE_BOTH);

        // sort by desc
        
        usort($response, function($a, $b) {
            return $b['id'] - $a['id'];
        });

        //  return json_encode($response['variations']);

        /////

        
        
        if($response != '')
        {
            $html = '';
            
          
            foreach($response as $prod)
            {
                if($prod['onrent'] == 'yes')
                {
                    $rent = 'R';
                }
                else
                {
                    $rent = 'S';
                }
                
                
                $variant_html = '';

                foreach($prod['variations'] as $variant)
                {
                    $variant_html .= "<li class='list-group-item selectbook_var' style='background-color:#eae4e4;' data-prod='".$variant['product_id']."' data-varn='".$variant['id']."' data-tran='".$rent."' data-type='book' data-inv='S'>".$variant['variant']."<font style='color:red'> ISBN: ".$variant['isbn']."</font> Price: ".$variant['price']."<b> Quantity:".$variant['qty']."</b></li> ";
                }
                if(sizeof($prod['variations']) != '0')
                {
                    $html .= "<li class='list-group-item var_prod' data-prod='".$prod['id']."' data-tran='".$rent."' data-type='book' data-inv='S' style='background-color:#f4f4f4;'>".$prod['name']."<ul class='list-group test' style='margin-bottom: 2px;margin-top: 2px;'>".$variant_html."</ul></li>";
                }
                else
                {
                    $html .= "<li class='list-group-item selectbook_prod' data-prod='".$prod['id']."' data-tran='".$rent."' data-type='book' data-inv='S' style='background-color:#f4f4f4;'>".$prod['name']."<font style='color:red'> ISBN: ".$prod['isbn']."</font> MRP: ".$prod['mrp']."<b> Quantity:".$prod['current_stock']."</b><ul class='list-group test' style='margin-bottom: 2px;margin-top: 2px;'>".$variant_html."</ul></li>";
                }
                  
              
                
            }
        
            $htmld = "<ul id='book-list' class='list-group test'>".$html."</ul>";
            
            //return $htmld; die();
            
            $data = array(
                'status'    =>  'yes',
                'html'      =>  $htmld
            );

            return json_encode($data);
                
            //return view('frontend.partials.search_content', compact('products', 'subsubcategories', 'keywords', 'shops'));
        }
        
        $data =array('status'=>'no', 'html'=>'');
        return json_encode($data);
        
    }
        public function getBookDetailInvoice(Request $request)
        {
            $orderId=$request->orderId;
            $keyword=$request->keyword;
            $discount='';
            $trxn=$request->trxn;
            
            $vart=$request->vart;
            $invoice_type = 'S';
            if(isset($request->invoice_type)){
                $invoice_type = $request->invoice_type;
            }
            $store_id = isset($_REQUEST['store_id'])?$_REQUEST['store_id']:'1';
            $customerId = $request->customerId;
            
          
            if(isset($customerId)){
                $product = Product::where('id',$keyword)->first();
                $author = Author:: where('id',$product->author_id)->first();
                if($author)
                {
                    $name =  $author->name;;
                }
                else
                {
                   $name = '';
                }
              $qty = $product->current_stock;
              $onrent = $product->onrent;
              if($onrent=='yes')
              {
                  $rent = 'R';
              }
              else
              {
                  $rent = 'S';
              }
              /*
              if($product->variant_product){
                  foreach ($product->stocks as $key => $stock) {
                      $qty = $stock->qty;
                  }
              }
              */
              
              if($vart == 'null'){
             if ($qty > 0)
             {
				 
                 $qty_info ="In Stock";
                 $erpprice = $product->erpprice;
                 $mrp = $product->mrp;
                 $isbn = $product->isbn;
                 $security = $product->securityamount;
                 $discount = 0; /// for right now
                 if($trxn == 'S'){
					 
					 
                 if($invoice_type == 'C'){
                $output='<tr class="brent"><td>Selling Price*</td><td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="SellingPrice" value="'.$erpprice.'" required="required" /></td><td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'</td></tr><tr class="bmrp"><td>MRP:</td><td><input type="text" name="mrp" value="'.$mrp.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" /> , Qty available : '.$qty.'<span>('.$qty_info.')</span><input type="hidden" name="qty_a" value="'.$qty.'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> <label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
                echo $output; 
			}else{
				
				echo $output='<tr class="brent"><td>Selling Price*</td><td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="SellingPrice" value="'.$erpprice.'" required="required" /></td><td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'</td></tr><tr class="bmrp"><td>MRP:</td><td><input type="text" name="mrp" value="'.$mrp.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" /> , Qty available : '.$qty.'<span>('.$qty_info.')</span><input type="hidden" name="qty_a" value="'.$qty.'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </tr>';
				}
			}else{ /// rent
				
				 if($invoice_type == 'C'){
                $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /></td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" />  , Qty available : '.$qty .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td><td><label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
                echo $output; 
			}else{
				echo $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /></td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" />  , Qty available : '.$qty .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td></tr>';
				}
				
				}

             }
              else
              {
                  $qty_info ="Out Stock";
                //  flash('Out Stock')->error();
                  return 'out';
                 // return back();
              }

            }else{
				$productV = \App\ProductStock::where('id',$request->vart)->first();
				if ($productV->qty > 0){
				$mrp = $productV->mrp;
				$price = $productV->price;
				$isbn = $productV->isbn;
				$qty = $productV->qty;
				$security = $mrp;
				$qty_info ="In Stock";
				 if($trxn == 'S'){
					 
					 
                 if($invoice_type == 'C'){
                $output='<tr class="brent"><td>Selling Price*</td><td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="SellingPrice" value="'.$price.'" required="required" /></td><td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'</td></tr><tr class="bmrp"><td>MRP:</td><td><input type="text" name="mrp" value="'.$mrp.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" /> , Qty available : '.$qty.'<span>('.$qty_info.')</span><input type="hidden" name="qty_a" value="'.$qty.'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> <label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
                echo $output; 
			}else{
				echo $output='<tr class="brent"><td>Selling Price*</td><td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="SellingPrice" value="'.$price.'" required="required" /></td><td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'</td></tr><tr class="bmrp"><td>MRP:</td><td><input type="text" name="mrp" value="'.$mrp.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" /> , Qty available : '.$qty.'<span>('.$qty_info.')</span><input type="hidden" name="qty_a" value="'.$qty.'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </tr>';
				}
			}else{ /// Rent
				
				  if($invoice_type == 'C'){
                $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /><input type="text" name="rentamt" value="'.$product->rentamount.'" required="required" /></td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" />  , Qty available : '.$qty .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td><td><label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
                echo $output; 
			}else{
				echo $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /><input type="text" name="rentamt" value="'.$product->rentamount.'" required="required" /></td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" />  , Qty available : '.$qty .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td></tr>';
				}
				
				}
			}else{
				$qty_info ="Out Stock";
               
                  return 'out';
				}
				
			
			}
		}
        
        }

    public function ProductSave(request $request)
    { 
        $idorder = $request->orderId;
        $invn = $request->invoice_number;

       //return json_encode($request->all());
            $msg="";
            $prepay=$request->prepay;
            $sale_rent=$request->sale_rent; // from ajax
            $baseprice=0;
            $special_discount = isset($request->special_discount)?$request->special_discount:'0';
            $transaction_type=$request->transaction_type; // from form
            $variant = $request->vart;
           $qunty = $request->qty;
            if($variant == 'null')
            {
                $variation = 'null';
                $qunty = $request->qty;
                $itemprice=$request->SellingPrice; 
                $mrp = $request->mrp;
            }
            else
            {
               
                $var_data = \App\ProductStock::where('id',$request->vart)->first();
                $variation = $var_data->variant;
                $price = $var_data->price;
                $qunty = $request->qty;
                if($transaction_type == 'R'){
                $itemprice= $request->security;
            }
            if($transaction_type == 'S'){
                $itemprice= $request->SellingPrice;
            }
                $mrp = $request->mrp;

            }

            $quantity=(isset($request->qty) && $request->qty !='')? $qunty:1;
            
            $quantity_a=$request->qty_a; // qty available in stock
            // check qty
            //$vm_id = $_POST['vm_id'];   // code for joomla to get joomla book id
            if($quantity_a < $quantity) 
            {

                $msg="Quantity not available !!";
                return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn)->with('msg', $msg);
            }
            $order = Prebooking::where('invoiceid',$request->orderId)->first();
            //return json_encode($order);
            // books for rent can also be sold , but books for sale cannot be leased
            if($transaction_type=="S")
            {
              //  die('hgfhfhf');
            //$transaction_type=$sale_rent;
                $item_price=$itemprice;  // selling price
              
                $pro = Product::select('igst','sgst')->where('id', $request->keyword)->first();
                $igst = $pro->igst;
                $sgst = $pro->sgst;
                $tax= 0;
                if($igst != 0 and $igst != '' ){
                    $tax=$igst;
                    }else if($sgst != 0 and $sgst != '' ){
                    $tax=$sgst;
                    }
                $baseprice = $item_price * 100/(100+$tax);

               
                
                $item_id=$request->keyword; // book id
         
                if($order->invoicelookuptype != 'C'){
                        if($sale_rent=="no" && $transaction_type=="R") 
                        {
                            $msg="Book on sale cannot be given on rent";
                            return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn)->with('msg', $msg);
                        }
                }
                $amount = $mrp; // MRP
                // return $amount;
                //if(isset($_POST['security'])) {$amount=$_POST['security']; $item_price=$_POST['security'];}
                 if($amount <=0 and $prepay!="-1")  
                 {
                    $msg="Choose Sale/Rent Amount is 0.";
                    // return $msg;
                    return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn)->with('msg', $msg);
                  }
                 
                
                $discount=$amount-$item_price;
                if($order->invoicelookuptype == 'C'){
                    $discount = ($amount*$special_discount/100);
                    $item_price = $amount-$discount;
                    $baseprice = $item_price * 100/(100+$tax);
                 }
                $security=0;
                $due_date="00-00-00";
                $description="";
                //die('test');
                //echo $baseprice;
                //die;
                if($special_discount == ''){$special_discount = 0;}
                if($msg=="")
                {
                
               $custid = $order->customerid; 
                $orderid = $order->invoiceid;
                $invoice_number = $order->invoicenumber;
            /*
                $update_discount = Product::where('id', $item_id)->where('user_id',$custid)->update([
                    'discount' => $special_discount
                ]);
                */
                // print_r($variation); die();
            $insert_line= PrebookingLine::insert([
                'invoiceid' =>$orderid ,
                'itemid' => $item_id,
                'transactiontype' => $transaction_type,
                'itemprice' => $item_price,
                'quantity' => $quantity,
                'discount' => $discount,
                'sgst' => $sgst,
                'cgst' => '',
                'gst' => '',
                'igst' => $igst,
                'gstamount' => '',
                'baseprice' => $baseprice,
                'security' =>$security,
                'amount' =>$amount,
                'rentduedate' => $due_date,
                'description'=> $description,
                'variation' => $variation,
                'lastupdatedate' => strtotime('now'),
                'LastUpdate' =>Auth::user(),
                'isdeleted' =>'N'
               
        ]);
        
        //print_r($insert_line); die();
       
            if($insert_line){
       
                $qty_inventory=$quantity_a-$quantity;
                if($variant == 'null'){
            $inventory_book_update= Product::where('id',$item_id)->update([
                'current_stock' => $qty_inventory
                ]);
            }elseif($variant != 'null'){
                $inventory_book_update = \App\ProductStock::where('id',$request->vart)->update([
                'qty' => $qty_inventory
                ]);
                }
          
            }
            if(!$inventory_book_update) {$msg="Inventory Update Failed"; die();}
            if($insert_line) 
            {
                $msg="Line Added Successfully";
                $idorder = $request->orderId;
                $invn = $request->invoice_number;
                return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn)->with('msg', $msg);
                
            }
            else {
                $msg="Failed";
                return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn)->with('msg', $msg);
            }
                }
                
            }
            else
            {
               
                // due date for rent 
                $date = date("Y-m-d");
                $due_date = strtotime(date("Y-m-d", strtotime($date)) . " +15 days");
                $due_date = date("Y-m-d",$due_date);
                //
                $sale_rent=$request->sale_rent; // from ajax
                $transaction_type=$request->transaction_type; // from form
                //$transaction_type=$sale_rent;
                
                
                $item_id=$request->keyword; // book id
                
                $amount=$request->security; // security
                
                $security=$request->security; // security
                
                $rent=$request->rentamt; 
                $item_price=$rent;
                $pro = Product::select('igst','sgst')->where('id', $request->keyword)->first();
                $igst = $pro->igst;
                $sgst = $pro->sgst;
                $tax= 0;
                if($igst != 0 and $igst != '' ){
                    $tax=$igst;
                    }else if($sgst != 0 and $sgst != '' ){
                    $tax=$sgst;
                    }
                $baseprice = $security * 100/(100+$tax);
                
                //$refund=$item_price-$rent;
                $refund=$security-$rent;
                // item price will have rent amount , amount will have security or MRP
                $description="";
                $discount=0;
                $order = Prebooking::where('invoiceid',$request->orderId)->first();
                    if($order->invoicelookuptype == 'C'){
                    $discount = ($amount*$special_discount/100);
                    $transaction_type = 'S';
                    
                    }else{
                        if($sale_rent=="no" && $transaction_type=="R")
                        {
                            $msg="Book on sale cannot be given on rent";
                            return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn)->with('msg', $msg);
                        }
                        $discount=0;	
                    }
                
                    if($amount<=0) 
                    {
                        $msg="Choose Sale/Rent Amount is 0";
                        return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn)->with('msg', $msg);
                    }
                    //print_r($_REQUEST); die;
                if($msg=="")
                {
                    
                $custid = $order->customerid;
                $update_discount = Product::where('id', $item_id)->where('user_id',$custid)->update([
                    'discount' => $special_discount
                ]);
                
              
                }
                $insert_line=PrebookingLine::insert([
                    'invoiceid' =>$request->orderId ,
                    'itemid' => $item_id,
                    'transactiontype' => $transaction_type,
                    'itemprice' => $item_price,
                    'quantity' => $quantity,
                    'discount' => $discount,
                    'sgst' => $sgst,
                    'cgst' => '',
                    'gst' => '',
                    'igst' => $igst,
                    'gstamount' => '',
                    'baseprice' => $baseprice,
                    'security' =>$security,
                    'amount' =>$amount,
                    'rentduedate' => $due_date,
                    'description'=> $description,
                    'variation' => $variation,
                    'lastupdatedate' => strtotime('now'),
                    'LastUpdate' =>Auth::user(),
                    'isdeleted' =>'N'
                   
            ]);
                
            if($insert_line) 
            {
                $qty_inventory=$quantity_a-$quantity;
                if($variant == 'null'){
            $inventory_book_update= Product::where('id',$item_id)->update([
                'current_stock' => $qty_inventory
                ]);
            }elseif($variant != 'null'){
                $inventory_book_update = \App\ProductStock::where('id',$request->vart)->update([
                'qty' => $qty_inventory
                ]);
                }
                
                    $msg="Line Added Successfully";
                    return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn)->with('msg', $msg);
                   
                
            }
            else 
            {
             $msg="Failed";
             return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn)->with('msg', $msg);
            }
        }
      
   }

   public function destroyLine(Request $request)
   {
        // return json_encode($order_detail);    
       $id = $request->id;
       $val= Prebooking::where('invoiceid',$invoice_id)->where('Status','O')->first();
       
       $validate = $val->count();
      
       if($validate==0) return "Invoice is not open, cannot edit the same !!";

       $isdeleted = PrebookingLine::select('isdeleted')->where('lineid',$id)->first();
       if($isdeleted == 'Y'){
        return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$val->invoicenumber);
        }
        $update_line = PrebookingLine::where('lineid',$id)->update([
            'isdeleted' => 'Y',

        ]);
        $order_detail = PrebookingLine::where('lineid',$id)->first();
        $qty = $order_detail->quantity;
       
       
       if($order_detail != null){
           if($order_detail->variation != "null" && $order_detail->variation != ''){
               $product_stock = \App\ProductStock::where('variant', $order_detail->variation)->where('product_id',$order_detail->product_id)->first();
               //return json_encode($product_stock);   
               $product_qty = $product_stock->qty ; 
               $current_stock =  $product_qty+$qty;
               // return json_encode($current_stock); 
                    $qty_update = \App\ProductStock::where('product_id',$order_detail->product_id)->where('variant', $order_detail->variation)->update([
                       'qty' => $current_stock
                   ]);
               
           }
           else
           { 
               $product_stock = Product::where('id',$order_detail->product_id)->first();
              // return json_encode($product_stock); 
                   $current_stock = $product_stock->current_stock+$qty;
                       $qty_update = Product::where('id',$order_detail->product_id)->update([
                           'current_stock' => $current_stock
                   ]);
              
           }

           PrebookingLine::where('lineid',$id)->delete();
                          return "done";
       }
       else{
          return "not done";
       }

   }

   public function getDescription(Request $request)
   {
       $orderid = $request->id;
           $desc = Prebooking::where('invoiceid',$orderid)->update([
               'description' => $request->desc
           ]);
           
          if($desc == '1')
          {
           $order = Prebooking::where('id',$orderid)->first();
           //print_r($oder); die();
           $custid = $order->customerid;
           $customer = Customer::where('id',$custid)->first();
           $user = User::where('id', $customer->user_id)->first();
           $userid = $order->user_id;
           $desc = $order->desc;
           if($userid != '')
           {
               $user = User::where('id', $userid)->first();
               sendSMS($user->phone, env('APP_NAME'), 'your description'. $desc.' updated '.env('APP_NAME'));
               return 'true';
           }
           
          }
   }


   public function applycoupon(Request $request)
   {
      $val =$request->coupon;
      $coupon = \App\Coupon::where('code', $val)->first();
      $user= Customer::where('id',$request->customerId)->first();
   if($val !='') {
      // echo $val." ". $coupon; die();
   if($coupon != null){
      // echo strtotime(date('d-m-Y'))." ".$coupon->start_date." ".strtotime(date('d-m-Y'))." ". $coupon->end_date;die();
       if(strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date){
          // echo $request->customerId; die();
           if(\App\CouponUsage::where('user_id', $user->user_id)->where('coupon_id', $coupon->id)->first() == null){
               $coupon_details = json_decode($coupon->details);
               if ($coupon->type == 'cart_base')
               {
                 
                    $sum = $request->amount;

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
                       $insert_coupon = \App\CouponUsage::insert([
                           'user_id' => $user->user_id,
                           'coupon_id'=> $coupon->id
                       ]);
                       if($insert_coupon)
                       {
                           $coupondis =sprintf("%.2f", $coupon_discount); 
                           $grandtotal = sprintf("%.2f", $sum - $coupon_discount);
                           $update_order = Prebooking::where('invoiceid', $request->order_id)->update([
                               'coupon_discount'=> $coupondis,
                               'coupon_code' => $val,
                               'amount' => $grandtotal
                               ]);
                       }
                       if($update_order){
                       
                        return $coupon_discount;
                       }
                   }
                   else
                   {
                       $msg = "Order Amout has not meet with coupon minimum amount requirement";
                       return "3";
                   }
               }
              
           }
           else{
               $msg = "Coupon does not applicable for this customers.";
               return "6";
           }
       }
       else{
           $msg = "Coupon expired does not applicable for this customers.";
           return "9";
       }
   }
   else {
       $msg = 'Coupan does not Exists';
        return "5";
   }
}
else {
   $msg = 'Empty Request';
   return "4";
}
   //return back();

}



public function deletecoupon($id)
{
    
}

public function resend_code(Request $request){
    $user = User :: where('email', $request->email)->where('phone', $request->phone)->first();
    
    $otp = mt_rand(100000,999999);
            $update_otp=User::where('id', $user->id)->update([
                'verification_code' => $otp
                ]);
            if($update_otp)
            {
                $updated_user = User::where('phone', $request->phone)->first();
                sendSMS($updated_user->phone, env('APP_NAME'), $updated_user->verification_code.' is your verification code for '.env('APP_NAME'));
                $data=array('status'=>"true", 'message'=> 'Otp Send on your Phone Number', 'phone'=>$updated_user->phone);
                return json_encode($data);
            }
    
}

public function statusChangeOrder(Request $request)
{
    //return json_encode($request);
            $orderid = $request->orderid;
            $status = $request->status;

            $changeStatus = Prebooking::where('invoiceid',$orderid)->update([
                'status' => $status
            ]);
            if($changeStatus)
            {
                return '1';
            }
}

public function PreBookingUpdate(Request $request)
{
   $msg='';
        // validate supplier and institute should have payment method
        if( $request->ModeOfPayment=='')
        {
        $msg="Mode of Payment mandatory for Institutes and Supplier";
        return redirect('admin/PreOrderBooking/PreOrderBookingLines/'.$request->invoiceno)->with('msg',$msg);
        }
        else{
        //
        if($request->cerror == 'error')
        {
            $invoice_id = $request->invoiceid;
            $pre_updt = Prebooking::where('invoiceid', $invoice_id)->update([
                "amount" =>$request->payment_amount_close,
                "creditamt" =>$request->creditamt,
                "modeofpayment" =>$request->ModeOfPayment,
                "description" =>$request->description,
                "notifyme" =>$request->notifyme,
                "status" =>'P'
            ]);
         
            $balanced = $request->payment_amount_close-$request->prepayment;
            ///////// Insert into Prebooking payment
            if($request->prepayment != '' ||$request->prepayment != 0 )
            {
                $ins_pay = PrebookingPayment::insert([
                    'invoiceid' => $invoice_id,
                    'paid' => $request->prepayment,
                    'balanced' => $balanced,
                    'paiddate' => NOW(),
                    'modeofpayment' => $request->ModeOfPayment,
                    'updatedby' => Auth::user()->id
                ]);

                return redirect('admin/PreOrderBooking/PreOrderBookingLines/'.$request->invoiceno);
            }
        }
        else
        {
            $invoice_id = $request->invoiceid;
            $pre_updt = Prebooking::where('invoiceid', $invoice_id)->update([
                "amount" =>$request->famount,
                "creditamt" =>$request->creditamt,
                "modeofpayment" =>$request->ModeOfPayment,
                "description" =>$request->description,
                "coupon_code" =>$request->couponCode,
                "coupon_discount" =>$request->discount,
                "notifyme" =>$request->notifyme,
                "status" =>'P'
            ]);
           
          
            $balanced = $request->famount-$request->prepayment;
            if($request->prepayment != '' ||$request->prepayment != 0 ){
           
                $ins_pay = PrebookingPayment::insert([
                    'invoiceid' => $invoice_id,
                    'paid' => $request->prepayment,
                    'balanced' => $balanced,
                    'paiddate' => NOW(),
                    'modeofpayment' => $request->ModeOfPayment,
                    'updatedby' => Auth::user()->id
                ]);
    
                return redirect('admin/PreOrderBooking/PreOrderBookingLines/'.$request->invoiceno);
            }
                     
            }

          
        }

}


public function CancelOrder($id)
{
   $upt_prebook =  PrebookingLine::where('invoiceid', $id)->update([
        'isdeleted' =>'Y'
    ]);
    if($upt_prebook)
    {
        Prebooking::where('invoiceid', $id)->update([
            'status' =>'C'
        ]);
    }
    $invn = Prebooking::select('invoicenumber')->where('invoiceid',$id)->first();
    
   return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn->invoicenumber);
}


public function GenerateAr(Request $request)
{
    //return json_encode($request->all());
    $invoicenumber = $request->invoice_number;
    $paidamt = $request->paidinv;
    $sltbk = $request->selectedbook;
    $booking =  Prebooking::where('invoicenumber', $invoicenumber)->first();
    $bookingstatus = $booking->status;
    $InvoiceID = $booking->invoiceid;
    $CustomerID = $booking->customerid;
    $coupon_code = $booking->coupon_code;
    $coupon_discount = $booking->coupon_discount;
    $insertstatus = 'N';
    $bookingstatus = Prebooking::select('status')->where('invoiceid', $InvoiceID)->where('invoicenumber', $invoicenumber)->first();
    //return  $bookingstatus->status;
	if($bookingstatus->status == 'P'){
    ////// copy available quantity to AR/////////////////////
    $Quant =  PrebookingLine::select('quantity')->where('invoiceid', $InvoiceID)->sum('quantity');
    $delivered_qty =PrebookingLine::select('delivered_qty')->where('invoiceid', $InvoiceID)->sum('delivered_qty');
    
    $Quantity = $Quant - $delivered_qty;
    
	
	if($Quantity == 0 && $delivered_qty != ''){
	/////////// Quantity not available ///////////
    $invn = $invoicenumber;
    return redirect('/admin/PreOrderBooking/PreOrderBookingLines/'.$invn);
	}

    
        // next invoice num
        $data = [];
        $qry = Order::select('id','invoice_number as num')->where('ordersource','!=','website')->orderBy('id','desc')->limit(1)->get();
      //  return $qry;
        foreach($qry as $da)
        {
            $item = [
                "num" =>  $da->num
            ];
            array_push($data, $item);
        }
       //return $data;
        if( filter_var($data[0]['num'], FILTER_SANITIZE_NUMBER_INT)=='0') $next_invoice_number =  10000;  // initialise first invoice number
		else $next_invoice_number=  filter_var($data[0]['num'], FILTER_SANITIZE_NUMBER_INT)+1;
        
        $customer_id=$CustomerID;
        $invoice_type = 'S';
        $description = "";
        $amount = $paidamt;
        $user = Customer::where('id',$customer_id)->first();

        $last_id= Order::insertGetId([
            'id' => Null,
            'invoice_number' => $next_invoice_number,
            'invoicelookuptype' => $next_invoice_number,
            'user_id' => $user->user_id,
            'invoicedate' => date('Y-m-d'),
            'description' => $description,
            'payment_status' => 'paid',
            'grand_total' => $amount,
            'preorderid' => $InvoiceID,
            'ordersource' => 'ERP'

        ]);
        
       
        $amt=0;
         if($last_id){
        //executequery($con,"update AR_Invoices_All set coupon_code='$coupon_code', coupon_discount='$coupon_discount' where InvoiceID='".$last_id."'");
            $rrr = PrebookingLine::where('invoiceid', $InvoiceID)->get();//default All
        if($sltbk == ''){
            $rrr = PrebookingLine::where('invoiceid', $InvoiceID)->get();
        }else{
            $rrr = PrebookingLine::where('invoiceid', $InvoiceID)->whereIn('lineid', $sltbk)->get();
       
        }
        //echo "testt"; die;
//return $rrr;
        foreach($rrr as $r):

        $availableQty = \App\Product::select('current_stock')->where('id',$r->itemid)->first();
     
        if($availableQty->current_stock > 0 && $r->quantity > $r->delivered_qty){ //// check available quantity
            $arr = array();
            
                $arr['order_id'] = $last_id;
                $arr['product_id'] = $r->itemid;
                $arr['transactiontype'] = $r->transactiontype;
                $arr['price'] = $r->itemprice;
               
            if($availableQty->current_stock > ($r->quantity - $r->delivered_qty)){
                ///// complete import line
                
                $arr['quantity'] = $r->quantity;
                $qty_inventory=$availableQty->current_stock-$r->quantity;
                
            }else{
                ///// Partial import line
                $deliveredQty = $availableQty->current_stock;
                $arr['quantity'] = $deliveredQty;
                
                
                
            }
            
                $arr['discount'] = $r->discount;
                $arr['securuty'] = $r->security;
                $arr['amount'] = $r->amount;
                $arr['rentduedate'] = $r->rentduedate;
                // $arr['lastUpdateDate'] = date('Y-m-d H:i:s');
                // $arr['LastUpdate'] = Auth::user()->id;
                $arr['isdeleted'] = 'N';
                $arr['preorderid'] = $r->invoiceid;
                $arr['preorderlineid'] = $r->lineid;
                
                $amt2 = $amt + ($r->amount - $r->discount ) * $arr['quantity'];
                $amt = isset($paidamt) ? $paidamt : $amt2;
                
                $invoice_id = OrderDetail::insertGetId($arr);

                if($invoice_id){
                   // $invoice_id = mysqli_insert_id($con);
                    $qty_inventory=$availableQty->current_stock - $arr['quantity'];
                    $inventory_book_update= Product::where('id',$r->itemid)->update([
                        'current_stock' => $qty_inventory
                        ]);

                    $updatepreline= Product::where('id',$r->lineid)->update([
                            'unit' => $arr['quantity']
                         ]);
                    
                    
                   
                    $insertstatus = 'Y';
                    }
            
            }
        endforeach;
        $updt= Order::where('id',$last_id)->update([
            'grand_total' => $amt
         ]);
       

        
        if($insertstatus == 'Y'){
            $invoice_number = $next_invoice_number;
            //return 'true';
            return redirect('/admin/PreOrderBooking/arinvoiceprebookview/'.$invoice_number);
            //ARinvoice-lines-workbench_prebooking.php
            //header("Location:ARinvoice-lines-workbench_prebooking.php?invoice_number=$next_invoice_number");
            }

         }


		
		}
}

public function Invoice($invoice_id,$bal)
{
    $invoice_id=$invoiceID=$invoice_id;
          
    $invoice_header_data = Prebooking::where('invoiceid',$invoice_id)->first();
    $invoice_line_data = [];
    $invoice_number = $invoice_header_data->invoicenumber;
    // get book detail for displaying invoice line
    $invoice_line= DB::table('prebooking_lines as a')
    ->select('a.lineid','a.rentduedate','a.itemid','a.sgst','a.cgst','a.gst','a.igst','a.baseprice','b.isbn', 'b.oldisbn', 'b.name', 'b.author_id','b.updated_at','a.amount','a.discount','a.quantity','a.transactiontype','a.itemprice','a.delivered_qty','a.lastupdateddate as lastline')
    ->join('products as b','b.id','=','a.itemid', 'left')
    ->where('a.invoiceid', $invoice_id)
    ->where('a.isdeleted', 'N')
    ->orderBy('a.lineid', 'asc')
    ->get();

    

    foreach($invoice_line as $data_item)
    {
        $item = [
            "Quantity" =>  $data_item->quantity,
            "Isbn1"      =>  $data_item->isbn ,
            "Name" =>    $data_item->name ,
            "Author"      =>  $data_item->author_id ,
            "RentDueDate"      =>  $data_item->rentduedate ,
            "TransactionType" => $data_item->transactiontype,
            "Amount" => $data_item->amount,
            "Discount" => $data_item->discount,
            "ItemPrice" => $data_item->itemprice,
            "delivered_qty" => $data_item->delivered_qty,
            "lineid" => $data_item->lineid

           
            ];

        array_push($invoice_line_data, $item);
    }
    $customer = Customer::where('id',$invoice_header_data->customerid)->first();
    $customer_detail= User::where('id',$customer->user_id)->first();
    
   

    //print_r($invoice_header_data); die();
    return view('prebooking.preInvoice',compact('invoice_id', 'invoice_number', 'invoice_line_data', 'invoice_header_data','customer_detail'));

}

public function AdvanceInvoice($invoice_id,$amt)

{
    $invoice_header_data = Prebooking::where('invoiceid',$invoice_id)->first();
    $invoice_no = $invoice_header_data->invoicenumber;
    $custid = $invoice_header_data->customerid;
                        $customer = \App\Customer::where('id',$custid)->first();
                        $user = \App\User:: where('id',$customer->user_id)->first();
    return view('prebooking.Aadvancepay',compact('invoice_id', 'invoice_no','amt', 'user'));
}




public function Arinvoicepre($invoice_id)
{
    // $invoice_id='';
    // $invoice_number='';
   $invoice_header_data = Prebooking::where('invoiceid',$invoice_id)->first();
//    if($invoice_header_data){
   // $invoice_id=$invoice_header_data->invoiceid;
    
    
    $invoice_number = $invoice_header_data->invoicenumber;
//    }
   $invoice_line_data = [];
   
    // get book detail for displaying invoice line
    $invoice_line= DB::table('prebooking_lines as a')
    ->select('a.lineid','a.rentduedate','a.itemid','a.sgst','a.cgst','a.gst','a.igst','a.baseprice','b.isbn', 'b.oldisbn', 'b.name', 'b.author_id','b.updated_at','a.amount','a.discount','a.quantity','a.transactiontype','a.itemprice','a.delivered_qty','a.lastupdateddate as lastline')
    ->join('products as b','b.id','=','a.itemid', 'left')
    ->where('a.invoiceid', $invoice_id)
    ->where('a.isdeleted', 'N')
    ->orderBy('a.lineid', 'asc')
    ->get();
  
    foreach($invoice_line as $data_item)
    {
        $item = [
            "Quantity" =>  $data_item->quantity,
            "Isbn1"      =>  $data_item->isbn ,
            "Name" =>    $data_item->name ,
            "Author"      =>  $data_item->author_id ,
            "RentDueDate"      =>  $data_item->rentduedate ,
            "TransactionType" => $data_item->transactiontype,
            "Amount" => $data_item->amount,
            "Discount" => $data_item->discount,
            "ItemPrice" => $data_item->itemprice,
            "delivered_qty" => $data_item->delivered_qty,
            "lineid" => $data_item->lineid

           
            ];

        array_push($invoice_line_data, $item);
    }
    $customer = Customer::where('id',$invoice_header_data->customerid)->first();
    $customer_detail= User::where('id',$customer->user_id)->first();
    
   

    //print_r($invoice_header_data); die();
    return view('prebooking.arinvoicepre',compact('invoice_id', 'invoice_number', 'invoice_line_data', 'invoice_header_data','customer_detail'));

}

public function AdvInvoice($invoice_id)
{
    $invoice_id=$invoiceID=$invoice_id;
          
    $invoice_header_data = Prebooking::where('invoiceid',$invoice_id)->first();
    $invoice_line_data = [];
    $invoice_number = $invoice_header_data->invoicenumber;
    // get book detail for displaying invoice line
    $invoice_line= DB::table('prebooking_lines as a')
    ->select('a.lineid','a.rentduedate','a.itemid','a.sgst','a.cgst','a.gst','a.igst','a.baseprice','b.isbn', 'b.oldisbn', 'b.name', 'b.author_id','b.updated_at','a.amount','a.discount','a.quantity','a.transactiontype','a.itemprice','a.delivered_qty','a.lastupdateddate as lastline')
    ->join('products as b','b.id','=','a.itemid', 'left')
    ->where('a.invoiceid', $invoice_id)
    ->where('a.isdeleted', 'N')
    ->orderBy('a.lineid', 'asc')
    ->get();

    

    foreach($invoice_line as $data_item)
    {
        $item = [
            "Quantity" =>  $data_item->quantity,
            "Isbn1"      =>  $data_item->isbn ,
            "Name" =>    $data_item->name ,
            "Author"      =>  $data_item->author_id ,
            "RentDueDate"      =>  $data_item->rentduedate ,
            "TransactionType" => $data_item->transactiontype,
            "Amount" => $data_item->amount,
            "Discount" => $data_item->discount,
            "ItemPrice" => $data_item->itemprice,
            "delivered_qty" => $data_item->delivered_qty,
            "lineid" => $data_item->lineid

           
            ];

        array_push($invoice_line_data, $item);
    }
    $customer = Customer::where('id',$invoice_header_data->customerid)->first();
    $customer_detail= User::where('id',$customer->user_id)->first();
    
   

    //print_r($invoice_header_data); die();
    return view('prebooking.arinvoicepre',compact('invoice_id', 'invoice_number', 'invoice_line_data', 'invoice_header_data','customer_detail'));

}


}   