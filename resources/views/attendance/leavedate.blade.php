@extends('layouts.app')

@section('content')
<div class="row">
        <div class="col-lg-12">
        <?php
        $user =  DB::table('staff')
        ->join('users', 'staff.user_id', '=', 'users.id')
        ->where('user_id',$id)
        ->first();
          
        $name = $user->name;
        $userid =  $user->user_id;
       
					 ?>
                    <h1 class="page-header">Leave Detail of <?php echo $name; ?></h1>
        </div>
 </div>
 <div class="row" style="margin-bottom:20px;">
            <div class="col-sm-12">
                <fieldset class="scheduler-border">
			<legend class="scheduler-border">Search</legend>
			<div class="control-group">
					 <form method="get" class="form-inline" action="{{route('attendance.Search')}}">
					  
					  <div class="form-group">
						<label for="date1">Start Date:</label>
						<input type="date" value="<?php echo $start; ?>" name="start" class="form-control" id="start">
					  </div>
					  <div class="form-group">
						<label for="cheque">End Date:</label>
						<input type="date" value="<?php echo $end; ?>" name="end" class="form-control" id="end">
					  </div>
					  <input type="hidden" name="empid" value="<?php echo $id; ?>" />
					 
					  
					  <button type="submit" name="search" value="search" class="btn btn-default">Search</button>
					   <!-- <button type="submit" name="clear" value="clear" class="btn btn-default">Clear</button> -->
					</form> 
					</div>
					 </fieldset> 
                 </div>
                 <div style="clear:both;"></div>
            </div>
			<?php
			echo "</p>";
        foreach($rrr as $r):
        $days[] = $r['day'];
        endforeach;
        echo "<h2>Not Presented in Office include Sunday (".count($days).")</h2><p>";
        echo implode(", ",$days);

        $result=array_diff($days,$sunday);
        echo "<h2>Not Presented in Office excluding Sunday(".count($result).")</h2><p>";

        echo implode(", ",$result);
		echo "<hr />";
		echo "<h2>Total Approved Leaves Days(".count($leavesDate).")</h2>";

        echo implode(", ",$leavesDate);

        $withoutapproval=array_diff($result,$leavesDate);

        echo "<h2>Total Leave Without approval (".count($withoutapproval).")</h2>";
        echo implode(', ',$withoutapproval);

        $presentleave=array_diff($leavesDate,$days);
        echo "<h2>Present After Approved Leave (".count($presentleave).")</h2>";
        echo implode(', ',$presentleave);
            ?>
 @endsection

@section('script')
<script type="text/javascript">
// $('#start').datepicker({ dateFormat: 'yy-mm-dd',maxDate: '0' });
// $('#end').datepicker({ dateFormat: 'yy-mm-dd',maxDate: '0' });
</script>


@endsection