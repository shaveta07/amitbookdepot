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
    <div class="panel-heading bord-btm clearfix pad-all h-100">
    <h3 class="panel-title pull-left pad-no">{{__('PP Invoice Booked order')}}</h3>
    <div class="col-sm-12">
        <fieldset class="scheduler-border">
		<legend class="scheduler-border">Search</legend>
		<div class="control-group">
            <form class="form-inline" action="{{route('PreOrderBooking.CreditBookingdata')}}" id="searchform" method="GET">
			
                <div class="form-group">
                <label for="startdate">Start Date:</label>
                <input type="date" style="display:block;"  name="startdate" value="<?php if(isset($_GET['startdate'])){echo $_GET['startdate']; } ?>" class="form-control" id="enddate">
                </div>
                <div class="form-group">
                <label for="startdate">End Date:</label>
                <input type="date" style="display:block;"  name="enddate" value="<?php if(isset($_GET['enddate'])){echo $_GET['enddate']; } ?>" class="form-control" id="enddate">
                </div>
                <div class="form-group " style="width:135px">
                <label for="invoicenum">Invoice No.:</label>
                <input type="text" name="invoicenum" class="form-control col-sm-2"  style="width:99% " id="invoicenum">
                </div>
                <!-- <div class="clearfix"></div> -->
                <div class="form-group " style="width:135px">
                <label for="invoicenum">Mobile No.:</label>
                <input type="text" name="phone" class="form-control col-sm-2" style="width:99% " id="phone">
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
            <form class="" id="sort_categories" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                  
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder=" Type name & Enter">
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
                    <th class="d-none d-sm-table-cell">{{__('Mobile No.')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('Order No.')}}</th>
                    <th>{{__('Credit Amt')}}</th>
                    <th>{{__('Balanced')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('Paid Amt')}}</th>
                    <th>{{__('Ordered Date')}}</th>
                    <th>{{__('Store')}}</th>
                    <th>{{__('IsCompleted')}}</th>
                    <th>{{__('Action')}}</th>
                </tr>
            </thead>
            @if($prebooking)
            <tbody>
                
                <?php 
               
                $cr=0;$pd=0;$bal=0;
                foreach($prebooking as $key => $val) {
                $customer = \App\Customer::where('id',$val->customerid)->first();
                //print_r($customer->id);
                $user = \App\User::where('id',$customer->user_id)->first(); 
                $creditamt = \App\PrebookingPayment:: select('paid')->where('invoiceid', $val->invoiceid)->sum('paid');
                $bal = $bal + ($creditamt - $val->amount);
                $cr = $cr+$creditamt;
                $pd = $pd + $val->amount;
                $originalqty = \App\PrebookingLine::select('quantity')->where('invoiceid', $val->invoiceid)->sum('quantity');
                $bal = \App\PrebookingLine::select('delivered_qty')->where('invoiceid', $val->invoiceid)->sum('delivered_qty');
              //  $originalqty = $orgqty;
                $orderQuantity = $originalqty - $bal;
                $deliveredQuantity = $bal;
               
               ?>
                    <tr>
                        <td>{{ ($key+1)}}</td>
                        <td class="d-none d-sm-table-cell">@if(isset($user)) {{ $user->phone }} @endif</td>
                        <td class="font-w600"><a href="{{url('/admin/PreOrderBooking/PreOrderBookingLines/')}}<?php echo $val->invoicenumber ?>">{{ $val->invoicenumber }}</a></td>
                        <td>{{ single_price($creditamt) }}</td>
                        <td>{{ (round($creditamt)-round($val->amount))}}</td>
                        <td>{{ single_price($val->amount) }}</td>
                        <td>{{ $val->invoicedate }} </td>
                        
                        <td>{{_('Amit Book Depot')}}</td>
                        <td class="font-w600">
                        <?php
                        if($val->status == 'C'){
                            echo "Canceled";
                        }else{
                        if($deliveredQuantity == 0 || $deliveredQuantity = ''){
                            echo "No";
                            }else if($orderQuantity > 0){
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
                    <?Php }?>
            </tbody>
            @endif
        </table>
        
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
