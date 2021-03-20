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


<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Search AR Invoice Form')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{route('ArInvoicesAllF.SearchData')}}" id="searchform" method="GET">
        	
            <div class="panel-body">
                <div class="form-group col-sm-6">
                    <input type="text" name="keyword" autofocus="autofocus" value="<?php echo isset($_REQUEST['keyword'])?$_REQUEST['keyword']:''; ?>" class="form-control"  required>
                </div>
                <div class="form-group col-sm-4">
                    <button class="btn btn-purple" name="search" type="submit" >{{__('Search')}}</button>
                </div>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->
       <br />	
       <?php
            if(isset($search_da))
            { ?>	
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
            <thead>
                <tr>
					<th class="text-center">Invoice#</th>
					<th class="d-none d-sm-table-cell">Customer Name</th>
					<th>Mobile</th>
					<th>Amount</th>
					<th>Store</th>
					<th class="d-none d-sm-table-cell">Action</th>
                    <th class="d-none d-sm-table-cell">Change Status</th>
				</tr>
            </thead>
            <tbody>
            
            @foreach($search_da as $search_data)
                <tr>
					
					<td>{{$search_data->invoicenumber}}</td>
					<td class="d-none d-sm-table-cell">
                    {{$search_data->name}}
					</td>
					<td class="font-w600">{{$search_data->phone}}</td>
                    <td class="font-w600">{{$search_data->amount}}</td>
					<td class="font-w600">{{_('Amit book depot')}}</td>
					<td class="d-none d-sm-table-cell">
						<a href="{{url('admin/ARinvoice_header_workbench_f/view')}}/{{$search_data->id}}/{{$search_data->invoicenumber}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View">
							<i class="fa fa-eye"></i> View
						</a>
					</td>
					<td class="d-none d-sm-table-cell">
                    <select id="changestatus" data-id="{{ $search_data->id }}" class="changestatus" name="changestatus" >
                    <option value="O" <?php if($search_data->status=="O"){echo "selected";} ?>>Open</option>
                    <option value="C"  <?php if($search_data->status=="C"){echo "selected";} ?>>Cancel</option>
                    <option value="P"  <?php if($search_data->status=="P"){echo "selected";} ?>>Paid</option>
                </select>
					</td>
				</tr>
                <input type="hidden" name="orderid" id="OrderId" value="{{$search_data->id}}" />
                @endforeach
            </tbody>
            </table>
            <?php } ?>
    </div>
</div>

<script type="text/javascript">
$('.changestatus').change(function(){
	var inv = $(this).attr('data-id');
    
	$.ajax({
				async : false,
				url : '{{url('admin/ARinvoice_header_workbench_f/statusChangeOrder')}}',
				type : "POST",
				data : {'orderid' : inv, 'status' : $(this).val(),  _token: "{{ csrf_token() }}" },
				dataType : 'text',
				error:function(){
				   alert('Error!');
				},
				success:function(dataType) {
					if(dataType == '1'){
						location.reload();
						}
				}
			});
	});
// $( document ).on( 'click', '.loadmore', function () {
// 	     //$(this).text('Loading...');
// 	     var ele = $(this).parent('li');
// 		 var keyword	= $('#keyword-box').val();
// 	      $.ajax({
// 	        url: '{{ url('admin/ARinvoice_header_workbench/search_data')}}',
// 	        type: 'POST',
// 	        data: {
// 	          page:$(this).data('page'),
// 			  'keyword':keyword
// 	        },
// 	        success: function(response){
// 	          if(response){
// 	           ele.hide();
// 	            $(".hide_row").hide();
// 	            $(".tableData").append(response);
// 	          }
// 	        }
// 	      });
// 	});
</script>
@endsection
