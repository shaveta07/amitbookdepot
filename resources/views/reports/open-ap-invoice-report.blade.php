@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Open AP Invoice Report')}}</h3>
        </div><?php echo $msg; ?> 
        <?php if(Auth::user()->user_type != "admin" ){ echo "<h3>Unauthorised Access !!</h3>"; die(); }  ?>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form  id="myForm1" class="form-horizontal" action="{{ route('report.open_ap_invoice_report_search')}}" method="GET" >
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
                <?php } ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Supplier Name:')}}</label>
                    <div class="col-sm-10">
                    <select name="s_id" class="form-control" onChange="submit_form();" >
                    <option value="">Please choose</option>
                    <?php
                    for($i=0;$i<count($supplier_data);$i++)
                    {
                    ?>
                    <option <?php if($s_id == $supplier_data[$i]['SupplierID']) {echo "selected"; }?> value="<?php echo $supplier_data[$i]['SupplierID']; ?>"><?php echo $supplier_data[$i]['Name']; ?></option>
                    <?php }  ?>
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
<tr><th>Sr No.</th><th>Invoice</th><th>Supplier Name</th><th>Status</th><th>Supplier</th><th>Amount</th><th>Invoice Date</th><th>Store</th></tr>
</thead>
<tbody>
<?php
			$liability=0;
			for($i=0;$i<count($data);$i++)
			{
			$count=$i;
			if(strtoupper(substr($data[$i]['InvoiceNumber'], 0, 2)) == 'RR' || strtoupper(substr($data[$i]['InvoiceNumber'], 0, 3)) == 'SAL' || strtoupper(substr($data[$i]['InvoiceNumber'], 0, 3)) == 'OLD'){
               // $supplierName = DB::table('ap_suppliers')->where('supplierid',$data[$i]['SupplierID'])->first();
               
			?>
			<tr><td><?php echo ++$count; ?></td><td><a href="{{url('admin/APinvoice_header_workbench_old/view')}}/<?php echo $data[$i]['InvoiceID']; ?>/<?php echo $data[$i]['SupplierID']; ?>" target="_blank"><?php echo $data[$i]['InvoiceNumber']; ?></a></td><td></td><td><?php if($data[$i]['Status'] == 'O'){echo "Open"; }elseif($data[$i]['Status'] == 'P'){echo "Paid"; }elseif($data[$i]['Status'] == 'C'){echo "Cancel"; } ?></td><td><a href="#" target="_blank"><?php echo $data[$i]['SupplierID']; ?></a></td><td><?php echo $data[$i]['Total']; $liability+=$data[$i]['Total']; ?></td><td><?php echo $data[$i]['Date']; ?></td><td><?php echo $data[$i]['store_id']; ?></td></tr>
			<?php 
			}else if(strtoupper(substr($data[$i]['InvoiceNumber'] , 0, 3)) == 'LUM'){ 
				//$supplierName = DB::table('ap_suppliers')->where('supplierid',$data[$i]['SupplierID'])->first();
				?>
			<tr><td><?php echo ++$count; ?></td><td><a href="{{url('admin/APinvoice_header_workbench_old2/view')}}/<?php echo $data[$i]['InvoiceID']; ?>/<?php echo $data[$i]['SupplierID']; ?>" target="_blank"><?php echo $data[$i]['InvoiceNumber']; ?></a></td><td></td><td><?php if($data[$i]['Status'] == 'O'){echo "Open"; }elseif($data[$i]['Status'] == 'P'){echo "Paid"; }elseif($data[$i]['Status'] == 'C'){echo "Cancel"; } ?></td><td><a href="#" target="_blank"><?php echo $data[$i]['SupplierID']; ?></a></td><td><?php echo $data[$i]['Total']; $liability+=$data[$i]['Total']; ?></td><td><?php echo $data[$i]['Date']; ?></td><td><?php echo $data[$i]['store_id']; ?></td></tr>	
				<?php }else{
				//$supplierName = DB::table('ap_suppliers')->where('supplierid',$data[$i]['SupplierID'])->first();
				
			?>	
			<tr><td><?php echo ++$count; ?></td><td><a href="{{url('admin/APinvoice_header_workbench/view')}}/<?php echo $data[$i]['InvoiceID']; ?>&supplier_id=<?php echo $data[$i]['SupplierID']; ?>" target="_blank"><?php echo $data[$i]['InvoiceNumber']; ?></a></td><td></td><td><?php if($data[$i]['Status'] == 'O'){echo "Open"; }elseif($data[$i]['Status'] == 'P'){echo "Paid"; }elseif($data[$i]['Status'] == 'C'){echo "Cancel"; } ?></td><td><a href="#" target="_blank"><?php echo $data[$i]['SupplierID']; ?></a></td><td><?php echo $data[$i]['Total']; $liability+=$data[$i]['Total']; ?></td><td><?php echo $data[$i]['Date']; ?></td><td><?php echo $data[$i]['store_id']; ?></td></tr>
			<?php
			}
			}
			?>
</tbody>
</table>
<br />
			<b>Total Liability: <?php echo $liability; ?></b>
            <br />
            <br />
            <br />
            <div class="clearfix"></div>
            <div class="clearfix"></div>
<!-- </div> -->
@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection