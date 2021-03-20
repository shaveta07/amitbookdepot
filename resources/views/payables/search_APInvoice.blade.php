@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Search AP Invoice')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{route('APInvoiceAlls.APinvoiceSearchData')}}" id="searchform" method="GET">
        	<!-- @csrf -->
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
            if(isset($search_data))
            { 
            ?><br />		
            <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                <th>Invoice #</th>
                <th>Supplier Name</th>
                <th>Amount</th>
                <th>Mobile No.</th>
                <th>Option</th>
                <th>Change Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            
            foreach($search_data as $data)
                {
                ?>
                <tr>
                <td><?php echo $data->invoice_number; ?> </td>
                <td><?php echo $data->name; ?> </td>
                <td><?php echo $data->Total; ?> </td>
                <td><?php echo $data->mobile1; ?></td>
                <td>
                <?php 
                if(substr($data->invoice_number,0,3)=="SAL" and $_SESSION['Role']!="1" and $_SESSION['Role']!="2") {echo "Hidden"; }else {
                    if(strtoupper(substr($data->invoice_number, 0, 2)) == 'RR'  || strtoupper(substr($data->invoice_number, 0, 3)) == 'OLD' || strtoupper(substr($data->invoice_number, 0, 3)) == 'SAL'){
                    ?>
                    <a href="{{url('admin/APinvoice_header_workbench_old/view')}}/<?php echo $data->invoiceid;; ?>/<?php echo $data->supplierid; ?>">View</a>
                    <?php
                }else if(strtoupper(substr($data->invoice_number , 0, 3)) == 'LUM'){ ?>
                    <a href="{{url('admin/APinvoice_header_workbench_old2/view')}}/<?php echo $data->invoiceid; ?>/<?php echo $data->supplierid; ?>">View</a>
                    <?php }else {
                    ?>
                    <a href="{{url('admin/APinvoice_header_workbench/view')}}/<?php echo $data->invoiceid;; ?>/<?php echo $data->supplierid; ?>">View</a>
                    <?php
                    
                    }
                }

                ?>
                </td>
                
                <td>
                <select id="changestatus" class="changestatus" name="changestatus" >
                    <option value="O" <?php if($data->Status=="O"){echo "selected";} ?>>Open</option>
                    <option value="C"  <?php if($data->Status=="C"){echo "selected";} ?>>Cancel</option>
                    <option value="P"  <?php if($data->Status=="P"){echo "selected";} ?>>Paid</option>
                </select>
               
                </tr>
                <input type="hidden" id="invoiceId" value="<?php echo $data->invoiceid ?>" />
                <?php
                
                }
            ?>
            <input type="hidden" id="keyword-box" value="<?php echo $_REQUEST['keyword']; ?>" />
            </tbody>
            </table>
            <div class="clearfix">
            <div class="pull-right">
                {{ $search_data->appends(request()->input())->links() }}
            </div>
        </div>

            <?php } // if ends ?>	
            
    </div>
</div>

<script type="text/javascript">
$('.changestatus').change(function(e){
	var inv = $('#invoiceId').val();
	$.ajax({
				async : false,
				url : '{{url('admin/APinvoice_header_workbench/statusChangeOrder')}}',
				type : "POST",
				data : {'invoiceId' : inv, 'status' : $(this).val(),  _token: "{{ csrf_token() }}" },
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
