@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('GST Reports')}}</h3>
        </div><?php echo $msg; ?> 
        <?php if(Auth::user()->user_type != "admin" ){ echo "<h3>Unauthorised Access !!</h3>"; die(); }  ?>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form  id="myForm1" class="form-horizontal" action="{{ route('report.gstreportsearch')}}" method="GET" >
        	@csrf
            <div class="panel-body">
           
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
                    <label class="col-sm-2 control-label">{{__('Start Date:')}}</label>
                    <div class="col-sm-10">
                            <input type="date" id="startdate" class="form-control" name="startdate" value="<?= $startdate ?>" />               
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('End Date:')}}</label>
                    <div class="col-sm-10">
                         <input type="date" id="enddate" class="form-control" name="enddate" value="<?= $enddate ?>" />     
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
<div style="clear:both;"></div>
 <div class="taskList col-sm-12">
	 
	 <div style="border-top:1px solid #ccc; margin-top:20px;" class="col-sm-12 col-lg-12 col-xs-12">
<div class="col-sm-6 col-lg-6 col-xs-6">
<h3> AR Invoice GST Report Till Date </h3>
<?php
$tax_data = DB::table('order_details as l')
//->select('l.quantity','l.cgst','l.sgst','l.gstamount','l.igst')
//->select(DB::raw('sum(l.quantity*l.sgst) as sgst,sum(l.quantity*l.cgst)as cgst,sum(l.quantity*l.gstamount) as gstamount,sum(l.quantity*l.igst) as igst'))
->join('orders as a','a.id','=','l.order_id')
->where('a.store_id',$store_id)
->where('l.IsDeleted','N')
->get();

$cgst = 0;
$sgst = 0;
$igst = 0;
$gst = 0;

foreach($tax_data as $d)
{
    $cgst += $d->quantity * $d->cgst;
    $sgst += $d->quantity * $d->sgst;
    $igst += $d->quantity * $d->igst;
    $gst  += $d->quantity * $d->gstamount;
}
//print_r($tax_data_ar);     die();
//echo $sgst; die();
//list($sgst,$cgst,$gst,$igst) = get_query_list($con,"select sum(l.Quantity*l.sgst),sum(l.Quantity*l.cgst),sum(l.Quantity*l.gst),sum(l.Quantity*l.igst) FROM `AR_Invoice_Lines` l INNER JOIN AR_Invoices_All a ON a.InvoiceID=l.InvoiceID where l.IsDeleted='N' and a.store_id='".$store_id."'");
?>
<table>
<tr><td><label>SGST: </label></td><td><?php echo round($sgst,2); ?></td></tr>
<tr><td><label>CGST: </label></td><td><?php echo round($cgst,2); ?></td></tr>
<tr><td><label>GST: </label></td><td><?php echo round($gst,2); ?></td></tr>
<tr><td><label>IGST: </label></td><td><?php echo round($igst,2); ?></td></tr>
</table>
</div>
<div class="col-sm-6 col-lg-6 col-xs-6">
<h3> AP Invoice GST Report Till Date. </h3>
<?php
$ap_tax_data = DB::table('ap_invoices_alls as l')
//->select(DB::raw('sum(l.sgst),sum(l.cgst),sum(l.gst),sum(l.igst)'))
->where('l.status','P')
->where('l.store_id',$store_id)
->get();
$cgst = 0;
$sgst = 0;
$igst = 0;
$gst = 0;

foreach($ap_tax_data as $d)
{
    $cgst += $d->cgst;
    $sgst += $d->sgst;
    $igst += $d->igst;
    $gst  += $d->gst;
}
//list($sgst,$cgst,$gst,$igst) = get_query_list($con,"select sum(l.sgst),sum(l.cgst),sum(l.gst),sum(l.igst) FROM `AP_Invoices_All` l where l.Status='P' and l.store_id = '$store_id'");
?>
<table>
<tr><td><label>SGST: </label></td><td><?php echo round($sgst,2); ?></td></tr>
<tr><td><label>CGST: </label></td><td><?php echo round($cgst,2); ?></td></tr>
<tr><td><label>GST: </label></td><td><?php echo round($gst,2); ?></td></tr>
<tr><td><label>IGST: </label></td><td><?php echo round($igst,2); ?></td></tr>
</table>
</div>
</div>
<div style="clear:both"></div>
<?php if(isset($search)): 

?>	
<div style="border-top:1px solid #ccc; margin-top:20px;" class="col-sm-12 col-lg-12 col-xs-12">
<div class="col-sm-6 col-lg-6 col-xs-6">
<h3> AR Invoice GST Report b/w <?php echo $startdate; ?> and <?php echo $enddate; ?></h3>
<?php
 $tax_data = DB::table('order_details as l')
 //->select('l.quantity','l.cgst','l.sgst','l.gstamount','l.igst')
 //->select(DB::raw('sum(l.quantity*l.sgst) as sgst,sum(l.quantity*l.cgst)as cgst,sum(l.quantity*l.gstamount) as gstamount,sum(l.quantity*l.igst) as igst'))
 ->join('orders as a','a.id','=','l.order_id')
 ->where('a.store_id',$store_id)
 ->where('l.IsDeleted','N')
 ->wherebetween('l.updated_at',[$startdate,$enddate])
 ->get();

 $cgst = 0;
 $sgst = 0;
 $igst = 0;
 $gst = 0;

 foreach($tax_data as $d)
 {
     $cgst += $d->quantity * $d->cgst;
     $sgst += $d->quantity * $d->sgst;
     $igst += $d->quantity * $d->igst;
     $gst  += $d->quantity * $d->gstamount;
 }
//list($sgst,$cgst,$gst,$igst) = get_query_list($con,"select sum(l.Quantity*l.sgst),sum(l.Quantity*l.cgst),sum(l.Quantity*l.gst),sum(l.Quantity*l.igst) FROM `AR_Invoice_Lines` l INNER JOIN AR_Invoices_All a ON a.InvoiceID=l.InvoiceID where IsDeleted='N' and a.store_id='".$store_id."'  and LastUpdateDate BETWEEN '".$_REQUEST['startdate']."' AND '".$_REQUEST['enddate']."'");
?>
<table>
<tr><td><label>SGST: </label></td><td><?php echo round($sgst,2); ?></td></tr>
<tr><td><label>CGST: </label></td><td><?php echo round($cgst,2); ?></td></tr>
<tr><td><label>GST: </label></td><td><?php echo round($gst,2); ?></td></tr>
<tr><td><label>IGST: </label></td><td><?php echo round($igst,2); ?></td></tr>
</table>
</div>
<div class="col-sm-6 col-lg-6 col-xs-6">
<h3> AP Invoice GST Report b/w <?php echo $startdate; ?> and <?php echo $enddate; ?></h3>
<?php
$ap_tax_data = DB::table('ap_invoices_alls as l')
//->select(DB::raw('sum(l.sgst),sum(l.cgst),sum(l.gst),sum(l.igst)'))
->where('l.status','P')
->where('l.store_id',$store_id)
->wherebetween('Date',[$startdate,$enddate])
->get();
$cgst = 0;
$sgst = 0;
$igst = 0;
$gst = 0;

foreach($ap_tax_data as $d)
{
    $cgst += $d->cgst;
    $sgst += $d->sgst;
    $igst +=  $d->igst;
    $gst  +=  $d->gst;
}
//list($sgst,$cgst,$gst,$igst) = get_query_list($con,"select sum(l.sgst),sum(l.cgst),sum(l.gst),sum(l.igst) FROM `AP_Invoices_All` l where l.Status='P' and l.store_id='$store_id' and l.Date BETWEEN '".$_REQUEST['startdate']."' AND '".$_REQUEST['enddate']."'");
?>
<table>
<tr><td><label>SGST: </label></td><td><?php echo round($sgst,2); ?></td></tr>
<tr><td><label>CGST: </label></td><td><?php echo round($cgst,2); ?></td></tr>
<tr><td><label>GST: </label></td><td><?php echo round($gst,2); ?></td></tr>
<tr><td><label>IGST: </label></td><td><?php echo round($igst,2); ?></td></tr>
</table>
</div>
</div>
<div style="clear:both"></div>
<?php endif; ?>


@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection