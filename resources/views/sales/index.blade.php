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
                    <th>Order Code</th>
                    <th>Num. of Products</th>
                    <th>Customer</th>
                    <th>Update Tracking</th>
                    <th>Amount</th>
                    <th>Delivery Status</th>
                    <th>Payment Status</th>
                    @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                        <th>Refund</th>
                    @endif
                    <th width="10%">{{__('options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order)
                    <tr>
                        <td>
                            {{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}
                        </td>
                        <td>
                            {{ $order->code }}
                        </td>
                        <td>
                            {{ count($order->orderDetails) }}
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
                            {{ single_price($order->grand_total) }}
                        </td>
                        <td>
                            @php
                                $status = 'Delivered';
                                foreach ($order->orderDetails as $key => $orderDetail) {
                                    if($orderDetail->delivery_status != 'delivered'){
                                        $status = 'Pending';
                                    }
                                }
                            @endphp
                            {{ $status }}
                        </td>
                        <td>
                            <span class="badge badge--2 mr-4">
                                @if ($order->payment_status == 'paid')
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
                                    <li><a href="{{route('sales.show', encrypt($order->id))}}">{{__('View')}}</a></li>
                                    <li><a href="{{ route('customer.invoice.download', $order->id) }}">{{__('Download Invoice')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('orders.destroy', $order->id)}}');">{{__('Delete')}}</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                   
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
