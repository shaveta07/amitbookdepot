@extends('layouts.app')

@section('content')
@php
    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
@endphp
<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Orders')}}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_orders" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="select" style="min-width: 300px;">
                        <select class="form-control demo-select2" name="payment_type" id="payment_type" onchange="sort_orders()">
                            <option value="">{{__('Filter by Payment Status')}}</option>
                            <option value="paid"  @isset($payment_status) @if($payment_status == 'paid') selected @endif @endisset>{{__('Paid')}}</option>
                            <option value="unpaid"  @isset($payment_status) @if($payment_status == 'unpaid') selected @endif @endisset>{{__('Un-Paid')}}</option>
                        </select>
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="select" style="min-width: 300px;">
                        <select class="form-control demo-select2" name="delivery_status" id="delivery_status" onchange="sort_orders()">
                            <option value="">{{__('Filter by Deliver Status')}}</option>
                            <option value="pending"   @isset($delivery_status) @if($delivery_status == 'pending') selected @endif @endisset>{{__('Pending')}}</option>
                            <option value="on_review"   @isset($delivery_status) @if($delivery_status == 'on_review') selected @endif @endisset>{{__('On review')}}</option>
                            <option value="on_delivery"   @isset($delivery_status) @if($delivery_status == 'on_delivery') selected @endif @endisset>{{__('On delivery')}}</option>
                            <option value="delivered"   @isset($delivery_status) @if($delivery_status == 'delivered') selected @endif @endisset>{{__('Delivered')}}</option>
                        </select>
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type Order code & hit Enter">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Order Code')}}</th>
                    <th>{{__('Num. of Products')}}</th>
                    <th>{{__('Customer')}}</th>
                    <th>{{__('Update Tracking')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{__('Delivery Status')}}</th>
                    <th>{{__('Payment Method')}}</th>
                    <th>{{__('Payment Status')}}</th>
                    @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                        <th>{{__('Refund')}}</th>
                    @endif
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order_id)
                    @php
                        $order = \App\Order::find($order_id->id);
                    @endphp
                    @if($order != null)
                        <tr>
                            <td>
                                {{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}
                            </td>
                            <td>
                                {{ $order->code }} @if($order->viewed == 0) <span class="pull-right badge badge-info">{{ __('New') }}</span> @endif
                            </td>
                            <td>
                                {{ count($order->orderDetails->where('seller_id', $admin_user_id)) }}
                            </td>
                            <td>
                                @if ($order->user != null)
                                    {{ $order->user->name }}
                                @else
                                    Guest ({{ $order->guest_id }})
                                @endif
                            </td>
                            <td>
                            <button type="button" data-code = "{{$order->code}}" data-id="{{$order->id}}" class="btn btn-primary track" >
                           Update Tracking</button>
                        </td>
                            <td>
                                {{ single_price($order->orderDetails->where('seller_id', $admin_user_id)->sum('price') + $order->orderDetails->where('seller_id', $admin_user_id)->sum('tax') + $order->shippingprice + $order->codprice - $order->coupon_discount) }}
                            </td>
                            <td>
                                @php
                                    $status = $order->orderDetails->first()->delivery_status;
                                @endphp
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </td>
                            <td>
                                {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                            </td>
                            <td>
                                <span class="badge badge--2 mr-4">
                                    @if ($order->orderDetails->where('seller_id',  $admin_user_id)->first()->payment_status == 'paid')
                                        <i class="bg-green"></i> Paid
                                    @else
                                        <i class="bg-red"></i> Unpaid
                                    @endif
                                </span>
                            </td>
                            @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                                <td>
                                    @if (count($order->refund_requests) > 0)
                                        {{ count($order->refund_requests) }} Refund
                                    @else
                                        No Refund
                                    @endif
                                </td>
                            @endif
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{ route('orders.show', encrypt($order->id)) }}">{{__('View')}}</a></li>
                                        <li><a href="{{ route('seller.invoice.download', $order->id) }}">{{__('Download Invoice')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('orders.destroy', $order->id)}}');">{{__('Delete')}}</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $orders->appends(request()->input())->links() }}
            </div>
        </div>
         <!-- The Modal -->
         <div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Update Tracking</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

     
         <!-- Modal body -->
      <div class="modal-body">
      <form class="form form-horizontal mar-top" action="{{ route('orders.updateTracking') }}" method="POST" id="trackform" >
		  @csrf
          <table style= "border-color: rgb(17, 2, 1);" class="table table-bordered"  >
            <thead>
                <tr>
                <th>#</th>
                     <th>Product</th>
                     <th>Track ID</th>
                     <th>Couriers</th>
                </tr>
            </thead>
            <tbody id="Trackdata">
           
            </tbody>
          
        </table>
                </br>
                </br>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Description')}}</label>
                    <div class="col-sm-10">
                        <textarea name="description" rows="4" class="form-control">{{strip_tags($order->description)}}</textarea>
                    </div>
                </div>
                
      </div>

      <!-- Modal footer -->
      <div class="modal-footer" style="border-top:0px;">
        <input type="submit"  value="save" name="save" class="btn btn-success" id="updatetrack" >
      
      </div>
      </form>
    </div>
  </div>
</div>

 <!-- Modal end -->
    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }

        $('.track').on('click',function(e){
        
        e.preventDefault(); 
        var val =  $(this).attr('data-id');
       
        var code =  $(this).attr('data-code');
        
        $.post("{{ url('admin/orders/gettrackdata') }}", {
                "_token": "{{ csrf_token() }}",
                "val": val,
                "code": code
            }
        )
        .done(function( data ) {
            $('#Trackdata').empty('');
            data = JSON.parse(data);
            $.each(data.prodata, function(k,v) {
               
                  var html= '<tr><td><input type="checkbox" name="check[]"  checked></td><td> <input type="text" readonly placeholder="Product" id="products[]" value="'+v.product+'" name="products[]" class="form-control" required></td><td><input type="hidden" id="ordid" name="ordid" value="'+v.orderid+'" ><input type="hidden" id="productd" name="productd[]" value="'+v.productid+'" ><input type="text" placeholder="Tracking ID" id="tracking_id[]" value="'+v.track+'" name="tracking_id[]" class="form-control" ></td><td><select name="courier[]" class="form-control"> "';
                  $.each(data.cordata, function(ke,va) {
                    if(va.link == v.courier)
                      {
                        html += '<option value="'+va.link+'" selected >'+va.courier_name+'</option>'; 

                      }
                      else{
                        html += '<option value="'+va.link+'" >'+va.courier_name+'</option>'; 
                      }
                  }); 
                  html += '</select></td></tr>';
                  $('#Trackdata').append(html);
            });
            
            $('#myModal').modal('show');            
        });
     });



      $('#trackform').on('submit',function(e){
        
      e.preventDefault(); 
      
        $.post("{{ route('orders.updateTracking') }}",$("#trackform").serialize()).done(function( data ) {
		 //console.log(data);
		
		 if(data == "true")
		 {
			location.reload(); 
		 }
		

		});
    });
    </script>
@endsection
