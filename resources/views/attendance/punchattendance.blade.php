@extends('layouts.app')

@section('content')
<!-- <div id="page-wrapper"> -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Punch Attendance</h1>
                    <?php
                    if(isset($_GET['sts']) && $_GET['sts'] == 'unauth'){
						?>
						<div  class="alert alert-danger">
					  <strong>Error!</strong> You are unauthorise to Punch. Please update all your open task.
					</div>
						
					<?php	} ?>
                   
                </div>
                <!-- /.col-lg-12 -->
            </div><?php echo $msg; ?>
       <?php //print_r($_SESSION);  ?>
Name: <?php echo $data[0]['Name']; ?><br />
Employee ID:  <?php echo $data[0]['EmployeeID']; ?><br />
Email:  <?php echo $data[0]['Email']; ?><br />
Last Activity today: <?php 
if(!isset($emp_last_punch[0]['OutTime']) and !isset($emp_last_punch[0]['InTime'])) $print_last="None"; 
else if($emp_last_punch[0]['InTime']!='' and $emp_last_punch[0]['OutTime']=="") $print_last="In"; 
else if($emp_last_punch[0]['InTime']!='' and $emp_last_punch[0]['OutTime']!="") $print_last="Out"; 
echo $print_last;

//print_r($emp_last_punch);
if(count($result) > 0){
   
?>
<br /><br /><br /><br /><br />
<form style="margin-left:10px;margin-right:10px;float:left" id="punchin" method="post" action="{{route('attendance.updateStatus')}}">
@csrf
<input type="submit" class="btn btn-success" <?php if(count($emp_last_punch) && $emp_last_punch[0]['OutTime']=="" and $emp_last_punch[0]['RowID']!="") {echo "disabled"; }else{ echo ""; } ?>  name="punch" value="Punch IN" />
<input name="emp_row_id" type="hidden" value="{{$emp_last_punch[0]['RowID']}}">
<input name="out_time" type="hidden" value="{{ $emp_last_punch[0]['OutTime'] }}">
<input name="emp_id" type="hidden" value="{{ $data[0]['EmployeeID'] }}">
</form>
<?php

$rrr = \App\ArInvoiceLinesF::where('status','=','')->orWhereNull('status')->where('IsDeleted','N')->where('itemid','!=','0')->groupBy('invoiceid')->get();
//return json_encode($rrr);
$num = count($rrr);
if(count($rrr)){
	echo "<p style='color:red'>You can not punch-out. Please Complete following invoice form.<br/>";
	foreach($rrr as $r){
        $invoicenumber = \App\ArInvoicesAllF::where('invoiceid', $r->invoiceid)->first();
       
		echo "<a target='_blank' href='".url('admin/ARinvoice_header_workbench_f/view')."/".$invoicenumber->invoiceid."/".$invoicenumber->invoicenumber."' >".$invoicenumber->invoicenumber."</a>, ";
		}
		echo "</p>";
}else{
?>
		<form style="margin-left:10px;margin-right:10px;float:left" id="punchout" method="post" action="{{route('attendance.updateStatus')}}">
		@csrf
        <input type="submit" class="btn btn-info" <?php if(count($emp_last_punch)  and $emp_last_punch[0]['RowID']!="") {echo ""; }else{ echo "disabled"; } ?> name="punchout" value="Punch OUT" />
		<input name="emp_row_id" type="hidden" value="{{$emp_last_punch[0]['RowID']}}">
        <input name="out_time" type="hidden" value="{{ $emp_last_punch[0]['OutTime'] }}">
        <input name="emp_id" type="hidden" value="{{ $data[0]['EmployeeID'] }}">
        
        </form>
<?php } 
 ///// end condition of punchout
}
?>

<?php
$today = date('Y-m-d');
$cnt = \App\Models\HrAttendance::where('CreateDate',$today)->count('RowID');
//list($cnt) = get_query_list($con,"select count(RowID) from Hr_Attendance where CreateDate = '".$today."'");
//echo date('H:i');
if($cnt > 0){
    $stsin = \App\Models\HrAttendance::where('CreateDate',$today)->where('status','lunchin')->where('EmployeeID',$data[0]['EmployeeID'])->count('RowID');
     $stsout = \App\Models\HrAttendance::where('CreateDate',$today)->where('status','lunchout')->where('EmployeeID',$data[0]['EmployeeID'])->count('RowID');
    $isin = \App\Models\HrAttendance::where(DB::raw("(date(InTime))"), date('Y-m-d'))->whereNull('OutTime')->where('EmployeeID',$data[0]['EmployeeID'])->count('RowID');

    $result = DB::table('hr_attendance')->select('InTime')
->where(DB::raw("(date(InTime))"), date('Y-m-d'))
->get();
//print_r($_SESSION);
if(count($result) > 0 ){
?>
<form method="post" action="{{route('attendance.updateStatus')}}" >
@csrf
	<input style="margin-right: 15px;" <?php if( $stsout > 0 || $isin == 0){ echo "disabled"; } ?> type="submit" name="lunchout" value="Lunch Punch Out" class="btn btn-danger col-sm-2" />
<input name="emp_row_id" type="hidden" value="{{$emp_last_punch[0]['RowID']}}">
<input name="emp_id" type="hidden" value="{{ $data[0]['EmployeeID'] }}">
</form>

<form action="{{route('attendance.updateStatus')}}" method="post">
@csrf
	<input <?php if( $stsin > 0 || $isin > 0){ echo "disabled"; } ?> class="btn btn-primary col-sm-2" type="submit" name="lunchin" value="Lunch Punch In" />
    <input name="emp_id" type="hidden" value="{{ $data[0]['EmployeeID'] }}">
</form>

<?php 
}
} ?>


        <!-- </div> -->
        <!-- /#page-wrapper -->


@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection