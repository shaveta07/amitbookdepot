@extends('layouts.app')

@section('content')
<style>
select + .select2-container
{
    width:auto !important;
}
.form-inline .form-group {
	display: inline-block;
	margin-bottom: 15px !important;
	vertical-align: middle;
	margin-right: 15px !important;
}

#DataTables_Table_0_wrapper {
	padding-left: 2% !important;
	padding-right: 2% !important;
}
.dt-buttons {
	display: none !important;
}
</style>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
<h1 class="panel-title pull-left pad-no" style="margin-left: 30px;">{{__('Form Report Employee wise')}}</h1>
    <div class="panel-heading bord-btm clearfix pad-all h-100" >
    <div class="col-sm-12">
    
        <fieldset class="scheduler-border">
		<legend class="scheduler-border">Search</legend>
		<div class="control-group">
            <form class="form-inline" action="{{route('ArInvoicesAllF.arformreportSubmit')}}" id="searchform" method="POST">
                @csrf
                <div class="form-group ">
                <label for="startdate">Start Date:</label>
                <input type="date" name="startdate" class="form-control" id="startdate" value="<?php if(isset($_GET['startdate'])){echo $_GET['startdate']; } ?>">
                </div>
                <div class="form-group ">
                <label for="enddate">End Date:</label>
                <input type="date" name="enddate" class="form-control" id="enddate" value="<?php if(isset($_GET['enddate'])){echo $_GET['enddate']; } ?>">
                </div>
					  
                <div class="form-group ">
                <label for="invoicenum">Users:</label>
                    <select class="form-control demo-select2-placeholder" name="user" id="user">
                            <option value="">{{__('Select User')}}</option>
                            @foreach(\App\User::all() as $user)
								<option value="{{$user->email}}"<?php if(isset($_GET['user'])){echo 'selected'; } ?>>{{__($user->email)}}</option>
							@endforeach
                                
                        </select>
                </div>
               
                <div class="form-group">
                    <input type="submit" name="search" value="search" class="btn btn-primary">
                    <!-- <button type="submit" name="clear" value="clear" class="btn btn-danger">Clear</button> -->
                </div>
            </form> 
        </div>
        </fieldset> 
    </div>

      
    </div>
    <div class="panel-body">
   
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Employee')}}</th>
                    <th>{{__('No of completed Form')}}</th>
                    <th>{{__('No. of Incomplete Form')}}</th>
                 
                </tr>
            </thead>
            @if($data != null)
            <tbody>
          
                @foreach ($data as $key => $val)
                <?php
             
                    $startdate = isset($request->startdate) ? $request->startdate:'';
                    $enddate = isset($request->enddate) ? $request->enddate:'';
              
            ?>
                        <tr>
                            <td>
                                {{ ($key+1) }}
                            </td>
                            <td>
                            {{ $val['email'] }}
                            </td>
                            <td>
                            {{ $val['complete'] }}
                            </td>
                            <td>
                            {{ $val['Incomplete'] }}
                            </td>
                          
                        </tr>
                   
                @endforeach
            </tbody>
            @endif
        </table>
      
        <div class="clearfix">
            <div class="pull-right">
                
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    </script>
@endsection
