@extends('layouts.app')

@section('content')
<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Employee Detail</h1>
        </div>
 </div><?php echo $msg; ?>
 <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
	<thead>
            <tr>
				<th>Sr. No</th>
				<th>Employee Name</th>
				<th>All Leave Date</th>
				
			</tr>
    </thead>
     <tbody>       
<?php

foreach($rrr as $r):
echo "<tr>";
echo "<td>".++$inc.'</td>';
echo "<td>".$r['Name']."</td>";
echo "<td> <a class='btn btn-info'target='_blank' href='".route('attendance.LeaveDate', ['id' => $r['EmployeeID']])."'>view all leave</a> </td>";
echo "</tr>";
endforeach;
?>
</tbody>
</table>
@endsection

@section('script')
<script type="text/javascript">

</script>


@endsection