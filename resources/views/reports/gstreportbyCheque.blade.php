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
        <div class="col-lg-12">
            <h1 class="page-header"> AP Reports By Cheque </h1>
        </div>
 </div><?php echo $msg; ?>  
 <!-- /.col-lg-12 -->
                
 <div class="row" style="margin-bottom:20px;">
            <div class="col-sm-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Search</legend>
                    <div class="control-group">
                        <form method="get" class="form-inline" action="{{route('report.gstreportbychequesearch')}}">
                        <div class="form-group">
                            <label for="startdate">Cheque Number:</label>
                            <input type="text" value="<?php echo $cheque; ?>" name="cheque" class="form-control" id="cheque">
                        </div>
                        <div class="form-group">
                            <label for="startdate">Start Date:</label>
                            <input style="line-height:20px;" type="date" name="startdate" value="<?php if(isset($startdate)){echo $startdate; } ?>" class="form-control" id="startdate">
                        </div>
                        <div class="form-group">
                            <label for="enddate">End Date:</label>
                            <input style="line-height:20px;" type="date" name="enddate" value="<?php if(isset($enddate)){echo $enddate; } ?>" class="form-control" id="enddate">
                        </div>
                        
                        <button type="submit" name="search" value="search" class="btn btn-primary">Search</button>
                        <button type="submit" name="clear" value="clear" class="btn btn-danger">Clear</button>
                        </form> 
                    </div>
				</fieldset> 
                 </div>
            </div>
            <div style="clear:both;"></div>
 <div class="taskList col-sm-12">
	 
	 
	 <?php if(isset($search)): ?>
	 
	
	<table class="table table-responsive table-striped js-dataTable-full table-inverse table-bordered table-hover">
	<thead>
	<tr><th>S.N.</th><th>Invoice Number</th><th>Cheque Number</th><th>GST</th><th>IGST</th><th>Pay Date</th> <th>Amount</th><th>Invoice Type</th></tr>
	</thead>
	<tfoot>
	<tr><th>S.N.</th><th>Invoice Number</th><th>Cheque Number</th><th>GST</th><th>IGST</th><th>Pay Date</th> <th>Amount</th><th>Invoice Type</th></tr>
	</tfoot>
	
<tbody>
	
<?php
$inc=0;

foreach($rrr as $r){
?>
<tr><td><?php echo ++$inc; ?></td><td><a target="_blank" href="{{url('/admin/APinvoice_header_workbench/view')}}/<?php echo $r->invoiceid; ?>/<?php echo $r->supplier_id; ?>"><?php echo $r->invoice_number; ?></a></td><td><?php echo $r->payinfo; ?></td><td><?php echo $r->gst; ?></td><td><?php echo $r->igst; ?></td><td><?php echo $r->paydate; ?></td><td><?php echo $r->Total; ?></td><td>AP</td></tr>
<?php } ?>



</tbody>
</table>
<?php endif; ?>
</div>
<div style="clear:both"></div>
        

@endsection

@section('script')
<script type="text/javascript">

</script>


@endsection