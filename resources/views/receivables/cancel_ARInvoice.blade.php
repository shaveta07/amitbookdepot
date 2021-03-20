@extends('layouts.app')

@section('content')

<style>
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
    
    <div class="panel-heading bord-btm clearfix pad-all h-100" >
    <div class="col-sm-12">
        <fieldset class="scheduler-border">
		<legend class="scheduler-border">Search</legend>
		<div class="control-group">
            <form class="form-inline" action="{{route('order.ARinvoiceCancelData')}}" id="searchform" method="GET">
			
                <div class="form-group col-sm-2">
                <label for="startdate">Start Date:</label>
                <input type="date" name="startdate" class="form-control" id="startdate">
                </div>
                <div class="form-group col-sm-2">
                <label for="enddate">End Date:</label>
                <input type="date" name="enddate" class="form-control" id="enddate">
                </div>
					  
                <div class="form-group col-sm-2">
                <label for="invoicenum">Invoice No.:</label>
                <input type="text" name="invoicenum" class="form-control col-sm-2"  style="width:99%" id="invoicenum">
                </div>
                <div class="form-group col-sm-2">
                <label for="invoicenum">Mobile No.:</label>
                <input type="text" name="phone" class="form-control col-sm-2" style="width:99%" id="phone">
                </div>
                <div class="form-group" style="padding-top: 2%;">
                    <button type="submit" name="search" value="search" class="btn btn-primary">Search</button>
                    <button type="submit" name="clear" value="clear" class="btn btn-danger">Clear</button>
                </div>
            </form> 
        </div>
        </fieldset> 
    </div>

        <h3 class="panel-title pull-left pad-no" style="margin-top: 30px;">{{__('Cancel Invoices')}}</h3>
       
        <!-- <div class="pull-right clearfix">
            <form class="" id="sort_orders" action="" method="GET">
               <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" style="margin-top: 40px;"class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type Order code & hit Enter">
                    </div>
                </div>
            </form>
        </div> -->
    </div>
    <div class="panel-body">
   
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="d-none d-sm-table-cell">{{__('Mobile')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('Invoice Number')}}</th>
                    <th>{{__('Invoice Type')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{__('Date')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('Options')}}</th>
                </tr>
            </thead>
            @if($data != null)
            <tbody>
                @foreach ($data as $key => $val)
                    @php
                        $order = \App\Order::find($val->id);
                    @endphp
                    @if($order != null)
                        <tr>
                            <td>
                                {{ ($key+1) }}
                            </td>
                            <td class="font-w600">
                               {{$val->phone}} 
                            </td>
                            <td>
                            <a href="{{url('admin/ARinvoice_header_workbench/view')}}/{{$order->id}}/{{$order->ordersource.$val->invoice_number}}">{{ $order->ordersource }}{{$val->invoice_number}}</a> 
                            </td>
                            <td>
                                {{ $order->invoice_type}}  
                            </td>
                            <td class="font-w600">
                                {{ single_price($order->grand_total)}}  
                            </td>
                            <td>
                                {{ $order->updated_at}}  
                            </td> 
                            <td class="d-none d-sm-table-cell">
                            <a href="{{url('admin/ARinvoice_header_workbench/view')}}/{{$order->id}}/{{$order->ordersource.$val->invoice_number}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View">
							    <i class="fa fa-eye"></i> View
						    </a>
                               
                            </td>
                          
                        </tr>
                    @endif
                @endforeach
            </tbody>
            @endif
        </table>
      
       
</div>

@endsection


@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    </script>
@endsection
