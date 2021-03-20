<?php

namespace App\Http\Controllers;

use App\ArInvoicesAllF;
use App\ArInvoiceLinesF;
use App\Customer;
use App\Product;
use App\Author;
use App\Color;
use App\Order;
use App\OrderDetail;
use App\CouponUsage;
use App\ApSuppliers;
use App\OtpConfiguration;
use App\Formstatus;
use App\User;
use Auth;
use App\BusinessSetting;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Http\Request;
use Session;
use DB;
use PDF;
use Mail;
use Illuminate\Support\Collection;
use App\Mail\InvoiceEmailManager;
use CoreComponentRepository;


class ArInvoicesAllFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('receivablesform.ARinvoice_header_workbench_f');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msg = '';
        $custId = $request->customer_id;
        $usercust = Customer::where('id',$custId)->first();
        $inst_id = $request->institutes;
        $cat_id = $request->category;
        $state = $request->state;
        $store_id = isset($request->store_id)?$request->store_id:'1';
        $gstin = $request->gstin;
        if($state = '')
        {
            $state = Null;
        }
        if($gstin = '')
        {
            $gstin = Null;
        }

        $allopen = Order::where('invoicelookuptype','S')->where('payment_status','O')->where('invoice_number',  'not like', '%' . 'web' . '%')->where('invoicedate', '<=', date('d.m.Y',strtotime("-1 days")) )->get();
        
        if(isset($request->type) && $request->type == 'S' && count($allopen) > 0){
            $msg = "Please close all open invoice before process.";
          // return redirect('/admin/ARinvoice_header_workbench/create')->with('msg',$msg);
          $data= array('phone'=>$request->type, 'status'=>'emailmsg','msg'=> "Please close all open invoice before process");
                return json_encode($data);
            } 

        if($custId != '')
        {
           $user = User::where('id',$usercust->user_id)->where('email_verified_at', '!=' , Null)->update([
               'name' => $request->name,
            //    'email' => $request->email,
               'address' => $request->address,
               'country' => 'IN',
               'postal_code' => $request->postal_code,
               'institute_id' => $inst_id,
               'category_id' => $cat_id,
               'state' => $state,
               'gstin' => $gstin

           ]);
          
                     $order = new ArInvoicesAllF;
                    
                    $order->invoicelookuptype = $request->type;
                    $order->customerid = $custId;
                    $order->storelocationid = $store_id;
                   // $order->invoicedate = strtotime('now');
                   
                    if($request->description == '')
                    {
                        $request->description = 'Null';
                    }
                    $order->description = $request->description;
                    $order->status = 'O';
                    $order->lastupdateby = Auth::user();
                    $order->lastupdate = NOW();
                    $order->store_id = $store_id;
                    $datanum = [];
                    $qry = ArInvoicesAllF::select('invoiceid','invoicenumber as num')->where('invoicenumber',  'not like', '%' . 'web' . '%')->where('invoicenumber',  'not like', '%' . 'TRI' . '%')->orderBy('invoiceid','desc')->limit(1)->get();
                   
                    foreach($qry as $da)
                    {
                        $item = [
                            "num" =>  $da->num,
                        ];
                        array_push($datanum, $item);
                    }
                   
                 
                    if( filter_var($datanum[0]['num'], FILTER_SANITIZE_NUMBER_INT)=='0') $next_invoice_number =  10000;  // initialise first invoice number
                    else $next_invoice_number=  filter_var($datanum[0]['num'], FILTER_SANITIZE_NUMBER_INT)+1;
                    
                    
            $order->invoicenumber =  $next_invoice_number ;
            $order->invoicedate = date('Y-m-d');
            if($order->save()){
               
                $data= array('status'=>'olduser', 'order_id' => $order->id, 'cust_id' => $custId,  'description' => $request->description, 'newInvcNum' =>$next_invoice_number);
              
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
                'country' => 'IN',
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'phone' => '+91'.$request->phone,
                'verification_code' => rand(100000, 999999),
                'institute_id' => $inst_id,
                'category_id' => $cat_id,
                'state' => $state,
                'gstin' => $gstin
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

                $order = new ArInvoicesAllF;
           
                $order->invoicelookuptype = $request->type;
                $order->customerid = $customer->id;
                $order->storelocationid = '1';
               // $order->invoicedate = strtotime('now');
               
                if($request->description == '')
                {
                    $request->description = 'Null';
                }
                $order->description = $request->description;
                $order->status = 'O';
                $order->lastupdateby = Auth::user();
                $order->lastupdate = NOW();
                $order->store_id = '1';
                $items = [];
                $data = ArInvoicesAllF::select('invoiceid','invoicenumber as num')->where('invoicenumber',  'not like', '%' . 'web' . '%')->where('invoicenumber',  'not like', '%' . 'TRI' . '%')->orderBy('invoiceid','desc')->limit(1)->get();
                
                foreach($data as $da)
                {
                    $item = [
                        "num" =>  $da->num,
                    ];
                    array_push($items, $item);
                }
                if( filter_var($data[0]['num'], FILTER_SANITIZE_NUMBER_INT)=='0') $next_invoice_number =  10000;  // initialise first invoice number
                else $next_invoice_number=  filter_var($data[0]['num'], FILTER_SANITIZE_NUMBER_INT)+1;
                
                
            $order->invoicenumber =  $next_invoice_number ;
            $order->invoicedate = date('Y-m-d');
            if($order->save()){
               
                    $data= array('status'=>'true', 'newInvcNum' => $next_invoice_number, 'user_id' =>$insertedId, 'order_id' => $order->id, 'cust_id'=>$customer->id,  'description' => $request->description);
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


    /**
     * Display the specified resource.
     *
     * @param  \App\ArInvoicesAllF  $arInvoicesAllF
     * @return \Illuminate\Http\Response
     */
    public function View($invoiceid,$invoice_number)
    {
        $order = ArInvoicesAllF::where('invoiceid',$invoiceid)->where('invoicenumber',$invoice_number)->first();
        //print_r($order); die();
        return view('receivablesform.ARinvoice_header_workbench_f_view',compact('order'))->with('msg', null);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ArInvoicesAllF  $arInvoicesAllF
     * @return \Illuminate\Http\Response
     */
    public function edit(ArInvoicesAllF $arInvoicesAllF)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ArInvoicesAllF  $arInvoicesAllF
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArInvoicesAllF $arInvoicesAllF)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ArInvoicesAllF  $arInvoicesAllF
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArInvoicesAllF $arInvoicesAllF)
    {
        //
    }

    public function Search()
    {
        $search_da = null ;
        return view('receivablesform.search_ARInvoice_f',compact('search_da'));
    }


    public function SearchData(Request $request)
    {
        $keyword = $request->keyword;
        // return $keyword;
        $search_da=$this->searchInvoice($request->keyword);
      //  return json_encode($search_da);
        return view('receivablesform.search_ARInvoice_f',compact('search_da'));
    }

    public function searchInvoice($keyword)
	{
        //$query="select c.Name,c.Mobile1,a.Status,a.InvoiceNumber,a.Amount,a.InvoiceID,a.preorderid,a.store_id from `AR_Customers` c, `AR_Invoices_All` a where c.CustomerID=a.CustomerID and (c.Name like '%$keyword%' or c.Mobile1 like '%$keyword%' or c.Mobile2 like '%$keyword%' or a.InvoiceNumber like '%$keyword%') order by a.`InvoiceID` DESC LIMIT $offset , 10";
        $data = DB::table('ar_invoices_all_f_s')
        ->select('ar_invoices_all_f_s.invoiceid as id', 'users.name as name', 'users.email as email', 'users.phone as phone', 'users.id as userid', 'ar_invoices_all_f_s.invoicenumber as invoicenumber', 'ar_invoices_all_f_s.amount as amount', 'ar_invoices_all_f_s.status as status')
        ->join('customers as customer', 'customer.id', '=', 'ar_invoices_all_f_s.customerid')
        ->join('users as users', 'users.id', '=', 'customer.user_id')
        ->orWhere(function($query) use ($keyword)
            {
                $query->orWhere('users.name', 'like', '%' . $keyword . '%');
                
            })
            ->orWhere(function($query) use ($keyword)
            {
                $query->orWhere('users.phone', 'like', '%' . $keyword . '%');
               
            })
            ->orWhere(function($query) use ($keyword)
            {
                $query->orWhere('users.email', 'like', '%' . $keyword . '%');
               
            })
            ->orWhere(function($query) use ($keyword)
            {
                $query->orWhere('ar_invoices_all_f_s.invoicenumber', 'like', '%' .$keyword . '%');
               
            })
        ->orderBy('ar_invoices_all_f_s.invoiceid', 'desc')
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
            'customer_id' => $customer->customerid,
            'user_id' => $customer->id,
            'categoryId' => $customer->category_id,
            'instituteId' => $customer->institute_id,
            'address' => $customer->address,
          //  'address2' => $customer->address2,
            'city' => $customer->city,
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

    

    public function arformreportCreate(Request $request)
    {
        $data = [];
        $datarecord = DB::table('formstatuses as a')
        ->select(
            'a.status as fststus',
            'a.updatedby as firstattempt',
            'a.updateddate as firstdate'
           )
        
        
        ->groupBy('a.updatedby')
        ->get();
            foreach($datarecord as $record)
                {
                    $complete = DB::table('formstatuses')
                                
                            ->when($request->startdate, function($query) use ($request){
                                $query->whereBetween('formstatuses.updateddate', [$request->startdate,$request->enddate]);
                                // $query->whereBetween('a.updateddate', array($request->startdate,$request->enddate));
                            
                            })
                            
                            ->where('formstatuses.updatedby','=',$record->firstattempt)
                        
                            ->where('formstatuses.status','completed')
                            // ->where('a.isdeleted','N')
                            ->count();
                    $Incomplete = DB::table('formstatuses')
                                
                            ->when($request->startdate, function($query) use ($request){
                                $query->whereBetween('formstatuses.updateddate', [$request->startdate,$request->enddate]);
                                // $query->whereBetween('a.updateddate', array($request->startdate,$request->enddate));
                            
                            })
                            
                            ->where('formstatuses.updatedby','=',$record->firstattempt)
                        
                            ->where('formstatuses.status','incomplete')
                            // ->where('a.isdeleted','N')
                            ->count();
               
                $items = [
                    'email' => $record->firstattempt, 
                    'complete' => $complete, 
                    'Incomplete' => $Incomplete
                 ];
    
                 array_push($data,$items);

                }
                   

            $startdate = isset($request->startdate)?$request->startdate:'';
            $enddate = isset($request->enddate)?$request->enddate:'';
//return json_encode($data);
        return view('receivablesform.arformreport',compact('startdate','enddate'))->with('data', $data);

    }

    public function arformreportSubmit(Request $request)
    {
       // return $request->search;
       $data = [];
       $request->startdate = date('Y-m-d h:i:s', strtotime($request->startdate));
       $request->enddate = date('Y-m-d h:i:s', strtotime($request->enddate));

       // return $request->startdate. " - ". $request->enddate;
    
        if(isset($request->search) && $request->startdate != '' && $request->enddate != '' && $request->user != '')
        {
           
            $datarecord = DB::table('formstatuses as a')
            ->select(
                'a.status as fststus',
                'a.updatedby as firstattempt',
                'a.updateddate as firstdate'
               )
            ->when($request->startdate, function($query) use ($request){
                $query->whereBetween('a.updateddate', [$request->startdate,$request->enddate]);
                // $query->whereBetween('a.updateddate', array($request->startdate,$request->enddate));
            
            })
            ->where('a.updatedby','=',$request->user)
            
            ->groupBy('a.updatedby')
            ->get();
            
           
         } 
           
         
        if(isset($request->search) && $request->startdate != '' && $request->enddate != '' && $request->user == '')
        {
             
            $datarecord = DB::table('formstatuses as a')
            ->select(
                'a.status as fststus',
                'a.updatedby as firstattempt',
                'a.updateddate as firstdate'
               )
               ->when($request->startdate, function($query) use ($request){
                $query->whereBetween('a.updateddate', [$request->startdate,$request->enddate]);
                // $query->whereBetween('a.updateddate', array($request->startdate,$request->enddate));
            
                 })
           
                ->orwhere('a.updatedby','=',$request->user)
            
            
            ->groupBy('a.updatedby')
            ->get();
          
            
        } 
        if(Auth::user()->user_type != 'admin')
        {

                $pd = date('Y-m-d', strtotime('-3 days'));	
                $cd = date('Y-m-d');	
                $datarecord = DB::table('formstatuses as a')
                ->select(
                    'a.status as fststus',
                    'a.updatedby as firstattempt',
                    'a.updateddate as firstdate'
                   )
                ->when($pd, function($query) use ($request){
                    $query->whereBetween('a.updateddate', [$pd,$request->enddate]);
                    // $query->whereBetween('a.updateddate', array($request->startdate,$request->enddate));
                
                })
                ->when($pd, function($query) use ($request){
                    $query->orwhere('a.updatedby','=',$request->user);
                })
                
                ->groupBy('a.updatedby')
                ->get();
               
        }
        foreach($datarecord as $record)
            {
                
                $complete = DB::table('formstatuses')
                            
                        ->when($request->startdate, function($query) use ($request){
                            $query->whereBetween('formstatuses.updateddate', [$request->startdate,$request->enddate]);
                            // $query->whereBetween('a.updateddate', array($request->startdate,$request->enddate));
                        
                        })
                        
                        ->where('formstatuses.updatedby','=',$record->firstattempt)
                    
                        ->where('formstatuses.status','completed')
                        // ->where('a.isdeleted','N')
                        ->count();
                $Incomplete = DB::table('formstatuses')
                            
                        ->when($request->startdate, function($query) use ($request){
                            $query->whereBetween('formstatuses.updateddate', [$request->startdate,$request->enddate]);
                            // $query->whereBetween('a.updateddate', array($request->startdate,$request->enddate));
                        
                        })
                        
                        ->where('formstatuses.updatedby','=',$record->firstattempt)
                    
                        ->where('formstatuses.status','incomplete')
                        // ->where('a.isdeleted','N')
                        ->count();
            
                
                $items = [
                    'email' => $record->firstattempt, 
                    'complete' => $complete, 
                    'Incomplete' => $Incomplete
                ];

                array_push($data,$items);
                }
            
       
       
        return view('receivablesform.arformreport')->with('data', $data);

    }

    public function statusChangeOrder(Request $request)
        {
            //return json_encode($request->all());
                    $orderid = $request->orderid;
                    $status = $request->status;

                    $changeStatus = ArInvoicesAllF::where('invoiceid',$orderid)->update([
                        'status' => $status
                    ]);
                    if($changeStatus)
                    {
                        return '1';
                    }
        }

        public function ArInvoice($order_id)
        {
           
            $invoice_header_data = ArInvoicesAllF::where('invoiceid',$order_id)->first();
            $invoice_number=$invoice_header_data->invoicenumber;
           
            $invoice_id=$order_id;
            // get book detail for displaying invoice line
            $invoice_line_data= ArInvoiceLinesF::where('invoiceid',$invoice_header_data->id)->get()->toArray();

           // return json_encode($invoice_line_data);
            // customer detail
            $customer = Customer::where('id',$invoice_header_data->customerid)->first();
            $customer_detail= User::where('id',$customer->user_id)->first();

            //print_r($order); die();
            return view('receivablesform.ARinvoicef',compact('invoice_number','invoice_header_data','invoice_id','invoice_line_data', 'customer_detail'));
        }
        public function getInvoicePrint(Request $request)
        {
            $val = $request->_val;
            $orderid = $request->_orderid;
            
            if($val == '2')
            {
                $status='P';
                
            }
            else
            {
                $status='O';
            }
            $orderupdate = ArInvoicesAllF::where('invoiceid',$orderid)->update(['status'=> $status]);
           // print_r($orderupdate); die();
             // get book detail for displaying invoice line
            $invoice_line_data= ArInvoiceLinesF::where('invoiceid',$orderid)->get()->toArray();
            $invoice_header_data = ArInvoicesAllF::where('invoiceid',$orderid)->first();
        
            $customer = Customer::where('id',$invoice_header_data->customerid)->first();
            $customer_detail= User::where('id',$customer->user_id)->first();
          //  print_r($update_payment); die();
            $custId = $invoice_header_data->customerid;
            $invoice_number = $invoice_header_data->invoicenumber;
            // customer detail

             //stores the pdf for invoice
             $pdf = PDF::setOptions([
                'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                'logOutputFile' => storage_path('logs/log.htm'),
                'tempDir' => storage_path('logs/')
            ])->loadView('receivablesform.ARinvoicef', compact('invoice_header_data','invoice_line_data','customer_detail','invoice_number'));
            $output = $pdf->output();
            file_put_contents('public/invoices/'.'Order-'.$invoice_header_data->code.'.pdf', $output);
       
            if($val == '2')
            {
                
                // $pdf = PDF::loadView('receivables.ARinvoice', compact('order','invoice_line_data','customer_detail'));
                // return $pdf->download('Order#'.$order->code.'.pdf');
                return asset('invoices/Order-'.$invoice_header_data->code.'.pdf');
            }
           
            return 'done';
        }

        public function getInvoiceEmail(Request $request)
        {
            //return json_encode($request->all());
            $orderId = $request->id;
          
            $invoice_header_data = ArInvoicesAllF::where('invoiceid',$orderId)->first();
            $update_payment = ArInvoicesAllF::where('invoiceid',$orderId)->update([
                'modeofpayment' => $request->modepayment,
                'description' => $request->desc,
                'amount' => $request->amt
             
            ]);
            // get book detail for displaying invoice line
            $invoice_line_data= ArInvoiceLinesF::where('invoiceid',$invoice_header_data->id)->get()->toArray();

           // return json_encode($invoice_line_data);
            // customer detail
            $customer = Customer::where('id',$invoice_header_data->customerid)->first();
            $customer_detail= User::where('id',$customer->user_id)->first();
          //  print_r($update_payment); die();
            $custId = $invoice_header_data->customerid;
            $invoice_number = $invoice_header_data->invoicenumber;
             //stores the pdf for invoice
             $pdf = PDF::setOptions([
                'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                'logOutputFile' => storage_path('logs/log.htm'),
                'tempDir' => storage_path('logs/')
            ])->loadView('receivablesform.ARinvoicef', compact('invoice_header_data','invoice_line_data','customer_detail', 'invoice_number'));
            $output = $pdf->output();
            file_put_contents('public/invoices/'.'Order-'.$invoice_header_data->invoicenumber.'.pdf', $output);
            $array['view'] = 'emails.invoice';
            $array['subject'] = 'Order Placed - '.$invoice_header_data->invoicenumber;
            $array['from'] = env('MAIL_USERNAME');
            $array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
            $array['file'] = 'public/invoices/Order#'.$invoice_header_data->invoicenumber.'.pdf';
            $array['file_name'] = 'Order-'.$invoice_header_data->invoicenumber.'.pdf';
           if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_order')->first()->value){
                try {
                    $otpController = new OTPVerificationController;
                    $otpController->send_order_code($invoice_header_data);
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


        public function getDescription(Request $request)
        {
           
            $orderid = $request->id;
                $desc = ArInvoicesAllF::where('invoiceid',$orderid)->update([
                    'description' => $request->desc,
                    'amount' => $request->amt
                ]);
                
               if($desc == '1')
               {
                $order = ArInvoicesAllF::where('invoiceid',$orderid)->first();
                //print_r($oder); die();
                $userid = $order->customerid;
                $desc = $order->desc;
                if($userid != '')
                {
                    $cust = Customer::where('id', $userid)->first();
                    $user = User::where('id', $cust->user_id)->first();
                    sendSMS($user->phone, env('APP_NAME'), 'your description'. $desc.' updated '.env('APP_NAME'));
                    return 'true';
                }
            }
        }
    public function LinearformreportCreate(Request $request)
    {
        $data = '';
        $msg = '';
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $users = isset($request->users)?$request->users:'';
        $paybank = isset($request->paybank)?$request->paybank:'';
        $application = isset($request->application)?$request->application:'';
        $status = isset($request->status)?$request->status:'';
        $searchkey = isset($request->searchkey)?$request->searchkey:'';
        $startidate = isset($request->startidate)?$request->startidate:'';
        $endidate = isset($request->endidate)?$request->endidate:'';
        $total_records=0;
         $sc=0;$amt=0; 
        return view('receivablesform.arlineformreport',compact('startdate','enddate','users','paybank','application','status','searchkey','startidate','endidate','total_records','sc', 'amt'))->with('data', $data)->with('msg', $msg);

    }

    public function LinearformreportSubmit(Request $request)
    {
        $sql = ''; $msg = '';
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $users = isset($request->users)?$request->users:'';
        $paybank = isset($request->paybank)?$request->paybank:'';
        $application = isset($request->application)?$request->application:'';
        $status = isset($request->status)?$request->status:'';
        $searchkey = isset($request->searchkey)?$request->searchkey:'';
        $startidate = isset($request->startidate)?$request->startidate:'';
        $endidate = isset($request->endidate)?$request->endidate:'';
        $total_records=0;

        $store_id = $request->store_id;
        $sc = 0;
        $amt = 0;


        if($request->search){

            $sql = DB::table('ar_invoice_lines_f_s')
        ->join('products','ar_invoice_lines_f_s.itemid', '=' , 'products.id' )
        -> where('ar_invoice_lines_f_s.isdeleted','N')
        ->get();
        if(isset($request->searchkey) && $request->searchkey != ''){
            $sql =  DB::table('ar_invoice_lines_f_s')
            ->join('products','ar_invoice_lines_f_s.itemid', '=' , 'products.id' )
            -> where('ar_invoice_lines_f_s.isdeleted','N')
            ->where('products.isbn', 'like', '%' . $request->searchkey . '%')
            ->orwhere('products.oldisbn', 'like', '%' . $request->searchkey . '%')
            ->orwhere('products.name', 'like', '%' . $request->searchkey . '%')
        ->get();
        
        }
        if($request->startdate != '' && $request->enddate != ''){
            $rrr = DB::table('ar_invoice_lines_f_s')
            -> where('ar_invoice_lines_f_s.isdeleted','N')
            ->where('ar_invoice_lines_f_s.application', 'like', '%' . $request->application . '%')
            ->orwhere('ar_invoice_lines_f_s.status', 'like', '%' . $request->status . '%')
            ->orwhere('ar_invoice_lines_f_s.paybank', 'like', '%' . $request->paybank . '%')
            ->orwhereBetween('ar_invoice_lines_f_s.paydate', [$startdate,$enddate])
            ->get();

            $pd1 = date('Y-m-d', strtotime('-3 days'));
            $cd1 = date('Y-m-d');
            $sql = DB::table('ar_invoice_lines_f_s')
            ->join('products','ar_invoice_lines_f_s.itemid', '=' , 'products.id' )
            -> where('ar_invoice_lines_f_s.isdeleted','N')
            ->orwhereBetween('ar_invoice_lines_f_s.paydate', [$startdate,$enddate])
           ->get();
        }
        if($request->startidate != '' && $request->endidate != ''){
	
            $pd = date('Y-m-d', strtotime('-3 days'));
            $cd = date('Y-m-d');
            $rrr = DB::table('ar_invoice_lines_f_s')
            ->where('ar_invoice_lines_f_s.application', 'like', '%' . $request->application . '%')
            ->orwhere('ar_invoice_lines_f_s.status', 'like', '%' . $request->status . '%')
            ->orwhere('ar_invoice_lines_f_s.paybank', 'like', '%' . $request->paybank . '%')
            ->orwhereBetween('ar_invoice_lines_f_s.paydate', [$startidate,$endidate])
            ->get();
            $sql = DB::table('ar_invoice_lines_f_s')
            ->join('products','ar_invoice_lines_f_s.itemid', '=' , 'products.id' )
            -> where('ar_invoice_lines_f_s.isdeleted','N')
            ->orwhereBetween('ar_invoice_lines_f_s.paydate', [$startidate,$endidate])
           ->get();
        }
        if($request->application != ''){
            $sql = DB::table('ar_invoice_lines_f_s')
            ->join('products','ar_invoice_lines_f_s.itemid', '=' , 'products.id' )
            -> where('ar_invoice_lines_f_s.isdeleted','N')
            ->where('ar_invoice_lines_f_s.application', 'like', $request->application)
            ->get();
        }
        if($request->paybank != ''){
            $sql = DB::table('ar_invoice_lines_f_s')
            ->join('products','ar_invoice_lines_f_s.itemid', '=' , 'products.id' )
            -> where('ar_invoice_lines_f_s.isdeleted','N')
            ->where('ar_invoice_lines_f_s.paybank', 'like', $request->paybank)
            ->get();
			
			}
			if($request->status != ''){
				if($request->status == 'incomplete'){
                    $sql = DB::table('ar_invoice_lines_f_s')
                    ->join('products','ar_invoice_lines_f_s.itemid', '=' , 'products.id' )
                    -> where('ar_invoice_lines_f_s.isdeleted','N')
                    ->where('ar_invoice_lines_f_s.status', '=', '')
                    ->orwhere('ar_invoice_lines_f_s.status', '=', $request->status)
                    ->get();
				
				}else{
                    $sql = DB::table('ar_invoice_lines_f_s')
                    ->join('products','ar_invoice_lines_f_s.itemid', '=' , 'products.id' )
                    -> where('ar_invoice_lines_f_s.isdeleted','N')
                    ->where('ar_invoice_lines_f_s.status', '=', $request->status)
                    ->get();
				
			}
				}
			if($request->users != ''){
                $sql = DB::table('ar_invoice_lines_f_s')
                    ->join('products','ar_invoice_lines_f_s.itemid', '=' , 'products.id' )
                    -> where('ar_invoice_lines_f_s.isdeleted','N')
                    ->where('ar_invoice_lines_f_s.lastattempt', '=', $request->users)
                    ->get();
				
				}
		
		    // if($request->orderby != ''){
            
			// 	$sql .= " order by ".$_REQUEST['orderby']." ".$_REQUEST['order'];
            //     }
                
            // foreach($sql as $r):
            //     $amt = $amt+$r->recievedamount;
            //     $sc = $sc+$r->amount;
            //     endforeach;
                  $totalres = $total_records = count($sql);
            }
            else
            {
                
            }
            return view('receivablesform.arlineformreport',compact('startdate','enddate','users','paybank','application','status','searchkey','startidate','endidate','total_records','sc','amt'))->with('data', $sql)->with('msg', $msg);		
	

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
              
              if($vart == 'null' || $vart == ''){
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
                        $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /></td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" />  , Qty available : '.$qty .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td><td><label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
                        echo $output; 
                    }else{
                        echo $output='<tr class="brent"><td>Rent*<td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="rent" value="'.$rent.'" required="required" /></td>,Book: '.$product->name.'| Author: '.$name.'| Isbn: '.$isbn.'<td></td></tr><tr class="bmrp"><td>Security:</td><td><input type="text" name="security" value="'.$security.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" />  , Qty available : '.$qty .'<span>('.$qty_info.')<input type="hidden" name="qty_a" value="'.$qty .'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> </td></tr>';
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
            
            $special_discount = isset($request->special_discount)?$request->special_discount:'0';
            $transaction_type=$request->transaction_type; // from form
            $variant = $request->vart;
           $qunty = $request->qty;
            if($variant == 'null' || $variant == '')
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
                return redirect('/admin/ARinvoice_header_workbench_f/view/'.$idorder.'/'.$invn)->with('msg', $msg);
            }
            $order = ArInvoicesAllF::where('invoiceid',$request->orderId)->first();
            //return json_encode($order);
            // books for rent can also be sold , but books for sale cannot be leased
            if($transaction_type=="S")
            {
              //  die('hgfhfhf');
            //$transaction_type=$sale_rent;
                $item_price=$itemprice;  // selling price
                $pd= Product::select('igst','sgst')->where('id', $request->keyword)->first();
            
                $tax= 0;
                if($pd->igst != 0 and $pd->igst != '' ){
                    $tax=$pd->igst;
                    }else if($pd->sgst != 0 and $pd->sgst != '' ){
                    $tax=$pd->sgst;
                    }
                $baseprice = $item_price * 100/(100+$tax);
                
                $item_id=$request->keyword; // book id
         
                if($order->invoicelookuptype != 'C'){
                        if($sale_rent=="no" && $transaction_type=="R") 
                        {
                            $msg="Book on sale cannot be given on rent";
                            return redirect('/admin/ARinvoice_header_workbench_f/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                        }
                }
                $amount = $mrp; // MRP
                // return $amount;
                //if(isset($_POST['security'])) {$amount=$_POST['security']; $item_price=$_POST['security'];}
                 if($amount <=0 and $prepay!="-1")  
                 {
                    $msg="Choose Sale/Rent Amount is 0.";
                    // return $msg;
                    return redirect('/admin/ARinvoice_header_workbench_f/view/'.$idorder.'/'.$invn)->with('msg', $msg);
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
                $university_fees = Product::select('university_fees')->where('id',$item_id)->first();
               
                $recivedamt = $university_fees->university_fees+$amount;
            $insert_line=ArInvoiceLinesF::insert([
                'invoiceid' =>$idorder ,
                'transactiontype' => $transaction_type,
                'itemid' => $item_id,
                'variation' => $variation,
                'itemprice' => $item_price,
                'quantity' => $quantity,
                'discount' => $discount,
                'security' =>$security,
                'amount' =>$amount,
                'recievedamount' => $recivedamt,
                'rentduedate' => $due_date,
                'description'=> $description,
                'baseprice' => $baseprice,
                'isdeleted' => 'N',
                'lastupdatedate' => NOW(),
                 'lastupdate' => Auth::user()

                
          
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
                return redirect('/admin/ARinvoice_header_workbench_f/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                
            }
            else {
                $msg="Failed";
                return redirect('/admin/ARinvoice_header_workbench_f/view/'.$idorder.'/'.$invn)->with('msg', $msg);
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
                $pd= Product::select('igst','sgst')->where('id', $request->keyword)->first();
            
                $tax= 0;
                if($pd->igst != 0 and $pd->igst != '' ){
                    $tax=$pd->igst;
                    }else if($pd->sgst != 0 and $pd->sgst != '' ){
                    $tax=$pd->sgst;
                    }
                $baseprice = $security * 100/(100+$tax);
                
                //$refund=$item_price-$rent;
                $refund=$security-$rent;
                // item price will have rent amount , amount will have security or MRP
                $description="";
                $discount=0;
                $order = ArInvoicesAllF::where('invoiceid',$request->orderId)->first();
                    if($order->invoicelookuptype == 'C'){
                    $discount = ($amount*$special_discount/100);
                    $transaction_type = 'S';
                    
                    }else{
                        if($sale_rent=="no" && $transaction_type=="R")
                        {
                            $msg="Book on sale cannot be given on rent";
                            return redirect('/admin/ARinvoice_header_workbench_f/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                        }
                        $discount=0;	
                    }
                
                    if($amount<=0) 
                    {   
                        $msg="Choose Sale/Rent Amount is 0";
                        return redirect('/admin/ARinvoice_header_workbench_f/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                    }
                    //print_r($_REQUEST); die;
                if($msg=="")
                {
                    
                $custid = $order->customerid;
                $update_discount = Product::where('id', $item_id)->where('user_id',$custid)->update([
                    'discount' => $special_discount
                ]);
                
              
                }

                $university_fees = Product::select('university_fees')->where('id',$item_id)->first();
               
                $recivedamt = $university_fees->university_fees+$amount;
            $insert_line=ArInvoiceLinesF::insert([
                'invoiceid' =>$idorder ,
                'transactiontype' => $transaction_type,
                'itemid' => $item_id,
                'variation' => $variation,
                'itemprice' => $item_price,
                'quantity' => $quantity,
                'discount' => $discount,
                'security' =>$security,
                'amount' =>$amount,
                'recievedamount' => $recivedamt,
                'rentduedate' => $due_date,
                'description'=> $description,
                'baseprice' => $baseprice,
                'isdeleted' => 'N',
                'lastupdatedate' => NOW(),
                 'lastupdate' => Auth::user()

                
          
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
                    return redirect('/admin/ARinvoice_header_workbench_f/view/'.$idorder.'/'.$invn)->with('msg', $msg);
                   
                
            }
            else 
            {
             $msg="Failed";
             return redirect('/admin/ARinvoice_header_workbench_f/view/'.$idorder.'/'.$invn)->with('msg', $msg);
            }
        }
      
  
        
        // return $msg;
        
            
    }

    public function changeStatusFormline(Request $request)
    {
        $invoice_line = $request->invoice_line;
        $status = $request->status;
        $user = $request->user;
        $userId = $request->userId;
        // if($request->userId == '8'){
        // echo "1";
        // exit;
        // }
        //list($user) = get_query_list($con,"SELECT updatedby FROM `formstatus` a WHERE id=(select MIN(id) FROM formstatus b WHERE a.lineid = b.lineid GROUP BY lineid) AND lineid='$invoice_line'  ORDER BY id ASC");
       // list($role) = get_query_list($con,"select Role from Users where RowId='".$_REQUEST['userId']."'");
       $user2 = ArInvoiceLinesF::select('lastattempt')->where('lineid',$invoice_line)->first();
        
        if($userId == '8' && $user != ''){
        echo "1";
        exit;
        }

        $role = User::select('user_type')->where('id', $userId)->first();
        if($user2->lastattempt == $user  || $user2->lastattempt == '' || $role->user_type == 'admin' ){
          $updtline =  ArInvoiceLinesF::where('lineid', $invoice_line)->update([
                'status' => $status,
                'lastattempt' => $user
            ]);
        if($updtline){
            $data = Formstatus::insert([
                'lineid' =>  $invoice_line,
                'userid' => $userId,
                'updatedby' => $user,
                'status' => $status
            ]);
          
            echo "1";
            }else{
                echo "0";
                }
        }else{
        echo "2";	
        }

    }

    public function saveStatusFormline(Request $request)
    {
        $invoice_line = $request->invoice_line;
        $status = $request->status;
        $user = $request->user;
        $userId = $request->userId;
        $user2 = ArInvoiceLinesF::select('lastattempt')->where('lineid',$invoice_line)->first();
        if($userId == '8' && $user != ''){
            echo "1";
            exit;
            }
    
        $role = User::select('user_type')->where('id', $userId)->first();
        if($user2->lastattempt == $user  || $user2->lastattempt == '' || $role->user_type == 'admin' ){
            $updtline =  ArInvoiceLinesF::where('lineid', $invoice_line)->update([
                'status' => $status,
                'lastattempt' => $user,
                'paybank' => $request->bank,
                'paydate' => $request->pay_date,
                'application' => $request->application,
                'recievedamount' => $request->recievedamount
            ]);
            if($updtline){
                $data = Formstatus::insert([
                    'lineid' =>  $invoice_line,
                    'userid' => $userId,
                    'updatedby' => $user,
                    'status' => $status
                ]);
              
                echo "1";
                }else{
                    echo "0";
                    }
            }else{
            echo "2";	
            }
      
            
    }


    public function getStatusFormline(Request $request)
    {

        $invoice_line = $request->invoice_line;

        $rrr = Formstatus::where('lineid', $invoice_line)->get();
        
        $html = '';
        foreach($rrr as $r):
        $html .= $r->updatedby." on ".$r->updateddate." with ".$r->status." Status\n";
        endforeach;
        echo $html;

    }

    public function saveRecievedAmount(Request $request)
    {
        $invoice_line = $request->invoice_line;
        $user2 = ArInvoiceLinesF::select('lastattempt')->where('lineid',$invoice_line)->first();
        if($request->userId == '8' && $user2->lastattempt != ''){
            echo "1";
            exit;
            }
            $role = User::select('user_type')->where('id', $request->userId)->first();
            if($user2->lastattempt == $request->user  || $user2->lastattempt == '' || $role->user_type == 'admin' ){
            
                if($request->userId  == '8'){
                    $updtline =  ArInvoiceLinesF::where('lineid', $invoice_line)->update([
                        'recievedamount' => $request->recievedamount
                    ]);
                    /////// for amitbookdepot.net ////////////////////
                    if($updtline){
                echo "1";
                exit;
                }else{
                    echo "0";
                    exit;
                    }
                    
                    }
                    $updtline =  ArInvoiceLinesF::where('lineid', $invoice_line)->update([
                        'recievedamount' => $request->recievedamount
                    ]);
            //executequery($con,"insert into formstatus(lineid,userid,updatedby,status)values('$invoice_line','".$_REQUEST['userId']."','".$_REQUEST['user']."','$status')");
            if($updtline){
                echo "1";
                }else{
                    echo "0";
                    }
            }else{
            echo "2";	
            }
    }


    public function destroyLine(Request $request)
        {

        // delete line
        $line_id=$request->id;
        $invoice = ArInvoiceLinesF::where('lineid',$line_id)->first();
        $validatedata = ArInvoicesAllF::where('status','O')->where('invoiceid', $invoice->lineid)->first();
        $validate = $validatedata->count();
       
        if($validate==0) return "Invoice is not open, cannot edit the same !!";

        $isdeleted = ArInvoiceLinesF::select('isdeleted')->where('lineid',$line_id)->first();
       
        if($isdeleted == 'Y'){
            return redirect('/admin/APinvoice_header_workbench_f/view/'.$invoice_id.'/'.$supplier_id);
        }
        $update_line = ArInvoiceLinesF::where('lineid',$line_id)->update([
            'isdeleted' => 'Y',

        ]);
        $line_detail = ArInvoiceLinesF::where('lineid',$line_id)->first();
       //return json_encode($line_detail);
        $qty = $line_detail->quantity;
        
        if($line_detail != null){
            if($line_detail->variation != "null" && $line_detail->variation != ''){
                $product_stock = \App\ProductStock::where('variant', $line_detail->variation)->where('product_id',$line_detail->itemid)->first();
               // return json_encode($product_stock);   
                $product_qty = $product_stock->qty ; 
                $current_stock =  $product_qty+$qty;
                // return json_encode($current_stock); 
                     $qty_update = \App\ProductStock::where('product_id',$line_detail->itemid)->where('variant', $line_detail->variation)->update([
                        'qty' => $current_stock
                    ]);
                
            }
            else
            { 
                $product_stock = Product::where('id',$line_detail->itemid)->first();
               // return json_encode($product_stock); 
                    $current_stock = $product_stock->current_stock+$qty;
                        $qty_update = Product::where('id',$line_detail->itemid)->update([
                            'current_stock' => $current_stock
                    ]);
               
            }

                ArInvoiceLinesF::where('lineid',$line_id)->delete();
                           return "done";
        }
        else{
           return "not done";
        }

    }

    public function sendsms(Request $request)
    {
        $msg = 'Sms Sent';
        $mobile = '+91'.$request->mobile; 
        $smstext = $request->smstext;
        $otpController = new OTPVerificationController;
        $user = User::where('phone',$mobile)->first();
        sendSMS($user->phone, env('APP_NAME'), $smstext.env('APP_NAME'));

        $data = '';
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $users = isset($request->users)?$request->users:'';
        $paybank = isset($request->paybank)?$request->paybank:'';
        $application = isset($request->application)?$request->application:'';
        $status = isset($request->status)?$request->status:'';
        $searchkey = isset($request->searchkey)?$request->searchkey:'';
        $startidate = isset($request->startidate)?$request->startidate:'';
        $endidate = isset($request->endidate)?$request->endidate:'';
        $total_records=0;
         $sc=0;$amt=0; 
        return view('receivablesform.arlineformreport',compact('startdate','enddate','users','paybank','application','status','searchkey','startidate','endidate','total_records','sc', 'amt'))->with('data', $data)->with('msg', $msg);
        
    }

}
