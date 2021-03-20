<?php
/*
Author: Diljot Singh
Version: 1.0
Description: Add Book Category
*/
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

$msg = isset($msg)?$msg:'';
if(Auth::user()->user_type = 'admin' || Auth::user()->user_type = 'staff'){
	
$html = '
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    

</head>
<body>';
   
   
    $cmonth = strtotime($year.'-'.$year);
    //date('M Y',$cmonth);
    $html .= '<h2>Attendance record of '.date('M Y',$cmonth).'</h2>';
    //$userid = $emp;
  list($empname,$userid) = get_query_list($con,"select name,id from users where id='".$emp."'");
    list($email) = get_query_list($con,"select email from users where id = '$userid'");
    $html .= '<h3>Employee Name: '.$empname.'</h3>';
    $html .= '<p>Email: '.$email.'</p>';
    
$html .= '<table width="100%" border="1">
<thead>
<tr><th>Date</th><th>Is Present</th><th>Is Late</th></tr>
</thead>
<tbody>';
$aDates = array();
$oStart = new DateTime($year.'-'.$month.'-01');
$oEnd = clone $oStart;
$oEnd->add(new DateInterval("P1M"));
$i=0;
while ($oStart->getTimestamp() < $oEnd->getTimestamp()) {
	if($oStart->format('D') == 'Sun'){
    $aDates[$i]['p'] = '<span style="color:red">'.$oStart->format('D d').' - '.$year.'</span>';
    $aDates[$i]['issun'] = 'yes';
}else{
	$aDates[$i]['p'] = $oStart->format('D d').' - '.$year;
	$aDates[$i]['issun'] = 'no';
	}
	//$aDates[$i]['dt'] = $oStart->format('Y-m-d');
    $aDates[$i]['s'] = $oStart->format('Y-m-d');
    $oStart->add(new DateInterval("P1D"));
    $i++;
}
foreach ($aDates as $day) {
    //echo $day;
    $sql = "SELECT * FROM `hr_attendance` where EmployeeID='".$emp."' and DATE_FORMAT(InTime, '%Y-%m-%d') = '".$day['s']."'";
    list($intime) = get_query_list($con,"SELECT InTime FROM `hr_attendance` where EmployeeID='".$emp."' and DATE_FORMAT(InTime, '%Y-%m-%d') = '".$day['s']."' order by RowID asc limit 0,1");
    list($outtime_actual) = get_query_list($con,"SELECT OutTime FROM `hr_attendance` where EmployeeID='".$emp."' and DATE_FORMAT(OutTime, '%Y-%m-%d') = '".$day['s']."' order by RowID desc limit 0,1");
    $res = get_all_array($con,$sql);
    $total=array();
    foreach($res as $r):
    $diff = date_diff(date_create ($r['InTime']), date_create ($r['OutTime']));
	$total[] = $diff->format ('%h:%i:%s'); 
    endforeach;
  //$total = array('1:00','2:20','4:00','5:10');
$islate = '<span style="font-weight:bold;color:green">No</span>';
$ptime = date("H:i:s",strtotime($intime));
if($day['issun'] == 'yes'){
	if($ptime > '11:30:00'){  /////late time for sunday
$islate = '<span style="font-weight:bold;color:red">Yes</span>';	
	}
	}else{
if($ptime > '10:10:00'){
$islate = '<span style="font-weight:bold;color:red">Yes</span>';	
	}
}
if($intime =='' && date('Y-m-d')>$day['s']){
	$islate = '<span style="font-weight:bold;color:dark red">Absent</span>';
	}
 $sum = strtotime('00:00:00');
 $sum2=0;  
 foreach ($total as $v){

        $sum1=strtotime($v)-$sum;

        $sum2 = $sum2+$sum1;
    }

    $sum3=$sum+$sum2;

    $presenttime =  date("H:i:s",$sum3);
    
$date_a = new DateTime($intime);
$date_b = new DateTime($outtime_actual);

$prentincludeintervalinterval = date_diff($date_a,$date_b);

$presentwithinterval = $prentincludeintervalinterval->format('%H:%I:%S');



$secs = date_diff(new DateTime($presentwithinterval),new DateTime($presenttime));
$intervaltime = $secs->format('%H:%I:%S');

    $ispresent= '<span style="font-weight:bold;color:red">No</span>';
    if($intime !=''){
		$ispresent = '<span style="font-weight:bold;color:green">Yes</span>';
		}
		if($presenttime == '00:00:00'){$presenttime = '';}
		if($intervaltime == '00:00:00'){$intervaltime = '';}
		if($presentwithinterval == '00:00:00'){$presentwithinterval = '';}
		
    $html.='<tr><td>'.$day['p'].'</td><td>'.$ispresent.'</td><td>'.$islate.'</td></tr>';
}


$html .='</tbody>
</table>';

//$html .='<a href="'.route("attendance.generate").'" class="btn btn-primary">Generate PDF</a>';


$html .= '</body>
</html>';
//echo $html;
;
	}else{
		$html = "<h2>You are not authorised to access this page</h2>";
	}

echo $html;