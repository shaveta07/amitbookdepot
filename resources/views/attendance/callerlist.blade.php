@extends('layouts.app')

@section('content')
<style>
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
</style>

<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Caller Users List</h1>
        </div>
 </div>
 <br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Caller List')}}</h3>
        
    </div>
    <div class="panel-body">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
	
	<thead><tr><th>S.No.</th><?php if(Auth::user()->user_type == 'admin'){ ?><th>Mobile</th> <?php } ?><th>Email</th><th>Action</th></tr></thead>
	<tfoot><tr><th>S.No.</th><?php if(Auth::user()->user_type == 'admin'){ ?><th>Mobile</th><?php } ?><th>Email</th><th>Action</th></tr></tfoot>
<tbody>
<?php
$i=0;
$rrr = DB::table('calling_user')->orderBy('id','desc')->get();

foreach($rrr as $r):
?>
<tr>
<td><?= ++$i ?></td>
<?php if(Auth::user()->user_type == 'admin'){ ?><td><?= $r->mobile1 ?></td> <?php } ?>
<td><?= $r->email ?></td>
<td>
<a class="btn btn-info" href="{{route('attendance.edit_caller', $r->id)}}">{{__('Edit')}}</a>
</td>
</tr>
<?php
endforeach;
?>
</tbody>
</table>

<!--------------------------------------------------------------------------------------------------------------->

 </div>
 @endsection

@section('script')

<script type="text/javascript">
$('#startdate').datepicker({ dateFormat: 'yy-mm-dd' });
$('#enddate').datepicker({ dateFormat: 'yy-mm-dd' });
$('#example').DataTable();
</script>
@endsection