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
            <form class="form-inline" action="{{route('PreOrderBooking.FindOrderBookingdata')}}" id="searchform" method="GET">
			
                <div class="input-daterange input-group" id="datepicker">
                <label for="startdate">Start Date:</label>
                <input style="padding:0px;" type="date" name="startdate" value="<?php if(isset($_GET['startdate'])){echo $_GET['startdate']; } ?>" class="form-control" id="startdate">
                </div>
                <div class="input-daterange input-group" id="datepicker">
                <label for="enddate">End Date:</label>
                <input style="padding:0px;" type="date" name="enddate" value="<?php if(isset($_GET['enddate'])){echo $_GET['enddate']; } ?>" class="form-control" id="enddate">
                </div>
					  
                <div class="form-group" style="width:135px">
                <label for="invoicenum">Isbn:</label>
                <input type="text" style="display:block; width:inherit;" name="isbn"  value="<?php if(isset($_GET['isbn'])){echo $_GET['isbn']; } ?>" class="form-control col-sm-2" id="isbn">
                </div>
                <div class="form-group " style="width:135px">
                <label for="invoicenum">Invoice No.:</label>
                <input type="text" name="invoicenum" class="form-control col-sm-2" value="<?php if(isset($_GET['invoicenum'])){echo $_GET['invoicenum']; } ?>" style="width:99% " id="invoicenum">
                </div>
                <!-- <div class="clearfix"></div> -->
                <div class="form-group " style="width:135px">
                <label for="invoicenum">Mobile No.:</label>
                <input type="text" name="phone" class="form-control col-sm-2" value="<?php if(isset($_GET['phone'])){echo $_GET['phone']; } ?>" style="width:99% " id="phone">
                </div>
                
				<div class="form-group" style="padding-top: 2%;">	  
                <button type="submit" name="search" value="search" class="btn btn-primary">Search</button>
                <button type="submit" name="clear" value="clear" id="Clear" class="btn btn-danger">Clear</button>
                </div>
            </form> 
        </div>
        </fieldset> 
    </div>

       
       
       
    
    <!-- <div class="pull-right clearfix">
            <form class="" id="sort_categories" action="{{route('PreOrderBooking.FindOrderBookingdata')}}" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                  
                        <input type="text" class="form-control" id="search" name="searchinput"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder=" Type name & Enter">
                    </div>
                </div>
            </form>
        </div> -->

        <h3 class="panel-title pull-left pad-no" style="margin-top: 30px;">{{__('Order List')}}</h3>
    </div>
    
    <div class="panel-body">
   
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="d-none d-sm-table-cell">{{__('Mobile')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('Order Number')}}</th>
                    <th>{{__('Invoice Type')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{__('Order Date')}}</th>
                    <th>{{__('Store')}}</th>
                    <th>{{__('IsCompleted')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('Options')}}</th>
                </tr>
            </thead>
            @if($data != null)
            <tbody>
                @foreach ($data as $key => $val)
                   
                        <tr>
                            <td>
                                {{ ($key+1) }}
                            </td>
                            <td class="d-none d-sm-table-cell">
                            @if(isset($val->phone))  {{ $val->phone }}  @endif
                            </td>
                            <td class="font-w600">
                            {{ $val->invoicenumber }} 
                            </td>
                            <td>    
                                {{ $val->invoicelookuptype}}  
                            </td>
                            <td>
                                {{ single_price($val->amount)}}  
                            </td>
                            <td>
                                {{ $val->invoicedate}}  
                            </td> 
                            <td>
                                {{__('Amit Book Depot')}}  
                            </td> 
                            <td>
                          <?php
                           // $quantity_data =\App\PrebookingLine::select('sum(quantity) as orgqty','sum(quantity)- sum(delivered_qty) as orderqty', 'delivered_qty as bal')->where('invoice_id', $val->invoiceid);
                            $originalqty = \App\PrebookingLine::select('quantity')->where('invoiceid', $val->invoiceid)->sum('quantity');
                            $bal = \App\PrebookingLine::select('delivered_qty')->where('invoiceid', $val->invoiceid)->sum('delivered_qty');
                          //  $originalqty = $orgqty;
                            $orderQuantity = $originalqty - $bal;
                            $deliveredQuantity = $bal;
                            if($val->status == 'C'){
                                echo "Canceled";
                            }else{
                            //if($deliveredQuantity == 0 || $deliveredQuantity = ''){
                            if($originalqty == $orderQuantity || $deliveredQuantity == ''){
                                echo "No";
                                }else if($orderQuantity < $originalqty && $orderQuantity != 0){
                                echo "Partial";	
                                }else if($orderQuantity == 0){
                                    
                                echo "Yes";
                                }else{
                                echo "Error";	
                                }
                            }
                            ?>
                            </td> 
                           
                            <td class="d-none d-sm-table-cell">
                            <a href="{{url('/admin/PreOrderBooking/PreOrderBookingLines/')}}/{{$val->invoicenumber}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View">
							    <i class="fa fa-eye"></i> View
						    </a>
                               
                            </td>
                        </tr>
                   
                @endforeach
            </tbody>
            @endif
        </table>
      
       
    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">
    // $('#startdate').datepicker({ dateFormat: 'yy-mm-dd' });
    // $('#enddate').datepicker({ dateFormat: 'yy-mm-dd' });
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    //     $('#clear').click(function(){
    //       var startdate =  $('#statdate').val();
    //       var enddate =  $('#enddate').val();
    //         $.ajax({
    //     type: 'POST', //THIS NEEDS TO BE GET
	// 	url: '{{ url('admin/PreOrderBooking/FindOrderBooking') }}',
	// 	data: { startdate: startdate, enddate: enddate,  _token: "{{ csrf_token() }}" },
    //     //dataType: 'json',
    //     success: function (data) {
    //         location.reload();
    //      //   data = JSON.parse(data);
			
    //     }
    // });
    //     });
    </script>
@endsection
