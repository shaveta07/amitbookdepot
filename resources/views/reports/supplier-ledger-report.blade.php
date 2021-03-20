@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Cash Flow Report - Supplier Statement')}}</h3>
        </div>
        <?php if(Auth::user()->user_type != "admin" ){ echo "<h3>Unauthorised Access !!</h3>"; die(); }  ?>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form  id="myForm1" class="form-horizontal" action="{{ route('report.supplier_statement_pdf')}}" method="Post" >
        	@csrf
            <div class="panel-body">
          
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
                <button class="btn btn-purple" name="submit" type="submit">{{__('Submit')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>


@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection