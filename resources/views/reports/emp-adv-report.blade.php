@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Employee Advance Salary Report')}}</h3>
        </div>
        <?php if(Auth::user()->user_type != "admin" ){ echo "<h3>Unauthorised Access !!</h3>"; die(); }  ?>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form  id="myForm1" class="form-horizontal" action="{{ route('report.emp_adv_report_search')}}" method="POST" >
        	@csrf
            <div class="panel-body">
           
                <div class="form-group">
                    <!-- <label class="col-sm-2 control-label" for="name">{{__('Store Name:')}}</label> -->
                    <div class="col-sm-10">
                    <select name="s_id" required="required" onChange="submit_form();" >
                        <option value="">Please choose</option>
                        <?php
                        for($i=0;$i<count($data_supplierid);$i++)
                        {
                        ?>
                        <option value="<?php echo $data_supplierid[$i]['SupplierID']; ?>"><?php echo $data_supplierid[$i]['Name']; ?></option>
                        <?php }  ?>
                    </select>
                    <button type="submit" class="btn btn-primary"  name="submit">Submit</button>
                    </div>
                </div>
              
				
            </div>
           
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->
        <div style="clear:both;"></div>
 <div class="taskList col-sm-12">
 <table class="table table-responsive table-striped js-dataTable-full table-inverse table-bordered table-hover">
	<thead>
	<tr><th>Invoice</th><th>Date</th><th>Advance</th><th>Amount</th></tr>
	</thead>
	<!-- <tfoot>
	<tr><th>Invoice</th><th>Date</th><th>Advance</th><th>Amount</th></tr>
	</tfoot> -->
    <tbody>
    <?php
    if($s_id)
    {
			$total=0;
			for($i=0;$i<count($data);$i++)
			{
		//	$count=$i;
			?>
			<tr>
			<td><?php echo $data[$i]['InvoiceNumber']; ?></td>
			<td><?php echo $data[$i]['Date']; ?></td>
			<td><?php $type=$data[$i]['Advance']; echo $type; ?></td>
			<td><?php $line_amt=$data[$i]['Cp']; echo $line_amt; ?></td>
			</tr>
			<?php
			$total+=$line_amt;
            }
        }
			?>
    </tbody>
</table>
<br />
			<b>Total : <?php echo $total; ?></b>
</div>
</div>
</div>
@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection