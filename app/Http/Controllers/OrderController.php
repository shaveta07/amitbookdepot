<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OTPVerificationController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\AffiliateController;
use App\Order;
use App\Product;
use App\Customer;
use App\Color;
use App\ApSuppliers;
use App\ApInvoicesAlls;
use App\ApInvoiceLines;
use App\OrderDetail;
use App\CouponUsage;
use App\OtpConfiguration;
use App\User;
use App\Author;
use App\BusinessSetting;
use App\ProductStock;
use Auth;
use Session;
use DB;
use PDF;
use Mail;
use App\Mail\InvoiceEmailManager;
use CoreComponentRepository;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = DB::table('orders')
                    ->orderBy('code', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.seller_id', Auth::user()->id)
                    ->select('orders.id')
                    ->distinct();

        if ($request->payment_status != null){
            $orders = $orders->where('order_details.payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }

        $orders = $orders->paginate(15);

        foreach ($orders as $key => $value) {
            $order = \App\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        return view('frontend.seller.orders', compact('orders','payment_status','delivery_status', 'sort_search'));
    }

    /**
     * Display a listing of the resource to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_orders(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $admin_user_id = User::where('user_type', 'admin')->first()->id;
        $orders = DB::table('orders')
                    ->orderBy('code', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.seller_id', $admin_user_id)
                    ->select('orders.id')
                    ->distinct();

        if ($request->payment_type != null){
            $orders = $orders->where('order_details.payment_status', $request->payment_type);
            $payment_status = $request->payment_type;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }
        $orders = $orders->paginate(15);
        return view('orders.index', compact('orders','payment_status','delivery_status', 'sort_search', 'admin_user_id'));
    }


    /**
     * Display a listing of the sales to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $sort_search = null;
        $orders = Order::orderBy('code', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }
        $orders = $orders->paginate(15);
        return view('sales.index', compact('orders', 'sort_search'));
    }

  

    public function order_index(Request $request)
    {
        if (Auth::user()->user_type == 'staff') {
            //$orders = Order::where('pickup_point_id', Auth::user()->staff->pick_up_point->id)->get();
            $orders = DB::table('orders')
                        ->orderBy('code', 'desc')
                        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->where('order_details.pickup_point_id', Auth::user()->staff->pick_up_point->id)
                        ->select('orders.id')
                        ->distinct()
                        ->paginate(15);

            return view('pickup_point.orders.index', compact('orders'));
        }
        else{
            //$orders = Order::where('shipping_type', 'Pick-up Point')->get();
            $orders = DB::table('orders')
                        ->orderBy('code', 'desc')
                        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->where('order_details.shipping_type', 'pickup_point')
                        ->select('orders.id')
                        ->distinct()
                        ->paginate(15);

            return view('pickup_point.orders.index', compact('orders'));
        }
    }

    public function pickup_point_order_sales_show($id)
    {
        if (Auth::user()->user_type == 'staff') {
            $order = Order::findOrFail(decrypt($id));
            return view('pickup_point.orders.show', compact('order'));
        }
        else{
            $order = Order::findOrFail(decrypt($id));
            return view('pickup_point.orders.show', compact('order'));
        }
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        return view('sales.show', compact('order'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ArInvoiceCreate()
    {
        return view('receivables.ARinvoice_header_workbench');
    }

    public function ArInvoiceSearch()
    {
        return view('receivables.search_ARInvoice');
    }


    public function ArInvoiceSearchData(Request $request)
    {
        $keyword = $request->keyword;
        // return $keyword;
        $search_da=$this->searchInvoice($request->keyword);
      //  return json_encode($search_da);
        return view('receivables.search_ARInvoice',compact('search_da'));
    }

    public function searchInvoice($keyword)
	{
        //$query="select c.Name,c.Mobile1,a.Status,a.InvoiceNumber,a.Amount,a.InvoiceID,a.preorderid,a.store_id from `AR_Customers` c, `AR_Invoices_All` a where c.CustomerID=a.CustomerID and (c.Name like '%$keyword%' or c.Mobile1 like '%$keyword%' or c.Mobile2 like '%$keyword%' or a.InvoiceNumber like '%$keyword%') order by a.`InvoiceID` DESC LIMIT $offset , 10";
        $data = DB::table('orders')
        ->select('orders.id as id', 'users.name as name', 'users.email as email', 'users.phone as phone', 'users.id as userid', 'orders.invoice_number as invoice_number', 'orders.ordersource as ordersource','orders.grand_total as grand_total', 'orders.payment_status as payment_status')
        ->join('users as users', 'users.id', '=', 'orders.user_id', 'left')
        // ->where('orders.ordersource','ERP')
        // ->where('invoice_number', 'like', '%' . substr($keyword,-4) . '%')
        
         ->orWhere(function($query) use ($keyword)
            {
                $query->orWhere('users.name', 'like', '%' . $keyword . '%')
                ->where('orders.ordersource','ERP');
            })
            ->orWhere(function($query) use ($keyword)
            {
                $query->orWhere('users.phone', 'like', '%' . $keyword . '%')
                ->where('orders.ordersource','ERP');
            })
            ->orWhere(function($query) use ($keyword)
            {
                $query->orWhere('users.email', 'like', '%' . $keyword . '%')
                ->where('orders.ordersource','ERP');
            })
            ->orWhere(function($query) use ($keyword)
            {
                $query->orWhere('invoice_number', 'like', '%' . substr($keyword,-4) . '%')
                ->where('orders.ordersource','ERP');
            })
        ->orderBy('orders.id', 'desc')
        ->get();

        // $items = [];
        
        // foreach($data as $data_item)
        // {
        //     $item = [
        //         "InvoiceNumber" =>  $data_item->invoice_number,
        //         "Name"      =>  $data_item->name,
        //         "Mobile1"   =>  $data_item->phone,
        //         "Amount"    =>  $data_item->grand_total,
        //         "Status"    =>  $data_item->payment_status,
        //         "orderid" =>$data_item->id,
        //         "ordersource" =>$data_item->ordersource
        //     ];

        //     array_push($items, $item);
        // }

        return $data;
	}
	

    public function ArInvoiceCancel(Request $request)
    {
        $data = Order::orderBy('orders.id', 'desc')->where('ordersource','ERP')->get();
        return view('receivables.cancel_ARInvoice')->with('data', $data);
    }
    public function ArInvoiceCanceldata(Request $request)
    {  
         // return "hi";
        if(isset($request->search))
        {
            $data = DB::table('orders')
            ->select('orders.id as id','orders.ordersource as source', 'users.name as name', 'users.phone as phone', 'users.id as userid', 'orders.invoice_number as invoice_number', 'orders.grand_total as grand_total', 'orders.payment_status as payment_status')
            ->join('users as users', 'users.id', '=', 'orders.user_id', 'left')
            // ->where('orders.invoice_number',  $request->invoicenum)
            // ->where('users.phone',  "+91".$request->phone)
            // ->orwhereBetween('orders.invoicedate', [$request->startdate,$request->enddate])
            ->when($request->startdate, function($query) use ($request){
                $query->orwhereBetween('orders.invoicedate', [$request->startdate,$request->enddate])
                ->where('orders.ordersource','ERP');
            })
            ->when($request->phone, function($query) use ($request){
                $query->where('users.phone', 'like', '%' . "+91".$request->phone. '%')
                ->where('orders.ordersource','ERP');
            })
            ->when($request->invoicenum, function($query) use ($request){
                $query->orWhere('orders.invoice_number', 'like', '%' . substr($request->invoicenum,-4) . '%')
                ->where('orders.ordersource','ERP');
            })
            ->orderBy('orders.id', 'desc')
            ->get();
         }
        if(isset($request->clear)){
            $data = Order::orderBy('orders.id', 'desc')->get();   
        }

        return view('receivables.cancel_ARInvoice')->with('data', $data);
    }

    public function ArInvoiceView($order_id)
    {
        $order = Order::where('id',$order_id)->first();
        //print_r($order); die();
        return view('receivables.ARinvoice_header_workbench_view',compact('order'))->with('msg', null);
    }
    public function CustomerForPrebooking(Request $request)
    {
        $phone = $request->term;
        $customers = User::where('user_type','customer')->Where('phone', 'like', '%' . $phone . '%')->get();
        $item = [];
        foreach($customers as $customer){
            $data = array('phone' => $customer->phone,
            'email' => $customer->email, 
            'name' => $customer->name,
            'father_name' => $customer->father_name,
            'tehsil' => $customer->tehsil,
            'address' => $customer->address,
            'district' => $customer->district,
            'landmark' => $customer->landmark,
            'postalcode' => $customer->postal_code,
            'instituteId' => $customer->institute_id,
            'categoryId' => $customer->category_id,
            'state' => $customer->state,
            'gstin' => $customer->gstin,
            'cutomer_id' => $customer->id,
            'user_type' => $customer->user_type
       );
         array_push($item,$data);
        }
       
    return json_encode($item);
       
    }



    public function ArInvoiceStore(Request $request)
    {
        // die('ghgfhfg');
        // return json_encode($request->all()); 
        
        $custId = $request->customer_id;
        $user = Customer::where('id',$custId)->first();
        $inst_id = $request->institutes;
      
        $cat_id = $request->category;
        $state = $request->state;
        
        $gstin = $request->gstin;
        if($state =='')
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



        // return $cat_id;

        if($custId != '')
        {
           $user = User::where('id',$custId)->where('email_verified_at', '!=' , Null)->update([
               'name' => $request->name,
               'email' => $request->email,
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
               'landmark' => $request->landmark,
               'user_type' => $request->user_type
               

           ]);
           $order = new Order;
           $order->user_id = $custId;
           $order->code = date('Ymd-His').rand(10,99);
            $order->date = strtotime('now');
            $order->invoicelookuptype = $request->type;
            if($request->description == '')
            {
                $request->description = 'Null';
            }
            $order->description = $request->description;
            $order->ordersource = 'ERP';
             // Get the last order id
             $data = [];
             $qry = Order::select('id','invoice_number as num')->where('invoice_number',  'not like', '%' . 'web' . '%')->where('invoice_number',  'not like', '%' . 'TRI' . '%')->orderBy('id','desc')->limit(1)->get();
            
             foreach($qry as $da)
             {
                 $item = [
                     "num" =>  $da->num,
                 ];
                 array_push($data, $item);
             }
            
          
             if( filter_var($data[0]['num'], FILTER_SANITIZE_NUMBER_INT)=='0') $next_invoice_number =  10000;  // initialise first invoice number
             else $next_invoice_number=  filter_var($data[0]['num'], FILTER_SANITIZE_NUMBER_INT)+1;
             
            $order->invoice_number =  $next_invoice_number ;
            $order->invoicedate = date('Y-m-d');
            if($order->save()){

                $data= array('status'=>'olduser', 'order_id' => $order->id, 'cust_id' => $custId, 'order_code'=> $order->code, 'description' => $request->description, 'newInvcNum' =>'ERP'.$next_invoice_number);
                return json_encode($data);
            }
        }   
        else
        {

            $mobile1 = $request->phone;
            if(strlen($mobile1)!=10)
            {
                $msg="Mobile Number should be of 10 digits !!";
                // return redirect('/admin/ARinvoice_header_workbench/create')->with('msg',$msg);
                $data= array('phone'=>$request->phone, 'status'=>'emailmsg','msg'=> "Mobile Number should be of 10 digits !!");
                return json_encode($data);
            }
           
             $duplicate= User::where('phone', $mobile1)->first();
       
            if($duplicate)
           {
               
                $msg="Mobile Number Duplicate with chosen Category !!";
              //  return redirect('/admin/ARinvoice_header_workbench/create')->with('msg',$msg);
                $data= array('phone'=>$request->phone, 'status'=>'emailmsg','msg'=> "Mobile Number Duplicate with chosen Category !!");
                return json_encode($data);
               
             }
             if($msg=="")
            {
                $supplier_check= ApSuppliers::where('mobile1',$mobile1)->first();
                if($supplier_check == '' )
                {

                // enter into ap_supplier as old book supplier, hard code C as customer supplier
                $supplier_insert= ApSuppliers::insertGetId([
                    'supplierid' => Null,
                    'name' => $request->name,
                    'type' => 'C',
                    'mobile1' => $mobile1,
                    'mobile2' => null,
                    'email1' => $request->email,
                    'email2' => null,
                    'address1' => $request->address,
                    'address2' => null,
                    'city' => $request->city,
                    'zipcode' => $request->postal_code,
                    'state' => $state,
                    'bankname' => null,
                    'ifsc' => null,
                    'bankaccountname' => null,
                    'accountnumber' => null,
                    'description' => null,
                    'isdeleted' => 'N',
                    'lastupdatedby' => Auth::user(),
                    'lastupdatedate' => NOW()
        
        
                ]);
        
               

                }
            
       
            
            $user = User::insert([
                'user_type' => 'customer',
                'name' => $request->name,
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
                'landmark' => $request->landmark,
                'user_type' => $request->user_type
            ]);
           $user = User::where('phone','+91'.$request->phone)->where('email',$request->email)->first();
            //print_r($user); die();
            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated){
                $otpController = new OTPVerificationController;
                $otpController->send_code($user);
                $data= array('email'=>$user->email, 'phone' => $user->phone, 'invoiceType'=>$request->type, 'cust_id' => $custId, 'description' => $request->description, 'status'=>'newuser');
                return json_encode($data);
            }
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
                $order = new Order;
                if(Auth::check()){
                    $order->user_id = Auth::user()->id;
                }
                $order->code = date('Ymd-His').rand(10,99);
                $order->date = strtotime('now');
                $order->invoicelookuptype = $request->invoice;
                if($request->description == '')
                {
                    $request->description = 'Null';
                }
                $order->description = $request->description;
                $order->ordersource = 'ERP';
                  // Get the last order id
                  $data = [];
                  $qry = Order::select('id','invoice_number as num')->where('invoice_number',  'not like', '%' . 'web' . '%')->where('invoice_number',  'not like', '%' . 'TRI' . '%')->orderBy('id','desc')->limit(1)->get();
                 
                  foreach($qry as $da)
                  {
                      $item = [
                          "num" =>  $da->num,
                      ];
                      array_push($data, $item);
                  }
                 
               
                  if( filter_var($data[0]['num'], FILTER_SANITIZE_NUMBER_INT)=='0') $next_invoice_number =  10000;  // initialise first invoice number
                  else $next_invoice_number=  filter_var($data[0]['num'], FILTER_SANITIZE_NUMBER_INT)+1;
                  
                $order->invoice_number =  $next_invoice_number ;
                if($order->save()){
                    $data= array('status'=>'true', 'newInvcNum' => $next_invoice_number, 'user_id' =>$insertedId, 'order_id' => $order->id, 'cust_id'=>$insertedId, 'order_code'=> $order->code, 'description' => $request->description);
                return json_encode($data);
                }
            }
            else{
                $data=array('status'=>'false', 'user_id'=>'', 'order_id' => '', 'order_code'=>'', 'description' => '');
                return json_encode($data);
            }
        }
        else
        {
            return 'User not exists';
        }
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
          /*
          if($product->variant_product){
              foreach ($product->stocks as $key => $stock) {
                  $qty = $stock->qty;
              }
          }
          */
          if($onrent=='yes')
          {
              $rent = 'R';
          }
          else
          {
              $rent = 'S';
          }
          
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
                    $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /><input type="text" name="rentamt" value="'.$product->rentamount.'" required="required" /></td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" />  , Qty available : '.$qty .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td><td><label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
                    echo $output; 
                }else{
                    echo $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /><input type="text" name="rentamt" value="'.$product->rentamount.'" required="required" /></td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" />  , Qty available : '.$qty .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td></tr>';
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
         /*  
       if($product == ''){return 'false';}
       
       $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;
        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if($lowest_price > $stock->price){
                    $lowest_price = $stock->price;
                }
                if($highest_price < $stock->price){
                    $highest_price = $stock->price;
                }
            }
        }
        if($product->discount_type == 'percent'){
            $lowest_price -= ($lowest_price*$product->discount)/100;
            $highest_price -= ($highest_price*$product->discount)/100;
        }
        elseif($product->discount_type == 'amount'){
            $lowest_price -= $product->discount;
            $highest_price -= $product->discount;
        }
        if($product->tax_type == 'percent'){
            $lowest_price += ($lowest_price*$product->tax)/100;
            $highest_price += ($highest_price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $lowest_price += $product->tax;
            $highest_price += $product->tax;
        }
        $lowest_price = $lowest_price;
        $highest_price = $highest_price;

        if($lowest_price == $highest_price){
            $sellingPrice = $lowest_price;
        }
        else{
            $sellingPrice = $lowest_price.' - '.$highest_price;
        }
            if(!empty($keyword) and $trxn=='S') 
            {
                $srent = 'S';
                if($vart =='null')
                {
                   
                   $qty_a = $qty; 
                   $isbn = $product->isbn;
                   $mrp = $product->mrp;
                  // $sellingPrice = home_discounted_price($product->id);
                   $discount = $sellingPrice-$product->unit_price;
                }
                else
                {
                    
                    $vartint = \App\ProductStock::where('id',$request->vart)->first();
                    $qty_a = $qty;
                    $mrp = $vartint->mrp;
                  //  $sellingPrice = $vartint->price;
                    $isbn = $vartint->isbn;
                    $discount = $sellingPrice-$vartint->price;
                }
               
                if($invoice_type == 'C'){
                $output='<tr class="brent"><td>Selling Price*</td><td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="SellingPrice" value="'.$sellingPrice.'" required="required" /></td><td>,Book: '.$product->name.'</td></tr><tr class="bmrp"><td>MRP:</td><td><input type="text" name="mrp" value="'.$mrp.'" /></td><td>, Sale/Rent : '.$srent.'<input type="hidden" name="sale_rent" value="'.$srent.'" /> , Qty available : '.$qty_a.'<span>('.$qty_info.')</span><input type="hidden" name="qty_a" value="'.$qty_a.'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> <input type = "hidden" name="vm_id" value="" />,<label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
                echo $output; 
                }else{
                $output='<tr class="brent"><td>Selling Price*</td><td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="SellingPrice" value="'.$sellingPrice.'" required="required" /></td><td>,Book: '.$product->name.'</td></tr><tr class="bmrp"><td>MRP:</td><td><input type="text" name="mrp" value="'.$mrp.'" /></td><td>, Sale/Rent : '.$srent.'<input type="hidden" name="sale_rent" value="'.$srent.'" /> , Qty available : '.$qty_a.'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty_a.'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> <input type = "hidden" name="vm_id" value="" /></td></tr>';	
                echo $output;
                }

            }
            if(!empty($keyword) and $trxn=='R')
            {   
                $srent = 'R';
                $book_data2=$this->bookDetailInvoice($keyword,$trxn);
                $rent=isset($book_data2->rentamount)? $book_data2->rentamount :0.00; // 35 % rent for new
                // check security 75% for old book
                $security=isset($book_data2->securityamount)?$book_data2->securityamount:$book_data2->mrp;
              
                if($vart =='null')
                {
                    
                   $qty_a = $qty; 
                   $isbn = $book_data2->isbn;
                   $mrp = $book_data2->mrp;
                   $sellingPrice = $sellingPrice;
                   $discount = $mrp-$sellingPrice;
                }
                else
                {
                   
                   $vartint = \App\ProductStock::where('id',$request->vart)->first();
                   return json_encode($vartint->qty);die();
                    $qty_a = $qty;
                    $mrp = $vartint->mrp;
                    $sellingPrice = $sellingPrice;
                    $isbn = $vartint->isbn;
                    $discount = $mrp-$sellingPrice;
                }
                //
                //$output='<tr><td>Rent*<td><input type="text" name="rent" value="'.$rent.'" required="required" /></td>,Book: '.$book_data2[0]['Name'].'<td></td></tr><tr><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$book_data2[0]['SaleRent'].'<input type="hidden" name="sale_rent" value="'.$book_data2[0]['SaleRent'].'" />  , Qty available : '.$book_data2[0]['Quantity'].'<input type="hidden" name="qty_a" value="'.$book_data2[0]['Quantity'].'" /></td></tr>';
                if($invoice_type == 'C'){
                $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /></td>,Book: '.$book_data2->name.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$srent.'<input type="hidden" name="sale_rent" value="'.$srent.'" />  , Qty available : '.$qty_a .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty_a .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td><td><label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
                echo $output; 
                }else{
                $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /></td>,Book: '.$book_data2->name.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$srent.'<input type="hidden" name="sale_rent" value="'.$srent.'" />  , Qty available : '.$qty_a .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty_a .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td></tr>';
                echo $output;	 
                }

            }
*/
        }

        public function ProductSave(request $request)
        { 
            $idorder = $request->orderId;
            $invn = $request->invoice_number;

           //return json_encode($request->all());
                $msg="";
                $prepay=$request->prepay;
                $sale_rent=$request->sale_rent; // from ajax
                
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
                    return redirect('/admin/ARinvoice_header_workbench/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                }
                $order = Order::where('id',$request->orderId)->first();
                //return json_encode($order);
                // books for rent can also be sold , but books for sale cannot be leased
                if($transaction_type=="S")
                {
                  //  die('hgfhfhf');
                //$transaction_type=$sale_rent;
                    $item_price=$itemprice;  // selling price
                    list($igst,$sgst) = Product::select('igst','sgst')->where('id', $request->keyword)->first();
                
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
                                return redirect('/admin/ARinvoice_header_workbench/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                            }
                    }
                    $amount = $mrp; // MRP
                    // return $amount;
                    //if(isset($_POST['security'])) {$amount=$_POST['security']; $item_price=$_POST['security'];}
                     if($amount <=0 and $prepay!="-1")  
                     {
                        $msg="Choose Sale/Rent Amount is 0.";
                        // return $msg;
                        return redirect('/admin/ARinvoice_header_workbench/view/'.$idorder.'/'.$invn)->with('msg', $msg);
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
                    
                   $custid = $order->user_id; 
                    $orderid = $order->id;
                    $invoice_number = $order->invoice_number;
                /*
                    $update_discount = Product::where('id', $item_id)->where('user_id',$custid)->update([
                        'discount' => $special_discount
                    ]);
                    */
                    // print_r($variation); die();
                $productUser = Product::where('id',$item_id)->first();
                $insert_line=OrderDetail::insert([
                    'order_id' =>$orderid ,
                    'transactiontype' => $transaction_type,
                    'product_id' => $item_id,
                    'variation' => $variation,
                    'price' => $item_price,
                    'discount' => $discount,
                    'baseprice' => $baseprice,
                    'securuty' =>$security,
                    'amount' =>$amount,
                    'rentduedate' => $due_date,
                    'description'=> $description,
                    'seller_id' =>$productUser->user_id,
                    'quantity' => $quantity
              
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
                    return redirect('/admin/ARinvoice_header_workbench/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                    
                }
                else {
                    $msg="Failed";
                    return redirect('/admin/ARinvoice_header_workbench/view/'.$idorder.'/'.$invn)->with('msg', $msg);
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
                 $item_price = $rent;
                    list($igst,$sgst) = Product::select('igst','sgst')->where('id', $request->keyword)->first();
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
                    $order = Order::where('id',$request->orderId)->first();
                        if($order->invoicelookuptype == 'C'){
                        $discount = ($amount*$special_discount/100);
                        $transaction_type = 'S';
                        
                        }else{
                            if($sale_rent=="no" && $transaction_type=="R")
                            {
                                $msg="Book on sale cannot be given on rent";
                                return redirect('/admin/ARinvoice_header_workbench/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                            }
                            $discount=0;	
                        }
                    
                        if($amount<=0) 
                        {
                            $msg="Choose Sale/Rent Amount is 0";
                            return redirect('/admin/ARinvoice_header_workbench/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                        }
                        //print_r($_REQUEST); die;
                    if($msg=="")
                    {
                        
                    $custid = $order->user_id;
                    $update_discount = Product::where('id', $item_id)->where('user_id',$custid)->update([
                        'discount' => $special_discount
                    ]);
                    
                  
                    }
                    $productUser = Product::where('id',$item_id)->first();
                    $insert_line=OrderDetail::insert([
                        'order_id' => $order->id,
                        'transactiontype' => $transaction_type,
                        'product_id' => $item_id,
                        'variation' => $variation,
                        'price' => $item_price,
                        'discount' => $discount,
                        'baseprice' => $baseprice,
                        'securuty' =>$security,
                        'amount' =>$amount,
                        'rentduedate' => $due_date,
                        'description'=> $description,
                        'seller_id' =>$productUser->user_id,
                        'quantity' => $quantity
                
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
                        return redirect('/admin/ARinvoice_header_workbench/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                       
                    
                }
                else 
                {
                 $msg="Failed";
                 return redirect('/admin/ARinvoice_header_workbench/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                }
            }
          
      
            
            // return $msg;
            
                
        }


        public function updateline(Request $request)
        {
           //return json_encode($request->all());
            $quantity = $request->quantity;
            $payline = $request->payline;
            $amount = $request->amount;
            $TransactionType = $request->TransactionType;
            $baseprice = $request->baseprice;
            $sr = $request->sr;
            $sgst = $request->sgst;
            $cgst = $request->cgst;
            $gst = $request->gst;
            $igst = $request->igst;
            $discount = $request->discount;
            //$quant = $request->quantity;
            $arr = array('code'=>0,'msg'=>'');

            $or_detail =OrderDetail::select('order_id','variation','product_id','transactiontype','quantity','price','securuty','amount' )->where('id',$request->id)->first();
            $OrderID = $or_detail->order_id;
            $item_id = $or_detail->product_id;
            $transtype = $or_detail->transactiontype;
            $line_quantity = $or_detail->quantity;
            $ItemPrice = $or_detail->price;
            $Security = $or_detail->securuty;
            $Amount = $or_detail->amount;
           $variation = $or_detail->variation; 

            if($variation != null && $variation != '' && $variation != "null"){
                $product_stock = \App\ProductStock::where('variant', $variation)->where('product_id',$or_detail->product_id)->first();
                //return json_encode($product_stock);  
                $avail_qty = $product_stock->qty ; 
                    //$avail_qty =  $product_qty+$qty;
                    // return json_encode($current_stock); 
                //          $qty_update = \App\ProductStock::where('product_id',$order_detail->product_id)->where('variant', $order_detail->variation)->update([
                //             'qty' => $current_stock
                // ]); 
            }
        
            
            $or_prod = Product::select('current_stock','onrent')->where('id',$item_id)->first();
            $avail_qty = $or_prod->current_stock;
            $SaleRent_1 = $or_prod->onrent;
            
            $invoice_id = $OrderID;

            $change_qty = ($quantity-$line_quantity); 

           $qty_inventory = $avail_qty - ($change_qty);
     //$qty_inventory = $avail_qty;

            if($TransactionType == 'S'){

                if($qty_inventory <= 0){
                    $arr['code'] = '0';
                    $arr['msg'] = 'Not available in stock. you can order maximum '.$avail_qty;
                    return json_encode($arr);
                   // exit;
                    }
                    $payline = $payline/$quantity;
                    $discountInPercent=0;
                    //TransactionType
                    if($ItemPrice != $payline || $Security != $sr || $Amount != $amount || $transtype != $TransactionType){
                        
                        $tax= 0;
                        if($igst != 0 and $igst != '' ){
                            $tax=$igst;
                            }else if($gst != 0 and $gst != '' ){
                            $tax=$gst;
                        }
                        $baseprice = $payline * 100/(100+$tax);
                                    
                        $discount = $amount-$payline;
                        $discountInPercent = round((($discount+$tax)/$amount)*100,2);
                        OrderDetail::where('id',$request->id)->update([
                                'transactiontype' => $TransactionType,
                                'quantity' => $quantity,
                                'price' => $payline,
                                'discount' => $discount,
                                'securuty' =>$sr,
                                'amount' =>$amount

                        ]);

                     }else{
                        OrderDetail::where('id',$request->id)->update([
                            'quantity' => $quantity
                    ]);
                }
                   // $inventory_book_update=$bookdal->updateBookQuantity($qty_inventory,$item_id); // update inventory = previous qty - line qty
                    $inventory_book_update= Product::where('id',$item_id)->update(['current_stock' => $qty_inventory]);
                   // updateqtytositeminus($vm_id,$change_qty,$item_id,'',$invoice_id);
                    $arr['code'] = '1';
                    $arr['msg'] = 'Updates successfully';
                    $arr['discount'] = $discountInPercent;
                    $arr['baseprice'] = $baseprice;
                    return json_encode($arr);
                    //exit;
            }

            if($TransactionType == 'R'){
                if($SaleRent_1 == 'no'){
                    $arr['code'] = '0';
                    $arr['msg'] = 'Book on Sale';
                    return json_encode($arr);
                   // exit;
                    }
                    $discountInPercent=0;
                    $payline = $payline/$quantity;
                    if($ItemPrice != $payline || $Security != $sr || $Amount != $amount || $transtype != $TransactionType){
                                    $tax= 0;
                        if($igst != 0 and $igst != '' ){
                            $tax=$igst;
                            }else if($gst != 0 and $gst != '' ){
                            $tax=$gst;
                        }
                        $baseprice = $payline * 100/(100+$tax);
                        
                        $discount = $amount-$payline;
                        $discountInPercent = round((($discount+$tax)/$amount)*100,2);
                        OrderDetail::where('id',$request->id)->update([
                            'transactiontype' => $TransactionType,
                            'quantity' => $quantity,
                            'price' => $payline,
                            'discount' => $discount,
                            'securuty' =>$sr,
                            'amount' =>$amount

                         ]);

                }else{
                        OrderDetail::where('id',$request->id)->update([
                        'quantity' => $quantity
                        ]);
                }
                
                    // $inventory_book_update=$bookdal->updateBookQuantity($qty_inventory,$item_id); // update inventory = previous qty - line qty
                    // updateqtytositeminus($vm_id,$change_qty,$item_id,'',$invoice_id);
                    $inventory_book_update= Product::where('id',$item_id)->update(['current_stock' => $qty_inventory]);
                    $arr['code'] = '1';
                    $arr['msg'] = 'Updates successfully';
                    $arr['discount'] = $discountInPercent;
                    $arr['baseprice'] = $baseprice;
                    return json_encode($arr);
                    //exit;
                
            }


            // $id = $request->id;
            // $update_order_detail = OrderDetail::where('id',$id)->update([
            //     "amount" => $request->price,
            //     "price" => $request->linerent,
			//     "baseprice" => $request->base_price,
			//     "quantity" => $request->qty,
			//     "transactiontype" => $request->type
			//     //"final_amount" => $request->final_amount
            // ]);
            // if($update_order_detail =='1')
            // {
            //     return "done";
            // }
        }
        public function destroyLine(Request $request)
        {
             // return json_encode($order_detail);    
            $id = $request->id;
            $order_detail = OrderDetail::findOrFail($id);
          
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

                $order_detail->delete();
                               return "done";
            }
            else{
               return "not done";
            }

        }
        public function detelecoupon(Request $request)
        {
            $order = Order::where('id',$request->id)->first();
            if($order_detail != null){
                $update_order = order::where('id', $request->order_id)->update([
                    'coupon_discount'=> '0.00',
                    'couponcode' => '',
                    'grand_total' => '0.00'
                    ]);
                $delete_coupon = \App\CouponUsage::findOrFail($order->user_id);
                if($delete_coupon != null){
                    $delete_coupon->delete();
                    return "done";
                }
            }
        }

        public function applycoupon(Request $request)
        {
           $val =$request->coupon;
           $coupon = \App\Coupon::where('code', $val)->first();
        if($val !='') {
           // echo $val." ". $coupon; die();
        if($coupon != null){
           // echo strtotime(date('d-m-Y'))." ".$coupon->start_date." ".strtotime(date('d-m-Y'))." ". $coupon->end_date;die();
            if(strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date){
               // echo $request->customerId; die();
                if(\App\CouponUsage::where('user_id', $request->customerId)->where('coupon_id', $coupon->id)->first() == null){
                    $coupon_details = json_decode($coupon->details);
                    if ($coupon->type == 'cart_base')
                    {
                        // $subtotal = 0;
                        // $tax = 0;
                        // $shipping = 0;
                        // foreach (Session::get('cart') as $key => $cartItem)
                        // {
                        //     $subtotal += $cartItem['price']*$cartItem['quantity'];
                        //     $tax += $cartItem['tax']*$cartItem['quantity'];
                        //     $shipping += $cartItem['shipping']*$cartItem['quantity'];
                        // }
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
                                'user_id' => $request->customerId,
                                'coupon_id'=> $coupon->id
                            ]);
                            if($insert_coupon)
                            {
                                $coupondis =sprintf("%.2f", $coupon_discount); 
                                $grandtotal = sprintf("%.2f", $sum - $coupon_discount);
                                $update_order = order::where('id', $request->order_id)->update([
                                    'coupon_discount'=> $coupondis,
                                    'couponcode' => $val,
                                    'grand_total' => $grandtotal
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
                    // elseif ($coupon->type == 'product_base')
                    // {
                    //     $coupon_discount = 0;
                    //     foreach (Session::get('cart') as $key => $cartItem){
                    //         foreach ($coupon_details as $key => $coupon_detail) {
                    //             if($coupon_detail->product_id == $cartItem['id']){
                    //                 if ($coupon->discount_type == 'percent') {
                    //                     $coupon_discount += $cartItem['price']*$coupon->discount/100;
                    //                 }
                    //                 elseif ($coupon->discount_type == 'amount') {
                    //                     $coupon_discount += $coupon->discount;
                    //                 }
                    //             }
                    //         }
                    //    }
                    //    $request->session()->put('coupon_id', $coupon->id);
                    //    $request->session()->put('coupon_discount', $coupon_discount);
                    //    $msg = "Coupon has Applied successfully On Product base";
                    //    return "3";
                    // }
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

        public function bookDetailInvoice($id,$type) // for ar invoice line work bench to get book details
        {
            $data = Product::where('id', $id)->first();
            
            //$data=array();
        return $data;	
        }

        public function getDescription(Request $request)
        {
            $orderid = $request->id;
                $desc = Order::where('id',$orderid)->update([
                    'description' => $request->desc
                ]);
                
               if($desc == '1')
               {
                $order = Order::where('id',$orderid)->first();
                //print_r($oder); die();
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

        public function ArInvoice($order_id)
        {
            $order = Order::where('id',$order_id)->first();

            // get book detail for displaying invoice line
            $invoice_line_data= OrderDetail::where('order_id',$order->id)->get()->toArray();

           // return json_encode($invoice_line_data);
            // customer detail
            $customer_detail= User::where('id',$order->user_id)->first();

            //print_r($order); die();
            return view('receivables.ARinvoice',compact('order','invoice_line_data', 'customer_detail'));
        }
        public function getInvoicePrint(Request $request)
        {
            $val = $request->_val;
            $orderid = $request->_orderid;
            
            if($val == '2' || $val == '4')
            {
                $status='paid';
                
            }
            else
            {
                $status='unpaid';
            }
            $orderupdate = Order::where('id',$orderid)->update(['payment_status'=> $status]);
             // get book detail for displaying invoice line
            $invoice_line_data= OrderDetail::where('order_id',$orderid)->get()->toArray();
            $order = Order::where('id',$orderid)->first();
            // return json_encode($invoice_line_data);
            // customer detail
            
            $customer_detail= User::where('id',$order->user_id)->first();

            if($val == '4' || $val == '3')
            {
                //stores the pdf for invoice
                $pdf = PDF::setOptions([
                    'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                    'logOutputFile' => storage_path('logs/log.htm'),
                    'tempDir' => storage_path('logs/')
                ])->loadView('receivables.ARinvoice', compact('order','invoice_line_data','customer_detail'));
                $output = $pdf->output();
                file_put_contents('public/invoices/'.'Order-'.$order->code.'.pdf', $output);
           
                if($val == '4')
                {
                    
                    // $pdf = PDF::loadView('receivables.ARinvoice', compact('order','invoice_line_data','customer_detail'));
                    // return $pdf->download('Order#'.$order->code.'.pdf');
                    return asset('invoices/Order-'.$order->code.'.pdf');
                }
                else
                {
                    $array['view'] = 'emails.invoice';
                    $array['subject'] = 'Order Placed - '.$order->code;
                    $array['from'] = env('MAIL_USERNAME');
                    $array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
                    $array['file'] = 'public/invoices/Order-'.$order->code.'.pdf';
                    $array['file_name'] = 'Order-'.$order->code.'.pdf';
                    if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_order')->first()->value){
                        try {
                            $otpController = new OTPVerificationController;
                            $otpController->send_order_code($order);
                        } catch (\Exception $e) {
        
                        }
                    }
                    //sends email to customer with the invoice pdf attached
                    if(env('MAIL_USERNAME') != null){
                        try {
                            Mail::to($customer_detail->email)->queue(new InvoiceEmailManager($array));
                            Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                            return "sent";
                        } catch (\Exception $e) {
                            return $e;
                        }
                    }
                }
            }
            return 'done';
        }

        public function getInvoiceEmailPre(Request $request)
        {
            $orderId = $request->id;
            $order = Order::where('id', $orderId )->first();
            $customer_detail= User::where('id',$order->user_id)->first();
            // validate supplier and institute should have payment method
            if($request->ModeOfPayment=='')
            {
            $msg="<div class='error'>Mode of Payment mandatory for Institutes and Supplier </div>";
            }
            else{
            //
            if($request->action == '1')
            {
                $update_payment = Order::where('id',$orderId)->update([
                    'payment_type' => $request->modepayment,
                    'description' => $request->desc,
                    'paydate' => $request->paydate,
                    'payment_status' => 'paid',
                    'grand_total' => $request->payment
                ]);
              
            if($request->crins == 'Y'){
                $sql = Prepaidcredit::insert([
                    'prebookingid' => $orderId,
                    'credit' => $request->credit,
                    'updateddate' => date('Y-m-d H:i:s')
                ]);
      
           
            }else{
                $sql = Prepaidcredit::where('prebookingid',$orderId)->update([
                     'credit' => $request->credit,
                    'updateddate' => date('Y-m-d H:i:s')
                ]);
           
            }
           return 'true';
            }
            else  if($request->action == '2'){
            if($request->paydate == ''){$request->paydate = date('Y-m-d');}
            $update_payment = Order::where('id',$orderId)->update([
                'payment_type' => $request->modepayment,
                'description' => $request->desc,
                'paydate' => $request->paydate,
                'grand_total' => $request->payment
            ]);
                return 'true';
            }
            else
            {
            //close and print invoice
            $update_payment = Order::where('id',$orderId)->update([
                'payment_type' => $request->modepayment,
                'description' => $request->desc,
                'paydate' => $request->paydate,
                'payment_status' => 'paid',
                'grand_total' => $request->payment
            ]);
            if($request->crins == 'Y'){
                $sql = Prepaidcredit::insert([
                    'prebookingid' => $orderId,
                    'credit' => $request->credit,
                    'updateddate' => date('Y-m-d H:i:s')
                ]);
      
           
            }else{
                $sql = Prepaidcredit::where('prebookingid',$orderId)->update([
                     'credit' => $request->credit,
                    'updateddate' => date('Y-m-d H:i:s')
                ]);
           
            }
            
             //sends email to customer with the invoice pdf attached
             if(env('MAIL_USERNAME') != null){
                try {
                    Mail::to($customer_detail->email)->queue(new InvoiceEmailManager($array));
                    Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {

                }
            }
           return 'true';
            }
            //header("Location: ARinvoice.php?id=".$invoice_number);
            //$url_invoice="ARinvoice.php?id=".$invoice_number;
            //$action_url="<script>window.open('".$url_invoice."');</script>";
            //header("Location: ARinvoice.php?id=".$invoice_number);
            } // validate supplier and institute 

        }

        public function getInvoiceEmail(Request $request)
        {
            //return json_encode($request->all());
            $orderId = $request->id;
            $status = $request->status;
            if($status == '')
            {
                $st = "paid";
            }
            else
            {
                $st = $status;
            }
            $order = Order::findOrFail($orderId);
            $update_payment = Order::where('id',$orderId)->update([
                'payment_type' => $request->modepayment,
                'description' => $request->desc,
                'paydate' => $request->paydate,
                'payment_status' => $st,
                'grand_total' => $request->payment
            ]);
            // get book detail for displaying invoice line
            $invoice_line_data= OrderDetail::where('order_id',$order->id)->get()->toArray();

           // return json_encode($invoice_line_data);
            // customer detail
            $customer_detail= User::where('id',$order->user_id)->first();
          //  print_r($update_payment); die();
            $custId = $order->user_id;
             //stores the pdf for invoice
             $pdf = PDF::setOptions([
                'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                'logOutputFile' => storage_path('logs/log.htm'),
                'tempDir' => storage_path('logs/')
            ])->loadView('receivables.ARinvoice', compact('order','invoice_line_data','customer_detail'));
            $output = $pdf->output();
            file_put_contents('public/invoices/'.'Order-'.$order->code.'.pdf', $output);
            $array['view'] = 'emails.invoice';
            $array['subject'] = 'Order Placed - '.$order->code;
            $array['from'] = env('MAIL_USERNAME');
            $array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
            $array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
            $array['file_name'] = 'Order-'.$order->code.'.pdf';
           if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_order')->first()->value){
                try {
                    $otpController = new OTPVerificationController;
                    $otpController->send_order_code($order);
                } catch (\Exception $e) {

                }
            }
            //sends email to customer with the invoice pdf attached
            if(env('MAIL_USERNAME') != null){
                try {
                    Mail::to($customer_detail->email)->queue(new InvoiceEmailManager($array));
                    Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {

                }
            }
            //unlink($array['file']);
            return '1';

            // if($custId != '')
            // {
            //     $user = User::where('id',$custId)->first();
               
            //    $email_to = array(
            //        'email' => $user->email,
            //        'name' => $user->name,
            //        'subject' =>'Email Send',
            //        'message' => 'Email Send on your Email:'.$user->email
            //    );
            //   // print_r( $email_to); die();
            //    Mail::send([],[],function($message) use ($email_to)
            //    {
            //        $message->to($email_to['email'],$email_to['name'])
            //                 ->subject($email_to['subject'])
            //                 ->setBody($email_to['message']);
            //    });
            //     return '1';
            // }
            // else
            // {
            //         return 'false';
            // }
            
            
        }

        public function statusChangeOrder(Request $request)
        {
            //return json_encode($request);
                    $orderid = $request->orderid;
                    $status = $request->status;

                    $changeStatus = Order::where('id',$orderid)->update([
                        'payment_status' => $status
                    ]);
                    if($changeStatus)
                    {
                        return '1';
                    }
        }
        public function statuschangeinvoice(Request $request)
        {
            //return json_encode($request);
                    $orderid = $request->orderid;
                    $status = $request->status;

                    $changeStatus = Order::where('id',$orderid)->update([
                        'payment_status' => $status
                    ]);
                    if($changeStatus)
                    {
                        return '1';
                    }
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $order = new Order;
        if(Auth::check()){
            $order->user_id = Auth::user()->id;
        }
        else{
            //$order->user_id = :user()Auth:->id;
            $order->guest_id = mt_rand(100000, 999999);

            $order->user_id = Session::get('shipping_info')['user_id'];
        }
        //print_r($_REQUEST);
//dd($request->session()->all());die;
        $order->shipping_address = json_encode($request->session()->get('shipping_info'));

        $order->payment_type = $request->payment_option;
        $order->delivery_viewed = '0';
        $order->payment_status_viewed = '0';
        $order->code = date('Ymd-His').rand(10,99);
        $order->date = strtotime('now');
        $order->track_id = date('Ymd').rand(10,99);
        if($order->save()){
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $admin_products = array();
            $seller_products = array();

            //Calculate Shipping Cost
            if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') {
                $shipping = \App\BusinessSetting::where('type', 'flat_rate_shipping_cost')->first()->value;
            }
            elseif (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') {
                foreach (Session::get('cart') as $key => $cartItem) {
                    $product = \App\Product::find($cartItem['id']);
                    if($product->added_by == 'admin'){
                        array_push($admin_products, $cartItem['id']);
                    }
                    else{
                        $product_ids = array();
                        if(array_key_exists($product->user_id, $seller_products)){
                            $product_ids = $seller_products[$product->user_id];
                        }
                        array_push($product_ids, $cartItem['id']);
                        $seller_products[$product->user_id] = $product_ids;
                    }
                }
                if(!empty($admin_products)){
                    $shipping = \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value;
                }
                if(!empty($seller_products)){
                    foreach ($seller_products as $key => $seller_product) {
                        $shipping += \App\Shop::where('user_id', $key)->first()->shipping_cost;
                    }
                }
            }
            $shippingcost = $request->session()->get('shipping_price');
            $codprice = 0;
            if($request->payment_option == 'cash_on_delivery'){
			$codprice =	$request->session()->get('codprice');
				}
            //End Shipping Cost Calculation

            //Order Details Storing
            $productshiping = 0;
            foreach (Session::get('cart') as $key => $cartItem){
                $product = Product::find($cartItem['id']);

                $subtotal += $cartItem['price']*$cartItem['quantity'];
                $tax += $cartItem['tax']*$cartItem['quantity'];

                $product_variation = $cartItem['variant'];

                if($product_variation != null){
                    $product_stock = $product->stocks->where('variant', $product_variation)->first();
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();
                }
                else {
                    $product->current_stock -= $cartItem['quantity'];
                    $product->save();
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id  =$order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
                $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];

                //Dividing Shipping Costs
                if ($cartItem['shipping_type'] == 'home_delivery') {
                    if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') {
                        $order_detail->shipping_cost = $shipping/count(Session::get('cart'));
                        $productshiping = $productshiping+$shipping/count(Session::get('cart'));
                    }
                    elseif (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') {
                        if($product->added_by == 'admin'){
                            $order_detail->shipping_cost = \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value/count($admin_products);
                            $productshiping = $productshiping+$order_detail->shipping_cost;
                            $shipping += \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value;
                        }
                        else {
                            $order_detail->shipping_cost = \App\Shop::where('user_id', $product->user_id)->first()->shipping_cost/count($seller_products[$product->user_id]);
                            $shipping += \App\Shop::where('user_id', $product->user_id)->first()->shipping_cost;
                            $productshiping = $productshiping+$order_detail->shipping_cost;
                        }
                    }
                    else{
                        $order_detail->shipping_cost = \App\Product::find($cartItem['id'])->shipping_cost;
                        $shipping += \App\Product::find($cartItem['id'])->shipping_cost*$cartItem['quantity'];
                        $productshiping = $productshiping+$order_detail->shipping_cost;
                    }
                }
                else{
                    $order_detail->shipping_cost = 0;
                    $order_detail->pickup_point_id = $cartItem['pickup_point'];
                }
                //End of storing shipping cost

                $order_detail->quantity = $cartItem['quantity'];
                $order_detail->save();

                $product->num_of_sale++;
                $product->save();
            }
			//echo $subtotal .'---'. $tax .'---'. $shippingcost .'---'. $codprice;
            $order->grand_total = $subtotal + $tax + $shippingcost + $productshiping + $codprice;
            
            $order->codprice = $codprice;
            $order->shippingprice = $shippingcost+$productshiping;
            $order->pincodeshippingprice = $shippingcost-$productshiping;

            if(Session::has('coupon_discount')){
                $order->grand_total -= Session::get('coupon_discount');
                $order->coupon_discount = Session::get('coupon_discount');

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Session::get('coupon_id');
                $coupon_usage->save();
            }
			
            $order->save();

            //stores the pdf for invoice
            $pdf = PDF::setOptions([
                            'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                            'logOutputFile' => storage_path('logs/log.htm'),
                            'tempDir' => storage_path('logs/')
                        ])->loadView('invoices.customer_invoice', compact('order'));
            $output = $pdf->output();
    		file_put_contents('public/invoices/'.'Order#'.$order->code.'.pdf', $output);

            $array['view'] = 'emails.invoice';
            $array['subject'] = 'Order Placed - '.$order->code;
            $array['from'] = env('MAIL_USERNAME');
            $array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
            $array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
            $array['file_name'] = 'Order#'.$order->code.'.pdf';

            foreach($seller_products as $key => $seller_product){
                try {
                    Mail::to(\App\User::find($key)->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {

                }
            }

            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_order')->first()->value){
                try {
                    $otpController = new OTPVerificationController;
                    $otpController->send_order_code($order);
                } catch (\Exception $e) {

                }
            }

            //sends email to customer with the invoice pdf attached
            if(env('MAIL_USERNAME') != null){
                try {
                    Mail::to($request->session()->get('shipping_info')['email'])->queue(new InvoiceEmailManager($array));
                    Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {

                }
            }
            unlink($array['file']);

            $request->session()->put('order_id', $order->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }



    public function updateTracking(Request $request)
    {
        $products = $request->productd;
        // return json_encode($products[0]);
        $trackings = $request->tracking_id;
        $couriers = $request->courier;

        $items = [];

        foreach($products as $key => $product)
        {
            //echo $key; 
            //die();
            $item = [
                "p_id"  =>  $products[$key],
                "t_id"  =>  $trackings[$key],
                "c_id"  =>  $couriers[$key]
            ];

            array_push($items, $item);
        }

       

        foreach($items as $item)
        {
            //return json_encode($item['c_id']);
            $productupd = OrderDetail::where('product_id', $item['p_id'])
                ->where('order_id', $request->ordid)->update([
                'courier_link'  =>  $item['c_id'],
                'track_id'      =>  $item['t_id']
            ]);

            

        }
        
        
        $orderid = $request->ordid;
       
        
        $descupd = Order::where('id',$orderid)->update([
             'description' => $request->description 
             ]);
        $order = Order::where('id',$orderid)->first();
        $product = OrderDetail::where('order_id',$orderid)->get();
        foreach($product as $pro)
                {
                    $productdet = \App\Product::where('id',$pro->product_id)->first();
                   
                }
        //print_r($oder); die();
        $userid = $order->user_id;

        if($userid != '')
        {
            $user = User::where('id', $userid)->first();

          $emails_to = array(
            'email' => $user->email,
            'name' => $user->name,
            'subject' => 'Tracking ID',
            'message' => 'Your Tracking ID :' .$productdet->track_id
            );

            // Mail::send([], [], function($message) use ($emails_to)
            // {
            // $message->to($emails_to['email'], $emails_to['name'])
            // ->subject($emails_to['subject'])
            // ->setBody($emails_to['message'], 'text/html');
            // });
            
            sendSMS($user->phone, env('APP_NAME'), 'your tracking ID'. $productdet->track_id.env('APP_NAME'));
            return 'true';
        }
      // return 'true';
    }


    public function gettrackdata(Request $request)
    {
        $orderid = $request->val;
        $code = $request->code;
        $product = OrderDetail::where('order_id',$orderid)->groupBy('product_id')->get();
      
         if($product)
         {
            $items = [];
            $coritems = [];
            foreach($product as $pro)
                {
                   
                   // $courier = $pro->courier_link;
                    $productdet = \App\Product::where('id',$pro->product_id)->first();
                    $data = ['product' => $productdet->name,
                    'orderid' => $orderid ,
                    'productid' => $productdet->id,
                    'track' => $pro->track_id,
                    'courier' => $pro->courier_link
                    ];
                    array_push($items,$data);
                } 
            
       
            }
          $courier = \App\Courier::get();
          foreach($courier as $courierdata)
                {
                    $data = ['courier_name' => $courierdata->courier_name,
                    'link' => $courierdata->link,
                    'description' => $courierdata->description 
                    ];
                    array_push($coritems,$data);
                }
         
          $retdata = array('prodata' => $items, 'cordata' =>$coritems );
        return json_encode($retdata);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if($order != null){
            foreach($order->orderDetails as $key => $orderDetail){
                $orderDetail->delete();
            }
            $order->delete();
            flash('Order has been deleted successfully')->success();
        }
        else{
            flash('Something went wrong')->error();
        }
        return back();
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        //$order->viewed = 1;
        $order->save();
        return view('frontend.partials.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->save();
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller'){
            foreach($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail){
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }
        else{
            foreach($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail){
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_delivery_status')->first()->value){
            try {
                $otpController = new OTPVerificationController;
                $otpController->send_delivery_status($order);
            } catch (\Exception $e) {
            }
        }

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller'){
            foreach($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail){
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }
        else{
            foreach($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail){
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach($order->orderDetails as $key => $orderDetail){
            if($orderDetail->payment_status != 'paid'){
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();

        if($order->payment_status == 'paid' && $order->commission_calculated == 0){
            if ($order->payment_type == 'cash_on_delivery') {
                if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
                    $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                    foreach ($order->orderDetails as $key => $orderDetail) {
                        $orderDetail->payment_status = 'paid';
                        $orderDetail->save();
                        if($orderDetail->product->user->user_type == 'seller'){
                            $seller = $orderDetail->product->user->seller;
                            $seller->admin_to_pay = $seller->admin_to_pay - ($orderDetail->price*$commission_percentage)/100;
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
                            $seller->admin_to_pay = $seller->admin_to_pay - ($orderDetail->price*$commission_percentage)/100;
                            $seller->save();
                        }
                    }
                }
            }
            elseif($order->manual_payment) {
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
            }

            if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliatePoints($order);
            }

            if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated) {
                $clubpointController = new ClubPointController;
                $clubpointController->processClubPoints($order);
            }

            $order->commission_calculated = 1;
            $order->save();
        }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_paid_status')->first()->value){
            try {
                $otpController = new OTPVerificationController;
                $otpController->send_payment_status($order);
            } catch (\Exception $e) {
            }
        }
        return 1;
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

    public function returnrent($invoice_num)
    {
        $invoice_number=$invoice_num;
        $order = Order::where('invoice_number',$invoice_number)->first();
        $invoice_id=$order->order_id;
        $store_id = $order->store_id;
        $arinv = (isset($invoice_number))?$invoice_number:'';
        // $redundant_invoice= DB::table('orders as a')
        // ->select('a.invoice_number', 'b.name', 'l.quantity')
        // ->join('order_details as l ', 'a.id', '=', 'l.order_id')
        // ->join('products as b ', 'b.id', '=', 'l.product_id')
        // ->where('a.invoice_number','RR'.$invoice_number.'%')
        // ->where('l.isdeleted','N')
        // ->orderBy('code', 'desc')
        // ->get();
        $redundant_invoice=array();                                                                                                                                                                                                                                                                     
       
        return view('receivables.returnrent',compact('order','invoice_number','redundant_invoice','invoice_id','store_id','arinv'))->with('msg', null);
        
    }

    public function saverent(Request $request)
    {
      // return json_encode($request->all());
        // find supplier is already present or needs to be added from Mobile
        $cust_mobile= User::where('id',$request->userid)->first();
        $msg='';
        $mobile1=$cust_mobile->phone;
        $s_name=$cust_mobile->name;
        //
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
        //// check invoice is opened
        $id = -1;
        if($id==-1) {
       $openinvoices = \App\Order::select('invoice_number','user_id','payment_status','invoicedate','grand_total')->where('payment_status','unpaid')->get();
        }
       else {
       $openinvoices= \App\Order::select('invoice_number','user_id','payment_status','invoicedate','grand_total')->where('payment_status','unpaid')->where('user_id',$id)->get();
       }
        if(count($openinvoices)>0){
        // $msg ="invoice has opened !!! Please close invoice first.. further process !!!";
        // return redirect('/admin/ARinvoice_header_workbench/returnrent/'.$request->invoicenumber)->with('msg', $msg);
        }
        $supplier_id=null;
        // check supplier duplicate by mobile1
        $supplier_check = ApSuppliers::where('mobile1',$mobile1)->first(); 
      
       // $supplier_id=$supplier_check->supplierid;
        //return json_encode($supplier_check->supplierid);
                                       
       if($supplier_check ='' || $supplier_check ='null')
       {
       // enter into ap_supplier as old book supplier, hard code C as customer supplier
       $supplier_insert= ApSuppliers::insertGetId([
          'name' => $s_name,
           'type' => 'C',
           'mobile1' => $mobile1,
           'mobile2' => null,
           'email1' => null,
           'email2' => null,
           'address1' => null,
           'address2' => null,
           'city' => null,
           'zipcode' => null,
           'state' => null,
           'bankname' => null,
           'ifsc' => null,
           'bankaccountname' => null,
           'accountnumber' => null,
           'description' => null,
           'isdeleted' => 'N',
           'lastupdatedby' => Auth::user(),
           'lastupdatedate' => NOW()


       ]);
      

           
               // pass supplier ID from insert record
            $supplier_id = $supplier_insert;
       }
       else
       {
           $supplier_id = $supplier_check->supplierid;

       }
   
      // add ap invoice
        // insert into ap invoices all

        $ap_invoice_number="RR".$request->invoicenumber.rand(100000,999999); // generate AP invoice number
        $sum=0;
        
        if(isset($request->ret_qty)){
        foreach($request->ret_qty as $rqt){
            foreach($rqt as $qt){
                $sum = $sum+$qt;
                }
        }
        }
    
        //print_r($_POST['ret_qty']);die;
        if($msg=="" && $sum > 0)
        {
           
        $success=  ApInvoicesAlls::insert([
            'invoice_number' => $ap_invoice_number,
            //'invoiceid' => $OldTemp->invoiceid,
            'invoicetype' => 'S',
            'supplier_id' => $supplier_id,
            'Date' => date("Y-m-d"),
            'sgst' => null,
            'cgst' => null,
            'gst' => null,
            'igst' => null,
            'Status' => 'O',
            'creationDate' => date('Y-m-d'),
            'Total' =>null,
            'image' => null,
            'description' => 'Rent Return AP Invoice from AR invoice: '.$ap_invoice_number,
            'type' => 'rent-return',
            'lastUupdateby' =>null,
            'lastupdatedate' => Now(),
            'store_id' => '1',

        ]);
       
     

        //
        // add ap invoice lines
        // get invoice header detail
        $invoice_header_data_ap= ApInvoicesAlls::where('invoice_number',$ap_invoice_number)->where('supplier_id',$supplier_id)->first();
       
        //echo "<pre>";
        //print_r($invoice_header_data_ap);
        //echo "</pre>";
        $invoice_id_ap=$invoice_header_data_ap->invoiceid;
        $version='O';
        // fetch arrays from form - bookid selected as on, refund, qty
        $book_id_arr=$request->bookid;  // fetch books selected
        $qty_arr=$request->ret_qty; // fetch qty
        $refund_arr=$request->refund; // fetch refund
        $invoiceId = $request->invoice;
        $aptype = $request->aptype;
        // add lines in for loop
        //print_r($invoiceId);die;
        $in=0;
        foreach($book_id_arr as $x => $x_val)
        {
            if(count($x_val)>0){
                $i=0;
                foreach($x_val as $xval):
                $quantity=$qty_arr[$x][$i]; // qty to be returned
        $book_id=$x; // $x is book id
        $book_qty_available= Product::select('current_stock')->where('id',$x)->first();
        
        $available_quantity=$book_qty_available->current_stock; // find qty present
        //
        //$mrp=$_POST['mrp'];// null
        $cp=$refund_arr[$x][$i]; // pass cost price as refund amount
        // $qty_inventory to be updated

        $qty_inventory=$available_quantity+$quantity;	

        // insert line
        if($quantity>0){  ///prevent to save with 0 quantity
            
        $success_line=ApInvoiceLines::insert([
            'lineid' => Null,
            'invoice_id' => $invoice_id_ap,
            'product_id' => $book_id,
            'version' => $version,
            'quantity' => $quantity,
            'mrp' => null,
            'cp' => $cp,
            'isdeleted' => 'N',
            'lastupdatedby' => Auth::user(),
            'lastupdateddate' => NOW(),
            'arinvoice' => $invoiceId[$book_id]

        ]);
        
        
        }else{
            $success_line = '';
        $sts = 2;	
        }
        $in++;
        
        If(!$success_line) 
        if($sts != 2){
        //die("error in line insert !!");
        }
        // update inventory
        $inventory_book_update= Product::where('id',$book_id)->update([
            'current_stock' => $qty_inventory
        ]);
        
        $boo_arr = Product::where('id',$book_id)->first();
   

        $i++;
        endforeach;
            }else{
                $quantity=$qty_arr[$x]; // qty to be returned
        $book_id=$x; // $x is book id
        $book_qty_available= Product::select('current_stock')->where('id',$x)->first();// array fetch from DB
        $available_quantity=$book_qty_available->current_stock; // find qty present
        //
        //$mrp=$_POST['mrp'];// null
        $cp=$refund_arr[$x]; // pass cost price as refund amount
        // $qty_inventory to be updated
        $qty_inventory=$available_quantity+$quantity;	
        // insert line
        if($quantity>0){
        $success_line=ApInvoiceLines::insert([
            'lineid' => Null,
            'invoice_id' => $invoice_id_ap,
            'product_id' => $book_id,
            'version' => $version,
            'quantity' => $quantity,
            'mrp' => null,
            'cp' => $cp,
            'isdeleted' => 'N',
            'lastupdatedby' => Auth::user(),
            'lastupdateddate' => NOW(),
            'arinvoice' => $invoiceId[$in]

        ]);
        
        
        }else{
            $sts = 2;
        }
        $in++;
        If(!$success_line) {
            if($sts != 2){
            //die("error in line insert !!");
        }
            
            }
            // update inventory
        $inventory_book_update= Product::where('id',$book_id)->update([
            'current_stock' => $qty_inventory
        ]);
        
        $boo_arr = Product::where('id',$book_id)->first();
       
        //executequery($con,"INSERT INTO `aparlog` (`id`, `bookid`,`qty`,`updateqty`, `apid`, `arid`, `updatedby`, `updateddate`) VALUES (NULL, '$book_id','$book_qty','-".$book_id_qty."', '$invoice_id', NULL, '".$_SESSION['user']."', CURRENT_TIMESTAMP)");
            }

        } // loop end

      // send sms
        
        // redirect to next page
        if($success) 
        return redirect('/admin/APinvoice_header_workbench_old/view/'.$invoice_id_ap.'/'.$supplier_id)->with('msg', $msg);
        //header("Location: APinvoice-lines-workbench-old.php?invoice_num=".$ap_invoice_number."&supplier_id=".$supplier_id);
      

    }

    }

    public function advancepay(Request $request)
    {
       // return $request->invoice_number;
        $msg ='';
        if(round($request->advance)>round($request->balance)){
           
            return redirect('/admin/ARinvoice_header_workbench/view/'.$request->id.'/'.$request->invoice_number)->with('msg', $msg);
          
            }
        $arr = array();
        $arr['invoiceid'] = $request->id;
        $arr['amount'] = $advance = isset($request->advance)?$request->advance:'0';
        $arr['clearancedate'] = $clearancedate = isset($request->clearancedate)?$request->clearancedate:date('YY-mm-dd');
        $arr['ModeOfPayment'] = $ModeOfPayment = isset($request->ModeOfPayment)?$request->ModeOfPayment:'cash';
        $arr['cheque'] = $cheque = isset($request->cheque)?$request->cheque:'';
        $arr['description'] = $notes = isset($request->notes)?$request->notes:'';
        if($arr['amount'] == ''){$arr['amount']=0;}
        $arr['paymentdate'] = date('Y-m-d H:i:s');
        $arr['updatedby'] = Auth::user()->email;
    // print_r($arr);die();

        if(DB::table('arcredit')->insert($arr)){
            return redirect('/admin/ARinvoice_header_workbench/view/'.$request->id.'/'.$request->invoice_number)->with('msg', $msg);
        }
    }
}
