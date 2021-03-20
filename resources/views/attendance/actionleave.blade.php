@extends('layouts.app')

@section('content')
<style>
input,select,select2 select2-container{border: 1px solid #999 !important;
border-radius: 0 !important; height:30px !important}
.form-group {
	line-height: 5px !important;
	padding-bottom: 0px;
	margin-bottom: 5px;
}
textarea{border: 1px solid #999 !important;
border-radius: 0 !important; height:50px !important}
.select2-container--default .select2-selection--single{border: 1px solid #999 !important;
border-radius: 0 !important; height:30px !important}
#book-list{float:left;list-style:none;margin:0;padding:0;width:650px;}
#book-list li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
#book-list li:hover{background:#FFFF00;}
.disable-select {
display:none;
}
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    width:inherit; /* Or auto */
    padding:0 10px; /* To give a bit of padding on the left and right */
    border-bottom:none;
}
</style>
<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">View Action Leave</h1><?php if(Auth::user()->user_type !="admin" )die("Unauthorised Access !!");?>
        </div>
 </div><?php echo $msg; ?>  
 <!-- /.col-lg-12 -->
                
 <div class="row" style="margin-bottom:20px;">
            <div class="col-sm-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Search</legend>
                    <div class="control-group">
                        <form method="get" class="form-inline" action="{{route('attendance.SearchActionLeave')}}">
                        <div class="form-group">
                            <label for="startdate">Start Date:</label>
                            <input style="line-height:20px;" type="date" name="startdate" value="<?php if(isset($startdate)){echo $startdate; } ?>" class="form-control" id="startdate">
                        </div>
                        <div class="form-group">
                            <label for="enddate">End Date:</label>
                            <input style="line-height:20px;" type="date" name="enddate" value="<?php if(isset($enddate)){echo $enddate; } ?>" class="form-control" id="enddate">
                        </div>
                        
                        <button type="submit" name="search" value="search" class="btn btn-primary">Search</button>
                        <button type="submit" name="clear" value="clear" class="btn btn-danger">Clear</button>
                        </form> 
                    </div>
				</fieldset> 
                 </div>
            </div>
           
<table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
	<thead>
<tr>
<th>Employee</th>
<th>Leave From Date</th><th>Leave To Date</th>
<th>Creation Date</th><th>Description</th>
<th>Leave Type</th>
<th>File</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php for($i=0;$i<count($data);$i++)
	{
		
	?>
	<tr>
	<td><?php echo $data[$i]['Name']; ?></td>
	<td><?php echo $data[$i]['Date']; ?></td><td><?php echo $data[$i]['ToDate']; ?></td>
	<td><?php echo $data[$i]['CreationDate']; ?></td><td><?php echo $data[$i]['Description']; ?></td>
	<td><?php if($data[$i]['leavetype'] == 'SL'){echo "Sick Leave"; }elseif($data[$i]['leavetype'] == 'EL'){echo "Emergency Leave"; }elseif($data[$i]['leavetype'] == 'CL'){echo "Casual Leave"; }elseif($data[$i]['leavetype'] == 'PL'){echo "Paternity Leave"; }elseif($data[$i]['leavetype'] == 'ML'){echo "Maternity Leave"; } ?></td>
	<td>
    <?php
    if($data[$i]['document'] != null && json_decode($data[$i]['document']) != "[]")
{
	// echo sizeof(json_decode($data_leave[$i]['Document']));
	foreach(json_decode($data[$i]['document']) as $key => $doc)
	{	
?>
		<a target="_blank" href="{{ asset($doc) }}">File {{ $key + 1 }}</a> &nbsp;
<?php
	}
}
?>
    </td>
	
	<td><a class="btn btn-info"  href="{{route('attendance.takeactionleave', $data[$i]['RowID'])}}" ><?php if($data[$i]['Status']=='P') echo "Approval Pending"; else if($data[$i]['Status']=='A') echo "Approved"; else echo "Rejected"; ?></a>
	<a href="javascript:void(0)" class="btn btn-danger" onclick="deleteLeave('<?php echo $data[$i]['RowID']; ?>')" >Delete Leave</a>
	</td>
	</tr>
<?php } ?>
</tbody>
</table>
@endsection

@section('script')
<script type="text/javascript">
function deleteLeave(id){
	if(confirm('Are you sure to delete this record !')){
		location.href="{{url('/admin/attendance/delete')}}/"+id;
		}else{
		return false;	
		}
    }
//     $('#startdate').datepicker({ dateFormat: 'yy-mm-dd' });
// $('#enddate').datepicker({ dateFormat: 'yy-mm-dd' });
//$('#example').DataTable();
</script>


@endsection