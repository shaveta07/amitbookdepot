@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('AR Ageing Bucket Report - Dunning SMS')}}</h3>
        </div><?php if(Auth::user()->user_type !="admin" ){ echo "<h3>Unauthorised Access !!</h3>"; die(); }  ?>
				 <p>Allowed Customer Categories: biology ( id-48), college faculties(id-51), institutes(id-17), mathematics(id-49), pc(id-50), supplier(id-39), shop(id-18).<br />
				  SMS Balance : <?php echo str_replace('"','',str_replace('.0000"}}','',$credit_check_array[5])); ?></p>
				 
        
        <!--Horizontal Form-->
        <!--===================================================-->
        <form  id="myForm1" class="form-horizontal" action="{{ route('report.open_ar_invoice_report_search')}}" method="Post" >
        	@csrf
            <div class="panel-body">
            <?php if(Auth::user()->user_type == "admin"){ ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Store Name:')}}</label>
                    <div class="col-sm-10">
                    <select name="store_id" class="store_id form-control" id="store_id" onChange="submit_form();">
						  <option value="">Select store</option>
			  			  <option <?php if($store_id == 1){echo 'selected';} ?> value="1">Amit Book Depot</option>
			  			  <option  <?php if($store_id == 2){echo 'selected';} ?> value="2">Tricity Ecoomerce</option>
			  	    </select>
                    </div>
                </div>
                <?php }else{ ?>
				<input type="hidden"  name="store_id" class="store_id" id="store_id" value=<?= Auth::user()->store_id ?> /> 
				  
				 <?php } ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('From:')}}</label>
                    <div class="col-sm-10">
                            <input type="date" id="fromdate" class="form-control" name="fromdate" value="<?= $fromdate ?>" />               
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('To:')}}</label>
                    <div class="col-sm-10">
                         <input type="date" id="todate" class="form-control" name="todate" value="<?= $todate ?>" />     
                    </div>
                </div>
              
               
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" name="submit" type="submit">{{__('search')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

<form method="post"><table><tr><td>Time</td><td><input type="text" name="time" /> (YYYY-MM-DD HH:MM AM/PM) <input type='submit' name='send' value='Send SMS'/></td></tr></table></br >
			<?php if(isset($msg)) echo $msg; ?>
			<table class="tableData">
			<tr><th>Sr No.</th><th>Invoice</th><th>Customer</th><th>Store</th><th>Amount</th><th>Invoice Date</th><th>Name</th><th>Mobile1</th><th>SMS</th></tr>
			<?php
			$due_amount=0;
			for($i=0;$i<count($data);$i++)
			{
			$count=$i;
			$count+=1;
			?>
			<tr><td><?php echo $count; ?></td>
			<td><a href="ARinvoice-lines-workbench.php?invoice_number=<?php echo $data[$i]['InvoiceNumber']; ?>" target="_blank"><?php echo $data[$i]['InvoiceNumber']; ?></a></td>
			<td><a href="edit-customer.php?customer-id=<?php echo $data[$i]['CustomerID']; ?>" target="_blank"><?php echo $data[$i]['CustomerID']; ?></a></td>
			<td><?php echo getUserStoreName($data[$i]['store_id']); ?></td>
			<td><?php if($data[$i]['Amount']=='') echo 0;else echo $data[$i]['Amount']; $due_amount+=$data[$i]['Amount']; ?></td><td><?php echo $data[$i]['InvoiceDate']; ?></td>
			<td><?php echo $data[$i]['Name']; ?></td>
			<td><input type='hidden' name='mobile[<?php echo $count;?>]' value='<?php echo $data[$i]['Mobile1']; ?>' /><?php echo $data[$i]['Mobile1']; ?></td>
			<td>
			<?php
			$tmp_body="Hi ".$data[$i]['Name'].", Please pay INR ".$data[$i]['Amount']." for invoice ".$data[$i]['InvoiceNumber']." Thanks Amit Book Depot"; echo $tmp_body;
			?><input type='hidden' name='smsbody[<?php echo $count; ?>]' value='<?php echo $tmp_body;  ?>' />
			</td>
			</tr>
			<?php 
			}
			?>
			</table><br />
			<b>Total Due Amount: <?php echo $due_amount; ?></b>


<!-- </div> -->
@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection