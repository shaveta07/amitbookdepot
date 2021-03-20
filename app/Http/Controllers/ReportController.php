<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Seller;
use App\User;
use DB;
use Session;
use Auth;
use Hash;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use DateTime;
use PDF;

class ReportController extends Controller
{
    public function stock_report(Request $request)
    {
        if($request->has('category_id')){
            $products = Product::where('category_id', $request->category_id)->get();
        }
        else{
            $products = Product::all();
        }
        return view('reports.stock_report', compact('products'));
    }

    public function in_house_sale_report(Request $request)
    {
        if($request->has('category_id')){
            $products = Product::where('category_id', $request->category_id)->orderBy('num_of_sale', 'desc')->get();
        }
        else{
            $products = Product::orderBy('num_of_sale', 'desc')->get();
        }
        return view('reports.in_house_sale_report', compact('products'));
    }

    public function seller_report(Request $request)
    {
        if($request->has('verification_status')){
            $sellers = Seller::where('verification_status', $request->verification_status)->get();
        }
        else{
            $sellers = Seller::all();
        }
        return view('reports.seller_report', compact('sellers'));
    }

    public function seller_sale_report(Request $request)
    {
        if($request->has('verification_status')){
            $sellers = Seller::where('verification_status', $request->verification_status)->get();
        }
        else{
            $sellers = Seller::all();
        }
        return view('reports.seller_sale_report', compact('sellers'));
    }

    public function wish_report(Request $request)
    {
        if($request->has('category_id')){
            $products = Product::where('category_id', $request->category_id)->get();
        }
        else{
            $products = Product::all();
        }
        return view('reports.wish_report', compact('products'));
    }
  
    public function open_ap_invoice_report(Request $request)
    {
        $msg ='';
        $s_id = isset($request->s_id)?trim($request->s_id):'';
        $store_id = isset($request->store_id)?trim($request->store_id):'';
        $fromdate = isset($request->fromdate)?trim($request->fromdate):'';
        $todate = isset($request->todate)?trim($request->todate):'';
        $supplier_data= array();
        $data= array();
        $supplier_data2=DB::table('ap_suppliers')->where('isdeleted','N')->where('Type','B')->orderBy('name','asc')->get();
        //return json_encode($supplier_data2);
        foreach($supplier_data2 as $sup)
        {
            $items = array(
                'SupplierID' => $sup->supplierid,
                'Name' =>$sup->name
            );
            array_push($supplier_data,$items);
        }
        $data2=$this->openInvoiceReport(-1,Auth::user()->user_type,Auth::user()->store_id,$fromdate,$todate); // view all open invoices
        if(Auth::user()->user_type == 'admin'){
            $data2=$this->openInvoiceReport(-1,Auth::user()->user_type,$store_id,$fromdate,$todate); // view all open invoices
           }
            
        if($s_id != '')
        {
            $data2=$this->openInvoiceReport($s_id,Auth::user()->user_type,$store_id,$fromdate,$todate); // view all open invoices   
       
        if(Auth::user()->user_type == 'admin'){
            $data2=$this->openInvoiceReport($s_id,Auth::user()->user_type,$store_id,$fromdate,$todate); // view all open invoices   
            }
        }
        foreach($data2 as $In)
        {
            $items = array(
                'InvoiceNumber' => $In->invoice_number,
                'SupplierID' =>$In->supplier_id,
                'Status' => $In->Status,
                'Total' =>$In->Total,
                'Date' => $In->Date,
                'store_id' => $In->store_id,
                'InvoiceID' => $In->invoiceid
            );
            array_push($data,$items);
        }

       // return json_encode($data);
        return view('reports.open-ap-invoice-report',compact('msg','s_id','store_id','fromdate','todate','supplier_data','data'));
    }

    public function openInvoiceReport($id,$role=1,$store_id,$fromdate='',$todate='')
	{
		if($fromdate != '' and $todate != ''){
            $sql = DB::table('ap_invoices_alls')->where('Status','O')->wherebetween('Date',[$fromdate,$todate])->get();
			}
            $sql = DB::table('ap_invoices_alls')->where('Status','O')->get();
	if($id != -1){
        $sql = DB::table('ap_invoices_alls')->where('supplier_id',$id)->where('Status','O')->get();
		
        }
        if($id != -1 and $fromdate != '' and $todate != ''){
            $sql = DB::table('ap_invoices_alls')->where('supplier_id',$id)->wherebetween('Date',[$fromdate,$todate])->where('Status','O')->get();
            
            }
	if($store_id != ''){
        $sql = DB::table('ap_invoices_alls')->where('store_id',$store_id)->where('Status','O')->get();
		
		}
		
	if($fromdate != '' and $todate != ''and $store_id != ''){
        $sql = DB::table('ap_invoices_alls')->where('store_id',$store_id)->where('Status','O')->wherebetween('Date',[$fromdate,$todate])->get();
		
			}
		

	
			return $sql;	
    }
    
    public function open_ap_invoice_report_search(Request $request)
    {
        
        $msg ='';
        $s_id = isset($request->s_id)?trim($request->s_id):'';
        $store_id = isset($request->store_id)?trim($request->store_id):'';
        $fromdate = isset($request->fromdate)?date('Y-m-d',strtotime($request->fromdate)):'';
        $todate = isset($request->todate)?date('Y-m-d',strtotime($request->todate)):'';
        $supplier_data= array();
        $data= array();
        $supplier_data2=DB::table('ap_suppliers')->where('isdeleted','N')->where('Type','B')->orderBy('name','asc')->get();
        //return json_encode($supplier_data2);
        foreach($supplier_data2 as $sup)
        {
            $items = array(
                'SupplierID' => $sup->supplierid,
                'Name' =>$sup->name
            );
            array_push($supplier_data,$items);
        }
        $data2 = array();
        // if($s_id != '')
        // {
            $data2=$this->openInvoiceReport($s_id,Auth::user()->user_type,$store_id,$fromdate,$todate); // view all open invoices   
       
        if(Auth::user()->user_type == 'admin'){
            $data2=$this->openInvoiceReport($s_id,Auth::user()->user_type,$store_id,$fromdate,$todate); // view all open invoices   
            }
        //}
        foreach($data2 as $In)
        {
            $items = array(
                'InvoiceNumber' => $In->invoice_number,
                'SupplierID' =>$In->supplier_id,
                'Status' => $In->Status,
                'Total' =>$In->Total,
                'Date' => $In->Date,
                'store_id' => $In->store_id,
                'InvoiceID' => $In->invoiceid
            );
            array_push($data,$items);
        }
        return view('reports.open-ap-invoice-report',compact('msg','s_id','store_id','fromdate','todate','supplier_data','data'));
    }

  

    public function open_ar_invoice_report(Request $request)
    {
        $store_id='';
        if(isset($request->store_id) && $request->store_id != ''){
            $store_id  = $request->store_id;
            }
        $fromdate = isset($request->fromdate)?trim($request->fromdate):'';
        $todate = isset($request->todate)?trim($request->todate):'';

        $data=$this->openInvoiceReport2(Auth::user()->store_id,Auth::user()->user_type);
        $condi = '';
        $rrr =array();
        $rrr2 =DB::table('orders')->where('payment_status','unpaid')->get();
        if(Auth::user()->user_type != 'admin'){
            $rrr2 =DB::table('orders')->where('payment_status','unpaid')->where('store_id',$store_id)->get();
           // $rrr = get_all_array($con,"SELECT * FROM `AR_Invoices_All` where Status = 'O' and store_id='$store_id' $condi");
            }
            foreach($rrr2 as $In)
            {
                $items = array(
                    'preorderid' => $In->preorderid,
                    'CustomerID' =>$In->user_id,
                    'InvoiceNumber' => $In->invoice_number,
                    'InvoiceLookupType' =>$In->invoicelookuptype,
                    'store_id' => $In->store_id,
                    'Amount' => $In->grand_total,
                    'InvoiceDate' => $In->invoicedate,
                    'orderid' => $In->id,
                    'ordersource' => $In->ordersource
                );
                array_push($rrr,$items);
            }
            $msg = '';

        return view('reports.open-ar-invoice-report',compact('msg','store_id','fromdate','data','todate','condi','rrr'));
    }

    public function openInvoiceReport2($store,$role)
	{
        $query = DB::table('orders as a')
        ->join('order_details as l','l.order_id','=','a.id')
        ->select('a.id','a.invoice_number','a.store_id','a.invoicelookuptype','a.user_id','a.invoicedate','a.preorderid')
        ->where('a.payment_status','unpaid')
      //  ->where('a.StoreLocationID',$store)
        ->where('l.IsDeleted','N')
        ->groupBy('a.id')->get();
		//$query="SELECT `InvoiceID`,`InvoiceNumber`,`InvoiceLookupType`,`CustomerID`,`Amount`,`InvoiceDate` FROM `AR_Invoices_All` WHERE `Status`='O' and `StoreLocationID`='$store'";
		
if(Auth::user()->user_type != 'admin'){
    $query = DB::table('orders as a')
    ->join('order_details as l','l.order_id','=','a.id')
    ->select('a.id','a.invoice_number','a.store_id','a.invoicelookuptype','a.user_id','a.invoicedate','a.preorderid')
    ->where('a.payment_status','unpaid')
    ->where('a.store_id',$store_id)
  //  ->where('a.StoreLocationID',$store)
    ->where('l.IsDeleted','N')
    ->groupBy('a.id')
   // ->SUM(('l.Amount'*'l.quantity')-('l.discount' * 'l.quantity') as Amount )
    ->get();
// 	$query="SELECT a.`InvoiceID`,a.`InvoiceNumber`,a.store_id,a.`InvoiceLookupType`,a.`CustomerID`,a.`InvoiceDate`,a.`preorderid`,SUM((l.`Amount`*l.`Quantity`)-(l.`Discount`*l.`Quantity`)) as Amount
// FROM `AR_Invoices_All` a left join `AR_Invoice_Lines` l on l.`InvoiceID`=a.`InvoiceID`
// WHERE a.`Status`='O' and a.`StoreLocationID`='$store' and a.store_id = '$store_id' and l.`IsDeleted`='N' group by a.`InvoiceID`";
	}

			return $query;	
    }
    public function open_ar_invoice_report_search(Request $request)
    {
        $store_id='';
        if(isset($request->store_id) && $request->store_id != ''){
            $store_id  = $request->store_id;
            }
        $fromdate = isset($request->fromdate)?date('Y-m-d',strtotime($request->fromdate)):'';
        $todate = isset($request->todate)?date('Y-m-d',strtotime($request->todate)):'';
        $rrr =array();
        if($fromdate != '' and $todate != ''){
           // return 'hjj';
            //$condi .= " and DATE_FORMAT(`InvoiceDate`,'%Y-%m-%d') between '$fromdate' and '$todate' ";
            $rrr2 =DB::table('orders')->where('payment_status','unpaid')->wherebetween('invoicedate',[$fromdate,$todate])->get();	
            }
            // if($fromdate != '' and $todate != '' and $store_id != ''  ){
            //     //$condi .= " and DATE_FORMAT(`InvoiceDate`,'%Y-%m-%d') between '$fromdate' and '$todate' ";
            //     $rrr2 =DB::table('orders')->where('payment_status','unpaid')->where('store_id',$store_id)->wherebetween('invoicedate',[$fromdate,$todate])->get();	
            //     }
           
          //  $rrr2 =DB::table('orders')->where('payment_status','unpaid')->get();
            if(Auth::user()->user_type != 'admin'){
                $rrr2 =DB::table('orders')->where('payment_status','unpaid')->where('store_id',$store_id)->get();
               // $rrr = get_all_array($con,"SELECT * FROM `AR_Invoices_All` where Status = 'O' and store_id='$store_id' $condi");
                }
                if($store_id != ''){
                    $rrr2 =DB::table('orders')->where('payment_status','unpaid')->where('store_id',$store_id)->get();
                   // $rrr = get_all_array($con,"SELECT * FROM `AR_Invoices_All` where Status = 'O' and store_id='$store_id' $condi");
                    }
                foreach($rrr2 as $In)
                {
                    $items = array(
                        'preorderid' => $In->preorderid,
                        'CustomerID' =>$In->user_id,
                        'InvoiceNumber' => $In->invoice_number,
                        'InvoiceLookupType' =>$In->invoicelookuptype,
                        'store_id' => $In->store_id,
                        'Amount' => $In->grand_total,
                        'InvoiceDate' => $In->invoicedate,
                        'ordersource' => $In->ordersource,
                        'orderid' => $In->id
                    );
                    array_push($rrr,$items);
                }
               
    $msg ='';

        return view('reports.open-ar-invoice-report',compact('msg','store_id','fromdate','todate','rrr'));

    }

    public function supplier_purchase_report(Request $request)
    {
        $s_id = isset($request->s_id)?trim($request->s_id):'';
        $store_id = isset($request->store_id)?trim($request->store_id):'';
      $data = array();
        $data_supplierid= array();
        $supplieriddata = DB::table('ap_suppliers')->where('isdeleted','N')->where('type','B')->get();
       
        foreach($supplieriddata as $In)
        {
            $items = array(
                'SupplierID' => $In->supplierid,
                'Name' =>$In->name
            );
            array_push($data_supplierid,$items);
        }
        return view('reports.supplier-purchase-report',compact('data','s_id','store_id','data_supplierid'));
    }

    public function supplier_purchase_report_search(Request $request)
    {
        $s_id = isset($request->s_id)?trim($request->s_id):'';
        $store_id = isset($request->store_id)?(Auth::user()->store_id):'';
     
        $data_supplierid= array();
        $supplieriddata = DB::table('ap_suppliers')->where('isdeleted','N')->where('type','B')->get();
       
        foreach($supplieriddata as $In)
        {
            $items = array(
                'SupplierID' => $In->supplierid,
                'Name' =>$In->name
            );
            array_push($data_supplierid,$items);
        }
        // if(isset($request->submit))
        // {
            $data = array();
            $supplierid=$request->s_id;
            $query =  DB::table('ap_invoice_lines as l')
            ->join('ap_invoices_alls as a','a.invoiceid','=','l.invoice_id')
            ->join('products as b','b.id','=','l.product_id')
            ->select('l.product_id','b.name','l.quantity','a.invoice_number','a.Status')
            ->where('l.isdeleted','N')
            ->where('a.supplier_id',$supplierid)->get();
            foreach($query as $q)
            {
                $items = array(
                    'BookID' => $q->product_id,
                    'Name' => $q->name,
                    'Status' => $q->Status,
                    'InvoiceNumber' => $q->invoice_number,
                    'Quantity' => $q->quantity,
                    'store_id' => '0'

                );
                array_push($data,$items);
            }
                  
            if(Auth::user()->user_type != 'admin')
            {
                $query =  DB::table('ap_invoice_lines as l')
                ->join('ap_invoices_alls as a','a.invoiceid','=','l.invoice_id','a.store_id')
                ->join('products as b','b.id','=','l.product_id')
                ->select('l.product_id','b.name','l.quantity','a.invoice_number','a.Status')
                ->where('l.isdeleted','N')
                ->where('a.supplier_id',$supplierid)
                ->where('a.store_id',Auth::user()->store_id)
                ->get();
                foreach($query as $q)
                {
                    $items = array(
                        'BookID' => $q->product_id,
                        'Name' => $q->name,
                        'Status' => $q->Status,
                        'InvoiceNumber' => $q->invoice_number,
                        'Quantity' => $q->quantity,
                        'store_id' => $q->store_id
    
                    );
                    array_push($data,$items);
                }
            }
           
           

       // }
        return view('reports.supplier-purchase-report',compact('data','s_id','store_id','data_supplierid'));
    }

    public function AR_book_report(Request $request)
    {
        $data= array();
        $cusarr = DB::table('customers')
        ->select('customers.id as cusid','users.name as name')
        ->join('users','users.id','=','customers.user_id')
        ->get();
        $keyword = isset($request->keyword)?$request->keyword:'';
        $postfrom = isset($request->from)?$request->from:'';
        $postto = isset($request->to)?$request->to:'';
        $customerto = isset($request->customer)?$request->customer:'';
        return view('reports.AR-book-report',compact('cusarr','keyword','postfrom','postto','customerto','data'));
    }

    public function AR_book_report_submit(Request $request)
    {
      //return  json_encode($request->all());
        $data= array();
        $cusarr = DB::table('customers')
        ->select('customers.id as cusid','users.name as name')
        ->join('users','users.id','=','customers.user_id')
        ->get();
        $keyword=$request->keyword;
        //$date_split_from=explode('/',$request->from);
        //$date_split_to=explode('/',$request->to);
        $date_from = isset($request->fromdate)?trim($request->fromdate):'';
        $date_to = isset($request->todate)?trim($request->todate):'';

       // $date_from=$date_split_from[2]."-".$date_split_from[0]."-".$date_split_from[1];
        //$date_to=$date_split_to[2]."-".$date_split_to[0]."-".$date_split_to[1];
        $data2=$this->arTransactionReport($keyword,$date_from,$date_to,Auth::user()->user_type,Auth::user()->store_id,$request->customer);
      // return json_encode($data2);
        foreach($data2 as $da)
        {
            $items = array(
            'Isbn1' => $da->isbn,
            'Name' => $da->name,
            'Author' => $da->author_id,
            'Publisher' => $da->brand_id,
            'InvoiceNumber' => $da->invoice_number,
            'TransactionType' => $da->transactiontype,
            'ItemPrice' =>$da->price,
            'Amount' => $da->amount,
            'Quantity' => $da->quantity,
            'store_id' => $da->store_id,
            'CustomerID' =>  $da->user_id,
            'customercat' => $da->customercat
            );
            array_push($data,$items);
        }
        // $keyword = isset($request->keyword)?$request->keyword:'';
        $postfrom = isset($request->from)?$request->from:'';
        $postto = isset($request->to)?$request->to:'';
        $customerto = isset($request->customer)?$request->customer:'';
        return view('reports.AR-book-report',compact('data','cusarr','keyword','postfrom','postto','customerto','data'));
    }

    public function AP_book_report(Request $request)
    {
        $data= array();
       // $cusarr = get_all_array($con,"select * from AP_Suppliers where Type = 'B'");
        $cusarr = DB::table('ap_suppliers')->where('Type','B')->get();
        $keyword = isset($request->keyword)?$request->keyword:'';
        $postfrom = isset($request->from)?$request->from:'';
        $postto = isset($request->to)?$request->to:'';
        $customerto = isset($request->supplier)?$request->supplier:'';
        return view('reports.AR-book-report',compact('cusarr','keyword','postfrom','postto','customerto','data'));
    }

    public function AP_book_report_submit(Request $request)
    {
      //return  json_encode($request->all());
        $data= array();
        $cusarr = DB::table('ap_suppliers')->where('Type','B')->get();
        $keyword = isset($request->keyword)?$request->keyword:'';
        $date_from = isset($request->from)?$request->from:'';
        $date_to = isset($request->to)?$request->to:'';
        $customerto = isset($request->supplier)?$request->supplier:'';
        
       // $date_from=$date_split_from[2]."-".$date_split_from[0]."-".$date_split_from[1];
        //$date_to=$date_split_to[2]."-".$date_split_to[0]."-".$date_split_to[1];
        $data2=$this->apTransactionReport($keyword,$date_from,$date_to,Auth::user()->user_type,Auth::user()->store_id,$request->supplier);
      // return json_encode($data2);
        foreach($data2 as $da)
        {
            $items = array(
            'Isbn1' => $da->isbn,
            'Name' => $da->name,
            'Author' => $da->author_id,
            'Publisher' => $da->brand_id,
            'InvoiceNumber' => $da->invoice_number,
            'SupplierID' => $da->supplierid,
          //  'ItemPrice' =>$da->price,
            'Total' => $da->amount,
            'Quantity' => $da->quantity,
            'store_id' => $da->store_id,
            'CustomerID' =>  $da->user_id,
            'customercat' => $da->customercat
            );
            array_push($data,$items);
        }
        // $keyword = isset($request->keyword)?$request->keyword:'';
        $postfrom = isset($request->from)?$request->from:'';
        $postto = isset($request->to)?$request->to:'';
        $customerto = isset($request->customer)?$request->customer:'';
        return view('reports.ap-book-report',compact('data','cusarr','keyword','postfrom','postto','customerto','data'));
    }
    public function apTransactionReport($keyword,$from,$to,$role,$store_id,$supplier)
	{
        $query = DB::table('ap_invoice_lines as l')
        ->join('ap_invoice_alls as i','i.invoiceid','=','l.invoice_id')
        ->join('ap_suppliers as s','s.supplierid','=','i.supplier_id')
        ->join('products as b','b.id','=','l.product_id') 
        ->select('b.name','b.isbn','i.store_id','b.author_id','b.brand_id','i.invoice_number','l.mrp','l.quantity','s.supplierid','s.name as customercat')
        //->wherebetween('i.invoicedate',[$from,$to])
        ->where('l.isdeleted','N')
        ->where('b.name','like','%$keyword%')
       // ->orwhere('b.author','like','%$keyword%')
       // ->orwhere('b.Publisher','like','%$keyword%')
        ->orwhere('b.isbn','like','%$keyword%')->get();
        return $query;
        
    }
    public function arTransactionReport($keyword,$from,$to,$role,$store_id,$customer)
	{
        
        $query = DB::table('order_details as l')
        ->join('orders as i','i.id','=','l.order_id')
        ->join('customers as c','c.id','=','i.user_id')
        ->join('users as s','s.id','=','c.user_id')
        ->join('products as b','b.id','=','l.product_id') 
        ->select('b.name','b.isbn','i.store_id','b.author_id','b.brand_id','i.invoice_number','l.price','l.quantity','l.transactiontype','i.user_id','l.amount','s.name as customercat','s.phone')
        //->wherebetween('i.invoicedate',[$from,$to])
        ->where('l.isdeleted','N')
        ->where('b.name','like','%$keyword%')
       // ->orwhere('b.author','like','%$keyword%')
       // ->orwhere('b.Publisher','like','%$keyword%')
        ->orwhere('b.isbn','like','%$keyword%')->get();
		
	//return 'yjjj';
	if($customer != '' && $from !='' && $to !='' && $keyword !=''){
        $query = DB::table('order_details as l')
        ->join('orders as i','i.id','=','l.order_id')
        ->join('customers as c','c.id','=','i.user_id')
        ->join('users as s','s.id','=','c.user_id')
        ->join('products as b','b.id','=','l.product_id') 
        ->select('b.name','b.isbn','i.store_id','b.author_id','b.brand_id','i.invoice_number','l.price','l.quantity','l.transactiontype','i.user_id','l.amount','s.name as customercat','s.phone')
        ->wherebetween('i.invoicedate',[$from,$to])
        ->where('l.isdeleted','N')
        ->where('b.name','like','%$keyword%')
        ->where('i.user_id',$customer)
       // ->orwhere('b.author','like','%$keyword%')
       // ->orwhere('b.Publisher','like','%$keyword%')
        ->orwhere('b.isbn','like','%$keyword%')->get();
		return 'ysssssjjj';
		}
		
		
	if($role != 'admin'){
        $query = DB::table('order_details as l')
        ->join('orders as i','i.id','=','l.order_id')
        ->join('customers as c','c.id','=','i.user_id')
        ->join('users as s','s.id','=','c.user_id')
        ->join('products as b','b.id','=','l.product_id') 
        ->select('b.name','b.isbn','i.store_id','b.author_id','b.brand_id','i.invoice_number','l.price','l.quantity','l.transactiontype','i.user_id','l.amount','s.name as customercat','s.phone')
        ->wherebetween('i.invoicedate',[$from,$to])
        ->where('l.isdeleted','N')
        ->where('b.name','like','%$keyword%')
       // ->where('i.user_id',$customer)
        ->where('i.store_id',$store_id)
       // ->orwhere('b.author','like','%$keyword%')
       // ->orwhere('b.Publisher','like','%$keyword%')
        ->orwhere('b.isbn','like','%$keyword%')->get();
		
if($customer != ''){
    $query = DB::table('order_details as l')
    ->join('orders as i','i.id','=','l.order_id')
    ->join('customers as c','c.id','=','i.user_id')
    ->join('users as s','s.id','=','c.user_id')
    ->join('products as b','b.id','=','l.product_id') 
    ->select('b.name','b.isbn','i.store_id','b.author_id','b.brand_id','i.invoice_number','l.price','l.quantity','l.transactiontype','i.user_id','l.amount','s.name as customercat','s.phone')
    ->wherebetween('i.invoicedate',[$from,$to])
    ->where('l.isdeleted','N')
    ->where('b.name','like','%$keyword%')
    ->where('i.user_id',$customer)
    ->where('i.store_id',$store_id)
   // ->orwhere('b.author','like','%$keyword%')
   // ->orwhere('b.Publisher','like','%$keyword%')
    ->orwhere('b.isbn','like','%$keyword%')->get();

	}

		}
	
	return $query;
	}

    public function supplier_ledger_report(Request $request)
    {
        $s_id = isset($request->s_id)?trim($request->s_id):'';
        $store_id = isset($request->store_id)?trim($request->store_id):'';
        $fromdate = isset($request->fromdate)?trim($request->fromdate):'';
        $todate = isset($request->todate)?trim($request->todate):'';
        $supplier_data= array();
        //$data= array();
        $supplier_data2=DB::table('ap_suppliers')->where('isdeleted','N')->where('Type','B')->orderBy('name','asc')->get();
        //return json_encode($supplier_data2);
        foreach($supplier_data2 as $sup)
        {
            $items = array(
                'SupplierID' => $sup->supplierid,
                'Name' =>$sup->name
            );
            array_push($supplier_data,$items);
        }
        return view('reports.supplier-ledger-report',compact('supplier_data','s_id','fromdate','todate'));
    }


    public function supplier_statement_pdf(Request $request)
    {
        //
        $data = array(); 
        $date_from = isset($request->fromdate)?date('Y-m-d',strtotime($request->fromdate)):'';
        $date_to = isset($request->todate)?date('Y-m-d',strtotime($request->todate)):'';  
        
        // $date_split_from=explode('/',$_POST['from']);
        // $date_split_to=explode('/',$_POST['to']);

        // $date_from=$date_split_from[2]."-".$date_split_from[0]."-".$date_split_from[1];
        // $date_to=$date_split_to[2]."-".$date_split_to[0]."-".$date_split_to[1];
        //
        if($request->s_id != '' and $date_from== '' and $date_to == '')
        {
        $query=DB::table('ap_invoices_alls')->where('supplier_id',$request->s_id)->get();
        }
        if($request->s_id != '' and $date_from!= '' and $date_to != '')
        {
        $query=DB::table('ap_invoices_alls')->where('supplier_id',$request->s_id)->where('Date',[$date_from,$date_to])->get();
        }
        foreach($query as $q)
        {
            $items = array(
                'InvoiceNumber' => $q->invoice_number,
                'Date' => $q->Date,
                'Status' => $q->Status,
                'Total' => $q->Total,
                'Description' => $q->description
            );
            array_push($data,$items);
        }
         // return json_encode($data);
        // return view('reports.supplier_statement_pdf', compact('data'));
        $supplier_data= DB::table('ap_suppliers')->where('isdeleted','N')->where('supplierid',$request->s_id)->first();
       //return json_encode($supplier_data);
          //stores the pdf for invoice
          $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('reports.supplier_statement_pdf', compact('data'));
        return $pdf->download('supplier_statement_pdf.pdf');
//         $output = $pdf->output();
//         file_put_contents('public/reports/'.$supplier_data->name.' report.pdf', $output);
//    return 'true';
    }



    public function AR_ageing_report(Request $request)
    {
        $credit_check_url="http://my.nicesms.in/api/v4/index.php?method=account.credits&api_key=Ac805e5297ec9d8bc48983547d8310efb";
        $credit_check_array=$sendsms->execute($credit_check_url);
        $credit_check_array=explode(":",$credit_check_array);
        return view('reports.AR-ageing-report',compact('credit_check_array'));
    }

    public function emp_adv_report(Request $request)
    {
        $s_id = isset($request->s_id)?trim($request->s_id):'';
        $store_id = isset($request->store_id)?trim($request->store_id):'';
        $total = '0';
        // $supplier_data = array();
        // $supdata = DB::table('ap_suppliers')->where('IsDeleted','N')->where('type','B')->orderBy('name','asc')->get();
        // foreach($supdata as $In)
        // {
        //     $items = array(
        //         'SupplierID' => $In->supplierid,
        //         'Name' =>$In->name
        //     );
        //     array_push($supplier_data,$items);
        // }
        $data_supplierid= array();
        $supplieriddata = DB::table('ap_suppliers')->where('type','E')->get();
        if(Auth::user()->user_type != 'admin')
        {
            $data_supplierid= DB::table('ap_suppliers')->where('type','E')->where('store_id',$store_id)->get();
        }
        foreach($supplieriddata as $In)
        {
            $items = array(
                'SupplierID' => $In->supplierid,
                'Name' =>$In->name
            );
            array_push($data_supplierid,$items);
        }
      
       
        return view('reports.emp-adv-report',compact('data_supplierid','s_id','total'));
    }

    public function emp_adv_report_search(Request $request)
    {
       // $s_id = isset($request->s_id)?trim($request->s_id):'';
        $s_id = isset($request->s_id)?trim($request->s_id):'';
        $store_id = isset($request->store_id)?trim($request->store_id):'';
        $data = array();
        $data_supplierid= array();
        $supplieriddata = DB::table('ap_suppliers')->where('type','E')->get();
        if(Auth::user()->user_type != 'admin')
        {
            $data_supplierid= DB::table('ap_suppliers')->where('type','E')->where('store_id',$store_id)->get();
        }
        foreach($supplieriddata as $In)
        {
            $items = array(
                'SupplierID' => $In->supplierid,
                'Name' =>$In->name
            );
            array_push($data_supplierid,$items);
        }
      
        $query =  DB::table('ap_invoices_alls as h')
                ->join('ap_invoice_lines as l','h.invoiceid','=','l.invoice_id')
                ->join('ap_suppliers as s','s.supplierid','=','h.supplier_id')
                ->where('h.Status','P')
                ->where('l.isdeleted','N')
                ->where('s.supplierid',$s_id)
                ->get();
        
        foreach($query as $In)
        {
            $items = array(
                'InvoiceNumber' => $In->invoice_number,
                'Date' =>$In->Date,
                'Advance' =>'',
                'Cp' =>$In->cp
            );
            array_push($data,$items);
        }
        return view('reports.emp-adv-report',compact('data','s_id','store_id','data_supplierid'));
//         $query="SELECT h.`InvoiceNumber`,h.`Date`,l.`LineID`,l.`Cp`,'Advance' FROM `AP_Invoices_All` h, `AP_Invoice_Lines` l, `AP_Suppliers` s where h.`InvoiceID`=l.`InvoiceID` AND
// h.`SupplierID`=s.`SupplierID` and h.`Status`='P' and l.`BookID` = '6116' and l.`IsDeleted`='N' and s.`SupplierID`='$supplier_id'";
    }



    public function creditinvoicereport(Request $request)
    {
        $fromdate = isset($request->fromdate)?trim($request->fromdate):'';
        $todate = isset($request->todate)?trim($request->todate):'';
        return view('reports.creditinvoicereport',compact('fromdate','todate'));
    }

    public function creditinvoice_pdf(Request $request)
    {
        $rrr = array();
        $sql = DB::table('orders')->where('invoicelookuptype','C')->get();
       
        if($request->c_id != ''){
           $sql = DB::table('orders')->where('invoicelookuptype','C')->where('user_id',$request->c_id)->get();
          return json_encode($sql);
            }
        if($request->fromdate != '' && $request->todate != ''){
            $sql = DB::table('orders')->where('invoicelookuptype','C')->wherebetween('invoicedate',[$request->fromdate,$request->todate])->get();
            
            }
            $sql = DB::table('orders')->where('invoicelookuptype','C')->orderBy('id')->get();
          
            foreach($sql as $r)
            {
                $items = array(
                'Amount' => $r->grand_total,
                'Status' => $r->payment_status,
                'InvoiceNumber' => $r->invoice_number,
                'InvoiceDate' => $r->invoicedate,
                'InvoiceID' =>$r->id
                );
                array_push('rrr','items');

            }
            $pdf = PDF::setOptions([
                'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                'logOutputFile' => storage_path('logs/log.htm'),
                'tempDir' => storage_path('logs/')
            ])->loadView('reports.creditinvoice_pdf', compact('rrr'));
            //return $pdf;
            return $pdf->download('creditinvoice_pdf.pdf');
     
       
    }

    public function gstreport(Request $request)
    {
        $msg ='';
        $store_id = isset($request->store_id)?trim(Auth::user()->store_id):'';
        $startdate = isset($request->startdate)?trim($request->startdate):'';
        $enddate = isset($request->enddate)?trim($request->enddate):'';
       // $search = isset($request->search)?trim($request->search):'';
       // $store_id= isset($_REQUEST['store_id'])?$_REQUEST['store_id']:$_SESSION['store_id'];
        return view('reports.gstreport',compact('msg','store_id','startdate','enddate'));
    }
    public function gstreportsearch(Request $request)
    {
        $msg ='';
        $store_id = isset($request->store_id)?trim(Auth::user()->store_id):'';
        $startdate = isset($request->startdate)?trim($request->startdate):'';
        $enddate = isset($request->enddate)?trim($request->enddate):'';
        $search = isset($request->search)?trim($request->search):'';
        return view('reports.gstreport',compact('msg','store_id','startdate','enddate','search'));
    }

    public function gstreportbysuppliers(Request $request)
    {
        $msg ='';
        $suppliers = isset($request->suppliers)?($request->suppliers):'';
        $startdate = isset($request->startdate)?($request->startdate):'';
        $enddate = isset($request->enddate)?($request->enddate):'';
       // $search = isset($request->search)?trim($request->search):'';
       // $store_id= isset($_REQUEST['store_id'])?$_REQUEST['store_id']:$_SESSION['store_id'];
        return view('reports.gstreportbysuppliers',compact('msg','suppliers','startdate','enddate'));
    }
    public function gstreportbysupplierssearch(Request $request)
    {
        $msg ='';
        $name ='';
        $mobile ='';
        $suppliers = isset($request->suppliers)?($request->suppliers):'';
        $startdate = isset($request->startdate)?date('Y-m-d',strtotime($request->startdate)):'';
        $enddate = isset($request->enddate)?date('Y-m-d',strtotime($request->enddate)):'';
        $search = isset($request->search)?trim($request->search):'';
        if($request->clear)
        {
            $suppliers = '';
            $startdate = '';
            $enddate = '';
            return view('reports.gstreportbysuppliers',compact('msg','suppliers','startdate','enddate'));
        }

        $supdata = DB::table('ap_suppliers')->where('supplierid',$suppliers)->first();
        if($supdata)
        {
            $name = $supdata->name;
            $mobile = $supdata->mobile1;
        }
      
      
        $ap_tax_data = DB::table('ap_invoices_alls as l')
        //->select(DB::raw('sum(l.sgst),sum(l.cgst),sum(l.gst),sum(l.igst)'))
        ->where('l.status','P')
        ->where('l.supplier_id',$suppliers)
       // ->wherebetween('Date',[$startdate,$enddate])
        ->get();
        $cgst = 0;
        $sgst = 0;
        $igst = 0;
        $gst = 0;

        foreach($ap_tax_data as $d)
        {
            $cgst += $d->cgst;
            $sgst += $d->sgst;
            $igst +=  $d->igst;
            $gst  +=  $d->gst;
        }
        $rrr = DB::table('ap_invoices_alls as l')
        ->where('l.status','P')
        ->where('l.supplier_id',$suppliers)
       // ->wherebetween('Date',[$startdate,$enddate])
        ->get();
       
        if($startdate != '' && $enddate != ''){
            $rrr = DB::table('ap_invoices_alls as l')
            //->select(DB::raw('sum(l.sgst),sum(l.cgst),sum(l.gst),sum(l.igst)'))
            ->where('l.status','P')
            ->where('l.supplier_id',$suppliers)
           ->wherebetween('l.Date',[$startdate,$enddate])
            ->get();
           
            }
       // $store_id= isset($_REQUEST['store_id'])?$_REQUEST['store_id']:$_SESSION['store_id'];
        return view('reports.gstreportbysuppliers',compact('msg','rrr','suppliers','startdate','search','enddate','name','mobile','cgst','sgst','igst','gst'));
    }


    public function gstreportbycheque(Request $request)
    {
        $msg ='';
        $cheque = isset($request->cheque)?($request->cheque):'';
        $startdate = isset($request->startdate)?($request->startdate):'';
        $enddate = isset($request->enddate)?($request->enddate):'';
       // $search = isset($request->search)?trim($request->search):'';
       // $store_id= isset($_REQUEST['store_id'])?$_REQUEST['store_id']:$_SESSION['store_id'];
        return view('reports.gstreportbyCheque',compact('msg','cheque','startdate','enddate'));
    }

    public function gstreportbychequesearch(Request $request)
    {
        $msg ='';
        $cheque = isset($request->cheque)?($request->cheque):'';
        $startdate = isset($request->startdate)? date('m/d/Y',strtotime($request->startdate)):'';
        $enddate = isset($request->enddate)?date('m/d/Y',strtotime($request->enddate)):'';
        $search = isset($request->search)?trim($request->search):'';

         $rrr = DB::table('ap_invoices_alls')->get();
         if($startdate == '' && $enddate == '' and $cheque!=''){
            $rrr =  DB::table('ap_invoices_alls')->where('payinfo',$cheque)->get();
           
           }
         if($startdate != '' && $enddate != '' and $cheque==''){
             $rrr =  DB::table('ap_invoices_alls')->whereBetween('paydate',[$startdate,$enddate])->get();
            
            }
            if($startdate != '' && $enddate != '' and $cheque!=''){
                $rrr = DB::table('ap_invoices_alls')->where('payinfo',$cheque)->whereBetween('paydate',[$startdate,$enddate])->get();
            
            }
            // $rrrq = get_all_array($con,"SELECT * FROM `arcredit` where cheque='".$_REQUEST['cheque']."'"); 
            // if($start != '' && $end != '' and $_REQUEST['cheque']==''){
            //     $rrrq = get_all_array($con,"SELECT * FROM `arcredit` where (clearancedate BETWEEN '".$start."' AND '".$end."')"); 
               
            //     }
            //     if($start != '' && $end != '' and $_REQUEST['cheque']!=''){
            //     $rrrq = get_all_array($con,"SELECT * FROM `arcredit` where (cheque='".$_REQUEST['cheque']."' and clearancedate BETWEEN '".$start."' AND '".$end."')"); 
               
            //     }
        return view('reports.gstreportbyCheque',compact('msg','cheque','startdate','enddate','search','rrr'));
    }

}
