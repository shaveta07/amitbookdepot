<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie;
use DB;
use DateTime;
use App\Models\HrAttendance;
use PDF;


class HrAttendanceController extends Controller
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
        //
    }

    public function PunchAttendance(){
        date_default_timezone_set('Asia/Kolkata');
        $datetime=date('Y-m-d H:i:s');
        // IP address of location
        $ip="";
        $msg = '';
        /*****
         * for Lunc time functionality 
         */
        $today=date('Y-m-d');
        $loggeduserid = Auth::user()->id;
        $user =  DB::table('users')->where('id',$loggeduserid)->first();
        if($user->user_type == 'staff')
        {
                $items =  DB::table('users')
                ->select('users.id as userid', 'users.name', 'users.email', 'staff.salaryrate', 'staff.holidayrate', 'staff.id as staffid')
                ->join('staff', 'staff.user_id', '=', 'users.id')
                ->where('staff.user_id', Auth::user()->id)
                ->get();
            
                $data = array();
                $emp_last_punch = array();
                foreach($items as $dat)
                {
                    $item = array(
                        'Name' => $dat->name,
                        'EmployeeID' => $dat->userid,
                        'Email' => $dat->email,
                        'SalaryRate' => $dat->salaryrate,
                        'Holidayrate' => $dat->holidayrate


                    );
                    array_push($data,$item);
                    $now = date('Y-m-d');
                    // return $now;
                    $emp_last_punch_data = DB::table('hr_attendance')->where('EmployeeID', $dat->userid)->where('CreateDate', $now)->orderBy('RowID','DESC')->limit(1)->get();
                    //return json_encode($emp_last_punch_data);
                    
                    
                    foreach($emp_last_punch_data as $item_emp)
                    {
                        $item_emp_data = array(
                            'RowID' => $item_emp->RowID,
                            'InTime' => $item_emp->InTime,
                            'OutTime' => $item_emp->OutTime


                        );
                       
                        array_push($emp_last_punch,$item_emp_data);
                    }
                
                }
            }
            else
            {
                
                    $items =  DB::table('users')->where('id',$loggeduserid)->where('user_type','admin')->get();
                    $data = array();
                    $emp_last_punch = array();
                    foreach($items as $dat)
                    {
                        $item = array(
                            'Name' => $dat->name,
                            'EmployeeID' => $dat->id,
                            'Email' => $dat->email
    
    
                        );
                         //return json_encode($item);
                        array_push($data,$item);
                       // return $data;
                        $now = date('Y-m-d');
                        // return $now;
                        $emp_last_punch_data = DB::table('hr_attendance')->where('EmployeeID', $dat->id)->where('CreateDate', $now)->orderBy('RowID','DESC')->limit(1)->get();
                        //return json_encode($emp_last_punch_data);
                        
                        
                        foreach($emp_last_punch_data as $item_emp)
                        {
                            $item_emp_data = array(
                                'RowID' => $item_emp->RowID,
                                'InTime' => $item_emp->InTime,
                                'OutTime' => $item_emp->OutTime
    
    
                            );
                            array_push($emp_last_punch,$item_emp_data);
                        }

                    //return json_encode($emp_last_punch_data);
                    }
            }
        //return json_encode($emp_last_punch_data);
        
        $result = DB::table('hr_attendance')->select('InTime')
       ->where(DB::raw("(date(InTime))"), date('Y-m-d'))
       ->get();

       //return DATE_FORMAT('2020-11-07 16:44:09','Y-m-d');
      
       //return json_encode($emp_last_punch);
       
        return view("attendance.punchattendance", compact('data', 'emp_last_punch','msg','result'));
    }


    public function updateStatus(Request $request)
    {
       // return json_encode($request->all());
       $datetime=date('Y-m-d H:i:s');
       $today = date('Y-m-d');
       $ip="";
        if($request->lunchout){
            $stsin = HrAttendance::where('CreateDate',$today)->where('status','lunchin')->where('EmployeeID',$request->emp_id)->count('RowID');
           $stsout = HrAttendance::where('CreateDate',$today)->where('status','lunchout')->where('EmployeeID',$request->emp_id)->count('RowID');
           $isin = HrAttendance::where(DB::raw("(date(InTime))"), date('Y-m-d'))->whereNull('OutTime')->where('EmployeeID',$request->emp_id)->count('RowID');
           
            if($isin >= 0 && $stsin >= 0 && $stsout >= 0){
                
            HrAttendance::where('RowID',$request->emp_row_id)->where('EmployeeID',$request->emp_id)->update([
                'OutTime'=> $datetime,
                'status' => 'lunchout'
            ]);
            return redirect()->route('attendance.PunchAttendance');
           
            }
        
        }

        if($request->lunchin){
         
            //die('testt');
            $stsin = HrAttendance::where('CreateDate',$today)->where('status','lunchin')->where('EmployeeID',$request->emp_id)->count('RowID');
            $stsout = HrAttendance::where('CreateDate',$today)->where('status','lunchout')->where('EmployeeID',$request->emp_id)->count('RowID');
            // $isin = HrAttendance::where(DB::raw("(date(InTime))"), date('Y-m-d'))->where(DB::raw("(date(OutTime))"), date('Y-m-d'))->where('EmployeeID',$request->emp_id)->count('RowID');
              $isin = HrAttendance::where(DB::raw("(date(InTime))"), date('Y-m-d'))->where('EmployeeID',$request->emp_id)->count('RowID');
            
              if($isin >= 0 && $stsin >= 0 && $stsout >= 0){
                //return 'ghfh';
                HrAttendance::insert([
                    'EmployeeID'=> $request->emp_id,
                    'InTime' => $datetime,
                    'CreateDate' => date('Y-m-d'),
                    'IP' => '',
                    'status' => 'lunchin',
                    'CreatedBy' => Auth::user()->email
                ]);
                return redirect()->route('attendance.PunchAttendance');
               
            }
        
        }


        ///////////////Punc In Feature /////////////////////
        if($request->punch && $request->punch == 'Punch IN')
        {
        if($request->out_time == "" and $request->emp_row_id !="") {
            //$employeedal->updateEmpPunch($emp_last_punch[0]['RowID']);
            ;
            }
        else {
            HrAttendance::insert([
                'EmployeeID'=> $request->emp_id,
                'InTime' => $datetime,
                'OutTime' => Null,
                'CreateDate' => date('Y-m-d'),
                'IP' => $ip,
               // 'status' => 'lunchin',
                'CreatedBy' => Auth::user()->email
            ]);

          
        }
        return redirect()->route('attendance.PunchAttendance');
        }



        if($request->punchout && $request->punchout == 'Punch OUT')
        {
            //return $request->emp_row_id;
        $userId = Auth::user()->id;
        if($request->out_time == "" and $request->emp_row_id !="") 
        {
            HrAttendance::where('RowID',$request->emp_row_id)->update([
                'OutTime'=> $datetime
            ]);
            
        }
        else {
            //$employeedal->addEmpPunch($data[0]['EmployeeID'],$datetime,$ip,$_SESSION['user']);
            ;
        }
        return redirect()->route('attendance.PunchAttendance');
        //header("Refresh: .1; url=punch-attendance.php");
        }



    }


    public function  EmpSalary(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $employee_data= \App\User::select('email')->where('user_type', 'staff')->get();
       $emp_data = [];
       foreach($employee_data as $da)
       {
           $item = array(
               'Email' => $da->email
           );
           array_push($emp_data, $item);
       }
       //return json_encode($emp_data);
        $difference=0;
        $difference_time=0;
        $total_time=0;
        $total=0;
        $msg = '';
        $data = array();
        $employee_id='';
        $data_attendance = array();
        $total_time_holiday=0;
        $total_normal_sal=0;
        $data_sal_advance=array();
        $datal=array();
        $loggedinEmp = \App\Staff::where('user_id', Auth::user()->id)->first();
        $email = isset($request->email)?$request->email:'';
        $gmonth = isset($request->month)?$request->month:date("m");
        $month = $request->month;
        $year = $request->year;
        $years = isset($request->year)?$request->year:'0000';
        $months = isset($request->month)?$request->month:'00';
        $yr = isset($request->year)?$request->year:'1970';
        $mo = isset($request->month)?$request->month:'01';
        $mon = isset($request->month)?$request->month:'';//date('m',strtotime($_GET['end']));
        $cmon = isset($request->month)?$request->month:'';
        $cssstr = "display:none";
        if(Auth::user()->user_type == 'admin' ){
        $cssstr = "display:block";
        }
      
        return view("attendance.view_emp_salary",compact('gmonth','yr','mo','mon','cmon','years','months','month','year','email','cssstr','loggedinEmp','emp_data', 'difference', 'difference_time', 'total_time', 'total', 'msg', 'data', 'employee_id', 'data_attendance', 'total_time_holiday', 'total_normal_sal', 'data_sal_advance', 'datal'));
    }


    public function getEmp(Request $request)
    {
        //return json_encode($request->all());
        date_default_timezone_set('Asia/Kolkata');
        $logusmail = \App\User::where('email',$request->email)->first();
        $employee_data= \App\User::select('email')->where('user_type', 'staff')->get();
       $emp_data = [];
       foreach($employee_data as $da)
       {
           $item = array(
               'Email' => $da->email
           );
           array_push($emp_data, $item);
       }
       //return json_encode($emp_data);
        $difference=0;
        $difference_time=0;
        $total_time=0;
        $total=0;
        $msg = '';
        $data = array();
        $employee_id='';
        $data_attendance = array();
        $total_time_holiday=0;
        $total_normal_sal=0;
        $data_sal_advance=array();
        $datal=array();
        $loguser = \App\Staff::where('user_id', $logusmail->id)->first();
        $loggedinEmp = $loguser->user_id;
        $email = isset($request->email)?$request->email:'';
        $gmonth = isset($request->month)?$request->month:date("m");
        $month = $request->month;
        $year = $request->year;
        $years = isset($request->year)?$request->year:'0000';
         $months = isset($request->month)?$request->month:'00';
         $yr = isset($request->year)?$request->year:'1970';
        $mo = isset($request->month)?$request->month:'01';
        $mon = isset($request->month)?$request->month:'';//date('m',strtotime($_GET['end']));
        $cmon = isset($request->month)?$request->month:'';
        $cssstr = "display:none";
        if(Auth::user()->user_type == 'admin' ){
        $cssstr = "display:block";
        }
        $suplierId = 0;
        $from = null;
        $to = null;
        // if(isset($request->get_emp))
        // {from
            $items =  DB::table('users')
        ->select('users.id as userid', 'users.name', 'users.email', 'staff.salaryrate', 'staff.holidayrate', 'staff.id as staffid')
        ->join('staff', 'staff.user_id', '=', 'users.id')
        ->where('users.email',$request->email)
        ->get();
       
        $data = array();
        foreach($items as $dat)
        {
            $item = array(
                'Name' => $dat->name,
                'EmployeeID' => $dat->userid,
                'Email' => $dat->email,
                'SupplierID' => $dat->userid,
                'SalaryRate' => $dat->salaryrate,
                'Holidayrate' => $dat->holidayrate

            );
            array_push($data,$item);
            $employee_id=isset($dat->userid)?$dat->userid:'0';
            $suplierId = isset($dat->userid)?$dat->userid:'0';
            $from=$request->year.'-'.$request->month.'-01';
            $to=$request->year.'-'.$request->month.'-31';
            // get attendance
            $data_attendance = array();
            $data_attendance_data= DB::table('hr_attendance')->select('RowID','EmployeeID','status','InTime','OutTime')->addSelect(DB::raw('Date(InTime) as day,TIMESTAMPDIFF(SECOND,InTime,OutTime) as difference'))
             ->where('EmployeeID',$employee_id)
             ->whereDate('InTime','>=',$from)->whereDate('InTime','<=',$to)
            //  ->where(DB::raw('DATE(`InTime`) between '.$from.' and '.$to.''))
             ->get();
            //return json_encode( $data_attendance_data);
            foreach($data_attendance_data as $attdata)
            {
                $attendancedata = array(
                    'difference' => $attdata->difference,
                    'day' =>$attdata->day,
                    'InTime' => $attdata->InTime,
                    'OutTime' => $attdata->OutTime,
                    'status' => $attdata->status
                );
                array_push($data_attendance,$attendancedata);
            }
           // return json_encode( $data_attendance);
      
            // $datal = DB::table('hr_attendance as a1')
            // ->select('a1.InTime as a1','a1.OutTime as a2')
            // ->join('hr_attendance as a2','a1.CreateDate','=', 'a2.CreateDate')
            // ->where('a1.status','lunchin')->orwhere('a2.status','lunchout')
            // ->where('a1.EmployeeID',$employee_id)
            // ->where('a2.EmployeeID',$employee_id)
            // ->whereDate('a1.InTime','>=',$from)->whereDate('a1.InTime','<=',$to)
            // ->get();
             $servername = env('DB_HOST');//$conf->host;
        $username = env('DB_USERNAME');//$conf->user;
        $password = env('DB_PASSWORD');//$conf->password;
        $dbname = env('DB_DATABASE');//$conf->db;
        $con = mysqli_connect($servername, $username, $password, $dbname);
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }else{
            //echo "connected";
        }
        function get_all_array($conn,$selectquery)
		{
		  $rrr=executequery($conn,$selectquery);
		  $result=array();
		  
		      // return multi dimensions array  .. ie. [0][array] ...[1][array] ...
			  while($m=fetchrecord($rrr))
			  {
				$result[]=$m;
			  }
		  
		  return $result;
        }	
        function executequery($conn,$string,$debug=0)
        {
                    if ($debug == 1)
                    print $string;
                    if ($debug == 2)
                    error_log($string);
                    $result = mysqli_query($conn,$string);
                    if (!$result) {
                        printf("Error: %s\n", mysqli_error($conn));
                        exit();
                    }
                    if ($result == false)
                    {
                            error_log( "SQL error: reading database");
                    }
                    return $result;
        }
        function fetchrecord($queryresult_string)
        {
            return mysqli_fetch_array($queryresult_string,MYSQLI_ASSOC);
        }

            $query="SELECT a1.InTime,a2.OutTime FROM `hr_attendance` a1 INNER JOIN `hr_attendance` a2 ON a1.CreateDate = a2.CreateDate WHERE a1.status='lunchin' and a2.status='lunchout' and a1.EmployeeID = '$employee_id' and a2.EmployeeID = '$employee_id' and (DATE(a1.`InTime`) between '$from' and '$to')";
            //$result = $this->conn->executeQuery($query);
            $result=executequery($con,$query);
                $sum = 0;$break=0;
                while ($row = mysqli_fetch_assoc($result))
                {
                    $diff = strtotime($row['InTime']) - strtotime($row['OutTime']);
                    $break = $break+$diff;
                    $sum = $sum+(35*60-$diff);
                }
               $datal = array($break,$sum);
           
           // return json_encode( $datal);
            $data_sal_advance = array();
            $data_sal_advance_data = DB::select( DB::raw("SELECT SUM(l.`cp`) as `sum` FROM `ap_invoices_alls` h, `ap_invoice_lines` l, `ap_suppliers` s where h.`invoiceid`=l.`invoice_id` AND
            h.`supplier_id`=s.`supplierid` and h.`Status`='P'  and l.`isdeleted`='N' and s.`supplierid`='$suplierId'") );
                foreach($data_sal_advance_data as $data_sal)
                {
                    $salary = array(
                        'sum'=> $data_sal->sum
                    );
                    array_push($data_sal_advance,$salary);
                }
            
        }
            //return $year;
         //  return json_encode($data);
       // }
        return view("attendance.view_emp_salary",compact('data','employee_id','suplierId','from','to','datal','data_sal_advance','data_attendance','gmonth','yr','mo','mon','cmon','years','months','month','year','email','cssstr','loggedinEmp','emp_data', 'difference', 'difference_time', 'total_time', 'total', 'msg', 'data', 'employee_id',  'total_time_holiday', 'total_normal_sal',  'datal'));
    }

    public function salInv(Request $request)
    {
      //  return json_encode($request->all());
        date_default_timezone_set('Asia/Kolkata');
        $employee_data= \App\User::select('email')->where('user_type', 'staff')->get();
       $emp_data = [];
       foreach($employee_data as $da)
       {
           $item = array(
               'Email' => $da->email
           );
           array_push($emp_data, $item);
       }
       //return json_encode($emp_data);
        $difference=0;
        $difference_time=0;
        $total_time=0;
        $total=0;
        $msg = '';
        $data = array();
        $employee_id='';
        $data_attendance = array();
        $total_time_holiday=0;
        $total_normal_sal=0;
        $data_sal_advance=array();
        $datal=array();
        $logusmail = \App\User::where('email',$request->email)->first();
        $loguser = \App\Staff::where('user_id', $logusmail->id)->first();
        $loggedinEmp = $loguser->user_id;
        $email = isset($request->email)?$request->email:'';
        $gmonth = isset($request->month)?$request->month:date("m");
        $month = $request->month;
        $year = $request->year;
        $years = isset($request->year)?$request->year:'0000';
         $months = isset($request->month)?$request->month:'00';
         $yr = isset($request->year)?$request->year:'1970';
        $mo = isset($request->month)?$request->month:'01';
        $mon = isset($request->month)?$request->month:'';//date('m',strtotime($_GET['end']));
        $cmon = isset($request->month)?$request->month:'';
        $items =  DB::table('users')
        ->select('users.id as userid', 'users.name', 'users.email', 'staff.salaryrate', 'staff.holidayrate', 'staff.id as staffid')
        ->join('staff', 'staff.user_id', '=', 'users.id')
        ->where('users.email',$request->email)
        ->get();
        $data = array();
        foreach($items as $dat)
        {
            $item = array(
                'Name' => $dat->name,
                'EmployeeID' => $dat->userid,
                'Email' => $dat->email,
                'SupplierID' => $dat->userid,
                'SalaryRate' => $dat->salaryrate,
                'Holidayrate' => $dat->holidayrate

            );
            array_push($data,$item);
            $employee_id=isset($dat->userid)?$dat->userid:'0';
            $suplierId = isset($dat->userid)?$dat->userid:'0';
            $from=$request->year.'-'.$request->month.'-01';
            $to=$request->year.'-'.$request->month.'-31';
        }
        $cssstr = "display:none";
        if(Auth::user()->user_type == 'admin' ){
        $cssstr = "display:block";
        }
        if($request->sal_inv)
        {
        $salary_calcuated=$request->sal_tot;
        $employee_supplier_id=$request->sal_supplier;
        $employee_name=$request->sal_supplier_emp_name;
        if($employee_supplier_id=="")
        {
            die("Employee Supplier not present !!");
        }
        else
        {
       
        $ap_invoice_number="SAL-".$employee_name."-".date("Y-M"); // generate AP invoice number
        // create invoice header
        $isexist = DB::table('ap_invoices_alls')->where('invoice_number',$ap_invoice_number)->count('invoiceid');
        //return json_encode($isexist);
        if($isexist > 0){
            $msg ="Duplicate AP Invoice Number";
            //return $employee_id;
            return view("attendance.view_emp_salary",compact('msg','gmonth','yr','mo','mon','cmon','years','months','month','year','email','cssstr','loggedinEmp','emp_data', 'difference', 'difference_time', 'total_time', 'total', 'msg', 'data', 'employee_id', 'data_attendance', 'total_time_holiday', 'total_normal_sal', 'data_sal_advance', 'datal'))->with('sts', 'duplicate');
           
            }
            $datetime=date('Y-m-d');
        $success= DB::table('ap_invoices_alls')->insert([
            'invoice_number' => $ap_invoice_number,
            'invoiceid' => null,
            'invoicetype' => 'S',
            'supplier_id' => $employee_supplier_id,
            'Date' => date("Y-m-d"),
            'Status' => 'O',
            'igst' => null,
            'sgst' => null,
            'cgst' => null,
            'gst' => null,
            'Total' => null,
            'image' => null,
            'creationDate' => $datetime,
            'description' => 'Salary invoice',
            'lastupdatedate' => NOW(),
            'lastUupdateby' => Auth::user()->email,
            'store_id' => '1',
            'type' => 'Salary'
            

        ]);
       
        $invoice_header_data_ap=DB::table('ap_invoices_alls')->where('invoice_number', $ap_invoice_number)->where('supplier_id',$employee_supplier_id)->first();
       
        $invoice_id_ap=$invoice_header_data_ap->invoiceid;
        // add line
        $success_line=DB::table('ap_invoice_lines')->insert([
            'lineid' => Null,
            'invoice_id' => $invoice_id_ap,
            'product_id' => '9830',
            'version' => 'N',
            'quantity' => '1',
            'mrp' => null,
            'cp' => $salary_calcuated,
            'isdeleted' => 'N',
            'lastupdatedby' => Auth::user(),
            'lastupdateddate' => NOW(),
            'arinvoice' => NULL

        ]);
        
        
        // redirect to next page
        if($success) 
        return redirect('/admin/APinvoice_header_workbench_old/view/'. $invoice_id_ap.'/'.$employee_supplier_id);
       
        //die($ap_invoice_number);
        }
        }

    }

    public function ApplyLeaveView()
    {
        $msg = '' ; 
        date_default_timezone_set('Asia/Kolkata');
        $datetime=date('Y-m-d H:i:s');
        $ip="";

        $data = array();
        $data_leave = array();
        $loggeduserid = Auth::user()->id;
        $user =  DB::table('users')->where('id',$loggeduserid)->first();
        if($user->user_type == 'staff')
        {
        $items =  DB::table('users')
        ->select('users.id as userid', 'users.name', 'users.email', 'staff.salaryrate', 'staff.holidayrate', 'staff.id as staffid')
        ->join('staff', 'staff.user_id', '=', 'users.id')
        ->where('staff.user_id', Auth::user()->id)
        ->get();
         //  return json_encode($items);
         foreach($items as $dat)
         {
             $item = array(
                 'Name' => $dat->name,
                 'EmployeeID' => $dat->userid,
                 'Email' => $dat->email,
                 'SalaryRate' => $dat->salaryrate,
                'Holidayrate' => $dat->holidayrate
 
 
             );
             array_push($data,$item);
          
             $data2 = DB::table('Hr_Leave')->where('EmployeeID',$data[0]['EmployeeID'])->orderBy('RowID','DESC')->get();
             foreach($data2 as $d)
             {
                 $dataitem =[
                 'Date' => $d->Date,
                 'ToDate' => $d->ToDate,
                 'Description' => $d->Description,
                 'Document'     => $d->document,
                 'leavetype' => $d->leavetype,
                 'Status' =>  $d->Status,
                 'RowID' => $d->RowID
                 ];
                 array_push($data_leave,$dataitem);
             }
         }
        }
        else
        {
            $items =  DB::table('users')->where('id',$loggeduserid)->where('user_type','admin')->get();
             //  return json_encode($items);
        foreach($items as $dat)
        {
            $item = array(
                'Name' => $dat->name,
                'EmployeeID' => $dat->id,
                'Email' => $dat->email


            );
            array_push($data,$item);
         
            $data2 = DB::table('Hr_Leave')->where('EmployeeID',$data[0]['EmployeeID'])->orderBy('RowID','DESC')->get();
            foreach($data2 as $d)
            {
                $dataitem =[
                'Date' => $d->Date,
                'ToDate' => $d->ToDate,
                'Description' => $d->Description,
                'Document'     =>  $d->document,
                'leavetype' => $d->leavetype,
                'Status' =>  $d->Status,
                'RowID' => $d->RowID
                ];
                array_push($data_leave,$dataitem);
            }
        }
           
        }
       
      //  return json_encode($data_leave);
        // IP address of location
        return view('attendance.apply_leave',compact('data_leave','data'))->with('msg',$msg);

    }
    public function ApplyLeave(Request $request)
    {
        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));
        //$date_to = date('Y-m-d',$request->date_to);
        $exists1 = DB::table('Hr_Leave')->where('Date', '<=', $date_from )->where('ToDate', '>=', $date_to )->where('Status' ,'A')->count('RowID');
        $exists2 = DB::table('Hr_Leave')->whereBetween('Date',[$date_from, $date_to] )->where('Status' ,'A')->count('RowID');
        $exists3 = DB::table('Hr_Leave')->whereBetween('ToDate',[$date_from, $date_to] )->where('Status' ,'A')->count('RowID');

        $rrr1 = DB::table('Hr_Leave')->where('Date', '<=', $date_from )->where('ToDate', '>=',$date_to )->where('Status' ,'A')->get();
        $rrr2 = DB::table('Hr_Leave')->whereBetween('Date',[$date_from, $date_to] )->where('Status' ,'A')->get();
        $rrr3 = DB::table('Hr_Leave')->whereBetween('ToDate',[$date_from, $date_to] )->where('Status' ,'A')->get();
        $arr = array();$i=0; $s=0;
        
        if($exists1>0){ 
            foreach($rrr1 as $r):
                $empname = DB::table('staff')->where('user_id',$r->EmployeeID)->first();
            // list($empname) = get_query_list($con,"select Name FROM `Hr_Employees` where EmployeeID = ".$r['EmployeeID']);
                $arr[$i]['sts'] = 'no'; 
                $arr[$i]['name'] = $empname;
                $arr[$i]['FromDate'] = $r->Date;
                $arr[$i]['ToDate'] = $r->ToDate;
                $i++;
            endforeach;
            }else if($exists2>0){
        
            foreach($rrr2 as $r):
                $empname = DB::table('staff')->where('user_id',$r->EmployeeID)->first();
                $arr[$i]['sts'] = 'no'; 
                $arr[$i]['name'] = $empname;
                $arr[$i]['FromDate'] = $r->Date;
                $arr[$i]['ToDate'] = $r->ToDate;
                $i++;
            endforeach;
            }else if($exists3>0){
        
            foreach($rrr3 as $r):
                $empname = DB::table('staff')->where('user_id',$r->EmployeeID)->first();
                $arr[$i]['sts'] = 'no';  
                $arr[$i]['name'] = $empname;
                $arr[$i]['FromDate'] = $r->Date;
                $arr[$i]['ToDate'] = $r->ToDate;
                $i++;
            endforeach;
            }else{ 
            //list($empname) = get_query_list($con,"select Name FROM `Hr_Employees` where EmployeeID = ".$r['EmployeeID']);
            $arr[$i]['sts'] = 'yes';
            $arr[$i]['name'] = '';
            $arr[$i]['FromDate'] = '';
            $arr[$i]['ToDate'] = '';
            $i++;
            }
            
        return json_encode($arr);	
        

    }

    public function ApplyLeaveSubmit(Request $request)
    {
       // return json_encode($request->all());
        // if($request->punch)
        // {
        $date1=date_create($request->date);
        //return $request->date;
        $date2=date_create($request->date_to);
        $diff=date_diff($date1,$date2);
        
        $fsts = $request->status;

        $days = $diff->format("%R%a"); 
        if($days < 0){
        $msg = "From Date should be less then To Date";	
            }else{
                $exists1 = DB::table('Hr_Leave')->where('Date','<=',$request->date)->where('ToDate','>=',$request->date_to)->where('Status','A')->where('EmployeeID',Auth::user()->id)->count('RowID');
                $exists2 = DB::table('Hr_Leave')->wherebetween('Date',[$request->date,$request->date_to])->where('Status','A')->where('EmployeeID',Auth::user()->id)->count('RowID');
                $exists3 = DB::table('Hr_Leave')->wherebetween('ToDate',[$request->date,$request->date_to])->where('Status','A')->where('EmployeeID',Auth::user()->id)->count('RowID');
        if($exists1 > 0){
            redirect('admin/attendance/applyleaveview');
          
            } else	if($exists3 > 0){
                redirect('admin/attendance/applyleaveview');
            } else	if($exists1 > 0){
                redirect('admin/attendance/applyleaveview');
            } else{
            //return false;	
            	
                
                
            $status = 'P';
        if($days <= 2){ $status = 'A'; }else{ $status = 'P'; }
        if($fsts == 'N'){$status = 'P';}

        if($request->date <= date('Y-m-d')){
            $status = 'P';
            }

       //return $status;
            $isavail=0;
            $leavetype = $request->leavetype;
        // $leavetype = $_POST['status'];
        if($request->leavetype == 'EL'){
            $isavail = DB::table('Hr_Leave')->where('EmployeeID',Auth::user()->id)->where('Status','!=','R')->where('leavetype','EL')->where('Date("Date", "Y") = ".date("Y")." ')->count('RowID');
      
            if($isavail > 3){
                $status = 'P';
                }else{
                $status = 'A';	
                }
            }
            $photos = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
           // $photos = json_encode($photos);
        } 
      // return $photos;
      $time = strtotime($request->date);
      $date_from = date('Y-m-d',$time);
      $timeto = strtotime($request->date_to);
      $date_to = date('Y-m-d',$timeto);
           $dataIns = DB::table('Hr_Leave')->insert([
                'RowID' => NULL,
                'EmployeeID' => Auth::user()->id,
                'Date' => $date_from,
                'Status' => $status,
                'CreationDate' => Now(),
                'CreatedBy' => '-1',
                'ToDate' => $date_to,
                'Description' => $request->desc,
                'leavetype' => $leavetype,
                'document' => json_encode($photos)
            ]);
          //  $requestdata = array('date_from'=> $date_from,'date_to' => $date_to);
        return redirect($request->header('Referer'));
         // redirect('admin/attendance/applyleaveview');
            // $data = array('sts' => $status, 'name'=> Auth::user()->name , 'FromDate' => $request->date , 'ToDate' => $request->date_to);
            // return json_encode($data);
       // $employeedal->addLeave($data[0]['EmployeeID'],$_POST['date'],$_POST['desc'],$_POST['date_to'],$status,$leavetype,$file);
        }
         }
        // $data = array('sts' => $status, 'name'=> Auth::user()->name , 'FromDate' => $request->date , 'ToDate' => $request->date_to);
        // return json_encode($data);

    }


    public function ActionLeave(Request $request)
    {
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $data = array();
        $msg ='';

        $items =  DB::table('Hr_Leave as l')
        ->join('users as u','u.id', '=', 'l.EmployeeID')
        ->select('l.RowID', 'l.Date', 'l.ToDate', 'l.Status', 'l.Description', 'l.CreationDate', 'l.document', 'l.leavetype','u.name')
        ->orderBy('l.RowID', 'DESC')
        ->get();

       foreach($items as $item)
       {
           $itemdata = array(
               'Name' => $item->name,
               'Date' => $item->Date,
               'Description' => $item->Description,
               'ToDate' => $item->ToDate,
               'CreationDate' => $item->CreationDate,
               'leavetype' => $item->leavetype,
               'document' => $item->document,
               'RowID' => $item->RowID,
               'Status' => $item->Status
           );
           array_push($data,$itemdata);
       }
        return view('attendance.actionleave',compact('data','startdate','enddate'))->with('msg',$msg);

    }

    public function SearchActionLeave(Request $request)
    {
        $data = array();
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $msg ='';
        if(isset($request->search)){
        $items =  DB::table('Hr_Leave as l')
        ->join('users as u','u.id', '=', 'l.EmployeeID')
        ->select('l.RowID', 'l.Date', 'l.ToDate', 'l.Status', 'l.Description', 'l.CreationDate', 'l.document', 'l.leavetype','u.name')
        ->orderBy('l.RowID', 'DESC')
        ->wherebetween('l.date',[$request->startdate,$request->enddate])
        ->get();
        // $items =  DB::table('staff')
        // ->join('users', 'staff.user_id', '=', 'users.id')
        // ->join('Hr_Leave as l', 'l.EmployeeID', '=', 'staff.id')
        // ->select('l.RowID', 'l.Date', 'l.ToDate', 'l.Status', 'l.Description', 'l.CreationDate', 'l.document', 'l.leavetype','users.name')
        // ->wherebetween('l.date',[$request->startdate,$request->enddate])
        // ->orderBy('l.RowID', 'DESC')
        // ->get();
        foreach($items as $item)
       {
           $itemdata = array(
               'Name' => $item->name,
               'Date' => $item->Date,
               'Description' => $item->Description,
               'ToDate' => $item->ToDate,
               'CreationDate' => $item->CreationDate,
               'leavetype' => $item->leavetype,
               'document' => $item->document,
               'RowID' => $item->RowID,
               'Status' => $item->Status
           );
           array_push($data,$itemdata);
       }
    }
    if(isset($request->clear)){
        $items =  DB::table('Hr_Leave as l')
        ->join('users as u','u.id', '=', 'l.EmployeeID')
        ->select('l.RowID', 'l.Date', 'l.ToDate', 'l.Status', 'l.Description', 'l.CreationDate', 'l.document', 'l.leavetype','u.name')
        ->orderBy('l.RowID', 'DESC')
        ->get();

       foreach($items as $item)
       {
           $itemdata = array(
               'Name' => $item->name,
               'Date' => $item->Date,
               'Description' => $item->Description,
               'ToDate' => $item->ToDate,
               'CreationDate' => $item->CreationDate,
               'leavetype' => $item->leavetype,
               'document' => $item->document,
               'RowID' => $item->RowID,
               'Status' => $item->Status
           );
           array_push($data,$itemdata);
       }
    }
        
       return view('attendance.actionleave',compact('data','startdate','enddate'))->with('msg',$msg);

    }

    public function LunchReport(Request $request)
    {
        $msg ='';
        $sum = 0;$break=0; $arr = array();$i=0;
        $emp_datas= [];
        $email ='';
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $users = isset($request->users)?$request->users:'';
        return view('attendance.lunchreport',compact('startdate','enddate','email','users','sum','break','arr','i','emp_datas'))->with('msg',$msg);

    }

    public function lunchreportSubmit(Request $request)
    {
        $msg ='';
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $users = isset($request->users)?$request->users:'';
       
        $emailval = DB::table('users')->select('email')->where('id',$request->users)->first();
        $email =$emailval->email;
        $loggedinEmp = DB::table('users')->select('id')->where('id',$request->users)->first();
		//$loggedinEmp = DB::table('staff')->select('id')->where('user_id',$request->users)->first();
        $query =  DB::table('hr_attendance as a1')
        ->where('a1.status','lunchin')
		->orwhere('a1.status','lunchout')
        ->where('a1.EmployeeID',$loggedinEmp->id)
        ->whereDate('a1.InTime','>=',$request->startdate)->whereDate('a1.InTime','<=',$request->enddate)
        
       // return $loggedinEmp->id;
		// $query = DB::table('hr_attendance as a1')
		// ->join('hr_attendance as a2', 'a1.CreateDate', '=' ,'a2.CreateDate')
		// ->where('a1.status','lunchin')
		// ->orwhere('a2.status','lunchout')
		// ->where('a1.EmployeeID',$loggedinEmp->id)
        // ->where('a2.EmployeeID',$loggedinEmp->id)
        // ->whereDate('a1.InTime','>=',$request->startdate)->whereDate('a1.InTime','<=',$request->enddate)
		//->wherebetween(Date('a1.InTime'),[$request->startdate, $request->enddate])
		->get();
		//return json_encode($query);
		$sum = 0;$break=0; $arr = array();$i=0;
		$emp_datas= [];
		foreach($query as $row)
		{
			$arr =[
				'time' => strtotime($row->InTime) - strtotime($row->OutTime),
				'InTime' => $row->InTime,
				'OutTime' => $row->OutTime

            ];
            
			array_push($emp_datas,$arr);
        }
        return view('attendance.lunchreport',compact('startdate','enddate','email','sum','users','break','arr','i','emp_datas'))->with('msg',$msg);
       
    }

    public function LeaveReport(Request $request)
    {
        $msg ='';
        $rrr = array();
        $items =  DB::table('staff')
        ->join('users', 'staff.user_id', '=', 'users.id')
        ->get();
        foreach($items as $r)
        {
            $darr = array(
                'Name' => $r->name,
                'EmployeeID' => $r->user_id
            );
            array_push($rrr,$darr);
        }
        
        $inc=0;
        return view('attendance.leavereport',compact('rrr','msg','inc'));

    }

    public function LeaveDate(Request $request,$id)
    {
        
        $start = isset($request->start) ? $request->start : '';
        $end = isset($request->end) ? $request->end : '';
        $rrr =array();
        $days = array();
        $sunday = array();
        $leavesDate = array();
        return view('attendance.leavedate',compact('start','end','id','rrr','days','sunday','leavesDate'));

    }

    public function editleave(Request $request,$id)
    {
        $arr = DB::table('Hr_Leave')->where('RowID',$id)->first();
        $msg = '';
        //$category_data="SELECT `CategoryId`,`Name`,`customer_type` FROM `Customer_Category` WHERE `IsDeleted`='N' order by Name ASC"
        return view('attendance.editleave',compact('msg','id','arr'));

    }

    public function EditLeaveSubmit(Request $request)
    {
        if($request->has('previous_photos')){
            $photos = $request->previous_photos;
        }
        else{
            $photos = array();
        }
       
        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
        }

       $updleave = DB::table('Hr_Leave')->where('RowID',$request->rowid)->update([
            'Description' => $request->desc,
            'document' => json_encode($photos)
        ]) ;
       
            return redirect()->route('attendance.ApplyLeaveView');
    }

    public function Search(Request $request)
    {
        $start = isset($request->start) ? $request->start : '';
        $end = isset($request->end) ? $request->end : '';
        $yr = date('Y',strtotime($request->start));
        $s_yr = date('Y',strtotime($request->start));
        $e_yr = date('Y',strtotime($request->end));
      
      

        // if($_SESSION['Role'] == 1 || $_SESSION['Role']== 2){
        $yr = date('Y',strtotime($request->start));
        $mo = date('m',strtotime($request->start));
        $date = "$yr-01-01"; //$_POST['start']
        $date = "$yr-$mo-01";
        //$date2 = date('Y-m-d');
        $date2 = $request->end ;
        $empid =  $request->empid;
        $id =  $request->empid;
        $leave = array();
        // function getSundays($y,$m){ 
        //     $date = "$y-$m-01";
        //     $first_day = date('N',strtotime($date));
        //     $first_day = 7 - $first_day;
        //     $last_day =  date('t',strtotime($date));
        //     $days = array();
        //     for($i=$first_day; $i<=$last_day; $i=$i+7 ){
        //         $days[] = "$y-$m-$i";
        //     }
        //     return  $days;
        // }
        $yr = date('Y',strtotime($request->end)); //end year
        //$mon = date('m'); //current month
        $mon = date('m',strtotime($request->end));
        $cmon = date('m',strtotime($request->start));

        $startDate = new DateTime($request->start);
        $endDate = new DateTime($request->end);
        
        $sundays = array();

        while ($startDate <= $endDate) {
            if ($startDate->format('w') == 0) {
                $sundays[] = $startDate->format('Y-m-d');
            }

            $startDate->modify('+1 day');
        }
        
        $sunday = $sundays;
        $servername = env('DB_HOST');//$conf->host;
        $username = env('DB_USERNAME');//$conf->user;
        $password = env('DB_PASSWORD');//$conf->password;
        $dbname = env('DB_DATABASE');//$conf->db;
        $con = mysqli_connect($servername, $username, $password, $dbname);
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }else{
            //echo "connected";
        }
        function get_all_array($conn,$selectquery)
		{
		  $rrr=executequery($conn,$selectquery);
		  $result=array();
		  
		      // return multi dimensions array  .. ie. [0][array] ...[1][array] ...
			  while($m=fetchrecord($rrr))
			  {
				$result[]=$m;
			  }
		  
		  return $result;
        }	
        function executequery($conn,$string,$debug=0)
        {
                    if ($debug == 1)
                    print $string;
                    if ($debug == 2)
                    error_log($string);
                    $result = mysqli_query($conn,$string);
                    if (!$result) {
                        printf("Error: %s\n", mysqli_error($conn));
                        exit();
                    }
                    if ($result == false)
                    {
                            error_log( "SQL error: reading database");
                    }
                    return $result;
        }
        function fetchrecord($queryresult_string)
        {
            return mysqli_fetch_array($queryresult_string,MYSQLI_ASSOC);
        }

        $rrr = get_all_array($con,"SELECT * FROM ( SELECT DATE_ADD('$date', INTERVAL t4+t16+t64+t256+t1024 DAY) day FROM (SELECT 0 t4 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 ) t4, (SELECT 0 t16 UNION ALL SELECT 4 UNION ALL SELECT 8 UNION ALL SELECT 12 ) t16, (SELECT 0 t64 UNION ALL SELECT 16 UNION ALL SELECT 32 UNION ALL SELECT 48 ) t64, (SELECT 0 t256 UNION ALL SELECT 64 UNION ALL SELECT 128 UNION ALL SELECT 192) t256, (SELECT 0 t1024 UNION ALL SELECT 256 UNION ALL SELECT 512 UNION ALL SELECT 768) t1024 ) b WHERE day NOT IN (SELECT CreateDate FROM hr_attendance where hr_attendance.EmployeeID=$empid) AND day<'$date2'");
        //$rrr = get_all_array($con,"SELECT * FROM ( SELECT DATE_ADD('$date', INTERVAL t4+t16+t64+t256+t1024 DAY) day FROM (SELECT 0 t4 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 ) t4, (SELECT 0 t16 UNION ALL SELECT 4 UNION ALL SELECT 8 UNION ALL SELECT 12 ) t16, (SELECT 0 t64 UNION ALL SELECT 16 UNION ALL SELECT 32 UNION ALL SELECT 48 ) t64, (SELECT 0 t256 UNION ALL SELECT 64 UNION ALL SELECT 128 UNION ALL SELECT 192) t256, (SELECT 0 t1024 UNION ALL SELECT 256 UNION ALL SELECT 512 UNION ALL SELECT 768) t1024 ) b WHERE day NOT IN (SELECT CreateDate FROM Hr_Attendance where Hr_Attendance.EmployeeID=$empid) AND day<='$date2'");
        
        $days=array();
        //echo implode(", ",$rrr[0]);
        // echo "</p>";
        // foreach($rrr as $r):
        // $days[] = $r['day'];
        // endforeach;
        // echo "<h2>Not Presented in Office include Sunday (".count($days).")</h2><p>";
        // echo implode(", ",$days);

        // $result=array_diff($days,$sunday);
        // echo "<h2>Not Presented in Office excluding Sunday(".count($result).")</h2><p>";

        // echo implode(", ",$result);
        // echo "<hr />";
        //echo implode(", ",$sunday);
        //$leaves = get_all_array($con,"SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(Date,'%Y')  >= '$s_yr' and DATE_FORMAT(Date,'%Y')  <= '$e_yr'");
        //echo "SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(Date,'%Y') = '$yr'";
        //$leaves = get_all_array($con,"SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(ToDate,'%Y%m%d')  >= '".date('Ymd',strtotime($_GET['start']))."' and DATE_FORMAT(Date,'%Y%m%d')  <= '".date('Ymd',strtotime($_GET['end']))."'");
        $leaves = get_all_array($con,"SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(ToDate,'%Y%m%d')  >= '".date('Ymd',strtotime($_GET['start']))."' and DATE_FORMAT(Date,'%Y%m%d')  <= '".date('Ymd',strtotime($_GET['end']))."'");
        //echo "SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(ToDate,'%Y%m%d')  >= '".date('Ymd',strtotime($_GET['start']))."' and DATE_FORMAT(Date,'%Y%m%d')  <= '".date('Ymd',strtotime($_GET['end']))."'";
        $leave = array();
        $leavesDate=array();
        //print_r($leaves);
        foreach($leaves as $leave):



        $date_from = $leave['Date'];  
        $date_from = strtotime($date_from); // Convert date to a UNIX timestamp  
        //$date_from = strtotime($_GET['start']);  
        // Specify the end date. This date can be any English textual format  
        $date_to = $leave['ToDate'];  
        $date_to = strtotime($date_to); // Convert date to a UNIX timestamp  
        if($date_to >= strtotime($request->end)){
        //$date_to = strtotime($_GET['end']); 
        }
        //$date_to = strtotime($_GET['end']); 
        // Loop from the start date to end date and output all dates inbetween  
        for ($i=$date_from; $i<=$date_to; $i+=86400) { 
        if(date('Ymd',strtotime($request->start)) > date("Ymd", $i)) {continue;} 
            $leavesDate[] = date("Y-m-d", $i);  
        } 

        endforeach;

        // echo "<h2>Total Approved Leaves Days(".count($leavesDate).")</h2>";

        // echo implode(", ",$leavesDate);

        // $withoutapproval=array_diff($result,$leavesDate);

        // echo "<h2>Total Leave Without approval (".count($withoutapproval).")</h2>";
        // echo implode(', ',$withoutapproval);

        // $presentleave=array_diff($leavesDate,$days);
        // echo "<h2>Present After Approved Leave (".count($presentleave).")</h2>";
        // echo implode(', ',$presentleave);
// }else{
// echo "<h3 style='color:red'>You are not authorised to view this employee detail.</h3>";	
// }
return view('attendance.leavedate',compact('start','id','end','yr','s_yr','e_yr','yr','mo','date','date2','empid','leave','mon','cmon','startDate','endDate','sundays','sunday','rrr','days','leaves','leave','leavesDate'));

    }
    
    public function Calling(Request $request)
    {
        $search ='';
        $msg ='';
        // $condi = ' where 1 = 1 ';
		// $rrr = DB::table('calling')->where('1','1')->orderBy('id','Desc')->limit('50');
		
        $id = isset($request->id)?$request->id:'';
       // $query="SELECT `CategoryId`,`Name`,`customer_type` FROM `Customer_Category` WHERE `IsDeleted`='N' order by Name ASC";
        $query= DB::table('customer_categories')->where('IsDeleted', 'N')->orderBy('name','ASC')->get();
        $category_data = [];
        foreach($query as $qry)
        {
            $arrd = array(
                'CategoryId' => $qry->id,
                'Name' => $qry->name,
                'customer_type' => $qry->customertype

            );
            array_push($category_data,$arrd);
        }
        return view('attendance.calling',compact('category_data','msg','search'));

    }
    public function CallerList(Request $request)
    {
        return view('attendance.callerlist');

    }
    public function AddCaller(Request $request)
    {
        $msg = '';
        //$category_data="SELECT `CategoryId`,`Name`,`customer_type` FROM `Customer_Category` WHERE `IsDeleted`='N' order by Name ASC"
        return view('attendance.addcaller',compact('msg'));

    }
    public function editCaller(Request $request,$id)
    {
        $calleruser = DB::table('calling_user')->where('id',$id)->first();
        $msg = '';
        //$category_data="SELECT `CategoryId`,`Name`,`customer_type` FROM `Customer_Category` WHERE `IsDeleted`='N' order by Name ASC"
        return view('attendance.editcaller',compact('msg','id','calleruser'));

    }
    public function AddCallerSave(Request $request)
    {
       // return $request->id;
       if($request->address == '')
       {
        $request->address = '';

       }
       if($request->category == '')
       {
        $request->category = '';

       }
       if($request->city == '')
       {
        $request->city = '';

       }
       if($request->comment == '')
       {
        $request->comment = '';

       }
       if($request->email == '')
       {
        $request->email = '';

       }
       if($request->mobile2 == '')
       {
        $request->mobile2 = '';

       }
       if($request->state == '')
       {
        $request->state = '';

       }
       if($request->zipcode == '')
       {
        $request->zipcode = '';

       }
       
        $arr = array(
            'name'=>$request->c_name,
            'mobile1'=>$request->mobile,
            'mobile2'=>$request->mobile2,
            'email'=>$request->email,
            'category'=>$request->category,
            'institute'=>$request->institute,
            'city'=>$request->city,
            'zipcode'=>$request->zipcode,
            'state'=>$request->state,
            'address'=>$request->address,
            'comment'=>$request->comment,
            'createdby'=>Auth::user()->id
            );
            //print_r($con);
          //  return $request->mobile1;
            $customers = DB::table('users')->where('phone',"+91".$request->mobile)->where('phone',$request->mobile)->first();
         
            if($customers){
                $msg = "Caller user already exist in customer list.";
                return view('attendance.addcaller',compact('msg'));
                }else{
            $x = DB::table('calling_user')->insert([ $arr ]);
           
            if($x){
                $msg = "Caller user added successfully";
                return view('attendance.addcaller',compact('msg'));
                }
            }
       // return view('attendance.addcaller');

    }
    public function UpdateCallerSave(Request $request)
    {
     // return $request->id;
     if($request->address == '')
     {
      $request->address = '';

     }
     if($request->category == '')
     {
      $request->category = '';

     }
     if($request->city == '')
     {
      $request->city = '';

     }
     if($request->comment == '')
     {
      $request->comment = '';

     }
     if($request->email == '')
     {
      $request->email = '';

     }
     if($request->mobile2 == '')
     {
      $request->mobile2 = '';

     }
     if($request->state == '')
     {
      $request->state = '';

     }
     if($request->zipcode == '')
     {
      $request->zipcode = '';

     }
     
        $arr = array(
            'name'=>$request->c_name,
            'mobile1'=>$request->mobile,
            'mobile2'=>$request->mobile2,
            'email'=>$request->email,
            'category'=>$request->category,
            'institute'=>$request->institute,
            'city'=>$request->city,
            'zipcode'=>$request->zipcode,
            'state'=>$request->state,
            'address'=>$request->address,
            'comment'=>$request->comment,
            'createdby'=>Auth::user()->id
            );
            //print_r($con);
          //  return $request->mobile1;
            $customers = DB::table('users')->where('phone',"+91".$request->mobile)->where('phone',$request->mobile)->first();
       //  return json_encode($customers);
            if($customers){
                $msg = "Caller user already exist in customer list.";
                $id = $request->callerid;
                $calleruser = DB::table('calling_user')->where('id',$request->callerid)->first();
                return view('attendance.editcaller',compact('msg','id','calleruser'));
                }else{
            $x = DB::table('calling_user')->where('id',$request->callerid)->update($arr);
        
            if($x){
                $msg = "Caller user updated successfully";
                $id = $request->callerid;
                $calleruser = DB::table('calling_user')->where('id',$request->callerid)->first();
                return view('attendance.editcaller',compact('msg','id','calleruser'));
                }
            }
            $id = $request->callerid;
            $calleruser = DB::table('calling_user')->where('id',$request->callerid)->first();
            return view('attendance.editcaller',compact('msg','id','calleruser'));

    }
    public function getCaller(Request $request)
    {
        $category = $request->category;
        $institute = $request->institute;
        $userId = $request->userid;
        $servername = env('DB_HOST');//$conf->host;
        $username = env('DB_USERNAME');//$conf->user;
        $password = env('DB_PASSWORD');//$conf->password;
        $dbname = env('DB_DATABASE');//$conf->db;
        $con = mysqli_connect($servername, $username, $password, $dbname);
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }else{
            //echo "connected";
        }
        function get_all_array($conn,$selectquery)
		{
		  $rrr=executequery($conn,$selectquery);
		  $result=array();
		  
		      // return multi dimensions array  .. ie. [0][array] ...[1][array] ...
			  while($m=fetchrecord($rrr))
			  {
				$result[]=$m;
			  }
		  
		  return $result;
        }	
        function executequery($conn,$string,$debug=0)
        {
                    if ($debug == 1)
                    print $string;
                    if ($debug == 2)
                    error_log($string);
                    $result = mysqli_query($conn,$string);
                    if (!$result) {
                        printf("Error: %s\n", mysqli_error($conn));
                        exit();
                    }
                    if ($result == false)
                    {
                            error_log( "SQL error: reading database");
                    }
                    return $result;
        }
        function fetchrecord($queryresult_string)
        {
            return mysqli_fetch_array($queryresult_string,MYSQLI_ASSOC);
        }

        function get_query_list($conn,$sql, $debug=0)
         {
			//echo $sql."<br/>";
                  if ($debug == 1)
                  echo $sql;
                  if ($debug == 2)
                  error_log($sql);
                 //print_r($conn);
                  $result = mysqli_query($conn,$sql);
                  if (!$result) {
                    printf("Error: %s\n", mysqli_error($conn));
                    exit();
                }
                if ($result == false)
                {
                        error_log( "SQL error: reading database");
                }   
                  
                  if($lst = mysqli_fetch_row($result))
                  {
                           mysqli_free_result($result);
                           return $lst;/// returns records in rows
                  }
                  mysqli_free_result($result);
                  return false;
         }

        $sql1 = "SELECT * from calling_user where 1=1 and cancall='yes' ";
        $sql2 =  "SELECT e.id as custid, u.name, u.email, u.phone,u.category_id,u.institute_id,u.id as uerid FROM `customers` AS e INNER JOIN `users` AS u ON e.user_id = u.id WHERE e.cancall = 'yes'";
        // $sql2 = "SELECT * FROM `customers` where 1=1 and cancall='yes' ";
       // return $sql2;
        $where1 = '';$where2 = '';
        if($category == '' && $institute != ''){
        //$rrr = get_all_array($con,"select * from calling");	
        $where1 .= " and institute = '$institute' ";	
        $where2 .= " and u.institute_id = '$institute' ";	

            }
        if($institute == '' && $category != ''){
        $where1 .= " and category = '$category' ";
        $where2 .= " and u.category_id = '$category' ";
            }
            
        if($institute != '' && $category != ''){
            $where1 .= " and category = '$category' and institute = '$institute' ";
            $where2 .= " and u.category_id = '$category' and u.institute_id = '$institute' ";
            
        }

        $callingmobiles = get_all_array($con,"select DISTINCT(mobile) from calling where is_repeat='no' ");
        $mobiles = array();
        foreach($callingmobiles as $mo):
        //if( strpos( $mobiles, 'xx' ) !== false){
                //continue;
                //}
        $mobiles[] = $mo['mobile'];
        endforeach;
       
        if(count($mobiles) > 0){
        $mob = "'".str_replace(",","','",implode(',',$mobiles))."'"; 	
        $where1 .= " and mobile1 NOT IN (".$mob.")";
        $where2 .= " and u.phone NOT IN (".$mob.")";
        }
         $sql1 = $sql1.$where1.' limit 0,1'; 
        $sql2 = $sql2.$where2.' limit 0,1';

        $data1 = get_all_array($con,$sql1);
        $data2 = get_all_array($con,$sql2);
        //print_r($data2); die();

        $mobile=array();$i=0;
        foreach($data1 as $d1):
        $mobile[$i]['name'] = $d1['name'];
        $mobile[$i]['mobile1'] = $d1['mobile1'];
        $mobile[$i]['type'] = 'caller';
        $mobile[$i]['id'] = $d1['id'];
        $i++;
        endforeach;
       // print_r($mobile); die();

        foreach($data2 as $d2):
            $mobile[$i]['name'] = $d2['name'];
            $mobile[$i]['mobile1'] = $d2['phone'];
            $mobile[$i]['type'] = 'customers';
            $mobile[$i]['id'] = $d2['custid'];
        $i++;
        endforeach;
//return json_encode($mobile);
        $tdate = date('Y-m-d');
        list($currentval) = get_query_list($con,"select currentval from userlimit where tdate = '".$tdate."' and userid = '".$userId."'");
        list($dailylimit) = get_query_list($con,"select dailylimit from Users where RowId=".$userId);
        if($currentval < $dailylimit || $currentval == ''){
            //$mobile1 = $_GET['mobile'];
            if($currentval == ''){
            //	executequery($con,"INSERT INTO `userlimit` (`id`, `userid`, `dailylimit`, `currentval`, `tdate`, `created`, `modified`) VALUES (NULL, '".$userId."', '$dailylimit', '1', '".$tdate."', current_timestamp(), '');");
                }else{
            //executequery($con,"update userlimit set currentval = currentval+1 where tdate = '".$tdate."' and userid = '".$userId."'");
        }
            }else{
            //echo "<option value=''>Daily Limit Cross</option>";	die;exit;
        }


        //echo "<option data-name='' value=''> Select Caller</option>";
        if(sizeof($mobile>0)){
        foreach($mobile as $mob):
        $optdata = "<option data-type='".$mob['type']."' data-id='".$mob['id']."' data-name='".$mob['name']."' value='".$mob['mobile1']."'>".$mob['mobile1']." - ".$mob['name']."</option>";
        //break;
        endforeach;
        }
        return $optdata;
    }


    public function delete(Request $request,$id)
    {
        $leave = DB::table('Hr_Leave')->where('RowID',$id)->delete();
        // $leave->delete();
        return redirect($request->header('Referer'));
        
    }

    public function takeactionleave($id)
    {
        $msg = '';
       
        return view('attendance.takeactionleave',compact('id','msg'));

    }
    public function takeactionleaveSubmit(Request $request)
    {
        
        $status=$request->status;
        $success = DB::table('Hr_Leave')->where('RowID',$request->id)->update(['Status' =>$status]);
         $id = $request->id;
       
        $msg="Status Changed Successfully !!";
        return view('attendance.takeactionleave',compact('id','msg'));
    }

    public function repeatCategory(Request $request)
    {
        $cat = $request->category;
        $update_calling = DB::table('calling')->where('category',$cat)->update([
            'is_repeat' => 'yes'
        ]);
       
       // $msg ="Category updated successfully";
        return redirect($request->header('Referer'));
        //return view('attendance.takeactionleave',compact('id','msg'));
    }


    public function callersave(Request $request)
    {
        $calling = array(
            'name'=>$request->name,
            'mobile'=>$request->caller,
            'institute'=>$request->institute,
            'category'=>$request->category,
            'status'=>$request->status,
            'entry_by'=>Auth::user()->email,
            'reminder_date'=>$request->reminder
            );
            Auth::user()->category_id = $request->category;
            Auth::user()->institute_id = $request->institute;
          
            $m1 = DB::table('calling')->where('mobile',$request->caller);
            $x = DB::table('calling')->insertGetId($calling);
              
                if($x){
                    $caller_id=$x;
                    $comments = array(
                    'calling_id'=>$caller_id,
                    'comment'=>$request->comment,
                    'reminder_date'=>$request->reminder,
                    'status'=>$request->status,
                    'entry_by'=>Auth::user()->email,
                    );
                    $x = DB::table('calling_comment')->insert($comments);
                    
                    if($x){
                        return redirect($request->header('Referer'));
                        //_setmsg("Calling list has been added successfully.");
                        //header("Location:calling.php");
                        }
                    }
                    

    }


    public function callingsearch(Request $request)
    {
        //return 'ghhh';
        $msg ='';
        $search = $request->search;
        $rstartdate = $request->rstartdate;
        $renddate = $request->renddate;
        $cstartdate = $request->cstartdate;
        $cenddate = $request->cenddate;
        $status = $request->status;
        $ssearch = $request->ssearch;
        $category = $request->category;
        $institutes = $request->institutes;
        
        $condi = ' where 1 = 1 ';
        if($request->institute != ''){
            $condi .= " and institute = '".$request->institute."' ";	
                }
            if($request->category != ''){
            $condi .= " and category = '".$request->category."' ";		
                }
            if($request->status != ''){
            $condi .= " and status like '".$request->status."' ";	
                }
                
            if($request->ssearch != ''){
                $condi .= " and (name like '".$request->ssearch."' OR mobile = '".$request->mobile."') ";	
                }
            if($request->cstartdate != '' && $request->cenddate != ''){
                $condi .= " and (DATE_FORMAT(`entrydate`, '%Y-%m-%d') >= '".$request->cstartdate."' and  (DATE_FORMAT(`entrydate`, '%Y-%m-%d') <= '".$request->cenddate."') ";
                }
            if($request->rstartdate != '' && $request->renddate != ''){
               // return 'gfggh';
                $condi .= " and (reminder_date >= '".$request->rstartdate."' and  reminder_date <= '".$request->renddate."') ";
                }

                $query= DB::table('customer_categories')->where('IsDeleted', 'N')->orderBy('name','ASC')->get();
                $category_data = [];
                foreach($query as $qry)
                {
                    $arrd = array(
                        'CategoryId' => $qry->id,
                        'Name' => $qry->name,
                        'customer_type' => $qry->customertype

                    );
                    array_push($category_data,$arrd);
                }
                //return $condi;
                return view('attendance.calling',compact('condi','search','msg','category_data','rstartdate','renddate','cstartdate','cenddate','status','ssearch','category','institutes'));
    }

    public function callingcomment($id)
    {
       
        $rrr = DB::table('calling_comment')->where('calling_id',$id)->orderBy('id','Desc')->get();
       // return json_encode($rrr);
        return view('attendance.callingcomments',compact('rrr','id'));
    }

    public function callercommentsubmit(Request $request)
    {
       
            $sql = DB::table('calling_comment')->insertGetId([
                'calling_id' => $request->caller_id,
                'comment' =>$request->comment,
                'reminder_date' => $request->reminder,
                'status' => $request->status,
                'entry_by' => Auth::user()->email
            ]);
            $updt_sql2 = DB::table('calling')->where('id',$request->caller_id)->update([
                'status' => $request->status,
                'entrydate' => date('Y-m-d'),
                'entry_by' => Auth::user()->email,
                'reminder_date' => $request->reminder
                ]);
         return 'saved';
       
        
    }
    public function callingsms($id,$mobile,$userid)
    {
       
        $tdate = date('Y-m-d');
        $currentval = '0';
        $dailylimit ='0';
        $currentvaldata = DB::table('userlimit')->where('tdate',$tdate)->where('userid',$userid)->first();
       if($currentvaldata != '' || $currentvaldata != null)
       {
        $currentval = $currentvaldata->currentval;
       }
       
        $dailylimitdata = DB::table('users')->where('id',$userid)->first();
        if($dailylimitdata != '' || $dailylimitdata != null)
        {
            $dailylimit = $dailylimitdata->dailylimit;
        }
       
        $mobile1 = substr($mobile,0,3).'xxxxx'.substr($mobile,-2);	
        if($currentval < $dailylimit || $currentval == ''){
            $mobile1 = $mobile;
            if($currentval == ''){
                DB::table('userlimit')->insert([
                    'id' =>NULL,
                    'userid' => $userid,
                    'dailylimit' => $dailylimt,
                    'currentval' => '1',
                    'tdate' => $tdate,
                    'created' => current_timestamp(),
                    'modified' => ''
                ]);
               
                }else{
                    DB::table('userlimit')->where('tdate',$tdate)->where('userid',$userid)->update([
                        'currentval' => $currentval+1
                    ]);
           
        }
            }else{
                $mobile1 = substr($mobile,0,3).'xxxxx'.substr($mobile,-2);	
        }
       
    

        return view('attendance.callersms',compact('tdate','mobile1'));
    }
    

    public function callersmssubmit(Request $request)
    {
       
        $body=trim($request->body);
        $mobile=$request->to;
        sendSMS($mobile, env('APP_NAME'), $body);
       // $data=array('status'=>"true", 'message'=> 'Sms send', 'phone'=>$mobile);
       // return json_encode($data);
       return 'done';
        
    }

    public function download_attendance($emp,$month,$year)
    {
        $pdf = PDF::loadView('attendance.dowload_attendance',compact('emp','year','month'));   
        return $pdf->download('demo.pdf');
    }
    public function generatePDF($emp,$month,$year)
    {
        $pdf = PDF::loadView('attendance.dowload_attendance',compact('emp','year','month'));   
        return $pdf->download('demo.pdf');
    }
    
}


