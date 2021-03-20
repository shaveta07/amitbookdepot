@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Credit invoice reportt')}}</h3>
        </div>
        <?php if(Auth::user()->user_type != "admin" ){ echo "<h3>Unauthorised Access !!</h3>"; die(); }  ?>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form  id="myForm1" class="form-horizontal" action="{{ route('report.creditinvoice_pdf')}}" method="Post" >
        	@csrf
            <div class="panel-body">
          
            <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Cutomers:')}}</label>
                    <div class="col-sm-10">
                    <?php
                    $rrr =  DB::table('users as cus')
                    ->join('customer_categories as cuscat','cuscat.id','=','cus.category_id')
                    ->select('cuscat.customertype','cuscat.name as catname', 'cus.id as userid','cus.name as custname')
                   
                    ->where('cuscat.customertype','C')->get();
                    ?>
				 <select name="c_id" id="c_id" class="form-control" >
                    <option value="">Select Customers</option>
                    <?php
                    foreach($rrr as $r):
                    
                    ?>
                    <option value="<?php echo $r->userid; ?>"><?php echo $r->custname; ?></option>
                    <?php endforeach;  ?>
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