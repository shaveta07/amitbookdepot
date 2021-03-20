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
            <h3 class="panel-title">{{__('Search AR Invoice')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{route('order.ARinvoiceSearchData')}}" id="searchform" method="GET">
        	
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
        <?php
            if(isset($search_da))
            { 
            ?><br />		
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
            <thead>
                <tr>
                <th  class="text-center">Invoice #</th>
                <th class="d-none d-sm-table-cell">Customer Name</th>
                <th class="d-none d-sm-table-cell">Mobile</th>
                <th class="d-none d-sm-table-cell">Amount</th>
                <!-- <th>Store</th> -->
                <th class="d-none d-sm-table-cell">Option</th>
                <th>Change Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            
            foreach($search_da as $search_data)
                {
                ?>
                <tr>
                <td><?php echo $search_data->ordersource.$search_data->invoice_number; ?> </td>
                <td class="font-w600" ><?php echo $search_data->name; ?> </td>

                <td class="d-none d-sm-table-cell"><?php echo $search_data->phone; ?></td>
                <td class="font-w600"><?php echo $search_data->grand_total; ?> </td>
                <!-- <td></td> -->
                <td>
                    <?php 
                            if($search_data->id != 0 && $search_data->id != ''){
                                ?>
                                	<a href="{{url('admin/ARinvoice_header_workbench/view')}}/{{$search_data->id}}/{{$search_data->ordersource.$search_data->invoice_number}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                              
                                    <?php if($search_data->payment_status == 'paid'){ ?>
                                        <a href="{{url('admin/ARinvoice_header_workbench/returnrent')}}/<?php echo $search_data->invoice_number; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Return Rent">
                                        Return Rent
                                    </a>
               
                <?php }else{
                    
                    echo "<span style='color:red'><b>Invoice Opened or Canceled !!</b></span>";
                    } ?>
                                <?php
                                }else{
                            ?>
                    <a  href="{{url('admin/ARinvoice_header_workbench/view')}}/{{$search_data->orderid}}/{{$search_data->ordersource.$search_data->invoice_number}}">View</a> | 
                    <?php if($search_data->payment_status == 'P'){ ?>
                <a href="return-rent.php?invoice_number=<?php echo $search_data->invoice_number; ?>">Return Rent</a>
                <?php }else{
                    
                    echo "<span style='color:red'><b>Invoice Opened or Canceled !!</b></span>";
                    } ?>
                    
                    
                    <?php
                }
                    
                    
                    ?>
                    
                </td>
                
                <td>
                <select id="changestatus" class="changestatus" name="changestatus" >
                    <option value="unpaid" <?php if($search_data->payment_status=="unpaid"){echo "selected";} ?>>Open</option>
                    <option value="cancel"  <?php if($search_data->payment_status=="cancel"){echo "selected";} ?>>Cancel</option>
                    <option value="paid"  <?php if($search_data->payment_status=="paid"){echo "selected";} ?>>Paid</option>
                </select>
                
                </tr>
                <input type="hidden" id="OrderId" value="<?php echo $search_data->id ?>" />
                <?php
                
                }
            ?>
            <input type="hidden" id="keyword-box" value="<?php echo $_REQUEST['keyword']; ?>" />
            </tbody>
            </table>
           

            <?php } // if ends ?>	
    </div>
</div>

<script type="text/javascript">
$('.changestatus').change(function(e){
	var inv = $('#OrderId').val();
	$.ajax({
				async : false,
				url : '{{url('admin/ARinvoice_header_workbench/statusChangeOrder')}}',
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
