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
<div class="row" style="margin-bottom:20px;" >
        <div class="col-lg-12">
            <h1 class="page-header">Lunch Report Employee wise</h1>
        </div>
 </div><?php echo $msg; ?>    
            <div style="clear:both"></div>
          <div style="clear:both"></div>
            <div class="row" style="margin-bottom:20px;">
            <div class="col-sm-12">
                <fieldset class="scheduler-border">
						<legend class="scheduler-border">Search</legend>
						<div class="control-group">
								<form method="get" class="form-inline" action="{{route('attendance.lunchreportSubmit')}}">
								<div class="form-group">
									<label for="startdate">Start Date:</label>
									<input style="line-height:20px;" type="date" name="startdate" value="<?php echo $startdate; ?>" class="form-control" id="startdate">
								</div>
								<div class="form-group">
									<label for="enddate">End Date:</label>
									<input style="line-height:20px;" type="date" name="enddate" value="<?php echo $enddate; ?>" class="form-control" id="enddate">
								</div>
								
								<div class="form-group">
									<label for="enddate">Users:</label>
									<select name="users" class="form-control" id="users">
										<?php
										$rrr = \App\User::where('email','!=','')->where('user_type','admin')->orwhere('user_type','staff')->get();
										
										
										foreach($rrr as $r):
										?>
										<option <?php if(isset($users) && $users == $r->id){ echo "selected"; } ?> value="<?php echo $r->id; ?>"><?php echo $r->email; ?></option>
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
 <div class="taskList col-sm-12">

	 

	 <div style="clear:both;"></div>
	 <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
	<thead><tr><th>Sr. No.</th> <th>Punch Out</th><th>punch IN</th><th>interval time</th></tr>
	</thead>
	<tfoot>
	<tr><th>Sr. No.</th> <th>Punch Out</th><th>Punch In</th><th>interval time</th></tr>
	</tfoot>
	
<tbody>
	<?php

	
	$i=0;
	echo "<h3> Records of: ".$email."</h3>";
	foreach($emp_datas as $data):
	$i++;
	//print_r($data);
	?>
<tr>
<td><?php echo $i; ?></td>
<td><?php echo $data['OutTime']; ?></td>
<td><?php echo $data['InTime']; ?></td>
<?php
$hrs = floor(round($data['time']/3600,2));
$mins = round((round($data['time']/3600,2) - $hrs) * 60);
?>
<td><?php echo "$hrs Hours and $mins Minutes"; ?></td>

</tr>	
<?php 
endforeach;
?>
</tbody>
</table>

</div>
<div style="clear:both"></div>

<!--------------------------------------------------------------------------------------------------------------->

				<div style="clear:both"></div>
	
	

 @endsection

@section('script')
<script type="text/javascript">

</script>


@endsection