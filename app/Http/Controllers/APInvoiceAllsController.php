<?php

namespace App\Http\Controllers;

use App\ApInvoicesAlls;
use App\ApInvoiceLines;
use App\ApSuppliers;
use App\Order;
use App\Author;
use App\Product;
use App\ProductStock;
use App\ApInvoicesAllOldTemp;
use App\Http\Controllers\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use ImageOptimizer;
use CoreComponentRepository;


class APInvoiceAllsController extends Controller
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
        $msg= '';
        $supplier = DB::table('ap_suppliers')->where('IsDeleted','N')->where('Type','b')->orderBy('Name', 'ASC')->get();
        $supplier_data = [];
        
        foreach($supplier as $data_item)
        {
            $item = [
                "SupplierID" =>  $data_item->supplierid,
                "Name"      =>  $data_item->name    
                ];

            array_push($supplier_data, $item);
        }
        return view('payables.create_APInvoice_header_workbench',compact('msg','supplier_data'));
    }


    public function createOld()
    {
        $msg= '';
       
        return view('payables.create_APInvoice_header_workbench_old',compact('msg'));
    }

    public function getCustomerAP(Request $request)
    {

        if(!empty($request->mobile1)) 
        {
            $mobile = $request->mobile1;
            $customer_data= ApSuppliers::where('mobile1', $mobile)->orwhere('mobile2',$mobile)->first();
            // return json_encode($customer_data);
            if($customer_data)
            {
                $output='<input type="text" name="c_name" id="cust_name" value="'.$customer_data->name.'" required="required"/>';
            }
            else
            {
                $output='<input type="text" name="c_name" id="cust_name" required="required" />';
            }
            echo $output;
      
        }

    }

    public function OldInvoiceStore(Request $request)
    {

        $msg ='';
        $mobile1=$request->mobile1;
        $s_name=$request->c_name; // supplier name
       
        $supplier_check= ApSuppliers::where('mobile1',$mobile1)->first();
          //print_r($supplier_check);die;
        $supplier_id=null;
        // $store_id = isset($_POST['store_id'])?$_POST['store_id']:'1';
        // if($_SESSION['Role'] != 1){
        // $store_id=$_SESSION['store_id'];
        // }
      
        if($supplier_check == '' )
        {
        // $store_id = isset($_POST['store_id'])?$_POST['store_id']:'1';

        // enter into ap_supplier as old book supplier, hard code C as customer supplier
        $supplier_insert= ApSuppliers::insertGetId([
            'supplierid' => Null,
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
            'description' => $request->description,
            'isdeleted' => 'N',
            'lastupdatedby' => Auth::user(),
            'lastupdatedate' => NOW()


        ]);
      
		
        // get supplier ID
        $query_getid= ApSuppliers::max('supplierid');
    
        // pass supplier ID from insert record
        $supplier_id=$supplier_insert;
        }
        else {
        $supplier_id=$supplier_check->supplierid; // pass supplier ID of existing supplier

        }
       
        $date_split=explode('/',$request->date);
        $date=$date_split[2]."-".$date_split[0]."-".$date_split[1];
        //
        $invoice_number='OLD'.$request->invoice_number;
        //
       // if(strpos($invoice_number,'&')!=false) $msg="Ampersand ('&') not allowed in Invoice Number";
       // return view('payables.create_APInvoice_header_workbench_old')->with('msg', $msg);

        $duplicate= ApInvoicesAlls::where('invoice_number', $invoice_number)->where('supplier_id',$supplier_id)->first();
       
        if(! empty($duplicate))
       {
         $duplicate_check = $duplicate->count();
        
        
        if($duplicate_check>0)
        {
        $msg="Duplicate Invoice Number for Selected Supplier !!";
        return view('payables.create_APInvoice_header_workbench_old')->with('msg', $msg);

        }
    }
        if($msg=="")
        {

        $success= ApInvoicesAlls::insertGetId([
            'invoice_number' =>$invoice_number,
            'invoicetype' => 'S',
            'supplier_id' =>$supplier_id,
            'Date'=>$date,
            'Status' => 'O',
            'igst' => null,
            'sgst' => null,
            'cgst' => null,
            'gst' => null,
            'Total' => null,
            'image' => null,
            'creationDate' => date('Y-m-d'),
            'description' => $request->description,
            'lastupdatedate' => NOW(),
            'lastUupdateby' => Auth::user(),
            'store_id' => '1',
            'type' => 'purchase'
        ]);
        
        if($success) 
        
      
        
        return redirect('/admin/APinvoice_header_workbench_old/view/'. $success.'/'.$supplier_id);
        }

    }

    public function createOld2()
    {
        $msg= '';
       
        return view('payables.create_APInvoice_header_workbench_old_2',compact('msg'));
    }

    public function get_customerForOldBooking(Request $request)
    {
        
        $phone = $request->term;

      
        $customers = ApSuppliers::where('type', 'C')->where('mobile1','like','%' . $phone . '%')->orderBy('supplierid','desc')->take(20)->get();
   //return json_encode($customers);
        $item = [];
        foreach($customers as $customer){
            $data = array('mobile1' => $customer->mobile1,
            'mobile2' => $customer->mobile2,
            'supplierid' => $customer->supplierid,
            'email1' => $customer->email1, 
            'email2' => $customer->email2, 
            'name' => $customer->name,
            'address1' => $customer->address1,
            'address2' => $customer->address2,
            'city' => $customer->city,
            'zipcode' => $customer->zipcode,
            'type' => $customer->type,
            'state' => $customer->state,
            'bankname' =>$customer->bankname,
            'ifsc' => $customer->ifsc,
            'bankaccountname' => $customer->bankaccountname,
            'accountnumber' =>$request->accountnumber,
            'gstin' => $customer->gstin,
            'description' => $customer->description,
          
       );
         array_push($item,$data);
        }
       
    return json_encode($item);
       
    }


    public function ApInvoiceOld2Store(Request $request)
    {

        $store_id = isset($request->store_id)?$request->store_id:'1';
        
        $mobile1=$request->mobile1;
        $mobile2 = isset($request->mobile2)?$request->mobile2:'';
        $s_name=$request->c_name; // supplier name
        $customer_id=$request->supplier_id;
        $customer_name=ucwords($request->c_name);
        $invoice_type='S';
        $category=$request->type;
        $description=$request->description;
        $date = $request->date;
        //$mobile1=$request->mobile1;
        $address1=$request->address1;
        $address2=isset($request->address2)?$request->address2:'';
        $city=$request->city;
        $state= isset($request->state)?$request->state:'';
        $email= trim($request->email);
        $zipcode = $request->zipcode;
        $gstin = $request->gstin;
        $type = $request->type;
        $ifsc = $request->ifsc;
        $bankname = $request->bankname;
        $bankaccountname = $request->bankaccountname;
        $bankaccountnumber =$request->bankaccountnumber;
        $quantity = $request->quantity;
        $msg="";
        $state = $request->state;
        
        $gstin = $request->gstin;
        if($state = '')
        {
            $state = Null;
        }
        if($gstin = '')
        {
            $gstin = Null;
        }


        $supplier_check= ApSuppliers::select('supplierid')->where('mobile1',$mobile1)->first();
       // print_r($supplier_check); die();
        $supplier_id=null;

        // $duplicate_mobile=$customerdal->duplicateMobile($mobile1,$mobile2,$category);
        // if($duplicate_mobile[0]['num']!=0) $msg="<div class='error'>Mobile Number Duplicate with chosen Category !!</div>";
       
        if($supplier_check=='')
        {

        // enter into ap_supplier as old book supplier, hard code C as customer supplier
        $supplier_insert= ApSuppliers::insertGetId([
            'name' => $s_name,
            'type' => 'C',
            'mobile1' => $mobile1,
            'mobile2' => null, 
            'email1' => $email,
            'email2' => null, 
            'address1' => $address1,
            'address2' => $address2,
            'city' => $city,
            'zipcode' => $zipcode,
            'state' => $state,
            'bankname' =>$bankname,
            'ifsc' => $ifsc,
            'bankaccountname' => $bankaccountname,
            'accountnumber' =>$bankaccountnumber,
            'gstin' => $gstin,
            'description' => $description,
            'isdeleted' => 'N',
            'lastupdatedby' => Auth::user(),
            'lastupdatedate' => NOW()
          
        ]);
       
        
        // pass supplier ID from insert record
        $supplier_id=$supplier_insert;
        }
        else {
        $supplier_id=$supplier_check->supplierid; // pass supplier ID of existing supplier
        //$supplier_insert=$supplierdal->editSupplierOld($s_name,$mobile1,null,$email,null,$address1,$address2,$city,$state,$bankname,$ifsc,$bankaccountname,$bankaccountnumber,$description,$_SESSION['user'],$gstin,$supplier_id);
        }
        $invoice_number='LUM'.substr($request->mobile1, -5).date('ymd').rand(100,999);
        if(strpos($invoice_number,'&')!=false) $msg="Ampersand ('&') not allowed in Invoice Number";
        //

        $duplicate= ApInvoicesAlls::where('invoice_number', $invoice_number)->where('supplier_id',$supplier_id)->first();
       
        if(! empty($duplicate))
        {
             $duplicate_check = $duplicate->count();
        
        
            if($duplicate_check>0)
            {
            $msg="Duplicate Invoice Number for Selected Supplier !!";
        // return view('payables.create_APInvoice_header_workbench_old')->with('msg', $msg);

            }
        }
        if($msg=="")
        {

        $success= ApInvoicesAllOldTemp::insert([
            'invoicenumber' => $invoice_number,
            'invoicetype' => 'S',
            'supplierid' => $supplier_id,
            'Date' => $date,
            'Status' => 'O',
            'igst' => null,
            'sgst' => null,
            'cgst' => null,
            'gst' => null,
            'quantity' => $quantity,
            'Total' => null,
            'image' => null,
            'creationdate' => date('Y-m-d'),
            'description' => $request->description,
            'lastupdatedate' => NOW(),
            'lastupdateby' => Auth::user()
        ]);
        
        
        if($success) return redirect('/admin/APinvoice_header_workbench/varifyme/'. $invoice_number.'/'.$supplier_id);
       // header("Location: VarifyMe.php?invoice_num=".$invoice_number."&supplier_id=".$supplier_id);
        }

           
    
    }
    public function resend($invoice_number,$supplier_id)
    {
        $OldTemp = ApInvoicesAllOldTemp::select('invoiceid','Total','otp','otpdate','varified')->where('invoicenumber',$invoice_number)->first();
     
        $invoiceidtmp  = $OldTemp->invoiceid;
        $amount = $OldTemp->Total;
        $otp = $OldTemp->otp;
        $otpdate = $OldTemp->otpdate;
        $varified = $OldTemp->varified;
        $gotp = '';
       
       
        return view('payables.resend',compact('invoice_number', 'supplier_id', 'invoiceidtmp', 'amount', 'otp', 'otpdate','varified'));
    }
    public function VarifyMe($invoice_number,$supplier_id)
    {
        
       
        $OldTemp = ApInvoicesAllOldTemp::select('invoiceid','Total','otp','otpdate','varified')->where('invoicenumber',$invoice_number)->first();
     
        $invoiceidtmp  = $OldTemp->invoiceid;
        $amount = $OldTemp->Total;
        $otp = $OldTemp->otp;
        $otpdate = $OldTemp->otpdate;
        $varified = $OldTemp->varified;
        $gotp = '';
       
       
        return view('payables.varifyme',compact('invoice_number', 'supplier_id', 'invoiceidtmp', 'amount', 'otp', 'otpdate','varified'));
    }


    public function otpSend(Request $request)
    {
        $invoice_number =$request->invoicenumber;
        $supplier_id =$request->supplier_id;
        $payment = $request->payment;
     
        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_order')->first()->value){
            $user_up = ApInvoicesAllOldTemp::where('invoicenumber', $invoice_number)->where('supplierid',$supplier_id)->update([
                'Total' => $payment,
                'otp' => rand(100000, 999999),
                'otpdate' => NOW(),
                'varified' => 'no'
            ]);
            $ap = ApInvoicesAllOldTemp::where('invoicenumber', $invoice_number)->where('supplierid',$supplier_id)->first();
            $supplier = ApSuppliers::where('supplierid',$supplier_id)->first();
            $userarr = [
                'phone' => "+91".$supplier->mobile1,
                'verification_code' => $ap->otp
            ];
            $user = json_decode(json_encode((object) $userarr), FALSE);

            $otpController = new OTPVerificationController;
            $otpController->send_code($user);
        }
        $OldTemp = ApInvoicesAllOldTemp::select('invoiceid','Total','otp','otpdate','varified')->where('invoicenumber',$invoice_number)->first();
     
        $invoiceidtmp  = $OldTemp->invoiceid;
        $amount = $OldTemp->Total;
        $otp = $OldTemp->otp;
        $otpdate = $OldTemp->otpdate;
        $varified = $OldTemp->varified;
      
        return view('payables.resend',compact('invoice_number', 'supplier_id', 'invoiceidtmp', 'amount', 'otp', 'otpdate','varified'));

        
    }

    public function otpreSend(Request $request)
    {
        
        $invoice_number =$request->invoicenumber;
        $supplier_id =$request->supplier_id;
        $payment = $request->payment;
     
        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_order')->first()->value){
            $user_up = ApInvoicesAllOldTemp::where('invoicenumber', $invoice_number)->where('supplierid',$supplier_id)->update([
                'Total' => $payment,
                'otp' => rand(100000, 999999),
                'otpdate' => NOW(),
                'varified' => 'no'
            ]);
            $ap = ApInvoicesAllOldTemp::where('invoicenumber', $invoice_number)->where('supplierid',$supplier_id)->first();
            $supplier = ApSuppliers::where('supplierid',$supplier_id)->first();
            $userarr = [
                'phone' => "+91".$supplier->mobile1,
                'verification_code' => $ap->otp
            ];
            $user = json_decode(json_encode((object) $userarr), FALSE);

            $otpController = new OTPVerificationController;
            $otpController->send_code($user);

        }
        $OldTemp = ApInvoicesAllOldTemp::select('invoiceid','Total','otp','otpdate','varified')->where('invoicenumber',$invoice_number)->first();
     
        $invoiceidtmp  = $OldTemp->invoiceid;
        $amount = $OldTemp->Total;
        $otp = $OldTemp->otp;
        $otpdate = $OldTemp->otpdate;
        $varified = $OldTemp->varified;
      return 'true';
        //return view('payables.resend',compact('invoice_number', 'supplier_id', 'invoiceidtmp', 'amount', 'otp', 'otpdate','varified'));

        
    }

    public function verifyotp(Request $request){
        $invoicenumber =$request->invoicenumber;
        $supplier_id =$request->supplier_id;
        $payment = $request->payment;
        $otp = $request->otp;
        $OldTemp = ApInvoicesAllOldTemp::select('invoiceid','invoicenumber','invoicetype','supplierid','Date','Status','creationdate','Total','otp','otpdate','varified','lastupdateby','lastupdatedate','quantity', 'store_id')->where('invoicenumber',$invoicenumber)->first();
       
        if ($OldTemp->otp == $request->otp) {
            $user_up = ApInvoicesAllOldTemp::where('invoicenumber', $invoicenumber)->where('supplierid',$supplier_id)->update([
                'Total' => $payment,
                'otp' => rand(100000, 999999),
                'otpdate' => NOW(),
                'varified' => 'yes',
                'invoicetype' => 'S',
                'Date' => NOW(),
                'lastupdateby' => Auth::user(),
                'lastupdatedate' => NOW()
            ]);
            $all =  $user_up = ApInvoicesAlls::where('invoice_number', $invoicenumber)->where('supplier_id',$supplier_id)->first();
           if($all)
           {
            $insert_all = ApInvoicesAlls::where('invoice_number',$invoicenumber)->update([
                'invoice_number' => $OldTemp->invoicenumber,
                //'invoiceid' => $OldTemp->invoiceid,
                'invoicetype' => $OldTemp->invoicetype,
                'supplier_id' => $OldTemp->supplierid,
                'Date' => $OldTemp->Date,
                'Status' => $OldTemp->Status,
                'creationDate' => $OldTemp->creationdate,
                'Total' => $OldTemp->Total,
                'otp' => $OldTemp->otp,
                'otpdate' => $OldTemp->otpdate,
                'varified' => $OldTemp->varified,
                'lastUupdateby' => $OldTemp->lastupdateby,
                'lastupdatedate' => $OldTemp->lastupdatedate,
                'quantity' => $OldTemp->quantity,
                'store_id' => $OldTemp->store_id,

            ]);
           }
           else
           {
            $insert_all = ApInvoicesAlls::insert([
                'invoice_number' => $OldTemp->invoicenumber,
                //'invoiceid' => $OldTemp->invoiceid,
                'invoicetype' => $OldTemp->invoicetype,
                'supplier_id' => $OldTemp->supplierid,
                'Date' => $OldTemp->Date,
                'Status' => $OldTemp->Status,
                'creationDate' => $OldTemp->creationdate,
                'Total' => $OldTemp->Total,
                'otp' => $OldTemp->otp,
                'otpdate' => $OldTemp->otpdate,
                'varified' => $OldTemp->varified,
                'lastUupdateby' => $OldTemp->lastupdateby,
                'lastupdatedate' => $OldTemp->lastupdatedate,
                'quantity' => $OldTemp->quantity,
                'store_id' => $OldTemp->store_id,

            ]);

           }
          
            
            $invoiceid= ApInvoicesAlls::where('invoice_number',$invoicenumber)->first();
          //return json_decode($invoiceid);
            //  return redirect('/admin/APinvoice_header_workbench_old2/view/'. $invoiceid.'/'.$supplier_id); 
          return redirect('/admin/APinvoice_header_workbench_old2/view/'. $invoiceid->invoiceid.'/'.$supplier_id);
          
        }
        else
        {
           // return redirect('/admin/APinvoice_header_workbench/resend/'. $invoicenumber.'/'.$supplier_id); 
            return $this->resend($invoicenumber,$supplier_id);
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
                $lastorderId = Order::orderBy('id', 'desc')->first()->invoice_number;
                // print_r($lastorderId); die();
                if($lastorderId == '')
                {
                    $newInvcNum = '1001';
                }
                else
                {
                    // Get last 3 digits of last order id
                    $lastIncreament = substr($lastorderId, -4);
    
                    // Make a new order id with appending last increment + 1
                    $newInvcNum = str_pad($lastIncreament + 1, 4, 0, STR_PAD_LEFT);
                }
                $order->invoice_number =  $newInvcNum ;
                if($order->save()){
                    $data= array('status'=>'true', 'newInvcNum' => $newInvcNum, 'user_id' =>$insertedId, 'order_id' => $order->id, 'cust_id'=>$insertedId, 'order_code'=> $order->code, 'description' => $request->description);
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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
       
         return view('payables.search_APInvoice');
    }

    public function APInvoiceSearchData(Request $request)
    {
       $search_data=$this->searchInvoice($request->keyword);
        //return $search_data;
        return view('payables.search_APInvoice',compact('search_data'));
    }

    public function searchInvoice($keyword)
	{
        
        $data = DB::table('ap_invoices_alls as a')
            ->select('c.name','c.mobile1', 'c.supplierid', 'a.invoice_number','a.Status','a.Total','a.invoiceid','c.type')
            ->join('ap_suppliers as c','c.supplierid','=','a.supplier_id', 'left')
            ->where('a.invoice_number', 'like', '%' . $keyword . '%')
            ->orwhere('c.name', 'like', '%' . $keyword . '%')
            ->orwhere('c.mobile1', 'like', '%' . $keyword . '%')
            ->orderBy('a.invoiceid', 'desc')
            ->paginate(15);
       return $data;
       //print_r($data); die();
       
        $items = [];
        
        foreach($data as $data_item)
        {
            $item = [
                "InvoiceNumber" =>  $data_item->invoice_number,
                "Name"      =>  $data_item->name,
                "Mobile1"   =>  $data_item->mobile1,
                "Amount"    =>  $data_item->Total,
                "Status"    =>  $data_item->Status,
                "InvoiceID" =>$data_item->invoiceid,
                "SupplierID" => $data_item->supplierid
            ];

            array_push($items, $item);
        }

        return $items;
	}
	


    public function instantAp()
    {
       
         return view('payables.instantap');
    }
    public function instantApUpdate($invoiceid)
    {
        $invoice_id = $invoiceid;
        $data = ApInvoicesAlls::where('invoiceid', $invoice_id)->first();
      // return json_encode($data);
         return view('payables.instantapupdate',compact('data'));
    }

    public function InstantapUpdateStore(Request $request)
    {
        //return json_encode($request->all());
        $msg='';
        $title=$request->title;
        $invnum=isset($request->invnum)?'ins-'.$request->invnum:'ins-'.(rand(1000000,9999999));
        $amount=$request->amount;
        $paydate = $invdate=$request->invdate;
        
        
        $suppliers=$request->suppliers;
        $description=$request->description;
        $msg="";
        $aptype=$request->aptype;
        $store_id=$request->store_id;
        $invoice_id = $request->invoiceid; 
        $payaccount = $request->payaccount;
        $image = $request->previous_img;
        if($request->hasFile('image')){
            $image = $request->image->store('public/invoices');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        }
        if($image != ''){
        
        
        $update_instant = ApInvoicesAlls::where('invoiceid',$invoice_id)->update([
            'Total' =>$amount,
            'paydate' =>$paydate,
            'payaccount' =>$payaccount,
            'description' => $description,
            'title' => $title,
            'store_id' => $store_id,
            'type' => $aptype,
            'image' =>$image
        ]);
       
        }else{
            $update_instant = ApInvoicesAlls::where('invoiceid',$invoice_id)->update([
                'Total' =>$amount,
                'paydate' =>$paydate,
                'payaccount' =>$payaccount,
                'description' => $description,
                'title' => $title,
                'store_id' => $store_id,
                'type' => $aptype
            ]);
        }
     
        $update_line = ApInvoiceLines::where('invoice_id',$invoice_id)->update([
            'mrp' =>$amount
            
        ]);
      
        if($update_line)
        {
            return redirect('/admin/APinvoice_header_workbench/view/'. $invoice_id.'/'.'102602');
        
        }


    }
    public function invoiceView($image)
    {
        return view('payables.invoiceview',compact('image'));

    }
    public function InstantapStore(Request $request)
    {
       //return  json_encode($request->all());
        $msg='';
        $store_id = isset($request->store_id)?$request->store_id:'1';
        
        $title=$request->title;
        $invnum=isset($request->invnum)?'ins-'.$request->invnum:'ins-'.(rand(1000000,9999999));
        $amount=$request->amount;
        $invdate=$request->invdate;
        if ($request->file('image') == null) {
            $image = "";
        }else{
           $image = $request->file('image')->store('public/invoices');  
        }
        
       
      
        $invoice=$request->invoice;
        $suppliers=$request->suppliers;
        
        $description=$request->description;
        $payaccount = $request->payaccount;
        $msg="";
        $aptype=$request->aptype;
        $store_id=$request->store_id;
        // $target_file = getcwd()."/APInvoices/".basename($_FILES["invoice"]["name"]);
        // $image = basename($_FILES["invoice"]["name"]);
        // move_uploaded_file($_FILES["invoice"]["tmp_name"], $target_file);
        $lastId = ApInvoicesAlls::insertGetId([
            'invoice_number' =>$invnum,
            'invoicetype' => 'S',
            'supplier_id' => '102602',
            'Date'=>Date('Y-m-d'),
            'Status' => 'P',
            'igst' => null,
            'sgst' => null,
            'cgst' => null,
            'gst' => null,
            'quantity' =>null,
            'Total' => $amount,
            'totalwithoutgst' =>NULL,
            'payinfo' =>NULL,
            'paydate' => $invdate,
            'bank_commission' => '0',
            'payaccount' => $payaccount,
            'image' => $image,
            'creationDate' => NULL,
            'description' => $description,
            'modeofpayment' => 'Clearing',
            'otp' => NULL,
            'otpdate' => NULL,
            'varified' => NULL,
            'lastupdatedate' => NOW(),
            'lastUupdateby' => Auth::user(),
            'title' => $title,
            'store_id' => '1',
            'type' => $aptype
        ]);

      
        $qry = ApInvoiceLines::insert([
            'lineid' => Null,
            'invoice_id' => $lastId,
            'product_id' => '9577',
            'version' => 'N',
            'quantity' => '1',
            'mrp' => $amount,
            'cp' => Null,
            'isdeleted' => 'N',
            'lastupdatedby' => Auth::user(),
            'lastupdateddate' => NOW(),
            'arinvoice' => Null

        ]);
        
        

        if($qry)
        {
        return redirect('/admin/APinvoice_header_workbench/view/'. $lastId.'/'.'102602');
      
        }



    }


    public function APInvoice($invoice_id)
        {
            $invoice_id=$invoiceID=$invoice_id;
          
            $invoice_header_data1 = ApInvoicesAlls::where('invoiceid',$invoice_id)->first();
            $invoice_line_data = [];
            $invoice_number = $invoice_header_data1->invoice_number;
            // get book detail for displaying invoice line
            $invoice_line= DB::table('ap_invoice_lines as a')
            ->select('a.lineid','b.isbn', 'b.oldisbn', 'b.name', 'b.author_id','b.updated_at','a.quantity','a.version','a.cp','a.mrp','a.lastupdateddate as lastline')
            ->join('products as b','b.id','=','a.product_id', 'left')
            ->where('a.invoice_id', $invoice_id)
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
                    "Version"      =>  $data_item->version ,
                   
                    ];
    
                array_push($invoice_line_data, $item);
            }
            $invoice_header_data = [];
            $invoice_header= DB::table('ap_invoices_alls as a')
            ->select('a.invoice_number','s.mobile1','s.email1','s.city','s.zipcode','s.address1','s.address2', 's.State', 'a.Date', 's.name', 'a.description','a.Status')
            ->join('ap_suppliers as s','a.supplier_id','=','s.supplierid', 'left')
            ->where('a.invoiceid', $invoice_id)
            ->get();
           
           
        
            foreach($invoice_header as $data_item)
            {
                $item = [
                    "InvoiceNumber" =>  $data_item->invoice_number,
                    "Name"      =>  $data_item->name ,
                    "Mobile1" =>    $data_item->mobile1 ,
                    "Email1"      =>  $data_item->email1 ,
                    "Address1"      =>  $data_item->address1 ,
                    "Address2"      =>  $data_item->address2 ,
                    "State"      =>  $data_item->State ,
                    "Date"      =>  $data_item->Date ,
                    "Status"      =>  $data_item->Status ,
                    "Description"      =>  $data_item->description ,
                    "City"      =>  $data_item->city ,
                    "zipcode"      =>  $data_item->zipcode 
                    ];
    
                array_push($invoice_header_data, $item);
            }

            //print_r($invoice_header_data); die();
            return view('payables.APInvoice',compact('invoice_id','invoice_header_data1', 'invoice_number', 'invoice_line_data', 'invoice_header_data'));
        }


        public function APInvoiceold($invoice_id)
        {
            $msg='';
            
            // $invoice_id=$invoiceID=$invoice_id;
            
                $data = ApInvoicesAlls::where('invoiceid',$invoice_id)->first();
              
                $invoice_number = $data->invoice_number;
               
                $supplier_id = $data->supplier_id;
           
          
            $invoice_header_data = [];
            $invoice_header= ApInvoicesAlls::where('invoice_number',$invoice_number)->where('supplier_id', $supplier_id)->get();
            foreach($invoice_header as $data_item)
            {
                $item = [
                    "SupplierID" => $data_item->supplier_id,
                    "InvoiceNumber" =>  $data_item->invoice_number,
                    "Total" => $data_item->Total,
                    "Date"      =>  $data_item->Date ,
                    "Status"      =>  $data_item->Status ,
                    "Description"      =>  $data_item->description ,
                    ];
    
                array_push($invoice_header_data, $item);
            }
            $supplier= ApSuppliers::where('supplierid',$supplier_id)->first();
            
            //print_r($invoice_header_data); die();
         
            $invoice_line_data= [];
            $invoice_line= DB::table('ap_invoice_lines as a')
            ->select('a.lineid','b.isbn', 'b.oldisbn', 'b.name', 'b.author_id','b.updated_at','a.quantity','a.version','a.cp','a.mrp','a.lastupdateddate as lastline')
            ->join('products as b','b.id','=','a.product_id', 'left')
            ->where('a.invoice_id', $invoice_id)
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
                    "Version"      =>  $data_item->version ,
                    "Mrp"      =>  $data_item->mrp ,
                    "Cp"      =>  $data_item->cp,
                    "lastupdateddate"      =>  $data_item->updated_at ,
                    "lastline"      =>  $data_item->lastline ,
                   "LineID" => $data_item->lineid
                    ];
    
                array_push($invoice_line_data, $item);
            }
          //   print_r($invoice_line_data); die();
            return view('payables.APInvoiceold',compact('supplier','supplier_id','invoice_id', 'invoice_number', 'invoice_line_data', 'invoice_header_data'));
        }
    /**
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msg="";    
       // return json_encode($request->all());
        $date=date('Y-m-d',strtotime($request->date));
        $totalwithoutgst = intval($request->taxon0) + intval($request->taxon5)+intval($request->taxon12)+intval($request->taxon18)+intval($request->taxon28);
        $tax = intval($request->gst)+intval($request->igst);
        $amountwithgst = intval($request->totalwithoutgst)+$tax;
        if(isset($request->nogst) && $request->nogst='nogst'){
            if($request->totalwithoutgst != $request->amount ){
                   
                }
            
            }else{
                if((intval($totalwithoutgst) - intval($request->totalwithoutgst)) <= -2 || (intval($totalwithoutgst) - intval($request->totalwithoutgst)) >= 2){
            
            }
            if(($amountwithgst - intval($request->amount)) <= -2 || ($amountwithgst - intval($request->amount)) >= 2){
           
            }
        }
        $invoice_number=$request->invoice_number;
        $amount =$request->amount;
        if($request->invoicetype == 'C'){
            $invoice_number = "CN".$request->supplier.date('Ymd').rand(1000,9999);
            if($request->amount > 0){ $amount =  -($request->amount); }
            
        }
        // if(strpos($invoice_number,'&')!=false) 
        // $msg="Ampersand ('&') not allowed in Invoice Number"; 
        // return redirect('/admin/APinvoice_header_workbench/create')->with('msg', $msg);
        //
        $supplier_id=$request->supplier;
        if($msg=="")
        {
            //echo $_POST['invoicetype'];die;
            $igst = $request->igst;
            $sgst = $request->sgst;
            $cgst = $request->cgst;
            $gst = $sgst + $cgst;

            if ($request->file('image') == null) {
                $image = "";
            }else{
               $image = $request->file('image')->store('public/invoices');  
            }
            
            $arr = array(
            'invoice_number'=>$invoice_number,
            'invoicetype'=>$request->invoicetype,
            'supplier_id'=>$supplier_id,
            'date'=>$date,
            'status'=>'O',
            'gst'=>$request->gst,
            'cgst'=>$request->cgst,
            'sgst'=>$request->sgst,
            'igst'=>$request->igst,
            'gst5'=>$request->gst5,
            'cgst2_5'=>$request->cgst2_5,
            'sgst2_5'=>$request->sgst2_5,
            'igst5'=>$request->igst5,
            'gst12'=>$request->gst12,
            'cgst6'=>$request->cgst6,
            'sgst6'=>$request->sgst6,
            'igst12'=>$request->igst12,
            'gst18'=>$request->gst18,
            'cgst9'=>$request->cgst9,
            'sgst9'=>$request->sgst9,
            'igst18'=>$request->igst18,
            'gst28'=>$request->gst28,
            'cgst14'=>$request->cgst14,
            'sgst14'=>$request->sgst14,
            'igst28'=>$request->igst28,
            'taxon0'=>$request->taxon0,
            'taxon5'=>$request->taxon5,
            'taxon12'=>$request->taxon12,
            'taxon18'=>$request->taxon18,
            'taxon28'=>$request->taxon28,
            'Total'=>$amount,
            'totalwithoutgst'=>$request->totalwithoutgst,
            'image'=>$image,
            'creationDate'=>date('Y-m-d H:i:s'),
            'description'=>$request->description,
            'lastupdatedate'=>date('Y-m-d H:i:s'),
            'lastUupdateby'=>Auth::user(),
            'type'=>$request->aptype
            );
           
            $insert_ap = DB::table('ap_invoices_alls')->insertGetId($arr);
            //$insert_ap->save();
           
            if($insert_ap) 
            
            return redirect('/admin/APinvoice_header_workbench/view/'. $insert_ap.'/'.$supplier_id);
          
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\APInvoiceAlls  $aPInvoiceAlls
     * @return \Illuminate\Http\Response
     */
    public function show(APInvoiceAlls $aPInvoiceAlls)
    {
        //
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
    public function APinvoiceView($invoice_number,$supplier_id)
    {
        $invoiceID=$invoice_number;
        $invoice_header_data =ApInvoicesAlls::where('invoiceid',$invoice_number)->first();
        
        // get book detail for displaying invoice line
        // $invoice_line_data = array();
        $invoice_line_data=DB::table('products as b')
        ->select('a.lineid', 'b.oldisbn', 'b.id as proid','a.variation', 'b.isbn','b.name','b.author_id','b.updated_at','a.quantity','a.version','a.cp','a.mrp','a.lastupdateddate')
        ->join('ap_invoice_lines as a', 'b.id', '=', 'a.product_id', 'left')
        ->where('a.invoice_id','=',$invoiceID)
        ->where('a.isdeleted','=', 'N')
        ->orderBy('a.lineid', 'asc')
        ->get();
       // return json_encode($invoice_line_data);
       
       
                $invoice_number = $invoice_header_data->invoice_number;
                $invoice_id=$invoice_header_data->invoiceid;
                $supplier_id=$invoice_header_data->supplier_id;
                $supplier_data = ApSuppliers::where('isdeleted','N')->where('type','b')->orderby('name','asc')->get();
        //print_r($order); die();
        return view('payables.APinvoice_header_workbench_view',compact('invoice_header_data','invoice_line_data', 'invoice_number','invoice_id','supplier_id','supplier_data'))->with('msg', null);
    }


    public function APInvoiceViewOld2($invoice_number,$supplier_id)
    {
        $msg='';
        $supplier_id=$supplier_id;
       $invoiceID=$invoice_number;
        $invoice_header_data =ApInvoicesAlls::where('invoiceid',$invoice_number)->first();
        
        // get book detail for displaying invoice line
        // $invoice_line_data = array();
        $invoice_line_data=DB::table('products as b')
        ->select('a.lineid', 'b.oldisbn', 'b.id as proid','a.variation', 'b.isbn','b.name','b.author_id','b.updated_at','a.quantity','a.version','a.cp','a.mrp','a.lastupdateddate')
        ->join('ap_invoice_lines as a', 'b.id', '=', 'a.product_id', 'left')
        ->where('a.invoice_id','=',$invoiceID)
        ->where('a.isdeleted','=', 'N')
        ->orderBy('a.lineid', 'asc')
        ->get();
       // return json_encode($invoice_line_data);
       
       
                $invoice_number = $invoice_header_data->invoice_number;
                $invoice_id=$invoice_header_data->invoiceid;
                $supplier_id=$invoice_header_data->supplier_id;
                $supplier_data = ApSuppliers::where('isdeleted','N')->where('type','b')->orderby('name','asc')->get();
       // print_r($supplier_data); die();
        return view('payables.APinvoice_header_workbench_old2_view',compact('invoice_header_data','invoice_line_data', 'invoice_number','invoice_id','supplier_id','supplier_data'))->with('msg', null);
    }

    public function APinvoiceViewOld($invoice_number,$supplier_id)
    {
        //$invoice_line_data =array();
        $supplier_id=$supplier_id;
        $invoiceID=$invoice_number;
        $invoice_header_data =ApInvoicesAlls::where('invoiceid',$invoice_number)->first();
       // $invoice_header_data =ApInvoicesAlls::where('invoice_number',$invoice_number)->first();
        
        // get book detail for displaying invoice line
        // $invoice_line_data = array();
        $invoice_line_data=DB::table('products as b')
        ->select('a.lineid', 'b.oldisbn', 'b.id as proid','a.variation', 'b.isbn','b.name','b.author_id','b.updated_at','a.quantity','a.version','a.cp','a.mrp','a.lastupdateddate')
        ->join('ap_invoice_lines as a', 'b.id', '=', 'a.product_id', 'left')
        ->where('a.invoice_id','=',$invoiceID)
        ->where('a.isdeleted','=', 'N')
        ->orderBy('a.lineid', 'asc')
        ->get();
        //return json_encode($invoice_line_data);

       
                $invoice_number = $invoice_header_data->invoice_number;
                $invoice_id=$invoice_header_data->invoiceid;
                $supplier_id=$invoice_header_data->supplier_id;
                $supplier_data = ApSuppliers::where('isdeleted','N')->where('type','b')->orderby('name','asc')->get();
        //print_r($order); die();
        return view('payables.APinvoice_header_workbench_old_view',compact('invoice_header_data','invoice_line_data', 'invoice_number','invoice_id','supplier_id','supplier_data'))->with('msg', null);
    }




    public function getBookDetailInvoice(Request $request)
    {
       
        $keyword=$request->keyword;
      
        $trxn=$request->trxn;
        $vart=$request->vart;
       
        $store_id = isset($_REQUEST['store_id'])?$_REQUEST['store_id']:'1';
        
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
         if($vart == 'null'){
            
            // $discount = $product->discount;
            $qty = $product->current_stock;
            $onrent = $product->onrent;
         if ($qty > 0)
         {
             
             $qty_info ="In Stock";
             //$output='<tr class="brent"><td>Selling Price*</td><td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="SellingPrice" value="'.$erpprice.'" required="required" /></td><td>,Book: '.$product->name.'-'.$isbn.'</td></tr><tr class="bmrp"><td>MRP:</td><td><input type="text" name="mrp" value="'.$mrp.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" /> , Qty available : '.$qty.'<span>('.$qty_info.')</span><input type="hidden" name="qty_a" value="'.$qty.'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> <label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
             $output='<tr><td>Book: '.$product->name.'&nbsp; Author: '.$name.' <input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="hidden" name="qty_a" value="'.$qty.'" /></td></tr>';
            echo $output;
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
            
            $qty = $productV->qty;
            $onrent = 'no';
            if ($qty > 0){
           
            $qty_info ="In Stock";
            //$output='<tr class="brent"><td>Selling Price*</td><td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="SellingPrice" value="'.$price.'" required="required" /></td><td>,Book: '.$product->name.'-'.$isbn.'</td></tr><tr class="bmrp"><td>MRP:</td><td><input type="text" name="mrp" value="'.$mrp.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" /> , Qty available : '.$qty.'<span>('.$qty_info.')</span><input type="hidden" name="qty_a" value="'.$qty.'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> <label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
            $output='<tr><td>Book: '.$product->name.'&nbsp; Author: '.$name.' &nbsp; Variant:'.$productV->variant.'&nbsp; <input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="hidden" name="qty_a" value="'.$qty.'" /></td></tr>';
            echo $output;
            
        }else{
            $qty_info ="Out Stock";
           
              return 'out';
            }
            
        
        }
    }
    


    public function getBookDetailInvoiceOld(Request $request)
    {
       
        $keyword=$request->keyword;
      
        $trxn=$request->trxn;
        $vart=$request->vart;
       
        $store_id = isset($_REQUEST['store_id'])?$_REQUEST['store_id']:'1';
        
       $product = Product::where('id',$keyword)->first();
       $author = Author:: where('id',$product->author_id)->first();
          // $discount = $product->discount;
        if($author)
        {
            $name =  $author->name;;
        }
        else
        {
           $name = '';
        }
         if($vart == 'null'){
            $qty = $product->current_stock;
            $onrent = $product->onrent;
         if ($qty > 0)
         {
             
            $qty_info ="In Stock"; 
           
             $output='<tr><td>Book: '.$product->name.'&nbsp; Author: '.$name.'<input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="hidden" name="qty_a" value="'.$qty.'" /></td></tr><tr><td>MRP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="mrp" id="mrp" value="'.$product->mrp.'" /></td></tr>';
            echo $output; 
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
            $qty = $productV->qty;
            $onrent = 'no';
            if ($qty > 0){
           
            $qty_info ="In Stock";
            //$output='<tr class="brent"><td>Selling Price*</td><td><input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="text" name="SellingPrice" value="'.$price.'" required="required" /></td><td>,Book: '.$product->name.'-'.$isbn.'</td></tr><tr class="bmrp"><td>MRP:</td><td><input type="text" name="mrp" value="'.$mrp.'" /></td><td>, Sale/Rent : '.$onrent.'<input type="hidden" name="sale_rent" value="'.$onrent.'" /> , Qty available : '.$qty.'<span>('.$qty_info.')</span><input type="hidden" name="qty_a" value="'.$qty.'" /><input type="hidden" name="prepay" value="'.$isbn.'" /> <label>Special Discount</label><input type="text" name="special_discount" id="special_discount" required value="'.$discount.'" />%</td></tr>';
            $output='<tr><td>Book: '.$product->name.'&nbsp; Author: '.$name.' &nbsp;Variant:'.$productV->variant.'  <input type="hidden" name="vart" id="vart" value="'.$vart.'"><input type="hidden" name="qty_a" value="'.$qty.'" /></td></tr>
            <tr><td>MRP
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" name="mrp" id="mrp" value="'.$productV->mrp.'" /></td></tr>';
            echo $output;
            
        }else{
            $qty_info ="Out Stock";
           
              return 'out';
            }
            
        
        }
    }
    




    public function ProductSave(Request $request)
    {
        
        $msg="";
        $version=$request->version;
        $vart = $request->vart;  
        $quantity=(isset($request->qty) && $request->qty !='')?$request->qty:0; 
        $book_id=$request->keyword;
        $available_quantity=$request->qty_a;
        $invoice_id = $request->invoice_id;
        $supplier_id = $request->supplier_id;
        $new_qty = $available_quantity + $quantity;
        if($vart == 'null')
        {
            if($msg=="")
            {
               
                $success= ApInvoiceLines::insert([
                    'lineid' => Null,
                    'invoice_id' => $invoice_id,
                    'product_id' => $book_id,
                    'version' => $version,
                    'quantity' => $quantity,
                    'isdeleted' => 'N',
                    'lastupdatedby' => Auth::user(),
                    'lastupdateddate' => NOW()
    
                ]);
            
                if($success) {
                    $qty_update = Product::where('id',$book_id)->update([
                        'current_stock' => $new_qty
                    ]);
                $msg="Line Added Successfully";
                if($request->old=='old')
                {
                    return redirect('/admin/APinvoice_header_workbench_old2/view/'.$invoice_id.'/'.$supplier_id)->with('msg', $msg);
                }
                return redirect('/admin/APinvoice_header_workbench/view/'.$invoice_id.'/'.$supplier_id)->with('msg', $msg);
    
               
                }
            }
            else
            {
                die('els');
            }
          


        }
        else
        {
            $variation = ProductStock:: where('id',$vart)->first();
            if($msg=="")
            {
               
                $success= ApInvoiceLines::insert([
                    'lineid' => Null,
                    'invoice_id' => $invoice_id,
                    'product_id' => $book_id,
                    'variation' => $variation->variant,
                    'version' => $version,
                    'quantity' => $quantity,
                    'isdeleted' => 'N',
                    'lastupdatedby' => Auth::user(),
                    'lastupdateddate' => NOW()
    
                ]);
            
                if($success) {
                    $qty_update = ProductStock::where('id', $variation->id)->update([
                        'qty' => $new_qty
                    ]);
                $msg="Line Added Successfully";
                return redirect('/admin/APinvoice_header_workbench/view/'.$invoice_id.'/'.$supplier_id)->with('msg', $msg);
    
            // $inventory_book_update=$bookdal->updateBookQuantity($qty_inventory,$book_id);
            
               
                }
            }
            else
            {
                die('els');
            }
           
        }
       
       
       
      
    }



    public function ProductSaveOld(Request $request)
    {
        
        $msg="";
        $version='O';
        $quantity=(isset($request->qty) && $request->qty !='')?$request->qty:0; 
        $book_id=$request->keyword;
        $vart = $request->vart;  
        $available_quantity=$request->qty_a;
        $invoice_id = $request->invoice_id;
        $supplier_id = $request->supplier_id;
        $mrp=$request->mrp;
        $cp=$request->cp;
       
        // $qty_inventory to be updated
        $new_qty=$available_quantity-$quantity;
        //
        if($quantity<1) 
        {
            $msg="Quantity should be more than 0";
        return redirect('/admin/APinvoice_header_workbench/view/'.$invoice_id.'/'.$supplier_id)->with('msg', $msg);
        }
        if($vart == 'null')
        {
            if($msg=="")
            {
                
            

                $success= ApInvoiceLines::insert([
                    'lineid' => Null,
                    'invoice_id' => $invoice_id,
                    'product_id' => $book_id,
                    'version' => $version,
                    'quantity' => $quantity,
                    'mrp' => $mrp,
                    'cp' => $cp,
                    'isdeleted' => 'N',
                    'lastupdatedby' => Auth::user(),
                    'lastupdateddate' => NOW()

                ]);
            
                if($success) {
                    $qty_update = Product::where('id',$book_id)->update([
                        'current_stock' => $new_qty
                    ]);
                $msg="Line Added Successfully";
                return redirect('/admin/APinvoice_header_workbench_old/view/'.$invoice_id.'/'.$supplier_id)->with('msg', $msg);

            // $inventory_book_update=$bookdal->updateBookQuantity($qty_inventory,$book_id);
            
            
                }
            }
            else
            {
                die('els');
            }
        }
        else
        {
            $variation = ProductStock:: where('id',$vart)->first();
            if($msg=="")
            {
                
            

                $success= ApInvoiceLines::insert([
                    'lineid' => Null,
                    'invoice_id' => $invoice_id,
                    'product_id' => $book_id,
                    'variation' => $variation->variant,
                    'version' => $version,
                    'quantity' => $quantity,
                    'mrp' => $mrp,
                    'cp' => $cp,
                    'isdeleted' => 'N',
                    'lastupdatedby' => Auth::user(),
                    'lastupdateddate' => NOW()

                ]);
            
                if($success) {
                    $qty_update = ProductStock::where('id', $variation->id)->update([
                        'qty' => $new_qty
                    ]);
                $msg="Line Added Successfully";
                return redirect('/admin/APinvoice_header_workbench_old/view/'.$invoice_id.'/'.$supplier_id)->with('msg', $msg);

            // $inventory_book_update=$bookdal->updateBookQuantity($qty_inventory,$book_id);
            
            
                }
            }
            else
            {
                die('els');
            }

        }
    }


    public function saveApInvoice(Request $request)
    {
        
        $query = ApInvoicesAlls::where('invoiceid', $request->invoice_id)->update([
            'description' => $request->desc,
            'modeofpayment' => $request->modepayment,
            'paydate' => $request->paydate,
            'payinfo' => $request->payinfo,
            'bank_commission' => $request->bank_commission,
            'payaccount' => $request->payaccount,
            'Status' => 'O'
        ]);
        if($query)
        {
            return "true";
        }
        
    }
    

    public function statusChangeOrder(Request $request)
    {
        //return json_encode($request);
                $invoiceid = $request->invoiceid;
                $status = $request->status;

                $changeStatus = ApInvoicesAlls::where('invoiceid',$invoiceid)->update([
                    'Status' => $status
                ]);
               // return json_encode($changeStatus);
                if($changeStatus)
                {
                    return '1';
                }
    }

    public function closeApInvoice(Request $request)
    {
        
        $query = ApInvoicesAlls::where('invoiceid', $request->invoice_id)->update([
            'description' => $request->desc,
            'modeofpayment' => $request->modepayment,
            'paydate' => $request->paydate,
            'payinfo' => $request->payinfo,
            'Status' => 'P',
            'bank_commission' => $request->bank_commission,
            'payaccount' => $request->payaccount
        ]);
        if($query)
        {
            return "true";
        }
        
    }

    public function ProductUpdate(Request $request)
    {
        
        $msg="";
        $sts="";
        $invoiceID = $request->InvoiceID; 
        $supplier_id = $request->supplier_id;
        $supplier = $request->supplier;
        $invoice_number = $request->invoice_number;
        $date = $request->date;
        $arr = explode("-",$date);
        if($arr[0] == '-' || $arr[0] == ''){unset($arr[0]);}
        if(isset($arr[4]) && ($arr[4] == '-' || $arr[4] == '')){unset($arr[4]);}
        $date = implode("-",$arr);
        $invoicetype = $request->invoicetype;
        $totalwithoutgst = intval($request->taxon0) + intval($request->taxon5)+intval($request->taxon12)+intval($request->taxon18)+intval($request->taxon28);
        $tax = intval($request->gst)+intval($request->igst);
        $amountwithgst = intval($request->totalwithoutgst)+$tax;
        if(isset($$request->nogst) && $request->nogst='nogst'){
            if($request->totalwithoutgst != $request->amount ){
               
                   
                    return redirect('/admin/APinvoice_header_workbench/view/'.$invoiceID.'/'.$supplier_id)->with('msg', $msg);
                    
                }
            
            }else{
                if((intval($totalwithoutgst) - intval($_POST['totalwithoutgst'])) <= -2 || (intval($totalwithoutgst) - intval($request->totalwithoutgst)) >= 2){
            //error
            return redirect('/admin/APinvoice_header_workbench/view/'.$invoiceID.'/'.$supplier_id)->with('msg', $msg);
            
            }
        if(($amountwithgst - intval($request->amount)) <= -2 || ($amountwithgst - intval($request->amount)) >= 2){
            //error
            return redirect('/admin/APinvoice_header_workbench/view/'.$invoiceID.'/'.$supplier_id)->with('msg', $msg);
          
            }
                
            }
            $amount = $request->amount;
            if( $amount > 0 ){
            $amount = -($amount);
            }
            
            $status = $request->status;
            $description = $request->description;
            $duplicate= ApInvoicesAlls::where('invoice_number', $invoice_number)->where('supplier_id',$supplier_id)->first();
          // print_r($duplicate); die();
            if(! empty($duplicate))
            {
            
                $duplicate_check = $duplicate->count();
                
                
                if($duplicate_check>0)
                {
                $msg="Duplicate Invoice Number for Selected Supplier !!";
                $sts = 'error';
               
                return redirect('/admin/APinvoice_header_workbench/view/'.$invoiceID.'/'.$supplier_id)->with('msg', $msg);
                }
            }  
            
           
            $igst = $request->igst;

            $sgst = $request->sgst;
            $cgst = $request->cgst;
            $totalwithoutgst = $request->totalwithoutgst;
            $gst = $sgst + $cgst;

            $image = $request->previous_img;
        if($request->hasFile('image')){
            $image = $request->image->store('public/invoices');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        }
            $arr = array(
            'invoice_number'=>$invoice_number,
            'invoicetype'=>$invoicetype,
            'supplier_id'=>$supplier_id,
            'Date'=>$date,
            'Status'=>$status,
            'gst'=>$gst,
            'cgst'=>$cgst,
            'sgst'=>$sgst,
            'igst'=>$igst,
            'gst5'=>$request->gst5,
            'cgst2_5'=>$request->cgst2_5,
            'sgst2_5'=>$request->sgst2_5,
            'igst5'=>$request->igst5,
            'gst12'=>$request->gst12,
            'cgst6'=>$request->cgst6,
            'sgst6'=>$request->sgst6,
            'igst12'=>$request->igst12,
            'gst18'=>$request->gst18,
            'cgst9'=>$request->cgst9,
            'sgst9'=>$request->sgst9,
            'igst18'=>$request->igst18,
            'gst28'=>$request->gst28,
            'cgst14'=>$request->cgst14,
            'sgst14'=>$request->sgst14,
            'igst28'=>$request->igst28,
            'taxon0'=>$request->taxon0,
            'taxon5'=>$request->taxon5,
            'taxon12'=>$request->taxon12,
            'taxon18'=>$request->taxon18,
            'taxon28'=>$request->taxon28,
            'Total'=>$amount,
            'image' => $image,
            'totalwithoutgst'=>$totalwithoutgst,
            'description'=>$description,
            'lastupdateddate'=>date('Y-m-d H:i:s'),
            'lastUupdateby'=>Auth::user(),
            'type'=>$request->aptype

            );
            $updatequery = ApInvoicesAlls::where('invoiceid', $invoiceID)->update($arr);
            if($updatequery){
                //if($apinvoicedal->updateHeader($invoiceID,$invoice_number,$invoicetype,$supplier,$date,$igst,$sgst,$cgst,$gst,$amount,$totalwithoutgst,$status,$imagename,$description)){
                   
                    $msg = "Header Updated successfully";
                    $sts = 'ok';
                    return redirect('/admin/APinvoice_header_workbench/view/'.$invoiceID.'/'.$supplier_id)->with('msg', $msg);
                }else{
                $msg = "ERROR in Header updation";	
                return redirect('/admin/APinvoice_header_workbench/view/'.$invoiceID.'/'.$supplier_id)->with('msg', $msg);
                }
              
            
    }

    public function ProductUpdateold(Request $request)
    {
        $msg="";
        $sts="";
        $invoiceID = $request->InvoiceID; 
        $supplier_id = $request->supplier_id;
        $supplier = $request->supplier;
        $invoice_number = $request->invoice_number;
        $date = $request->date;
        $arr = explode("-",$date);
        if($arr[0] == '-' || $arr[0] == ''){unset($arr[0]);}
        if(isset($arr[4]) && ($arr[4] == '-' || $arr[4] == '')){unset($arr[4]);}
        $date = implode("-",$arr);
        $invoicetype = $request->invoicetype;
       
            $amount = $request->amount;
            if(($invoicetype == 'C') && $amount > 0 ) if( $amount > 0 ){
            $amount = -($amount);
            }
            
            $status = $request->status;
            $description = $request->description;
            $duplicate= ApInvoicesAlls::where('invoice_number', $invoice_number)->where('supplier_id',$supplier_id)->first();
          // print_r($duplicate); die();
            if(! empty($duplicate))
            {
            
                $duplicate_check = $duplicate->count();
                
                
                if($duplicate_check>0)
                {
                $msg="Duplicate Invoice Number for Selected Supplier !!";
                $sts = 'error';
               
                return redirect('/admin/APinvoice_header_workbench_old2/view/'.$invoiceID.'/'.$supplier_id)->with('msg', $msg);
                }
            }  
            
           
            $igst = $request->igst;

            $sgst = $request->sgst;
            $cgst = $request->cgst;
            $totalwithoutgst = $request->totalwithoutgst;
            $gst = $sgst + $cgst;

            $image = $request->previous_img;
        if($request->hasFile('image')){
            $image = $request->image->store('public/invoices');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        }
            $arr = array(
            'invoice_number'=>$invoice_number,
            'invoicetype'=>$invoicetype,
            'supplier_id'=>$supplier_id,
            'Date'=>$date,
            'Status'=>$status,
            'gst'=>$gst,
            'cgst'=>$cgst,
            'sgst'=>$sgst,
            'igst'=>$igst,
            'Total'=>$amount,
            'image' => $image,
            'totalwithoutgst'=>$totalwithoutgst,
            'description'=>$description
            );
            $updatequery = ApInvoicesAlls::where('invoiceid', $invoiceID)->update($arr);
            if($updatequery){
                //if($apinvoicedal->updateHeader($invoiceID,$invoice_number,$invoicetype,$supplier,$date,$igst,$sgst,$cgst,$gst,$amount,$totalwithoutgst,$status,$imagename,$description)){
                   
                    $msg = "Header Updated successfully";
                    $sts = 'ok';
                    return redirect('/admin/APinvoice_header_workbench_old2/view/'.$invoiceID.'/'.$supplier_id)->with('msg', $msg);
                }else{
                $msg = "ERROR in Header updation";	
                return redirect('/admin/APinvoice_header_workbench_old2/view/'.$invoiceID.'/'.$supplier_id)->with('msg', $msg);
                }
              
            
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\APInvoiceAlls  $aPInvoiceAlls
     * @return \Illuminate\Http\Response
     */
    public function edit(APInvoiceAlls $aPInvoiceAlls)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\APInvoiceAlls  $aPInvoiceAlls
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, APInvoiceAlls $aPInvoiceAlls)
    {
        //
    }


    public function destroyLine(Request $request)
    {
         // return json_encode($order_detail);    
        $id = $request->id;
        $invoice_id =$request->invoice_id;
        $supplier_id =$request->supplier_id;
        $val= ApInvoicesAlls::where('invoiceid',$invoice_id)->where('Status','O')->first();
       
        $validate = $val->count();
       
        if($validate==0) return "Invoice is not open, cannot edit the same !!";

        $isdeleted = ApInvoiceLines::select('isdeleted')->where('lineid',$id)->first();
        if($isdeleted == 'Y'&& $request->old=='old')
        {
            return redirect('/admin/APinvoice_header_workbench_old2/view/'.$invoice_id.'/'.$supplier_id)->with('msg', $msg);
        }
        if($isdeleted == 'Y'){
            return redirect('/admin/APinvoice_header_workbench/view/'.$invoice_id.'/'.$supplier_id);
        }
        $update_line = ApInvoiceLines::where('lineid',$id)->update([
            'isdeleted' => 'Y',

        ]);
        
        $line_detail = ApInvoiceLines::where('lineid',$id)->first();
       // return json_encode($line_detail);
        $qty = $line_detail->quantity;
        
        if($line_detail != null){
            if($line_detail->variation != "null" && $line_detail->variation != ''){
                $product_stock = \App\ProductStock::where('variant', $line_detail->variation)->where('product_id',$line_detail->product_id)->first();
                //return json_encode($product_stock);   
                $product_qty = $product_stock->qty ; 
                $current_stock =  $product_qty-$qty;
                // return json_encode($current_stock); 
                     $qty_update = \App\ProductStock::where('product_id',$line_detail->product_id)->where('variant', $line_detail->variation)->update([
                        'qty' => $current_stock
                    ]);
                
            }
            else
            { 
                $product_stock = Product::where('id',$line_detail->product_id)->first();
               // return json_encode($product_stock); 
                    $current_stock = $product_stock->current_stock-$qty;
                        $qty_update = Product::where('id',$line_detail->product_id)->update([
                            'current_stock' => $current_stock
                    ]);
               
            }

                ApInvoiceLines::where('lineid',$id)->delete();
                           return "done";
        }
        else{
           return "not done";
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\APInvoiceAlls  $aPInvoiceAlls
     * @return \Illuminate\Http\Response
     */
    public function destroy(APInvoiceAlls $aPInvoiceAlls)
    {
        //
    }
}
