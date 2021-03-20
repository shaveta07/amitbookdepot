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
            <h1 class="page-header">Waring Message</h1>
        </div>
        <div class="col-sm-6">
                    <a style="float:right; margin-top: 44px" href="{{ route('Task.wrnmsgcreate')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
Create Message</a>
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
                        <form method="get" class="form-inline" action="{{route('Task.searchwrnmsg')}}">
                        <div class="form-group">
                            <label for="startdate">Start Date:</label>
                            <input style="line-height:20px;" type="date" name="startdate" value="<?php if(isset($startdate)){echo $startdate; } ?>" class="form-control" id="startdate">
                        </div>
                        <div class="form-group">
                            <label for="enddate">End Date:</label>
                            <input style="line-height:20px;" type="date" name="enddate" value="<?php if(isset($enddate)){echo $enddate; } ?>" class="form-control" id="enddate">
                        </div>
                        <div class="form-group">
                            <label for="enddate">Search Key:</label>
                            <input type="text" value="<?php echo $searchkey; ?>" name="searchkey" class="form-control" id="searchkey" />
                           
                        </div> 
                        <div class="form-group">
                            <label for="enddate">Status:</label>
                            <select name="status" class="form-control" id="status">
							<option value=""></option>
							<option value="publish" <?php if(($status == 'publish') || !isset($status)){echo "selected"; } ?>>Publish</option>
							<option value="unpublish" <?php if($status == 'unpublish'){echo "selected"; } ?>>unpublish</option>
						</select>
					  </div>
                           
                      
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="enddate">Users:</label>
                            <select name="users" class="form-control" id="users">
							<option value="">Select Users</option>
							<?php
                            $rrr = DB::table('users')->select('id','email')->where('email','!=','')->get();
                        
							if(Auth::user()->user_type != 'admin'){
                                $rrr = DB::table('users')->select('id','email')->where('email','!=','')->where('store_id',Auth()->user()->store_id)->get();
								
							}
							foreach($rrr as $r):
							?>
							<option value="<?php echo $r->id; ?>"><?php echo $r->email; ?></option>
							<?php endforeach; ?>
						</select>
                        </div>
                        
                        <button type="submit" style="margin-top: 13px;" name="search" value="search" class="btn btn-primary">Search</button>
                        <button type="submit" style="margin-top: 13px;" name="clear" value="clear" class="btn btn-danger">Clear</button>
                        </form> 
                    </div>
				</fieldset> 
                 </div>
            </div>
           
<table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
	<thead>
<tr>
<th>Id</th><th>Warning Msg</th><th>Assigned to</th><th>Status</th><th>Created Date</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php 
    //$rrr=array();
   // print_r($arr); die();
    $i=0;
	foreach($arr as $r):
	?>
	<tr>
	<td><?php echo $r->id; ?></td>
	<td><?php echo $r->Warning_msg; ?></td>
    <td><?php 
    $user = DB::table('users')->where('id',$r->assignto)->get();
   // print_r($user);
   foreach($user as $u)
   {
    echo  $u->email;
   }
  
    ?>
    </td>
    <td><?php echo $r->status; ?></td>
	
	<td><?php echo $r->createddate; ?></td>
	
	<td>
    <a href="{{ route('Task.warningmessageupdate',$r->id)}}" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
 Edit</a>
	</td>
	</tr>
	<?php
	endforeach;
	?>
	</tbody>
    <tfoot>
	<th>Id</th><th>Warning Msg</th><th>Assigned to</th><th>Status</th><th>Created Date</th><th>Action</th>
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