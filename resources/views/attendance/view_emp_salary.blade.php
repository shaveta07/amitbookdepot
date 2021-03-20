@extends('layouts.app')

@section('content')

<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">View Employee Salary</h1>
        </div>
</div>
@if($msg != Null)
			<div class="alert alert-danger">{{ $msg }}</div>
            @endif
			
<?php
 date_default_timezone_set('Asia/Kolkata');
    if(isset($_GET['sts']) && $_GET['sts'] == 'duplicate'){
        
        echo '<div class="alert alert-danger">
        <strong>Error! </strong> Duplicate AP Invoice Number</div>';  
        
    }
    $emailid = $email;
    $servername = env('DB_HOST');//$conf->host;
$username = env('DB_USERNAME');//$conf->user;
$password = env('DB_PASSWORD');//$conf->password;
$dbname = env('DB_DATABASE');//$conf->db;

$con = mysqli_connect($servername, $username, $password, $dbname);

function get_all_array($conn,$selectquery)
{
    $rrr = executequery($conn,$selectquery);
    if (!$rrr) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
    }
    $result=array();
    
    // return multi dimensions array  .. ie. [0][array] ...[1][array] ...
    while($m = fetchrecord($rrr))
    {
        $result[] = $m;
    }
    
    return $result;
}

function fetchrecord($queryresult_string)
{
    return mysqli_fetch_array($queryresult_string,MYSQLI_ASSOC);
}
        
function executequery($conn,$string,$debug=0)
{
            if ($debug == 1)
            print $string;
            if ($debug == 2)
            error_log($string);
            $result = mysqli_query($conn,$string);
            if ($result == false)
            {
                    error_log( "SQL error: reading database");
            }
            return $result;
}
        //print_r($emp_data);
       
?>
<form method="post" action="{{ route('attendance.getEmp') }}" class="form-inline">
@csrf
<select required="required" name="email" class="form-control">
<option value="">Select Employee Email</option>
<?php

for($i=0;$i<count($emp_data);$i++){
	if($emp_data[$i]['Email'] == Auth::user()->email){
		?>
		<option selected value="<?php echo $emp_data[$i]['Email']; ?>"><?php echo $emp_data[$i]['Email']; ?></option>
		<?php
		}else if(Auth::user()->user_type == "admin" ){
?>
<option <?php if($emp_data[$i]['Email'] == $emailid){ echo "selected"; } ?> value="<?php echo $emp_data[$i]['Email']; ?>"><?php echo $emp_data[$i]['Email']; ?></option>
<?php } 
}

?>
</select>&nbsp;
<select class="form-control" name="month" required="required" >

<option <?php if($gmonth == "01"){echo "selected";} ?> value="01">JAN</option>
<option <?php if($gmonth == "02"){echo "selected";} ?> value="02">FEB</option>
<option <?php if($gmonth == "03"){echo "selected";} ?> value="03">MAR</option>
<option <?php if($gmonth == "04"){echo "selected";} ?> value="04">APR</option>
<option <?php if($gmonth == "05"){echo "selected";} ?> value="05">MAY</option>
<option <?php if($gmonth == "06"){echo "selected";} ?> value="06">JUN</option>
<option <?php if($gmonth == "07"){echo "selected";} ?> value="07">JUL</option>
<option <?php if($gmonth == "08"){echo "selected";} ?> value="08">AUG</option>
<option <?php if($gmonth == "09"){echo "selected";} ?> value="09">SEP</option>
<option <?php if($gmonth == "10"){echo "selected";} ?> value="10">OCT</option>
<option <?php if($gmonth == "11"){echo "selected";} ?> value="11">NOV</option>
<option <?php if($gmonth == "12"){echo "selected";} ?> value="12">DEC</option>
</select>
&nbsp;
<select  class="form-control" name="year" required="required" >
<?php for($i=(date("Y")-5);$i<2035;$i++) {?>
<option <?php if($i == $year){echo "selected";}else{if($i == date('Y')){echo "selected";}} ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php } ?>
</select>
&nbsp;
<input type="submit" class="btn btn-danger" name="get_emp" value="Submit" />
</form>
</br>
</br>
Name: <?php echo isset($data[0]['Name'])?$data[0]['Name']:''; ?>&nbsp;&nbsp;&nbsp;&nbsp;Employee ID:  <?php echo $employee_id; ?>&nbsp;&nbsp;&nbsp;&nbsp;Email:  <?php echo isset($data[0]['Email'])?$data[0]['Email']:''; ?>
<?php  if(Auth::user()->user_type == "admin"): ?>
&nbsp;&nbsp;&nbsp;&nbsp;Attendance:

&nbsp;&nbsp;
<a href="{{url('/admin/attendance/download_attendance')}}/<?= $employee_id; ?>/<?= $months ?>/<?= $years ?>" target="_blank" >Download Attendance</a><br />

<?php endif;
 ?>
 </br>
<table  id="invtbl" style= "border-color: rgb(17, 2, 1);" class="table table-bordered" >
<tr>
<th>Day</th>
<th>Effort</th>
<th>Time</th>
<th>IN Time</th>
<th>OUT Time</th>
<th>Day</th>
</tr>
<?php 
//print_r($data_attendance);
if($data_attendance) {
for($i=0;$i<count($data_attendance);$i++)
{
	$difference_time=round($data_attendance[$i]['difference']/3600,2);
	$hours = floor($difference_time);
	$mins = round(($difference_time - $hours) * 60);
$timestamp = strtotime($data_attendance[$i]['day']);
$day = date('D', $timestamp);

$difference_time=round($data_attendance[$i]['difference']/3600,2);
$hours = floor($difference_time);
$mins = round(($difference_time - $hours) * 60);
$timestamp = strtotime($data_attendance[$i]['day']);
$day = date('D', $timestamp);
if($day!='Sun') $total_time+=$difference_time; else $total_time_holiday+=$difference_time;

//if($day!='Sun') $total_time+=$difference_time; else $total_time_holiday+=$difference_time;

	//if($_SESSION['Role']==1 || $_SESSION['Role']==2): 
	
	if(Auth::user()->user_type == "admin" || Auth::user()->user_type == "staff"){
	 if($data_attendance[$i]['day'] != date('Y-m-d')){
	 	continue;
	 }
	 
	}
	
?>
<tr>
<td><?php echo $data_attendance[$i]['day']; ?></td>
<td><?php echo $difference_time=round($data_attendance[$i]['difference']/3600,2); ?></td>
<td><?php //echo $difference_time;
$hours = floor($difference_time);
$mins = round(($difference_time - $hours) * 60);
echo $hours." Hours and ".$mins." Minutes";  //$total_time+=$difference_time; ?></td>
<td><?php echo $data_attendance[$i]['InTime']; if($data_attendance[$i]['status'] == 'lunchin'){echo " -".$data_attendance[$i]['status']; }  ?></td>
<td><?php echo $data_attendance[$i]['OutTime']; if($data_attendance[$i]['status'] == 'lunchout'){echo " -".$data_attendance[$i]['status']; } ?></td>
<td>
<?php
$timestamp = strtotime($data_attendance[$i]['day']);
$day = date('D', $timestamp); echo $day;
?>
</td>
</tr>
<?php 
//endif;
//if($day!='Sun') $total_time+=$difference_time; else $total_time_holiday+=$difference_time;
} }?>
<tr style="">
<td><span style="<?= $cssstr ?>"><b>Total Hours</b></span></td>
<td><span style="<?= $cssstr ?>"><b><?php echo $total_time+$total_time_holiday; ?></b></td></span>
<td><b><?php 
$hrs1=$mins1=$hrs2=$mns2='0';
//print_r($datal); die();
if(isset($datal[0])){
    $hrs1 = floor(round($datal[0]/3600,2));
    $mins1 = round((round($datal[0]/3600,2) - $hrs1) * 60);
    }
    if(isset($datal[1])){
    $hrs2 = floor(round(abs($datal[1])/3600,2));
    $mns2 = round((round(abs($datal[1])/3600,2) - $hrs2)*60);
    }

 $tot = $total_time+$total_time_holiday; 
$hours = floor($tot);
$mins = round(($tot - $hours) * 60);

$excludingholidaysinhrs = floor($total_time);
$excludingholidaysinmin = abs(round(($total_time-$excludingholidaysinhrs)*60));
//$includingholidaysinhrs = floor($total_time_holiday+$tot);
//$includingholidaysinmin = abs(round($tot-$includingholidaysinhrs));
$lunch_bonus_time=0;
//echo "Total Time (Working days) ".$hours." Hours and ".$mins." Minutes <br />";
echo "<div style='". $cssstr."'>Total Time (Working days - excluding sunday) ".$excludingholidaysinhrs." Hours and ".$excludingholidaysinmin." Minutes <br />";

$hours2 = isset($total_time_holiday)?floor($total_time_holiday):'0';
$mins2 = round(($total_time_holiday - $hours2) * 60);
echo "Total Time (Sunday) ".$hours2." Hours and ".$mins2." Minutes<br/>";


echo "Total Time(For Lunch Break): ".$hrs1." Hours and $mins1 Minutes<br/>"; 

if(isset($datal[1]) && $datal[1] < 0){
$lunch_bonus_time = -($hrs2+$mns2/60); 
echo "Total Bonus Time(For Lunch Break): -".$hrs2." Hours and -($mns2) Minutes";
}else{
  
$lunch_bonus_time = ($hrs2+$mns2/60);
echo "Total Bonus Time(For Lunch Break): ".$hrs2." Hours and $mns2 Minutes</div>";	
}
 ?></b></td>
 <td>&nbsp;</td>
 <td>&nbsp;</td>
 <td>&nbsp;</td>
</tr></table>

<?php 

if((isset($data[0]['EmployeeID']) && $loggedinEmp == $data[0]['EmployeeID']) ||  Auth::user()->user_type=="admin"){
 $rate= isset($data[0]['SalaryRate'])?$data[0]['SalaryRate']:'0';
$total_normal_sal=$rate*$total_time;

$dl0 = isset($datal[0])?$datal[0]:'0';
$dl1 = isset($datal[1])?$datal[1]:'0';
//$total_lunch_bonus = $rate * round($dl0/3600,2);
$total_lunch_bonus = $rate*$lunch_bonus_time;
$rate_h= isset($data[0]['HolidayRate'])?$data[0]['HolidayRate']:'0';
$total_h_sal=$rate_h*$total_time_holiday;
// $years = isset($_POST['year'])?$_POST['year']:'0000';
// $months = isset($_POST['month'])?$_POST['month']:'00';
$cur = $years-$months;
$lunch_salary=0;
if(isset($data[0]['EmployeeID'])){
$rrr = \App\Models\HrAttendance::where('EmployeeID',$data[0]['EmployeeID'])->where('status','lunchin')->whereraw('DATE_FORMAT(CreateDate,"%Y-%m") = ".$cur."')->groupBy('CreateDate')->get();
$lunch_salary=0;
//print_r($datal);
//echo "SELECT * FROM `Hr_Attendance` where EmployeeID = '".$data[0]['EmployeeID']."' and status='lunchin' AND DATE_FORMAT(CreateDate,'%Y-%m') = '2017-09' group by CreateDate";
foreach($rrr as $r){
$lunch_salary += round($rate*35/60 , 2);  /////35 minutes Lunch
}
}
$data_sal_advance[0]['sum'] = isset($data_sal_advance[0]['sum'])?$data_sal_advance[0]['sum']:'';
//echo $total_normal_sal;
 ?>
 <h4>Salary: <?php
 //if($_SESSION['Role']==1 || $_SESSION['Role']==2){
  echo $total=$total_normal_sal+$total_h_sal+$total_lunch_bonus+$lunch_salary;
  //}else{
   //echo $total=$total_normal_sal;
 // }
   ?> INR</h4>
 Working days : <?php echo $total_normal_sal;  ?> <span style="<?= $cssstr ?>">@ hourly rate <?php echo $rate;  ?></span><br />
 Sundays : <?php echo $total_h_sal;  ?> <span style="<?= $cssstr ?>">@ hourly rate <?php echo $rate_h;  ?></span><br />
 Lunch Compsation = <?php echo $lunch_salary; ?><br/>
 Lunch Bonus = <?php echo $total_lunch_bonus; ?>
  <br/>
 <?php echo "Advance ".$data_sal_advance[0]['sum']; ?><br /><br />
 
 
 <!--  View employee Salary -->
 <?php
 /*
  * VIEW Employee SALARY 
  */
 


$date = "$yr-$mo-01";
$date2 = "$yr-$mo-31";

if($mo == '04' || $mo == '06' || $mo == '09' || $mo == '11'){
$date2 = "$yr-$mo-30";
}
if($mo == 02){
if($year % 4 == 0){
$date2 = "$yr-$mo-29";
}else{
$date2 = "$yr-$mo-28";
}
}
$today = date('Y-m-d');

if($date2 > $today){
	$date2 = date('Y-m-d');
}

$empid = isset($data[0]['EmployeeID'])?$data[0]['EmployeeID']:'0';
$leave = array();

$yr = //date('Y',strtotime(date('Y-m-d')); //end year
//$mon = date('m'); //current month


$startDate = new DateTime($date);
$endDate = new DateTime($date2);

$sundays = array();

while ($startDate <= $endDate) {
    if ($startDate->format('w') == 0) {
        $sundays[] = $startDate->format('Y-m-d');
    }

    $startDate->modify('+1 day');
}
///chance of error due to empty employee id
$sunday = $sundays;
// $rrr = DB::select('SELECT DATE_ADD('.$date.', INTERVAL t4+t16+t64+t256+t1024 DAY) day 
// FROM (SELECT 0 t4 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 ) t4, 
// (SELECT 0 t16 UNION ALL SELECT 4 UNION ALL SELECT 8 UNION ALL SELECT 12 ) t16, 
// (SELECT 0 t64 UNION ALL SELECT 16 UNION ALL SELECT 32 UNION ALL SELECT 48 ) t64, 
// (SELECT 0 t256 UNION ALL SELECT 64 UNION ALL SELECT 128 UNION ALL SELECT 192) t256, 
// (SELECT 0 t1024 UNION ALL SELECT 256 UNION ALL SELECT 512 UNION ALL SELECT 768) t1024')
// ->whereNotIn('day', DB::select('SELECT CreateDate FROM Hr_Attendance where Hr_Attendance.EmployeeID=$empid'))
// ->where('day','<=',$date2)->get();



$rrr = get_all_array($con,"SELECT * FROM ( SELECT DATE_ADD('$date', INTERVAL t4+t16+t64+t256+t1024 DAY) day FROM (SELECT 0 t4 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 ) t4, (SELECT 0 t16 UNION ALL SELECT 4 UNION ALL SELECT 8 UNION ALL SELECT 12 ) t16, (SELECT 0 t64 UNION ALL SELECT 16 UNION ALL SELECT 32 UNION ALL SELECT 48 ) t64, (SELECT 0 t256 UNION ALL SELECT 64 UNION ALL SELECT 128 UNION ALL SELECT 192) t256, (SELECT 0 t1024 UNION ALL SELECT 256 UNION ALL SELECT 512 UNION ALL SELECT 768) t1024 ) b WHERE day NOT IN (SELECT CreateDate FROM hr_attendance where hr_attendance.EmployeeID=$empid) AND day<='$date2'");

// echo json_encode($rrr);
// die();

$days=array();
//echo implode(", ",$rrr[0]);
echo "</p>";
foreach($rrr as $r):
$days[] = $r['day'];
endforeach;
//if($_SESSION['Role']==1 || $_SESSION['Role']==2): 
 
echo "<h4>Not Presented in Office include Sunday (".count($days).")</h4><p>";
echo implode(", ",$days);

$result=array_diff($days,$sunday);
echo "<h4>Not Presented in Office excluding Sunday(".count($result).")</h4><p>";

echo implode(", ",$result);
echo "<hr />";

///// chance of error due ti empty employee id
//echo implode(", ",$sunday);
//$leaves = get_all_array($con,"SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(Date,'%Y')  >= '".$_REQUEST['year']."' and DATE_FORMAT(Date,'%Y')  <= '".$_REQUEST['year']."'");
//echo "SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(Date,'%Y') = '$yr'";
$leaves = get_all_array($con,"SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(ToDate,'%Y%m%d')  >= '".date('Ymd',strtotime($date))."' and DATE_FORMAT(Date,'%Y%m%d')  <= '".date('Ymd',strtotime($date2))."'");

//echo "SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(ToDate,'%Y%m%d')  >= '".date('Ymd',strtotime($date))."' and DATE_FORMAT(Date,'%Y%m%d')  <= '".date('Ymd',strtotime($date2))."'";
$leave = array();
$leavesDate=array();
//print_r($leaves);
//echo "SELECT * FROM `Hr_Leave` where EmployeeID = $empid and Status = 'A' and DATE_FORMAT(ToDate,'%Y%m%d')  >= '".date('Ymd',strtotime($date))."' and DATE_FORMAT(Date,'%Y%m%d')  <= '".date('Ymd',strtotime($date2))."'";
foreach($leaves as $leave):



$date_from = $leave['Date'];  
$date_from = strtotime($date_from); // Convert date to a UNIX timestamp  
//$date_from = strtotime($date); 
// Specify the end date. This date can be any English textual format  
$date_to = $leave['ToDate'];  
$date_to = strtotime($date_to); // Convert date to a UNIX timestamp  

if($date_to >= strtotime($date2)){
$date_to = strtotime($date2); 
}

// Loop from the start date to end date and output all dates inbetween  
for ($i=$date_from; $i<=$date_to; $i+=86400) { 
if(date('Ymd',strtotime($date)) > date("Ymd", $i)) {continue;}
    $leavesDate[] = date("Y-m-d", $i);  
} 

endforeach;
//if($_SESSION['Role']==1 || $_SESSION['Role']==2): 
//  if($_SESSION['Role']!=1 && $_SESSION['Role']!=2 && $_SESSION['Role']!=3 && $_SESSION['Role']!=4):
echo "<h4>Total Approved Leaves Days(".count($leavesDate).")</h4>";

echo implode(", ",$leavesDate);

$withoutapproval=array_diff($result,$leavesDate);

echo "<h4>Total Leave Without approval (".count($withoutapproval).")</h4>";
echo implode(', ',$withoutapproval);
//print_r($leavesDate);
//print_r($result);
$presentleave=array_diff($leavesDate,$days);
echo "<h4>Present After Approved Leave (".count($presentleave).")</h4>";
echo implode(', ',$presentleave);
 
 ?>
   <br/>
  
  <?php 
  $value = DB::table('business_settings')->select('value')->where('id','61')->first();
 
  $fine=$value->value*count($withoutapproval);
  echo "<h4><strong>Total Fine for Absent Without approval: </strong> ".$fine."</h4>"; ?>
  <?php echo "<h4><strong>Final Salary Amount: </strong> ".round(($total-$fine),2)."</h4>"; ?><br /><br />
 
  <?php 
//   endif;
  if(Auth::user()->user_type=="admin" || Auth::user()->user_type=="staff" ){  ?>
  <form method="post" action="{{route('attendance.salInv')}}">
  @csrf
  <input type="hidden" name="email" value="<?php if(sizeof($data) > 0) { echo $data[0]['Email']; } ?>" />
  <input type="hidden" name="sal_tot" value="<?php echo ($total-$fine); ?>" />
 <input type="hidden" name="sal_supplier" value="<?php  if(sizeof($data) > 0) { echo $data[0]['SupplierID']; }  ?>" />
 <input type="hidden" name="sal_supplier_emp_name" value="<?php if(sizeof($data) > 0) { echo $data[0]['Name']; }?>" />
 <input type="submit" class="btn btn-primary" name="sal_inv" value="Generate AP Invoice" />
  </form>
  <?php } }?>
@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection