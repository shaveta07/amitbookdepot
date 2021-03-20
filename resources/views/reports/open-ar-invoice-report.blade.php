@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Open AR Invoice Report')}}</h3>
        </div><?php echo $msg; ?> 
        
        <!--Horizontal Form-->
        <!--===================================================-->
        <form  id="myForm1" class="form-horizontal" action="{{ route('report.open_ar_invoice_report_search')}}" method="GET" >
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
                <?php } ?>
               
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" name="submit" type="submit">{{__('search')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>


<table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
<thead>
<tr><th>Sr No.</th><th>Invoice</th><th>Type</th><th>Customer</th><th>Customer Name</th><th>Amount</th><th>Store</th><th>Invoice Date</th></tr>
</thead>
<tbody>
<?php
			if(Auth::user()->user_type != 'admin'){
				$store_id = Auth::user()->store_id;
			}
            
			$due_amount=0;$i=0;
			foreach($rrr as $data)
			{
			$count=$i++;
            $customer = DB::table('users')->where('id',$data['CustomerID'])->first();
            // echo $customer->name;
            // die();
          
            //$cust = json_decode($customer);
       //     echo $customer['name'];
           //print_r($customer);
           // $customerdal->getCustomerMobileFromCustomerID($data['CustomerID']);
			if($data['preorderid']!='0' && $data['preorderid']!=''){
				?>
				<tr><td><?php echo ++$count; ?></td><td><a href="ARinvoice-lines-workbench_prebooking.php?invoice_number=<?php echo $data['InvoiceNumber']; ?>" target="_blank"><?php echo $data['InvoiceNumber']; ?></a></td><td><?php echo $data['InvoiceLookupType']; ?></td><td><a href="edit-customer.php?customer-id=<?php echo $data['CustomerID']; ?>" target="_blank"><?php echo $data['CustomerID']; ?></a></td><td><?php echo $customer->name; ?></td><td><?php if($data['Amount']=='') echo 0;else echo $data['Amount']; $due_amount+=$data['Amount']; ?></td><td><?php echo $data['InvoiceDate']; ?></td></tr>
				<?php
				}else{
                    // echo $customer->name;
                    // die();
			?>
			<tr>
                <td>
                    <?php echo ++$count; ?>
                </td>
                <td>
                    <a href="{{url('admin/ARinvoice_header_workbench/view')}}/{{ $data['orderid'] }}/{{ $data['ordersource'].$data['InvoiceNumber'] }}" target="_blank">
                        <?php echo $data['InvoiceNumber']; ?>
                    </a>
                </td>
                <td>
                    <?php echo $data['InvoiceLookupType']; ?>
                </td>
                <td>
                    <a href="edit-customer.php?customer-id=<?php echo $data['CustomerID']; ?>" target="_blank">
                        <?php echo $data['CustomerID']; ?>
                    </a>
                </td>
                <td>
                    @if($customer)
                        {{ $customer->name }}
                    @endif
                </td>
                <td>
                    <?php if($data['Amount']=='') echo 0;else echo $data['Amount']; $due_amount+=$data['Amount']; ?>
                </td>
                <td>
                    <?php echo $data['store_id']; ?>
                </td>
                <td>
                    <?php echo $data['InvoiceDate']; ?>
                </td>
            </tr>
			<?php 
		}
			}
			?>
</tbody>
</table>
<br />
<b>Total Due Amount: <?php echo $due_amount; ?></b>
<!-- </div> -->
@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection