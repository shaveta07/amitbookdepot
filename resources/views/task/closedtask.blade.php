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
        <div class="col-lg-6">
            <h1 class="page-header"> Closed Task List</h1>
        </div>
        <div class="col-sm-6">
                    <a style="float:right; margin-top: 44px" href="{{route('Task.create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
Add Task</a>
                    </div>
 </div> <?php if(isset($sts) && $sts == 'success'){ ?>
						<div class="alert alert-success">
  <strong>Success!</strong> Task Added Successfully.
</div>
<?php } ?>
 <!-- /.col-lg-12 -->
                
 <div class="row" style="margin-bottom:20px;">
            <div class="col-sm-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Search</legend>
                    <div class="control-group">
                        <form method="get" class="form-inline" action="{{route('Task.closesearch')}}">
                        <div class="form-group">
                            <label for="startdate">Start Date:</label>
                            <input style="line-height:20px;" type="date" name="startdate" value="<?php if(isset($startdate)){echo $startdate; } ?>" class="form-control" id="startdate">
                        </div>
                        <div class="form-group">
                            <label for="enddate">End Date:</label>
                            <input style="line-height:20px;" type="date" name="enddate" value="<?php if(isset($enddate)){echo $enddate; } ?>" class="form-control" id="enddate">
                        </div>
                        <div class="form-group">
                            <label for="enddate">Users:</label>
                            <select name="users" class="form-control" id="users">
							<option value="">Select Users</option>
							<?php
                            $rrra = DB::table('users')->select('id','email')->where('email','!=','')->get();
                        
							if(Auth::user()->user_type != 'admin'){
                                $rrra = DB::table('users')->select('id','email')->where('email','!=','')->where('store_id',Auth()->user()->store_id)->get();
								
							}
							foreach($rrra as $r):
							?>
							<option value="<?php echo $r->id; ?>"><?php echo $r->email; ?></option>
							<?php endforeach; ?>
						</select>
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
<th>S.No.</th><th>Task</th><th>Status</th><th>Assigned By</th><th>Assigned To</th><th>Created Date</th><th>Start Date</th><th>Due Date</th><th>Days Left</th><th>Store</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php 
//	$rrr=array();
	$i=0;
	//print_r($rrr);
	foreach($rrr as $r):
	?>
	<tr>
		<td><?php echo ++$i; ?></td>
	<td><?php echo $r->title; ?></td>
	<td><?php echo $r->status; ?></td>
    <td><?php 
    $assign = DB::table('taskissues')->select('assignfrom','assignto')->where('taskid',$r->id)->orderBy('id','desc') ->skip(0)->take(1)->first();
    $assignfrom = $assign->assignfrom;
    $assignto = $assign->assignto;
    $assignfromEmail = DB::table('users')->select('email')->where('id',$assignfrom)->first();
	 echo $assignfromEmail->email; ?></td>
    <td><?php 
    $assigntoEmail = DB::table('users')->select('email')->where('id',$assignto)->first();
	echo $assigntoEmail->email; ?></td>
	<td><?php echo $r->createddate; ?></td>
	<td><?php echo $r->startdate; ?></td>
	
	<td><?php 
	if($r->isrecursive == 'no'){
	echo $r->duedate; 
}
	?></td>
	<td>
	<?php
	if($r->status == 'open' && $r->isrecursive == 'no'){
					$date1=date_create(date('Y-m-d'));
					$date2=date_create($r->duedate);
					$diff=date_diff($date1,$date2);
					if($diff->format("%R%a") >= 0){
					echo " &nbsp;&nbsp;".$diff->format("%R%a days left");
					}else{
                        echo " &nbsp;&nbsp;<span style='color:red'>".$diff->format("%R%a days (Task overdue)")."</span>";
						}
		}else{
			
		echo $r->status;
		
		}
	?>
	</td>
	<td><?php echo 'Amit Book Depot ' ?></td>
	
	<td>
	<a href="{{ route('Task.taskdetail',$r->id)}}" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i>
 View</a>
	
	<?php 
	
	$iseditable = DB::select(DB::raw("select count(id) from tasks where id IN (select id from taskissues) && id = '.$r->id.'"));
    // $iseditable = DB::table('tasks')
    // ->whereIn('id', DB::raw('select id from taskissues'))
    // ->where('id',$r->id)
    // ->count('id');

	//echo $iseditable;
	if($r->createdby ==  Auth::user()->id || Auth::user()->user_type == 'admin'): ?>
	<a href="{{route('Task.edit',$r->id)}}" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
	Edit</a>
	<a href="{{route('Task.delete',$r->id)}}" class="btn btn-primary"><i class="fa fa-trash-o" aria-hidden="true"></i>
	Delete</a>
	<?php endif; ?>
	</td>
	</tr>
	<?php
	endforeach;
	?>
	</tbody>
    <tfoot>
	<th>S.No.</th><th>Task</th><th>Status</th><th>Assigned By</th><th>Assigned To</th><th>Created Date</th><th>Start Date</th><th>Due Date</th><th>Days Left</th><th>Action</th>
    </tfoot>
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